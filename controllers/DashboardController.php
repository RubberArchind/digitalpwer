<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\DepositForm;
use app\models\TopupForm;
use app\models\WithdrawForm;
use app\models\User;
use app\models\TrxMap;
use Ramsey\Uuid\Uuid;

// XENDIT SETUP
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Payout\PayoutApi;
use Xendit\Payout\CreatePayoutRequest;

// Configuration::setXenditKey('xnd_development_i1OBQHWUlcDc15iJC09x63l780OOAs9p1rFcAEVNpbFN0XZuQ7DLskNnM3IWJNW');

//MIDTRANS
// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'Mid-server-B0QsK6afVyTfe_Ue_PQbxjYw';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = true;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

class DashboardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'topup', 'earn', 'transaction', 'depo'],
                'rules' => [
                    [
                        'actions' => ['index', 'topup', 'earn', 'transaction', 'depo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    // throw new \Exception('You are not allowed to access this page');    
                    return $this->redirect(['/auth']);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function depo()
    {
        $postData = Yii::$app->request->post();
        var_dump($postData);
    }

    private function generateRandomString($length = 10)
    {
        $bytes = random_bytes(ceil($length / 2));
        $randomString = substr(bin2hex($bytes), 0, $length);

        return $randomString;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    //ADD LIMIT to 10jT deposit
    public function actionIndex()
    {
        $model = new DepositForm();
        $modelForm = new WithdrawForm();
        $postData = Yii::$app->request->post();

        if ($postData) {
            // THIS PART FOR DEPOSIT PROCESS
            if ($model->load($postData)  && $model->validate()) {
                $user = User::findOne(Yii::$app->user->id);
                $items = array(
                    array(
                        'id'       => 'DEPO' . rand(),
                        'price'    => $model->amount,
                        'quantity' => 1,
                        'name'     => 'DEPOSIT'
                    ),
                );
                $customer_details = array(
                    'first_name'       => $user->user_id,
                    'last_name'        => $user->username
                );
                $order_id = Uuid::uuid4()->toString();
                $params = array(
                    'customer_details' => $customer_details,
                    'item_details' => $items,
                    'transaction_details' => array(
                        'order_id' => $order_id,
                        'gross_amount' =>  $model->amount,
                    )
                );

                try {
                    // Get Snap Payment Page URL
                    // $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;                                       
                    $txmap = new TrxMap();
                    $txmap->attributes = array(
                        'user_id' => $user->user_id,
                        'id' => $order_id,
                    );
                    $txmap->save();
                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    return json_encode(array("token" => $snapToken));
                } catch (\Exception $e) {
                    echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
                    return json_encode($e->getMessage());
                }
            } else {
                // THIS PART FOR WITHDRAW
                $NOW = strtotime('NOW');
                $user = User::findOne(Yii::$app->user->id);
                if ($user->balance_deposit <= 200000) {
                    return json_encode(array("isValid" => false,  "error" => array("errorCode" => "Withdraw minimal 200,000")));
                }
                $usertime = strtotime($user->signup_time);

                $year1 = date("Y", $NOW);
                $month1 = date("n", $NOW);

                $year2 = date("Y", $usertime);
                $month2 = date("n", $usertime);

                $total_months = abs(($year2 - $year1) * 12 + ($month2 - $month1));

                if ($total_months <= 1) {
                    return json_encode(array("isValid" => false,  "error" => array("errorCode" => "Anda harus terdaftar selama minimal 30 hari untuk withdraw")));
                } else {
                    $modelForm->load($postData);
                    return json_encode(array("isValid" => false,  "error" => array("errorCode" => "Fitur ini masih dalam pengembangan")));
                    // $modelForm->amount = (int) $modelForm->amount;
                    if ($modelForm->validate()) {
                        if ($user->balance_deposit < (int) $modelForm->amount) {
                            return json_encode(array("isValid" => false, "amount" => $modelForm->amount, "error" => array("errorCode" => "Saldo anda tidak mencukupi")));
                        }
                        return json_encode(array("isValid" => false,  "error" => array("errorCode" => "Maaf, Fitur ini masih dalam pengembangan")));

                        $idempotency_key = "WD-" . $this->generateRandomString();
                        $payoutInstance = new PayoutApi();
                        $create_payout_request = new CreatePayoutRequest([
                            'reference_id' => 'DISB-' . $this->generateRandomString(),
                            'currency' => 'IDR',
                            'channel_code' => 'ID_BRI',
                            'channel_properties' => [
                                'account_holder_name' => 'John Doe',
                                'account_number' => '000000'
                            ],
                            'amount' => (int) $modelForm->amount,
                            'description' => 'Test Bank Payout',
                            'type' => 'DIRECT_DISBURSEMENT'
                        ]);
                        try {
                            $result = $payoutInstance->createPayout($idempotency_key, null, $create_payout_request);
                            return json_encode(array('dt' => $result));
                        } catch (\Xendit\XenditSdkException $e) {
                            // echo 'Exception when calling PayoutApi->createPayout: ', $e->getMessage(), PHP_EOL;
                            // echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
                            return json_encode(array("error" => $e->getFullError()));
                        }
                    } else {
                        return json_encode(array('errors' => $modelForm->errors, 'isValid' => false, 'data' => $modelForm->amount, 'pd' => $postData));
                    }
                }
                // return json_encode(array("model" => $modelForm->errors, 'data' => Yii::$app->request->post('amount')));
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * Displays topup page.
     *
     * @return string
     */
    public function actionTopup()
    {
        $postData = Yii::$app->request->post();
        $model = new TopupForm();

        if ($postData) {
            $model->load($postData);
            if ($model->validate()) {
                $user = User::findOne(Yii::$app->user->id);
                if ($user->balance_bonus >= $model->amount) {

                    $username   = "08816239976";
                    $apiKey   = "67366051b757fc42";
                    $ref_id  = "TP" . $this->generateRandomString();
                    $signature  = md5($username . $apiKey . $ref_id);

                    $json = sprintf('{
                    "username" : "%s",
                    "ref_id"   : "%s", 
                    "customer_id"       : "%s",
                    "product_code": "%s",
                    "sign"     : "%s"
                    }', $username, $ref_id, $model->pnumber, $model->code, $signature);

                    // $json = sprintf('{
                    //     "commands" : "topup",
                    //     "username" : "%s",
                    //     "ref_id"   : "%s", 
                    //     "hp"       : "%s",
                    //     "pulsa_code": "%s",
                    //     "sign"     : "%s"
                    //     }', $username, $ref_id, $model->pnumber, $model->code, $signature);

                    $url = sprintf("https://prepaid.iak.dev/api/top-up");

                    $ch  = curl_init();
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $data = curl_exec($ch);
                    curl_close($ch);
                    $result = json_decode($data)->data;
                    //$result->status => 0:process, 1:success, 2:failed
                    if ($result->status == 1) {
                        $user->balance_bonus = $user->balance_bonus - $model->amount;
                        $user->save();
                    }
                    return json_encode(array("data" => json_decode($data)->data));
                } else {
                    return json_encode(array(
                        "error" => "Saldo tidak mencukupi",
                        "balance" => $user->balance_bonus,
                        "amount" => $model->amount
                    ));
                    // $params = array(
                    //     'transaction_details' => array(
                    //         'order_id' => rand(),
                    //         'gross_amount' =>  $model->amount,
                    //     )
                    // );

                    // try {
                    //     $snapToken = \Midtrans\Snap::getSnapToken($params);

                    //     return json_encode(array("token" => $snapToken));
                    // } catch (\Exception $e) {
                    //     echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
                    //     return json_encode($e->getMessage());
                    // }
                }
            } else {
                return json_encode(array("error" => $model->errors, "data" => $postData));
            }
        }

        return $this->render('topup');
    }

    /**
     * Displays referral page.
     *
     * @return string
     */
    public function actionEarn()
    {
        return $this->render('earn');
    }

    /**
     * Displays transaction page.
     *
     * @return string
     */
    public function actionTransaction()
    {
        return $this->render('transaction');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
