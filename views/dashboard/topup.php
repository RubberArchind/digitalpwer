<?php

use yii\helpers\Html;
use app\models\User;
use PhpParser\Node\Stmt\TryCatch;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = 'digitalpwer';
$this->params['state'] = 'dashboard';
$user = User::findOne(Yii::$app->user->id);

$username   = "08816239976";
$apiKey   = "52866121528e1138oix4";
$signature  = md5($username . $apiKey . 'pl');

$json = sprintf('{
          "status" : "active",
          "username" : "%s",
          "sign"     : "%s"
        }', $username, $signature);

$toget = array("pulsa", "pln");

$data_list = array();

foreach ($toget as $target) {
    try {


        $url = sprintf("https://prepaid.iak.id/api/pricelist/%s", $target);

        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        curl_close($ch);
        // $data_list = array_merge($data_list, json_decode($data)->data->pricelist);
        array_push($data_list, json_decode($data)->data->pricelist);
    } catch (Exception $e) {
    }
}
?>

<div class="content-page">
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <?php $form = ActiveForm::begin([
                'id' => 'topup-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'options' => [
                    'class' => 'needs-validation',
                    'novalidate' => ''
                ]
            ]); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Masukkan Nomor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input style="display: none;" type="hidden" id="pcode" name="TopupForm[code]" />
                    <input style="display: none;" type="hidden" id="snap" name="TopupForm[snap]" />
                    <input style="display: none;" type="hidden" id="amount" name="TopupForm[amount]" />

                    <div class="input-group mb-4">
                        <input type="number" name="TopupForm[pnumber]">
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo Html::submitButton('Confirm', array('class' => 'btn btn-primary', 'name' => 'submit-button')) ?>
                    <!-- <button type="button" class="btn btn-secondary" id="btnTopupYes">Yes</button> -->
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?= count($data_list) == 0 ? "<h5>Gagal memuat produk</h5>" : ""; ?>
            <ul class="nav nav-pills mb-3 nav-fill" id="pills-tab-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-pulsa-tab-fill" data-toggle="pill" href="#pills-pulsa-fill" role="tab" aria-controls="pills-pulsa" aria-selected="true">Pulsa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-pln-tab-fill" data-toggle="pill" href="#pills-pln-fill" role="tab" aria-controls="pills-pln" aria-selected="false">PLN</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent-1">
                <div class="tab-pane fade active show" id="pills-pulsa-fill" role="tabpanel" aria-labelledby="pills-pulsa-tab-fill">
                    <?php
                    $cardCount = 0;
                    foreach ($data_list[0] as $data) {
                        // Open a new row if the current row has three cards
                        if ($cardCount % 3 === 0) {
                            echo '<div class="row ">';
                        }

                        echo sprintf('<div class="col">
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
                                                <button id="btnBeli" class="btn btn-primary" onclick="toggleTopup(`%s`,`%s`)">Beli</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>', $data->icon_url, Yii::$app->formatter->asCurrency($data->product_price + 500, 'IDR'), $data->product_description . " " . $data->product_nominal, $data->product_details, $data->product_code, $data->product_price + 500);

                        $cardCount++;

                        // Close the row if three cards have been output
                        if ($cardCount % 3 === 0) {
                            echo '</div>'; // Close the row
                        }
                    }

                    if ($cardCount % 3 !== 0) {
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="tab-pane fade" id="pills-pln-fill" role="tabpanel" aria-labelledby="pills-pln-tab-fill">
                    <?php
                    $cardCount = 0;
                    foreach ($data_list[1] as $data) {
                        // Open a new row if the current row has three cards
                        if ($cardCount % 3 === 0) {
                            echo '<div class="row ">';
                        }

                        echo sprintf('<div class="col">
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
                                                <button id="btnBeli" class="btn btn-primary" onclick="toggleTopup(`%s`,`%s`)">Beli</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>', $data->icon_url, Yii::$app->formatter->asCurrency($data->product_price + 500, 'IDR'), $data->product_description . " " . $data->product_nominal, $data->product_details, $data->product_code, $data->product_price + 500);

                        $cardCount++;

                        // Close the row if three cards have been output
                        if ($cardCount % 3 === 0) {
                            echo '</div>'; // Close the row
                        }
                    }

                    if ($cardCount % 3 !== 0) {
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>