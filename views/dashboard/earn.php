<?php

use app\models\User;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

/** @var yii\web\View $this */

$this->title = 'Digitalpwer';
$this->params['state'] = 'dashboard';
$user = User::findOne(Yii::$app->user->id);

$dates = array();

$level1 = User::findAll(['referral' => $user->user_referral]);

$level2 = [];
foreach ($level1 as $user1) {
    $tmp = User::findAll(['referral' => $user1->user_referral]);
    $level2 = array_merge($level2, $tmp);

    $dateTime = new DateTime($user1->signup_time);
    $dateOnly = $dateTime->format('Y-m-d');
}

$level3 = [];
foreach ($level2 as $user2) {
    $tmp = User::findAll(['referral' => $user2->user_referral]);
    $level3 = array_merge($level3, $tmp);

    $dateTime = new DateTime($user2->signup_time);
    $dateOnly = $dateTime->format('Y-m-d');
    array_push($dates, $dateOnly);
}

$level4 = [];
foreach ($level3 as $user3) {
    $tmp = User::findAll(['referral' => $user3->user_referral]);
    $level4 = array_merge($level4, $tmp);
}

sort($dates);
?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between breadcrumb-content">
                            <h5>Your Referrals</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Level 1 -->
            <div class="col-md-3 col-lg-3 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Level 1</h5>
                        <p class="mb-3"><i class="las la-user mr-2"></i><?= count($level1); ?> Orang</p>
                        <div class="iq-progress-bar bg-success-light mb-4">
                            <span class="bg-success iq-progress progress-1" data-percent="100" style="transition: width 2s ease 0s; width: 65%;"></span>
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
                                <a href="#" class="btn bg-success-light">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Level 2 -->
            <div class="col-md-3 col-lg-3 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Level 2</h5>
                        <p class="mb-3"><i class="las la-user mr-2"></i><?= count($level2); ?> Orang</p>
                        <div class="iq-progress-bar bg-primary-light mb-4">
                            <span class="bg-primary-light iq-progress progress-1" data-percent="100" style="transition: width 2s ease 0s; width: 65%;"></span>
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
                                <a href="#" class="btn bg-primary-light">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Level 3 -->
            <div class="col-md-3 col-lg-3 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Level 3</h5>
                        <p class="mb-3"><i class="las la-user mr-2"></i><?= count($level3); ?> Orang</p>
                        <div class="iq-progress-bar bg-warning-light mb-4">
                            <span class="bg-warning-light iq-progress progress-1" data-percent="100" style="transition: width 2s ease 0s; width: 65%;"></span>
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
                                <a href="#" class="btn bg-warning-light">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Level 4 -->
            <div class="col-md-3 col-lg-3 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Level 4</h5>
                        <p class="mb-3"><i class="las la-user mr-2"></i><?= count($level4); ?> Orang</p>
                        <div class="iq-progress-bar bg-secondary-light mb-4">
                            <span class="bg-secondary iq-progress progress-1" data-percent="100" style="transition: width 2s ease 0s; width: 65%;"></span>
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
                                <a href="#" class="btn bg-secondary-light">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?=
                            Highcharts::widget([
                                'options' => [
                                    'type' => 'pie',
                                    'credits' => ['enabled' => false],
                                    'dataLabels' => ['enabled' => false],
                                    'title' => ['text' => 'Referrals Tracking', 'align' => 'left'],
                                    'series' => [
                                        [
                                            'type' => 'pie',
                                            'name' => 'Referrarls',
                                            'colorByPoint' => true,
                                            'allowPointSelect' => true,
                                            'cursor' => 'pointer',
                                            'data' => [
                                                [
                                                    'name' => "Level 1",
                                                    'y' => count($level1),
                                                    'color' => new JsExpression('Highcharts.getOptions().colors[0]')
                                                ],
                                                [
                                                    'name' => "Level 2",
                                                    'y' => count($level2),
                                                    'color' => new JsExpression('Highcharts.getOptions().colors[1]')
                                                ],
                                                [
                                                    'name' => "Level 3",
                                                    'y' => count($level3),
                                                    'color' => new JsExpression('Highcharts.getOptions().colors[2]')
                                                ],
                                                [
                                                    'name' => "Level 4",
                                                    'y' => count($level4),
                                                    'color' => new JsExpression('Highcharts.getOptions().colors[3]')
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>