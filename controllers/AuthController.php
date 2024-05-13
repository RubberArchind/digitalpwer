<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\FormSignup;
use app\models\User;
use yii\base\Security;
use yii\helpers\Url;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
        return $this->actionLogin();
        // return $this->render('index');
    }

    public function actionSignup()
    {
        $model = new FormSignup();
        $postData = Yii::$app->request->post();
        try {
            if ($model->load($postData) && $model->validate()) {
                $model->attributes($postData);
                $newUser = new User();
                // $newUser->load($postData);
                $newUser->attributes = array(
                    'user_id' => Yii::$app->getSecurity()->generateRandomString(10),
                    'full_name' => $model->fullname,
                    'email' => $model->email,
                    'username' => $model->email,
                    'phone' => $model->phone,
                    'bank_code' => $model->bank_code,
                    'bank_number' => $model->bank_number,
                    'password' => password_hash($model->password, PASSWORD_DEFAULT),
                    'referral' => (isset($model->referral)) ? $model->referral : '3ONQDK',
                    'user_referral' => strtoupper($this->generate_referral())
                );

                $isNew = $model->isNewUser();
                if(!$isNew){
                    return json_encode(array('errors'=>['email'=>'Email sudah didaftarkan.']));
                }
                $newUser->save(false);
                if (is_null($newUser->getPrimaryKey())) {
                    return json_encode(array(
                        'user' => $newUser,
                        'attributes' => $newUser->attributes,
                        'primary' => $newUser->getPrimaryKey(),
                        'REF' => $this->generate_referral()
                    ));
                } else {
                    $loginModel = new LoginForm();
                    $loginModel->attributes = array(
                        'username' => $model->email,
                        'email' => $model->email,
                        'password' => $model->password
                    );
                    if ($loginModel->validate()) {
                        $loginModel->login();
                    } else {
                        return json_encode(array("errors" => $loginModel->getErrors()));
                    }

                    $url = Yii::$app->urlManager->createAbsoluteUrl('/dashboard');
                    $this->redirect($url);
                }
                // return json_encode(array('success' => true));
            } else if (count($model->getErrors()) > 0) {
                return json_encode(array('errors' => $model->getErrors()));
                // return "INVALID DATA" . var_dump($model->getErrors());             
            }

            return $this->render('signup', [
                'model' => $model,
            ], null, null);
        } catch (\Exception $e) {
            return json_encode(array('Message' => $e->getMessage()));
        }
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
        $postData = Yii::$app->request->post();

        if ($postData) {
            try{
                $model->load($postData);
                $model->email = $model->username;
                if ($model->validate()) {
                    
                        return json_encode(array("data" => $model, "error" => $model->getErrors(), "login" => $model->login()));
                } else {
                    return json_encode(array("errors" => $model->getErrors()));
                }
            } catch (\Exception $e) {
                    return json_encode(array('login'=>false, 'errors' => array('password'=>$e->getMessage())));
            }
        }

        $model->password = '';
        return $this->render('index', [
            'model' => $model,
        ], null, null);
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

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function checkReferral($s)
    {
        return User::find()->where(['user_referral' => $s])->exists();
    }

    /**
     * Generate Referral Function
     * 
     * @return string
     */
    public function generate_referral()
    {
        $generatedS = Yii::$app->getSecurity()->generateRandomString(6);
        while ($this->checkReferral(strtoupper($generatedS))) {
            $generatedS = Yii::$app->getSecurity()->generateRandomString(6);
        }

        return $generatedS;
    }
}
