<?php
require 'db.php';
require 'helpers.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount'] ?? 0);

if ($amount <= 0) {
    header('Location: dashboard.php');
    exit;
}

// NOTE: In a real system integrate payment gateway. Here we just credit the user's balance.
$mysqli->begin_transaction();
try {
    $u = $mysqli->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $u->bind_param('di', $amount, $user_id);
    $u->execute();

    $ins = $mysqli->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'topup', ?, ?)");
    $desc = "Top up via demo";
    $ins->bind_param('ids', $user_id, $amount, $desc);
    $ins->execute();

    $mysqli->commit();
} catch (Exception $e) {
    $mysqli->rollback();
}
header('Location: dashboard.php');
exit;
