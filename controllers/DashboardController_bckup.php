<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\DepositForm;
use app\models\WithdrawForm;
use app\models\User;

// XENDIT SETUP
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Payout\PayoutApi;
use Xendit\Payout\CreatePayoutRequest;

Configuration::setXenditKey('xnd_development_i1OBQHWUlcDc15iJC09x63l780OOAs9p1rFcAEVNpbFN0XZuQ7DLskNnM3IWJNW');

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
                'only' => ['index', 'topup', 'earn', 'transaction'],
                'rules' => [
                    [
                        'actions' => ['index', 'topup', 'earn', 'transaction'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        function generateRandomString($length = 10)
        {
            $bytes = random_bytes(ceil($length / 2));
            $randomString = substr(bin2hex($bytes), 0, $length);

            return $randomString;
        }

        $model = new DepositForm();
        $modelForm = new WithdrawForm();
        $postData = Yii::$app->request->post();

        if ($postData) {
            // THIS PART FOR DEPOSIT PROCESS
            if ($model->load($postData)  && $model->validate()) {
                $apiInstance = new InvoiceApi();
                $create_invoice_request = new CreateInvoiceRequest([
                    'external_id' => 'test1234',
                    'description' => 'Test Invoice',
                    'amount' => $model->amount,
                    'invoice_duration' => 172800,
                    'currency' => 'IDR',
                    'reminder_time' => 1
                ]);
                try {
                    $result = $apiInstance->createInvoice($create_invoice_request);
                    $jsonRes = json_decode($result);
                    if (isset($jsonRes->invoice_url)) {
                        return json_encode(array("url" => $jsonRes->invoice_url));
                    } else {
                        return json_encode($result);
                    }
                } catch (\Xendit\XenditSdkException $e) {
                    echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
                    return json_encode($e->getFullError());
                }
            } else {
                // THIS PART FOR WITHDRAW
                $NOW = strtotime('NOW');
                $user = User::findOne(Yii::$app->user->id);
                $usertime = strtotime($user->signup_time);

                $year1 = date("Y", $NOW);
                $month1 = date("n", $NOW);

                $year2 = date("Y", $usertime);
                $month2 = date("n", $usertime);

                $total_months = abs(($year2 - $year1) * 12 + ($month2 - $month1));
                    
                if ($total_months <= 1) {
                    return json_encode(array("isValid" => false,  "error" => array("errorCode" => "Umur akun anda belum lebih dari 30 hari")));
                } else {
                    $modelForm->load($postData);
                    // $modelForm->amount = (int) $modelForm->amount;
                    if ($modelForm->validate()) {
                        $idempotency_key = "WD-" . generateRandomString();
                        $payoutInstance = new PayoutApi();
                        $create_payout_request = new CreatePayoutRequest([
                            'reference_id' => 'DISB-' . generateRandomString(),
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
        }
        return $this->render('index');
    }

    /**
     * Displays topup page.
     *
     * @return string
     */
    public function actionTopup()
    {
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
