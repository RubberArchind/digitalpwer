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
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

class TransactionController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'add'],
                        'allow' => true,
                        'roles' => ['?'],
                        'ips' => ['127.0.0.1', '::1', '192.168.0.*'],
                    ],
                    [
                        'actions' => ['calculatebonus'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['PUT'],
                    'add' => ['post'],
                ],
            ],
        ];
    }

    private function calculatebonus($amount)
    {
        // Retrieve the user and its level 4 referrer
        $user = User::findOne(Yii::$app->user->id);
        $levelReferrer = $this->getLevelReferrer($user);
        // return json_encode(array("userid"=>Yii::$app->user->id,"result"=>$level4Referrer, "data"=>$user->referral));
        if ($levelReferrer) {
            foreach ($levelReferrer as $referrer) {
                $userReferrer = User::findOne(['user_id' => $referrer['user']]);
                if ($userReferrer !== null) {
                    // $userReferrer = User::findOne(['user_id'=>$referrer->user]);                
                    // Calculate bonus for level  referrer
                    $bonusAmount = $this->calculateBonusAmount($amount, $referrer['level']);
                    // return json_encode(array("userid"=>$userReferrer->user_id,"result"=>$bonusAmount, "data"=>$userReferrer->balance_bonus));

                    $userReferrer->balance_bonus = $userReferrer->balance_bonus + $bonusAmount;
                    $userReferrer->save();

                    // Log the bonus transaction
                    $this->logBonusTransaction($userReferrer->user_id, $bonusAmount);
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

        $referrers = [];
        array_push($referrers, ['user' => $referrer->user_id, 'level' => $level]);
        while ($referrer !== null) { // Check if $referrer is not null instead of $level
            $referrer = User::findOne(['user_referral' => $referrer->referral]);
            $level++;
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
                return $amount * 0.025; // 2% bonus for level 3
            case 4:
                return $amount * 0.025; // 2.5% bonus for level 4
                // Add cases for other levels as needed
            default:
                return 0; // No bonus for other levels
        }
    }

    private function logBonusTransaction($userId, $amount)
    {
        // Implement code to log bonus transactions to your database
        // For example, you can use Yii2's ActiveRecord to create a new log record
        $log = new Logs();
        $log->attributes = array(
            'user_id' => $userId,
            'type' => 'BONUS',
            'amount' => $amount
        );
        $log->save();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        return "OK";
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = User::findOne($_POST['user_id']);
        $transaction = new Transaction();
        $transaction->scenario = Transaction::SCENARIO_CREATE;
        $orderID = $_POST['id'];
        $amount =  $_POST['amount'];
        if ($_POST['user_id'] != 'Wt0k8G_E1y') {
            if ($amount >= 100000 && $amount <= 1100000) {
                $amount = $amount - 100000;
            } else if ($amount >= 1300000 && $amount <= 5300000) {
                $amount = $amount - 200000;
            } else if ($amount >= 5400000) {
                $amount = $amount - 300000;
            }
        }

        $responseBody  = \Midtrans\Transaction::status($orderID);

        if (
            $responseBody->fraud_status == "accept" &&
            ($responseBody->transaction_status == "success" ||
                $responseBody->transaction_status == "capture" ||
                $responseBody->transaction_status == "settlement")
        ) {

            $this->calculatebonus($amount);
            $convertedString = ucwords(str_replace("_", " ", $responseBody->payment_type));
            $transaction->attributes = array(
                'id' => $responseBody->transaction_id,
                'user_id' => $user->user_id,
                'target_id' => $user->user_id,
                'method' => $convertedString,
                'type' => "DEPOSIT",
                'amount' => $amount
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
        } else {
            return array('error' => 'Transaction Invalid');
        }
    }
}
