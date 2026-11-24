<?php
// File: api/logout.php
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Arahkan kembali ke file index.html di folder utama
header("Location: ../index.html");
exit;
?>