<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\popover\PopoverX;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */


$this->title = 'Digitalpwer';
$this->params['state'] = 'signup';

$url = \Yii::$app->urlManager->baseUrl . '/images/banks/';
$format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text; 
    src = '$url' +  state.id.toLowerCase() + '.png'
    return '<img class="flag" src="' + src + '" width="32" height="32"/>' + ' ' +state.text;
}
SCRIPT;
$this->registerJs($format, $this::POS_HEAD);
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
                                        <h2 class="mb-2 text-white">Sign Up</h2>
                                        <p>Daftarkan diri kamu ke Digitalpwer.</p>
                                        <?php $form = ActiveForm::begin([
                                            'id' => 'signup-form',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => false,
                                            'options' => [
                                                'class' => 'needs-validation',
                                                'novalidate' => ''
                                            ]
                                        ]); ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="floating-label form-group">
                                                    <input id="fullname" name="FormSignup[fullname]" class="floating-input form-control" type="text" placeholder=" " required>
                                                    <label>Full Name</label>
                                                    <div id="fullname-tooltip" class="invalid-tooltip">
                                                        Please provide a Full Name
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="floating-label form-group">
                                                    <input id="email" name="FormSignup[email]" class="floating-input form-control" type="email" placeholder=" " required>
                                                    <label>Email</label>
                                                    <div id="email-tooltip" class="invalid-tooltip">
                                                        Please provide a valid email
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="floating-label form-group">
                                                    <input name="FormSignup[phone]" id="phone" class="floating-input form-control" type="text" placeholder=" " required>
                                                    <label>Phone</label>
                                                    <div id="phone-tooltip" class="invalid-tooltip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="floating-label form-group">
                                                    <input id="password" name="FormSignup[password]" class="floating-input form-control" type="password" placeholder=" " required>
                                                    <label>Password</label>
                                                    <div id="password-tooltip" class="invalid-tooltip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="floating-label form-group">
                                                    <input id="confirm_password" name="FormSignup[confirm_password]" class="floating-input form-control" type="password" placeholder=" " required>
                                                    <label>Confirm Password</label>
                                                    <div id="confirm_password-tooltip" class="invalid-tooltip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="dropdownParent" class="col-lg-12">
                                                <div class="form-group">
                                                    <!-- <input name="" class="floating-input form-control" type="text" placeholder="BCA" required> -->
                                                    <select id="bank_code" name="FormSignup[bank_code]" class="form-select" data-placeholder="Pilih Bank">                                                        
                                                        <option></option>
                                                        <option value="bca">BCA</option>
                                                        <option value="bri">BRI</option>
                                                        <option value="bni">BNI</option>
                                                    </select>
                                                    <div id="bank_code-tooltip" class="invalid-tooltip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="floating-label form-group">
                                                    <input id="bank_number" name="FormSignup[bank_number]" class="floating-input form-control" type="text" placeholder=" " required>
                                                    <label>Nomor Rekening</label>
                                                    <div id="bank_number-tooltip" class="invalid-tooltip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="floating-label form-group">
                                                    <input id="referral" name="FormSignup[referral]" class="floating-input form-control" type="text" placeholder=" ">
                                                    <label>Kode Referral (opsional)</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" required>
                                                    <label class="custom-control-label text-white" for="customCheck1">I agree with the terms of use</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo Html::submitButton('Signup', array('class' => 'btn btn-white', 'name' => 'signup-button')) ?>

                                        <p class="mt-3">
                                            Already have an Account <a href="/auth" class="text-white text-underline">Sign In</a>
                                        </p>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 content-right">
                                    <img src="/images/login/01.png" class="img-fluid image-right" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function preventNonAlpha(event) {
        var value = String.fromCharCode(event.which);
        var pattern = new RegExp(/[a-zåäö ]/i);
        return pattern.test(value);
    }

    function preventNonNumeric(event) {
        var value = String.fromCharCode(event.which);
        var pattern = new RegExp(/[0-9]+/g);
        return pattern.test(value);
    }
</script>