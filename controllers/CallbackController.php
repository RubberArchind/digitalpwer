<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Transaction;
use app\models\User;
use app\models\Logs;
use app\models\TrxMap;

//MIDTRANS
// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'Mid-server-B0QsK6afVyTfe_Ue_PQbxjYw';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = true;

class CallbackController extends \yii\web\Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    private function calculatebonus($amount, $user, $trxid, $timestamp)
    {
        // Retrieve the user and its level 4 referrer
        // $user = User::findOne(Yii::$app->user->id);
        $levelReferrer = $this->getLevelReferrer($user);
        // return json_encode(array("levels" => $levelReferrer));
        if ($levelReferrer) {
            foreach ($levelReferrer as $referrer) {
                $userReferrer = User::findOne(['user_id' => $referrer['user']]);
                if ($userReferrer !== null) {
                    // $userReferrer = User::findOne(['user_id'=>$referrer->user]);                
                    // Calculate bonus for level  referrer
                    $bonusAmount = $this->calculateBonusAmount($amount, $referrer['level']);

                    $userReferrer->balance_bonus = $userReferrer->balance_bonus + $bonusAmount;
                    $saveStatus = $userReferrer->save(true, ["balance_bonus"]);

                    // return json_encode(array("userid" => $userReferrer->user_id, "result" => $bonusAmount, "data" => $userReferrer->balance_bonus, "status" => $saveStatus, "err" => $saveStatus->error));

                    // Log the bonus transaction
                    $this->logBonusTransaction($userReferrer->user_id, $bonusAmount, $trxid, $timestamp);
                    date_default_timezone_set('Australia/Melbourne');
                    $date = date('Y-m-d H:i:s');
                    $transaction = new Transaction();
                    $transaction->scenario = Transaction::SCENARIO_CREATE;
                    $transaction->attributes = array(
                        'id' => "" . rand(),
                        'user_id' => $user->user_id,
                        'target_id' => $userReferrer->user_id,
                        'method' => 'Internal',
                        'type' => "BONUS",
                        'amount' => $amount,
                        'time' => $timestamp
                    );
                    $transaction->validate();
                    $transaction->save();
                    // return json_encode(array('status' => $transaction->save(), 'error' => $transaction->getErrors()));
                }
            }

            // Respond with success message or handle errors
            return 'Bonus calculation completed successfully.';
        } else {
            // Respond with error message if no level 4 referrer found
            return 'No level referrer found.';
        }
    }

    private function getLevelReferrer($user)
    {
        // Assuming $user is the starting point
        $referrer = User::findOne(['user_referral' => $user->referral]);
        $level = 1;

        if ($referrer == null) {
            return;
        }
        $referrers = [];
        array_push($referrers, ['user' => $referrer->user_id, 'level' => $level]);
        while ($referrer !== null) { // Check if $referrer is not null instead of $level            
            $referrer = User::findOne(['user_referral' => $referrer->referral]);
            $level++;
            if ($referrer == null) {
                continue;
            }
            if ($referrer->user_id !== null) {
                array_push($referrers, ['user' => $referrer->user_id, 'level' => $level]);
            } else {
                array_push($referrers, ['user' => [], 'level' => $level]);
            }
        }
        return $referrers;
    }


    private function calculateBonusAmount($amount, $level)
    {
        // Define bonus calculation logic based on the user's referral level
        switch ($level) {
            case 1:
                return $amount * 0.1; // 10% bonus for level 1
            case 2:
                return $amount * 0.05; // 5% bonus for level 2
            case 3:
                return $amount * 0.025; // 2.5% bonus for level 3
            case 4:
                return $amount * 0.025; // 2.5% bonus for level 4
                // Add cases for other levels as needed
            default:
                return 0; // No bonus for other levels
        }
    }

    private function logBonusTransaction($userId, $amount, $trxid, $timestamp)
    {
        // Implement code to log bonus transactions to your database
        // For example, you can use Yii2's ActiveRecord to create a new log record
        $log = new Logs();
        $log->attributes = array(
            'user_id' => $userId,
            'type' => 'BONUS',
            'amount' => $amount,
            'time' => $timestamp,
            'ref' => $trxid
        );
        $log->save();
    }

    public function actionIndex()
    {
        $this->enableCsrfValidation = false;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return "ok";
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $notif = $notif->getResponse();
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;
        $amount = intval($notif->gross_amount);

        $trxmap = TrxMap::findOne(['id' => $order_id]);
        $userTrx = User::findOne(['user_id' => $trxmap->user_id]);

        // return array("notif" => $notif, "trxmap" => $trxmap->attributes, "user" => $userTrx->attributes);

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    return "Transaction order_id: " . $order_id . " is challenged by FDS";
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    return "Transaction order_id: " . $order_id . " successfully captured using " . $type;
                }
            }
        } else if ($transaction == 'settlement') {
            $transaction = new Transaction();
            $transaction->scenario = Transaction::SCENARIO_CREATE;
            if ($userTrx->user_id != 'Wt0k8G_E1y' && $userTrx->user_id != "p0DiCW2ND8") {
                if ($amount >= 100000 && $amount <= 1100000) {
                    $amount = $amount - 100000;
                } else if ($amount >= 1300000 && $amount <= 5300000) {
                    $amount = $amount - 200000;
                } else if ($amount >= 5400000) {
                    $amount = $amount - 300000;
                }
            }

            $this->calculatebonus($amount, $userTrx, $notif->transaction_id, $notif->settlement_time);
            $convertedString = ucwords(str_replace("_", " ", $type));
            $transaction->attributes = array(
                'id' => $notif->transaction_id,
                'user_id' => $userTrx->user_id,
                'target_id' => $userTrx->user_id,
                'method' => $convertedString,
                'type' => "DEPOSIT",
                'amount' => $amount,
                'time' => $notif->settlement_time
            );

            if ($transaction->validate() && $amount > 0) {
                return array('data' => $transaction, 'status' => $transaction->save());
            } else {
                if ($amount <= 0) {
                    return array('error' => "zero to negative result");
                } else {
                    return array('error' => $transaction->errors);
                }
            }
            // return "Transaction order_id: " . $order_id . " successfully transfered using " . $type . " data: " . json_decode($data)->data . " status:" . $http_status_code . "Error: " . $error;
            // return json_encode(array("data" => $data, "error" => $error));
        } else if ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            return "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        } else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            return "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        } else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            return "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        } else if ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            return "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        }
    }
}
