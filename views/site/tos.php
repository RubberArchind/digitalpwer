<?php

use yii\helpers\Html;
use app\models\User;

/** @var yii\web\View $this */

$this->title = 'digitalpwer';
$user = User::findOne(Yii::$app->user->id);
?>

<!-- Terms of Service -->
<main id="tos" class="container mt-5 text-muted" style="top: 5vh !important;">
    <h2>Terms of Service</h2>


    <h3>Penerimaan Syarat dan Ketentuan<br></h3>
    <p class="text-muted">
        Terima kasih telah menggunakan layanan Digitalpwer. Dengan mengakses atau menggunakan situs web Digitalpwer dan layanannya, Anda setuju untuk terikat dengan Syarat dan Ketentuan Penggunaan ini. Jika Anda tidak setuju dengan bagian mana pun dari syarat ini, Anda tidak diizinkan untuk menggunakan layanan kami.<br><br>
    </p>

    <h3>Deskripsi Layanan<br></h3>
    <p class="text-muted">
        Digitalpwer adalah platform PPOB (Payment Point Online Bank) yang menyediakan layanan untuk pembayaran tagihan dan transfer uang secara online. Selain itu, kami juga menyediakan program referral MLM yang memungkinkan pengguna untuk mendapatkan insentif dengan merekrut pengguna baru.<br><br>
    </p>


    <h3>Penggunaan Layanan<br></h3>
    <p class="text-muted">
        Dengan menggunakan layanan kami, Anda setuju untuk tidak melakukan hal berikut:<br>
        a. Melanggar hukum setempat, nasional, atau internasional.<br>
        b. Melakukan tindakan yang dapat merugikan atau mengganggu layanan kami atau pengguna lain.<br>
        c. Menggunakan informasi pribadi pengguna lain tanpa izin.<br>
        d. Menyalahgunakan sistem referral MLM untuk tujuan yang melanggar hukum atau mengganggu pengalaman pengguna lain.<br><br>
    </p>

    <h3>Pembayaran<br></h3>
    <p class="text-muted">
        Anda setuju untuk membayar semua biaya yang terkait dengan penggunaan layanan kami sesuai dengan ketentuan yang telah ditetapkan. Kami berhak untuk mengubah biaya dan tarif kapan saja dengan pemberitahuan sebelumnya.<br><br>
    </p>

    <h3>Perubahan Layanan<br></h3>
    <p class="text-muted">
        Kami berhak untuk mengubah atau menghentikan layanan kami (secara keseluruhan atau sebagian) tanpa pemberitahuan sebelumnya. Kami tidak bertanggung jawab atas kerugian yang timbul akibat perubahan atau penghentian layanan.<br><br>
    </p>

    <h3>Penyelesaian Sengketa<br></h3>
    <p class="text-muted">
        Setiap perselisihan yang timbul dari atau terkait dengan penggunaan layanan Digitalpwer akan diselesaikan melalui negosiasi antara kedua belah pihak. Jika penyelesaian tidak dapat dicapai, perselisihan akan diserahkan ke pengadilan yang berwenang.
    </p>
</main>