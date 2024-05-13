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

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/images/favicon.ico')]);

$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/css/landing-style.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/icofont/icofont.min.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/boxicons/css/boxicons.min.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/venobox/venobox.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/owl.carousel/assets/owl.carousel.min.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/aos/aos.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/vendor/bootstrap/css/bootstrap.min.css')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i']);

$isGuest = Yii::$app->user->getIsGuest();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class=" ">
    <?php $this->beginBody() ?>
    <!-- loader Start -->
    <!-- <div id="loading">
        <div id="loading-center">
        </div>
    </div> -->
    <!-- loader END -->

    <header id="header" class="fixed-top">
        <div class="container d-flex">

            <div class="logo mr-auto">
                <!-- Uncomment below if you prefer to use an image logo -->
                <a href="/" style="display: inline-block;">
                    <img src="images/logo.png" alt="" class="img-fluid" style="display: inline-block; vertical-align: middle;">
                </a>
                <a href="/" style="display: inline-block;">
                    <h1 style="display: inline-block; vertical-align: middle; color: #117060 !important;">
                        <span><?= Html::encode($this->title) ?></span>
                    </h1>
                </a>
            </div>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="/#hero">Home</a></li>
                    <!-- <li class="drop-down"><a href="">About</a>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#team">Team</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li class="drop-down"><a href="#">Deep Drop Down</a>
                            <ul>
                                <li><a href="#">Deep Drop Down 1</a></li>
                                <li><a href="#">Deep Drop Down 2</a></li>
                                <li><a href="#">Deep Drop Down 3</a></li>
                                <li><a href="#">Deep Drop Down 4</a></li>
                                <li><a href="#">Deep Drop Down 5</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#pricing">Pricing</a></li> -->
                    <li><a href="/#about">Tentang Kami</a></li>
                    <li><a href="/#footer">Contact</a></li>
                    <!-- <li><a href="#"><img width="32" src="images/user/1.jpg" class="img-fluid rounded-circle" alt="user"></a></li> -->
                    <li><a href=<?php echo $isGuest ? "/auth/signup" : "/dashboard" ?> class="btn fill-two text-white"><?php echo $isGuest ? "Daftar" : "Dashboard" ?></a></li>
                </ul>
            </nav><!-- .nav-menu -->

            <!-- <div class="header-social-links">
            <a href="#" class="twitter"><i class="icofont-twitter"></i></a>
            <a href="#" class="facebook"><i class="icofont-facebook"></i></a>
            <a href="#" class="instagram"><i class="icofont-instagram"></i></a>
            <a href="#" class="linkedin"><i class="icofont-linkedin"></i></i></a>            
        </div> -->

        </div>
    </header>
    <!-- End Header -->

    <?= $content ?>

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-info">
                            <div class="logo mr-auto">
                                <!-- Uncomment below if you prefer to use an image logo -->
                                <a href="/" class="logo-wrapper">
                                    <img src="images/logo.png" alt="" class="img-fluid" style="max-width: 25%;">
                                </a>
                                <a href="/" class="title-wrapper">
                                    <h3 style="color: #117060 !important;"><?= Html::encode($this->title) ?></h3>
                                </a>
                            </div>

                            <!--<h3>digitalpwer</h3>-->
                            <p>
                                1, Park Road #25918 <br>
                                Peoples Park Complex <br>
                                Singapore 059108<br><br>
                                <strong>Phone:</strong> +1 5589 55488 55<br>
                                <strong>Email:</strong> info@digitalpwer.com<br>
                            </p>
                            <div class="social-links mt-3">
                                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="/">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/#about">Tentang Kami</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/#footer">Contact</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/tos">Terms of service</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/privacy">Privacy policy</a></li>
                        </ul>
                    </div>

                    <!-- <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
                    </ul>
                </div> -->

                    <!-- <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
                    <form action="" method="post">
                        <input type="email" name="email"><input type="submit" value="Subscribe">
                    </form>

                </div> -->

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>digitalpwer</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/scaffold-bootstrap-metro-style-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top"><i class="bx bxs-up-arrow-alt"></i></a>

    <!-- Vendor JS Files -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="vendor/php-email-form/validate.js"></script>
    <script src="vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="vendor/venobox/venobox.min.js"></script>
    <script src="vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="vendor/aos/aos.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <!--<script src="/js/backend-bundle.min.js"></script>-->
    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>