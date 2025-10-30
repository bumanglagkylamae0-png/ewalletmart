<?php
require 'db.php';
require 'helpers.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $mysqli->prepare("SELECT email, name, balance FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($email, $name, $balance);
$stmt->fetch();
$stmt->close();

// Fetch last 50 transactions
$txs = [];
$tstmt = $mysqli->prepare("SELECT type, amount, counterparty_email, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$tstmt->bind_param('i', $user_id);
$tstmt->execute();
$tres = $tstmt->get_result();
while ($r = $tres->fetch_assoc()) {
    $txs[] = $r;
}
$tstmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CSU eWalletMart — Dashboard</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: url('csu_logo.png') no-repeat center center fixed;
      background-size: cover;
      color: white;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 20px;
    }

    /* Dark overlay */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.75);
      z-index: 0;
    }

    .container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 900px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 30px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      padding-bottom: 15px;
      margin-bottom: 25px;
    }

    .brand {
      font-size: 28px;
      font-weight: 700;
      color: #ffcc00;
    }

    .small {
      font-size: 14px;
      color: #ccc;
    }

    .nav {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .balance {
      font-weight: 700;
      color: #000;
      background: #ffcc00;
      font-size: 16px;
      padding: 8px 14px;
      border-radius: 8px;
    }

    .nav a {
      text-decoration: none;
      color: #000;
      background: #ffcc00;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .nav a:hover {
      background: #fff;
      color: #000;
    }

    h4 {
      margin: 0 0 10px;
      color: #ffcc00;
    }

    .card {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.2);
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    input[type="number"],
    input[type="email"] {
      padding: 8px 10px;
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 8px;
      font-size: 14px;
      width: 180px;
      background: rgba(0,0,0,0.5);
      color: white;
    }

    input::placeholder {
      color: #ccc;
    }

    input:focus {
      border-color: #ffcc00;
      outline: none;
    }

    button {
      background: #ffcc00;
      color: #000;
      padding: 8px 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255,255,255,0.05);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: left;
      font-size: 14px;
      color: #f0f0f0;
    }

    th {
      background: rgba(255,255,255,0.1);
      font-weight: 600;
      color: #ffcc00;
    }

    tr:hover td {
      background: rgba(255,255,255,0.08);
    }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 13px;
      color: #ccc;
      border-top: 1px solid rgba(255,255,255,0.2);
      padding-top: 15px;
    }

    @media (max-width: 600px) {
      .card form {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }

      input[type="number"], input[type="email"] {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="header">
    <div>
      <div class="brand">CSU eWalletMart</div>
      <div class="small">Welcome, <?= htmlspecialchars($name) ?></div>
    </div>
    <div class="nav">
      <div class="balance">₱ <?= number_format($balance, 2) ?></div>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <h4>Quick Actions</h4>
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
      <form style="margin:0;display:flex;gap:8px;" action="topup.php" method="post">
        <input name="amount" type="number" step="0.01" placeholder="Top-up amount" required>
        <button>Top Up</button>
      </form>

      <form style="display:flex;gap:8px;margin:0" action="transfer.php" method="post">
        <input name="to_email" type="email" placeholder="Recipient email" required>
        <input name="amount" type="number" step="0.01" placeholder="Amount" required>
        <button>Send</button>
      </form>
    </div>
    <div class="small" style="margin-top:8px">
      Note: This is a demo flow — integrate real payments for production.
    </div>
  </div>

  <div>
    <h4>Transactions</h4>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Amount</th>
          <th>Counterparty</th>
          <th>When</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($txs)): ?>
          <tr><td colspan="4" class="small">No transactions yet.</td></tr>
        <?php else: ?>
          <?php foreach ($txs as $tx): ?>
            <tr>
              <td><?= htmlspecialchars($tx['type']) ?></td>
              <td>₱ <?= number_format($tx['amount'], 2) ?></td>
              <td><?= htmlspecialchars($tx['counterparty_email'] ?? '-') ?></td>
              <td><?= htmlspecialchars($tx['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="footer">© <?= date('Y') ?> CSU eWalletMart. All rights reserved.</div>
</div>
</body>
</html>
