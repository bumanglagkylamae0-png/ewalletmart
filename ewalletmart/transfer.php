<?php
require 'db.php';
require 'helpers.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// ✅ Get sender info (balance + email)
$stmt = $mysqli->prepare("SELECT email, balance FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($user_email, $sender_balance);
$stmt->fetch();
$stmt->close();

$to_email = trim($_POST['to_email']);
$amount = floatval($_POST['amount']);

// ✅ Validate amount
if ($amount <= 0) {
    die("<script>alert('Invalid amount.');window.location='dashboard.php';</script>");
}

// ✅ Check balance properly (allow test bypass if you want)
if ($sender_balance < $amount) {
    die("<script>alert('Insufficient balance. You currently have ₱" . number_format($sender_balance, 2) . "');window.location='dashboard.php';</script>");
}

// ✅ Deduct from sender
$updateSender = $mysqli->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
$updateSender->bind_param('di', $amount, $user_id);
$updateSender->execute();
$updateSender->close();

// ✅ Ensure recipient exists
$check = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param('s', $to_email);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $createUser = $mysqli->prepare("INSERT INTO users (name, email, password, balance) VALUES ('Guest', ?, '', 0)");
    $createUser->bind_param('s', $to_email);
    $createUser->execute();
    $createUser->close();
}
$check->close();

// ✅ Credit recipient
$credit = $mysqli->prepare("UPDATE users SET balance = balance + ? WHERE email = ?");
$credit->bind_param('ds', $amount, $to_email);
$credit->execute();
$credit->close();

// ✅ Get recipient ID
$get = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
$get->bind_param('s', $to_email);
$get->execute();
$get->bind_result($to_id);
$get->fetch();
$get->close();

// ✅ Record transactions (sender)
$stmt1 = $mysqli->prepare("
    INSERT INTO transactions (user_id, type, amount, counterparty_email)
    VALUES (?, 'send', ?, ?)
");
$stmt1->bind_param('ids', $user_id, $amount, $to_email);
$stmt1->execute();
$stmt1->close();

// ✅ Record transactions (recipient)
$stmt2 = $mysqli->prepare("
    INSERT INTO transactions (user_id, type, amount, counterparty_email)
    VALUES (?, 'receive', ?, ?)
");
$stmt2->bind_param('ids', $to_id, $amount, $user_email);
$stmt2->execute();
$stmt2->close();

echo "<script>
alert('You have successfully sent ₱" . number_format($amount, 2) . " to $to_email');
window.location='dashboard.php';
</script>";
exit;
?>
