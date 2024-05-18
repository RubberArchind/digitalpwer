<?php

use app\models\Transaction;
use yii\helpers\Html;
use app\models\User;
use app\models\Logs;
use kartik\money\MaskMoney;
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */

$this->title = 'digitalpwer';
$this->params['state'] = 'dashboard';
$user = User::findOne(Yii::$app->user->id);

$bonuses = Logs::findAll(['user_id' => $user->user_id]);
$bonus_value = array();
$bonus_date = array();
foreach ($bonuses as $data) {
    $dateTime = new DateTime($data->time);
    $dateOnly = $dateTime->format('Y-m-d');
    $where = array_search($dateOnly, ($bonus_date));
    if ($where !== false) {
        $bonus_value[$where] += (int)$data->amount;
    } else {
        array_push($bonus_value, (int)$data->amount);
        array_push($bonus_date, $dateOnly);
    }
}
// Yii::$app->formatter->locale = 'id-ID';     
?>
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="Mid-client-ykXjhv_g8Ja88RAK"></script>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="copyToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                <rect width="100%" height="100%" fill="#007aff"></rect>
            </svg>
            <strong class="mr-auto">Info</strong>
            <small><?php echo Yii::$app->formatter->asRelativeTime(strtotime("now")); ?></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="toast-body">
            Referral Copied
        </div>
    </div>
</div>

