<?php
session_start();
// Hapus semua session
session_unset();
session_destroy();
// Redirect Ke halaman login
header("Location: login.php");
exit();
?>