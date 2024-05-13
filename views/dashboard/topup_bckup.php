<?php

use yii\helpers\Html;
use app\models\User;
use PhpParser\Node\Stmt\TryCatch;

/** @var yii\web\View $this */

$this->title = 'Digitalpwer';
$this->params['state'] = 'dashboard';
$user = User::findOne(Yii::$app->user->id);

$username   = "08816239976";
$apiKey   = "67366051b757fc42";
$signature  = md5($username . $apiKey . 'pl');

$json = sprintf('{
          "commands" : "pricelist",
          "username" : "%s",
          "sign"     : "%s"
        }', $username, $signature);

$toget = array("pln", "data");

$data_list = array();

foreach ($toget as $target) {
    try {


        $url = sprintf("https://testprepaid.mobilepulsa.net/v1/legacy/index/%s", $target);

        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        curl_close($ch);
        $data_list = array_merge($data_list, json_decode($data)->data);
    } catch (Exception $e) {
    }
}
?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <?= count($data_list) == 0 ? "<h5>Gagal memuat produk</h5>" : ""; ?>
            <?php
            foreach ($data_list as $data) {
                echo sprintf('<div class="col-lg-4 col-md-6">
                <div class="card-transparent card-block card-stretch card-height">
                    <div class="card-body text-center p-0">
                        <div class="item">
                            <div class="odr-img">
                                <img src="%s" class="img-fluid rounded-circle avatar-90 m-auto" alt="image">
                            </div>
                            <div class="odr-content rounded">
                                <h4 class="mb-2">%s</h4>
                                <p class="mb-3">%s</p>
                                <p class="mb-3">%s</p>
                                <!-- <ul class="list-unstyled mb-3">
                                    <li class="bg-secondary-light rounded-circle iq-card-icon-small mr-4" title="Credit"><i class="ri-mail-open-line m-0"></i></li>
                                    <li class="bg-primary-light rounded-circle iq-card-icon-small mr-4"><i class="ri-chat-3-line m-0"></i></li>
                                    <li class="bg-success-light rounded-circle iq-card-icon-small"><i class="ri-phone-line m-0"></i></li>
                                </ul> -->
                                <div class="pt-3 border-top">
                                    <a href="#" class="btn btn-primary">Beli</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>', $data->icon_url, Yii::$app->formatter->asCurrency($data->pulsa_price, 'IDR'), $data->pulsa_op, $data->pulsa_details);
            }
            ?>
        </div>
    </div>
</div>