<?php
require 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in both fields.';
    } else {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // ✅ Save session data
                $_SESSION['user_id'] = $id;

                // ✅ Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CSU eWalletMart</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('csu_logo.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: 0;
        }

        .login-box {
            position: relative;
            z-index: 1;
            width: 380px;
            padding: 40px 35px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .login-box h2 {
            color: #ffcc00;
            font-size: 26px;
            margin-bottom: 25px;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        button {
            background-color: #ffcc00;
            color: black;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            width: 95%;
            margin-top: 10px;
        }

        button:hover {
            background-color: #e6b800;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        .demo {
            margin-top: 10px;
            font-size: 13px;
            color: #cccccc;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>CSU eWalletMart</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <input type="password" name="password" placeholder="Enter your password" required><br>
            <button type="submit">Login</button>
        </form>

        <?php if ($error): ?>
            <div class="message" style="color: #ff4d4d;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
