<?php

use yii\helpers\Html;
use app\models\User;

/** @var yii\web\View $this */

$this->title = 'Digitalpwer';
$user = User::findOne(Yii::$app->user->id);
?>

<main id="tos" class="container mt-5 text-muted" style="top: 5vh !important;">
    <h2>Privacy Policy</h2>


    <h3>Informasi yang Kami Kumpulkan<br></h3>
    <p class="text-muted">
        Kami dapat mengumpulkan informasi pribadi dari pengguna, termasuk tetapi tidak terbatas pada nama, alamat email, nomor telepon, dan informasi pembayaran. Kami juga dapat mengumpulkan informasi non-pribadi seperti jenis browser, sistem operasi, dan alamat IP.<br><br>
    </p>

    <h3>Penggunaan Informasi<br></h3>
    <p class="text-muted">
        Informasi yang kami kumpulkan digunakan untuk menyediakan layanan kami kepada Anda, memproses pembayaran, meningkatkan layanan kami, dan mengirim pemberitahuan terkait layanan. Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga tanpa izin Anda.<br><br>
    </p>


    <h3>Keamanan Informasi<br></h3>
    <p class="text-muted">
        Kami mengambil langkah-langkah keamanan yang wajar untuk melindungi informasi pribadi pengguna dari akses yang tidak sah, penggunaan, atau pengungkapan yang tidak sah.<br><br>
    </p>

    <h3>Cookie<br></h3>
    <p class="text-muted">
        Kami dapat menggunakan cookie dan teknologi pelacakan serupa untuk meningkatkan pengalaman pengguna dan analisis. Anda dapat mengatur browser Anda untuk menolak semua cookie atau memberi tahu Anda ketika cookie dikirim. Namun, beberapa fitur layanan kami mungkin tidak berfungsi dengan baik jika cookie dinonaktifkan.<br><br>
    </p>

    <h3>Perubahan Kebijakan Privasi<br></h3>
    <p class="text-muted">
        Kami dapat mengubah Kebijakan Privasi kami dari waktu ke waktu. Perubahan tersebut akan efektif segera setelah posting revisi di situs web kami. Dengan melanjutkan penggunaan layanan kami setelah perubahan tersebut, Anda menyetujui Kebijakan Privasi yang direvisi.
    </p>

</main>