<div id="depositModal" class="modal fade bd-example-modal-lg" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'deposit-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'options' => [
                    'class' => 'needs-validation',
                    'novalidate' => ''
                ]
            ]); ?>
            <div class="modal-header">
                <h5 class="modal-title">Deposit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-4">
                    <?php
                    echo MaskMoney::widget([
                        'name' => 'DepositForm[amount]',
                        'value' => 200000,
                        'options' => [
                            'placeholder' => 'Amount',
                        ],
                        'pluginOptions' => [
                            'prefix' => 'Rp ',
                            'thousands' => '.',
                            'decimal' => ',',
                            'precision' => 0,
                            'allowZero' => false,
                            'allowEmpty' => false
                        ],
                    ]);
                    ?>
                </div>

                <p>* Proses deposit akan otomatis masuk dalam 1-5 menit</p>
                <p>** Fee admin akan otomatis terpotong dari nilai deposit Anda</p>
                <p>*** Jika dalam waktu 5 menit deposit belum masuk hubungi CS</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <!-- <button id="btnConfirm" type="submit" class="btn btn-primary"></button> -->
                <?php echo Html::submitButton('Confirm', array('class' => 'btn btn-primary', 'name' => 'submit-button')) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div id="withdrawModal" class="modal fade bd-example-modal-lg" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'withdraw-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'options' => [
                    'class' => 'needs-validation',
                    'novalidate' => ''
                ]
            ]); ?>
            <div class="modal-header">
                <h5 class="modal-title">Withdraw</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-4">
                    <?php
                    echo MaskMoney::widget([
                        'name' => 'WithdrawForm[amount]',
                        'value' => 200000,
                        'options' => [
                            'placeholder' => 'Amount'
                        ],
                        'pluginOptions' => [
                            'prefix' => 'Rp ',
                            'thousands' => '.',
                            'decimal' => ',',
                            'precision' => 0,
                            'allowZero' => false,
                            'allowEmpty' => false,
                            'allowNegative' => false
                        ],
                    ]);
                    ?>
                </div>
                <p>* Proses withdraw akan otomatis masuk dalam 1-5 menit</p>
                <p>** Jika dalam waktu 5 menit deposit belum masuk hubungi CS</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <?php echo Html::submitButton('Confirm', array('class' => 'btn btn-primary', 'name' => 'submit-button')) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <!-- <?= var_dump($bonus_value); ?> -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Deposit</h5>
                            </div>
                            <?php Pjax::begin(['id' => 'deposit']) ?>
                            <h3><span class=""><?php echo Yii::$app->formatter->asCurrency($user->balance_deposit, 'IDR'); ?></span></h3>
                            <?php Pjax::end() ?>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-6 col-lg-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Sales</h5>
                                <span class="badge badge-warning">Anual</span>
                            </div>
                            <h3>$<span class="counter">25100</span></h3>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <p class="mb-0">Total Revenue</p>
                                <span class="text-warning">35%</span>
                            </div>
                            <div class="iq-progress-bar bg-warning-light mt-2">
                                <span class="bg-warning iq-progress progress-1" data-percent="35"></span>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="col">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Bonus</h5>
                                <!-- <span class="badge badge-success">Today</span> -->
                            </div>
                            <?php Pjax::begin(['id' => 'bonus']) ?>
                            <h3><span><?php echo Yii::$app->formatter->asCurrency($user->balance_bonus, 'IDR'); ?></span></h3>
                            <?php Pjax::end() ?>
                            <!-- <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-success">85%</span>
                        </div>
                        <div class="iq-progress-bar bg-success-light mt-2">
                            <span class="bg-success iq-progress progress-1" data-percent="85"></span>
                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Cashback</h5>
                                <!-- <span class="badge badge-success">Today</span> -->
                            </div>
                            <?php Pjax::begin(['id' => 'bonus']) ?>
                            <h3><span><?php echo Yii::$app->formatter->asCurrency($user->balance_cashback, 'IDR'); ?></span></h3>
                            <?php Pjax::end() ?>
                            <!-- <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-success">85%</span>
                        </div>
                        <div class="iq-progress-bar bg-success-light mt-2">
                            <span class="bg-success iq-progress progress-1" data-percent="85"></span>
                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Funds</h5>
                            </div>
                            <div class="btn-group btn-group-toggle btn-group-edges">
                                <!-- <div class="row gx-1"> -->
                                <button id="btnDeposit" type="button" class="mt-2 btn btn-success"><i class="ri-upload-2-line"></i>Deposit</button>
                                <button type="button" class="mt-2 btn btn-danger " data-toggle="modal" data-target="#withdrawModal"><i class="ri-download-2-line"></i>Withdraw</button>
                                <!-- </div>                             -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="col-xl-8">
                <div class="card-transparent card-block card-stretch card-height">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <?=
                                            Highcharts::widget([
                                                'options' => [
                                                    'type' => 'line',
                                                    'credits' => ['enabled' => false],
                                                    'dataLabels' => ['enabled' => false],
                                                    'legend' => ['enabled' => false],
                                                    'title' => ['text' => 'Bonus & Cashback', 'align' => 'left'],
                                                    'xAxis' => [
                                                        'categories' => $bonus_date
                                                    ],
                                                    'yAxis' => [
                                                        'title' => ['text' => 'Rp (Rupiah)']
                                                    ],
                                                    'series' => [
                                                        ['name' => 'Cashback & Bonus', 'data' => $bonus_value],
                                                    ]
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TIMELINE SECTION -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="top-block d-flex align-items-center justify-content-between">
                                            <h5>Rewards</h5>
                                        </div>
                                        <div class="container-fluid py-5">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <div class="horizontal-timeline">

                                                        <ul class="list-inline items">
                                                            <li class="list-inline-item items-list">
                                                                <div class="px-4">
                                                                    <div class="event-date badge bg-info"><i class="ri-checkbox-blank-circle-fill"></i></div>
                                                                    <h5 class="pt-2">1 M</h5>
                                                                    <p class="text-muted">Sepeda Motor<br></p>
                                                                    <img class="card-img" src="/images/yamaha_aerox.png" alt="Yamaha Aerox">
                                                                    <div>
                                                                        <?php
                                                                        if ($user->balance_bonus >= 1000000000) {
                                                                            echo '<a class="btn btn-primary btn-sm" onClick="claimReward()">Claim</a>';
                                                                        } else {
                                                                            echo '<a href="#" class="btn btn-light btn-sm" disabled>Claim</a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-inline-item items-list">
                                                                <div class="px-4">
                                                                    <div class="event-date badge bg-success"><i class="ri-checkbox-blank-circle-fill"></i></div>
                                                                    <h5 class="pt-2">10 M</h5>
                                                                    <p class="text-muted">Mobil Sigra / Ayla<br></p>
                                                                    <img class="card-img" src="/images/mobil_sigra.png" alt="Yamaha Aerox">
                                                                    <div>
                                                                        <?php
                                                                        if ($user->balance_bonus >= 10000000000) {
                                                                            echo '<a class="btn btn-primary btn-sm" onClick="claimReward()">Claim</a>';
                                                                        } else {
                                                                            echo '<a href="#" class="btn btn-light btn-sm" disabled>Claim</a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-inline-item items-list">
                                                                <div class="px-4">
                                                                    <div class="event-date badge bg-danger"><i class="ri-checkbox-blank-circle-line"></i></div>
                                                                    <h5 class="pt-2">20 M</h5>
                                                                    <p class="text-muted">Mobil Avanza / Xenia<br></p>
                                                                    <img class="card-img" src="/images/mobil_xenia.png" alt="Yamaha Aerox">
                                                                    <div>
                                                                        <?php
                                                                        if ($user->balance_bonus >= 20000000000) {
                                                                            echo '<a href="#" class="btn btn-primary btn-sm">Claim</a>';
                                                                        } else {
                                                                            echo '<a href="#" class="btn btn-light btn-sm" disabled>Claim</a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-inline-item items-list">
                                                                <div class="px-4">
                                                                    <div class="event-date badge bg-warning"><i class="ri-checkbox-blank-circle-line"></i></div>
                                                                    <h5 class="pt-2">40 M</h5>
                                                                    <p class="text-muted">Mobil Fortuner / Pajero</p>
                                                                    <img class="card-img" src="/images/mobil_pajero.png" alt="Yamaha Aerox">
                                                                    <div>
                                                                        <?php
                                                                        if ($user->balance_bonus >= 40000000000) {
                                                                            echo '<a class="btn btn-primary btn-sm" onClick="claimReward()">Claim</a>';
                                                                        } else {
                                                                            echo '<a href="#" class="btn btn-light btn-sm" disabled>Claim</a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="col">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="top-block d-flex align-items-center justify-content-between">
                                <h5>Referral </h5>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="refCode" disabled="" value="<?php echo $user->user_referral; ?>">
                                <div class="input-group-append">
                                    <button id="copyToastBtn" class="btn btn-success text-center d-flex" type="button" onclick="copyRef()"><i class="las la-copy font-size-20 align-items-center" style="margin-top: 0.5em;"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="card border-bottom pb-2 shadow-none">
                            <div class="card-body text-center inln-date flet-datepickr">
                                <input type="text" id="inline-date" class="date-input basicFlatpickr d-none" readonly="readonly">
                            </div>
                        </div>
                        <div class="card card-list">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <svg class="svg-icon text-secondary mr-3" width="24" height="24" viewBox="0 0 239.563 239.5634" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path d="M146.962,36.978h-1.953L85.568,69.611H42.605C19.113,69.611,0,88.723,0,112.216c0,21.012,15.301,38.474,35.334,41.943
				L21.56,202.585h47.523l13.584-47.756h2.901l59.443,32.628h1.953c12.585,0,22.826-10.239,22.826-22.826V59.803
				C169.787,47.219,159.546,36.978,146.962,36.978z M57.592,187.366H41.71l8.352-29.364h15.882L57.592,187.366z M109.459,150.581
				l-19.988-10.972H42.605c-15.103,0-27.388-12.29-27.388-27.393c0-15.103,12.285-27.388,27.388-27.388h46.866l19.988-10.974
				V150.581z M154.57,164.631c0,3.637-2.567,6.683-5.978,7.431l-23.916-13.127V65.502l23.916-13.13
				c3.414,0.748,5.978,3.797,5.978,7.434V164.631z" />
                                                    <path d="M198.989,79.377L188.106,90.26c5.623,7.789,8.976,17.32,8.976,27.637c0,10.32-3.353,19.851-8.976,27.637l10.883,10.883
				c8.326-10.629,13.31-24,13.31-38.52C212.299,103.377,207.315,90.007,198.989,79.377z" />
                                                    <path d="M218.358,60.009l-10.794,10.794c10.482,12.856,16.782,29.252,16.782,47.094c0,17.845-6.3,34.238-16.782,47.094
				l10.794,10.794c13.216-15.648,21.205-35.849,21.205-57.888S231.574,75.657,218.358,60.009z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <!-- <i class="las la-bullhorn text-secondary mr-3" style="width: 32px; height: 32px;"></i> -->

                                    <div class="pl-3 border-left">
                                        <h5 class="mb-1">Announcement</h5>
                                        <p class="mb-0">Kami sedang melakukan perbaikan, berlangsung hingga 18-05-2024 22:00, harap abaikan jika ada error atau aneh.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-list">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <svg class="svg-icon text-warning mr-3" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <div class="pl-3 border-left">
                                        <h5 class="mb-1">Email CS</h5>
                                        <p class="mb-0">info@digitalpwer.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-list mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <svg class="svg-icon text-success mr-3" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <div class="pl-3 border-left">
                                        <h5 class="mb-1">Telepon CS</h5>
                                        <p class="mb-0">+6591378150</p>
                                        <p class="mb-0">+6281231365259</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-lg-12">
                <div class="card-transparent mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between p-0 pb-3">
                        <div class="header-title">
                            <h4 class="card-title">Current Projects</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                            <div id="top-project-slick-arrow" class="slick-aerrow-block">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-unstyled row top-projects mb-0">
                            <li class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Hotel Management App UI Kit</h5>
                                        <p class="mb-3"><i class="las la-calendar-check mr-2"></i>02 / 02 / 2021</p>
                                        <div class="iq-progress-bar bg-secondary-light mb-4">
                                            <span class="bg-secondary iq-progress progress-1" data-percent="65" style="transition: width 2s ease 0s; width: 65%;"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="iq-media-group">
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/01.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/02.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/03.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/04.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="btn bg-secondary-light">Design</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">General Improvement in pages</h5>
                                        <p class="mb-3"><i class="las la-calendar-check mr-2"></i>02 / 02 / 2021</p>
                                        <div class="iq-progress-bar bg-info-light mb-4">
                                            <span class="bg-info iq-progress progress-1" data-percent="65" style="transition: width 2s ease 0s; width: 65%;"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="iq-media-group">
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/05.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/06.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/07.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/08.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="btn bg-info-light">Testing</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Product list view changes</h5>
                                        <p class="mb-3"><i class="las la-calendar-check mr-2"></i>02 / 02 / 2021</p>
                                        <div class="iq-progress-bar bg-success-light mb-4">
                                            <span class="bg-success iq-progress progress-1" data-percent="65" style="transition: width 2s ease 0s; width: 65%;"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="iq-media-group">
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/03.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/04.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/05.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/06.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="btn bg-success-light">SEO</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Add Multiple theme options</h5>
                                        <p class="mb-3"><i class="las la-calendar-check mr-2"></i>02 / 02 / 2021</p>
                                        <div class="iq-progress-bar bg-warning-light mb-4">
                                            <span class="bg-warning iq-progress progress-1" data-percent="65" style="transition: width 2s ease 0s; width: 65%;"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="iq-media-group">
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/01.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/02.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/03.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/04.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="btn bg-warning-light">Development</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Admin Panel Customization</h5>
                                        <p class="mb-3"><i class="las la-calendar-check mr-2"></i>02 / 02 / 2021</p>
                                        <div class="iq-progress-bar bg-primary-light mb-4">
                                            <span class="bg-primary iq-progress progress-1" data-percent="65"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="iq-media-group">
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/01.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/02.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/03.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                                <a href="#" class="iq-media">
                                                    <img src="/images/user/04.jpg" class="img-fluid avatar-40 rounded-circle" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="btn bg-primary-light">Content</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- Page end  -->
    </div>
</div>

<!-- Wrapper End-->


<!-- <footer class="iq-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="../backend/privacy-policy.html">Privacy Policy</a></li>
                    <li class="list-inline-item"><a href="../backend/terms-of-service.html">Terms of Use</a></li>
                </ul>
            </div>
            <div class="col-lg-6 text-right">
                <span class="mr-1">
                    <script>
                        document.write(new Date().getFullYear())
                    </script>©
                </span> <a href="#" class="">Webkit</a>.
            </div>
        </div>
    </div>
</footer> -->
<script>
    window.setInterval(function() {
        try {
            $.pjax.reload({
                container: "#deposit",
                async: false,
                timeout: false
            });
        } catch (error) {

        }

    }, 5000);
    window.setInterval(function() {
        try {
            $.pjax.reload({
                container: "#bonus",
                async: false,
                timeout: false
            });
        } catch (error) {

        }

    }, 10000);
</script>