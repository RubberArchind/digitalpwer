<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = 'Digitalpwer';
$this->params['state'] = 'signin';
?>

<div class="wrapper">
    <section class="login-content">
        <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
                <div class="col-lg-8">
                    <div class="card auth-card">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center auth-content">
                                <div class="col-lg-6 bg-primary content-left">
                                    <div class="p-3">
                                        <h2 class="mb-2 text-white">Sign In</h2>
                                        <p>Login to stay connected.</p>
                                        <?php $form = ActiveForm::begin([
                                            'id' => 'login-form',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => false,
                                            'options' => [
                                                'class' => 'needs-validation',
                                                'novalidate' => ''
                                            ]
                                        ]); ?>
                                        <div class="row">
                                            <p id="wrongLogin" class="bg-danger-light pl-3 pr-3 pt-2 pb-2 rounded" style="display: none;">Email atau password salah.</p>
                                            <div class="col-lg-12">
                                                <div class="floating-label form-group">
                                                    <input id="email" name="LoginForm[username]" class="floating-input form-control" type="email" placeholder=" " required>
                                                    <label>Email</label>
                                                    <div id="email-tooltip" class="invalid-tooltip">
                                                        Please provide a valid Email
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="floating-label form-group">
                                                    <input id="password" name="LoginForm[password]" class="floating-input form-control" type="password" placeholder=" " required>
                                                    <label>Password</label>
                                                    <div id="password-tooltip" class="invalid-tooltip">
                                                        Please provide a valid password
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label control-label-1 text-white" for="customCheck1">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <a href="auth-recoverpw.html" class="text-white float-right">Forgot Password?</a>
                                            </div>
                                        </div>

                                        <?php echo Html::submitButton('Sign In', array('class' => 'btn btn-white', 'name' => 'login-button')) ?>

                                        <p class="mt-3">
                                            Create an Account <a href="auth/signup" class="text-white text-underline">Sign Up</a>
                                        </p>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 content-right">
                                    <img src="images/login/01.png" class="img-fluid image-right" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>