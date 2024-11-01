<?php
session_start();

// セッションからランキングデータを取得
header('Content-Type: application/json');
echo json_encode($_SESSION['ranking']);
?>