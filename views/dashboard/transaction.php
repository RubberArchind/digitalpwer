<?php

use yii\helpers\Html;
use app\models\User;
use app\models\Transaction;

/** @var yii\web\View $this */

$this->title = 'Digitalpwer';
$this->params['state'] = 'dashboard';
$user = User::findOne(Yii::$app->user->id);

$trxs = Transaction::findAll(['target_id' => $user->user_id]);
?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Daftar Transaksi</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="user-list-files d-flex">
                                <a class="bg-primary" style="cursor: pointer;" onclick="trxExcel()">
                                    Excel
                                </a>
                                <a class="bg-primary" style="cursor: pointer;" onclick="trxPdf()">
                                    Pdf
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="row justify-content-between">
                                <!-- <div class="col-sm-6 col-md-6">
                                    <div id="user_list_datatable_info" class="dataTables_filter">
                                        <form class="mr-3 position-relative">
                                            <div class="form-group mb-0">
                                                <input type="search" class="form-control" id="exampleInputSearch" placeholder="Search" aria-controls="user-list-table">
                                            </div>
                                        </form>
                                    </div>
                                </div> -->
                                <!-- <div class="col-sm-6 col-md-6">
                                    <div class="user-list-files d-flex">
                                        <a class="bg-primary" style="cursor: pointer;" onclick="trxPrint();">
                                            Print
                                        </a>
                                        <a class="bg-primary" style="cursor: pointer;" onclick="trxExcel()">
                                            Excel
                                        </a>
                                        <a class="bg-primary" style="cursor: pointer;" onclick="trxPdf()">
                                            Pdf
                                        </a>
                                    </div>
                                </div> -->
                            </div>
                            <table id="trx-table" class="table table-striped dataTable mt-4" role="grid" aria-describedby="user-list-page-info">
                                <thead>
                                    <tr class="ligth">
                                        <th scope="col">No</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Method</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($trxs as $trx) {
                                        echo sprintf('<tr>
                                        <td scope="row" class="text-center">%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td><span class="badge bg-primary">%s</span></td>                                        
                                    </tr>', $no++, Yii::$app->formatter->asCurrency($trx->amount, 'IDR'), $trx->type, 'Bank', $trx->time, 'Complete');
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="row justify-content-between mt-3">
                            <div id="user-list-page-info" class="col-md-6">
                                <span>Showing 1 to 5 of 5 entries</span>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>

            <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

        </div>
    </div>
</div>