<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\models\User;
use yii\helpers\Url;
use app\models\Transaction;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/images/favicon.ico')]);

$state = $this->params['state'];
global $user;

$user = User::findOne(Yii::$app->user->id);
if ($user) {
    $trxs = Transaction::findAll(['target_id' => $user->user_id]);
    $totaldeposit = 0;
    foreach ($trxs as $trx) {
        if ($trx->type == "DEPOSIT") {
            $totaldeposit += $trx->amount;
        }
    }
}
if ($state == "dashboard") {
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/css/backend-plugin.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/css/backend.css?v=1.0.0')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/remixicon/fonts/remixicon.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css')]);
    $this->registerJsFile("https://cdn.jsdelivr.net/npm/chart.js", ['position' => $this::POS_READY]);
    $this->registerJsFile("@web/js/yii2AjaxRequest.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_READY]);

    $this->registerCssFile("@web/css/timeline.css");
    $this->registerCssFile("https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css");
    $this->registerCssFile("https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css");
    $this->registerCssFile("@web/css/dashboard.css");

    $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.10/jspdf.plugin.autotable.min.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("@web/js/tableHTMLExport.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("https://cdn.datatables.net/2.0.3/js/dataTables.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("@web/js/dashboard.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJs("$('#trx-table').DataTable()");
} else if ($state == "landing") {
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/css/landing-style.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/icofont/icofont.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/boxicons/css/boxicons.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/venobox/venobox.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/owl.carousel/assets/owl.carousel.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/aos/aos.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/bootstrap/css/bootstrap.min.css')]);
    $this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i']);
} elseif ($state == "signup") {
    $this->registerCssFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", ['position' => $this::POS_HEAD]);
    $this->registerCssFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css", ['position' => $this::POS_HEAD]);
    $this->registerCssFile("https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css", ['position' => $this::POS_HEAD]);

    $this->registerJsFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", ['depends' => 'yii\web\JqueryAsset', 'position' => $this::POS_END]);
    $this->registerJsFile("@web/js/yii2AjaxRequest.js", ['position' => $this::POS_READY]);
    $this->registerJs("const configs = {messageLoading:'Loading',resetForm:false}; 
    yii2AjaxRequest('#signup-form', configs,                                    
        (data) => { 
            console.log('success',data)
            if(data.data==undefined){
                window.location.replace('/dashboard');
            }
            else{
                // if(data.data.errors.fullname){
                //     $('#fullname').val('');
                //     $('#fullname-tooltip').text(data.data.errors.fullname);
                //     $('#fullname-tooltip').show();
                // }
                var keys = Object.keys(data.data.errors);
                keys.forEach((e)=>{
                    $('#'+e).val('');
                    $('#'+e+'-tooltip').text(data.data.errors[e]);
                    $('#'+e+'-tooltip').show();
                })
            }
        },
        (error) => { // The return of a block try / catch
            console.error('ERROR')
        });");

    $this->registerJs("$('#fullname').bind('keypress', preventNonAlpha);");
    $this->registerJs("$('#phone').bind('keypress', preventNonNumeric);");
    $this->registerJs("$('#bank_number').bind('keypress', preventNonNumeric);");
    $this->registerJsFile("@web/js/signup-form.js", ['depends' => 'yii\web\JqueryAsset']);
} elseif ($state == "signin") {
    $this->registerJsFile("@web/js/yii2AjaxRequest.js", ['position' => $this::POS_READY]);
    $this->registerJs("const configs = {messageLoading:'Loading',resetForm:false}; 
    yii2AjaxRequest('#login-form', configs,                                    
        (data) => { 
            console.log('success',data);   
            if(data.data.login){
                $('#wrongLogin').hide();
                window.location.replace('/dashboard');
            }else if(data.data.login==false || data.data.errors.password[0]=='Incorrect username or password.'){         
                $('#wrongLogin').show();
            }
        },
        (error) => { // The return of a block try / catch
            console.error('ERROR')
        });");
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title style="color: #117060 !important;"><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class=" ">
    <?php $this->beginBody() ?>
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->

    <?php if ($state == "dashboard") : ?>

        <div class="wrapper">
            <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Log Out</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin keluar?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="btnLogoutYes">Yes</button>
                            <button type="button" class="btn btn-primary" id="btnLogoutNo">No</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-sidebar  sidebar-default ">
                <div class="iq-sidebar-logo d-flex align-items-center">
                    <a href="dashboard" class="header-logo">
                        <img src="/images/logo.png" alt="logo">
                        <h3 class="light-logo" style="color: #117060 !important;"><?= Html::encode($this->title) ?></h3>
                    </a>
                    <div class="iq-menu-bt-sidebar ml-0">
                        <i class="las la-bars wrapper-menu"></i>
                    </div>
                </div>
                <div class="data-scrollbar" data-scroll="1">
                    <nav class="iq-sidebar-menu">
                        <ul id="iq-sidebar-toggle" class="iq-menu">
                            <li class="<?php echo Yii::$app->requestedRoute == "dashboard" ? "active" : "" ?>">
                                <a href="/dashboard" class="svg-icon">
                                    <svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="ml-4">Home</span>
                                </a>
                            </li>

                            <?php if ($totaldeposit >= 100000 || $user->user_id=="Wt0k8G_E1y") { ?>
                                <li class="<?php echo Yii::$app->requestedRoute == "dashboard/topup" ? "active" : "" ?>">
                                    <!-- <a href="/dashboard/topup" class="svg-icon"> -->
                                    <a class="svg-icon" onClick="onWorking()">
                                        <svg class="svg-icon" width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.87617 3.75H19.1238L21 8.86683V10.5C21 11.2516 20.7177 11.9465 20.25 12.4667V21H3.75V12.4667C3.28234 11.9465 3 11.2516 3 10.5V8.86683L4.87617 3.75ZM18.1875 13.3929C18.3807 13.3929 18.5688 13.3731 18.75 13.3355V19.5H15V15H9L9 19.5H5.25V13.3355C5.43122 13.3731 5.61926 13.3929 5.8125 13.3929C6.63629 13.3929 7.36559 13.0334 7.875 12.4667C8.38441 13.0334 9.11371 13.3929 9.9375 13.3929C10.7613 13.3929 11.4906 13.0334 12 12.4667C12.5094 13.0334 13.2387 13.3929 14.0625 13.3929C14.8863 13.3929 15.6156 13.0334 16.125 12.4667C16.6344 13.0334 17.3637 13.3929 18.1875 13.3929ZM10.5 19.5H13.5V16.5H10.5L10.5 19.5ZM19.5 9.75V10.5C19.5 11.2965 18.8856 11.8929 18.1875 11.8929C17.4894 11.8929 16.875 11.2965 16.875 10.5V9.75H19.5ZM19.1762 8.25L18.0762 5.25H5.92383L4.82383 8.25H19.1762ZM4.5 9.75V10.5C4.5 11.2965 5.11439 11.8929 5.8125 11.8929C6.51061 11.8929 7.125 11.2965 7.125 10.5V9.75H4.5ZM8.625 9.75V10.5C8.625 11.2965 9.23939 11.8929 9.9375 11.8929C10.6356 11.8929 11.25 11.2965 11.25 10.5V9.75H8.625ZM12.75 9.75V10.5C12.75 11.2965 13.3644 11.8929 14.0625 11.8929C14.7606 11.8929 15.375 11.2965 15.375 10.5V9.75H12.75Z" fill="#080341" />
                                        </svg>
                                        <span class="ml-4">Top Up</span>
                                    </a>
                                </li>
                                <li class="<?php echo Yii::$app->requestedRoute == "dashboard/earn" ? "active" : "" ?>">
                                    <a href="/dashboard/earn" class="svg-icon">
                                        <svg class="svg-icon" width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                            <path d="M5,22a4,4,0,0,0,3.858-3h6.284a4.043,4.043,0,1,0,2.789-4.837L14.816,8.836a4,4,0,1,0-5.63,0L6.078,14.166A3.961,3.961,0,0,0,5,14a4,4,0,0,0,0,8Zm14-6a2,2,0,1,1-2,2A2,2,0,0,1,19,16ZM12,4a2,2,0,1,1-2,2A2,2,0,0,1,12,4ZM10.922,9.834A3.961,3.961,0,0,0,12,10a3.909,3.909,0,0,0,1.082-.168l3.112,5.323A4,4,0,0,0,15.142,17H8.858a3.994,3.994,0,0,0-1.044-1.838ZM5,16a2,2,0,1,1-2,2A2,2,0,0,1,5,16Z" />
                                        </svg>
                                        <span class="ml-4">Refer & Earn</span>
                                    </a>
                                </li>
                                <li class="<?php echo Yii::$app->requestedRoute == "dashboard/transaction" ? "active" : "" ?>">
                                    <a href="/dashboard/transaction" class="svg-icon">
                                        <svg class="svg-icon" width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                            <path d="M17.0020048,13 C17.5542895,13 18.0020048,13.4477153 18.0020048,14 C18.0020048,14.5128358 17.6159646,14.9355072 17.1186259,14.9932723 L17.0020048,15 L5.41700475,15 L8.70911154,18.2928932 C9.0695955,18.6533772 9.09732503,19.2206082 8.79230014,19.6128994 L8.70911154,19.7071068 C8.34862757,20.0675907 7.78139652,20.0953203 7.38910531,19.7902954 L7.29489797,19.7071068 L2.29489797,14.7071068 C1.69232289,14.1045317 2.07433707,13.0928192 2.88837381,13.0059833 L3.00200475,13 L17.0020048,13 Z M16.6128994,4.20970461 L16.7071068,4.29289322 L21.7071068,9.29289322 C22.3096819,9.8954683 21.9276677,10.9071808 21.1136309,10.9940167 L21,11 L7,11 C6.44771525,11 6,10.5522847 6,10 C6,9.48716416 6.38604019,9.06449284 6.88337887,9.00672773 L7,9 L18.585,9 L15.2928932,5.70710678 C14.9324093,5.34662282 14.9046797,4.77939176 15.2097046,4.38710056 L15.2928932,4.29289322 C15.6533772,3.93240926 16.2206082,3.90467972 16.6128994,4.20970461 Z" />
                                        </svg>
                                        <span class="ml-4">Transactions</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>

                </div>
            </div>
            <div class="iq-top-navbar">
                <div class="iq-navbar-custom">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                            <i class="ri-menu-line wrapper-menu"></i>
                            <a href="#" class="header-logo">
                                <img src="/images/logo.png" alt="logo">
                                <h3 class="logo-title light-logo" style="color: #117060 !important;"><?= Html::encode(" " . $this->title) ?></h3>
                            </a>
                        </div>
                        <div class="navbar-breadcrumb">
                            <h5><?php echo sprintf("Selamat Datang, %s", isset($user) ? $user->full_name : ""); ?></h5>
                        </div>
                        <div class="d-flex align-items-center">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                                <i class="ri-menu-3-line"></i>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto navbar-list align-items-center">
                                    <!-- <li>
                                <div class="iq-search-bar device-search">
                                    <form action="#" class="searchbox">
                                        <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                                        <input type="text" class="text search-input" placeholder="Search here...">
                                    </form>
                                </div>
                            </li> -->
                                    <!-- <li class="nav-item nav-icon search-content">
                                        <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-search-line"></i>
                                        </a>
                                        <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                                            <form action="#" class="searchbox p-2">
                                                <div class="form-group mb-0 position-relative">
                                                    <input type="text" class="text search-input font-size-12" placeholder="type here to search...">
                                                    <a href="#" class="search-link"><i class="las la-search"></i></a>
                                                </div>
                                            </form>
                                        </div>
                                    </li> -->
                                    <li class="nav-item nav-icon nav-item-icon dropdown">
                                        <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                </path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                            <span class="bg-primary"></span>
                                        </a>
                                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                            <div class="card shadow-none m-0">
                                                <div class="card-body p-0 ">
                                                    <div class="cust-title p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5 class="mb-0">All Messages</h5>
                                                            <a class="badge badge-primary badge-card" href="#">0</a>
                                                        </div>
                                                    </div>
                                                    <div class="px-3 pt-0 pb-0 sub-card">
                                                        <!--<a href="#" class="iq-sub-card">-->
                                                        <!--    <div class="media align-items-center cust-card py-3">-->
                                                        <!--        <div class="">-->
                                                        <!--            <img class="avatar-50 rounded-small" src="/images/user/03.jpg" alt="03">-->
                                                        <!--        </div>-->
                                                        <!--        <div class="media-body ml-3">-->
                                                        <!--            <div class="d-flex align-items-center justify-content-between">-->
                                                        <!--                <h6 class="mb-0">Kianna Carder</h6>-->
                                                        <!--                <small class="text-dark"><b>11 : 21 pm</b></small>-->
                                                        <!--            </div>-->
                                                        <!--            <small class="mb-0">Lorem ipsum dolor sit amet</small>-->
                                                        <!--        </div>-->
                                                        <!--    </div>-->
                                                        <!--</a>-->
                                                    </div>
                                                    <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#" role="button">
                                                        View All
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item nav-icon nav-item-icon dropdown">
                                        <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                            </svg>
                                            <span class="bg-primary "></span>
                                        </a>
                                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <div class="card shadow-none m-0">
                                                <div class="card-body p-0 ">
                                                    <div class="cust-title p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5 class="mb-0">Notifications</h5>
                                                            <a class="badge badge-primary badge-card" href="#">0</a>
                                                        </div>
                                                    </div>
                                                    <div class="px-3 pt-0 pb-0 sub-card">
                                                        <!--<a href="#" class="iq-sub-card">-->
                                                        <!--    <div class="media align-items-center cust-card py-3">-->
                                                        <!--        <div class="">-->
                                                        <!--            <img class="avatar-50 rounded-small" src="/images/user/03.jpg" alt="03">-->
                                                        <!--        </div>-->
                                                        <!--        <div class="media-body ml-3">-->
                                                        <!--            <div class="d-flex align-items-center justify-content-between">-->
                                                        <!--                <h6 class="mb-0">Kianna Carder</h6>-->
                                                        <!--                <small class="text-dark"><b>11 : 21 pm</b></small>-->
                                                        <!--            </div>-->
                                                        <!--            <small class="mb-0">Lorem ipsum dolor sit amet</small>-->
                                                        <!--        </div>-->
                                                        <!--    </div>-->
                                                        <!--</a>-->
                                                    </div>
                                                    <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#" role="button">
                                                        View All
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item nav-icon dropdown caption-content">
                                        <a href="#" class="search-toggle dropdown-toggle  d-flex align-items-center" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="/images/user/1.jpg" class="img-fluid rounded-circle" alt="user">
                                            <div class="caption ml-3">
                                                <h6 class="mb-0 line-height"><?php echo $user->full_name; ?><i class="las la-angle-down ml-2"></i></h6>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right border-none" aria-labelledby="dropdownMenuButton">
                                            <!-- <li class="dropdown-item d-flex svg-icon">
                                                <svg class="svg-icon mr-0 text-primary" id="h-01-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <a href="../app/user-profile.html">My Profile</a>
                                            </li>
                                            <li class="dropdown-item d-flex svg-icon">
                                                <svg class="svg-icon mr-0 text-primary" id="h-02-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <a href="../app/user-profile-edit.html">Edit Profile</a>
                                            </li>
                                            <li class="dropdown-item d-flex svg-icon">
                                                <svg class="svg-icon mr-0 text-primary" id="h-03-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <a href="../app/user-account-setting.html">Account Settings</a>
                                            </li>
                                            <li class="dropdown-item d-flex svg-icon">
                                                <svg class="svg-icon mr-0 text-primary" id="h-04-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <a href="../app/user-privacy-setting.html">Privacy Settings</a>
                                            </li> -->
                                            <li class="dropdown-item  d-flex svg-icon border-top" id="btnLogout" style="cursor: pointer;">
                                                <svg class="svg-icon mr-0 text-primary" id="h-05-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                <a href="#">Logout</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

        <?php endif  ?>
        <?php if (!empty($this->params['breadcrumbs'])) : ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>

        <!-- <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer> -->

        <!-- Backend Bundle JavaScript -->
        <script src="/js/backend-bundle.min.js"></script>

        <!-- Table Treeview JavaScript -->
        <script src="/js/table-treeview.js"></script>

        <!-- Chart Custom JavaScript -->
        <script src="/js/customizer.js"></script>

        <!-- Chart Custom JavaScript -->
        <script async src="/js/chart-custom.js"></script>
        <!-- Chart Custom JavaScript -->
        <script async src="/js/slider.js"></script>

        <!-- app JavaScript -->
        <script src="/js/app.js"></script>

        <script src="/vendor/moment.min.js"></script>
        <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>