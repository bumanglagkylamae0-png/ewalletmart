<?php
require 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSU eWalletMart</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('csu_logo.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            padding: 60px 80px;
            border-radius: 20px;
            backdrop-filter: blur(8px);
            max-width: 600px;
            box-shadow: 0 0 25px rgba(0,0,0,0.5);
        }

        h1 {
            color: #ffcc00;
            font-size: 40px;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            border: 2px solid #ffcc00;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        a:hover {
            background-color: #ffcc00;
            color: #000;
        }

        footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            color: #ccc;
            font-size: 14px;
            text-align: center;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to CSU eWalletMart</h1>
        <p>Your trusted e-commerce platform by CSU students.</p>
        <a href="register.php">Create an Account</a>
    </div>

    <footer>© 2025 CSU eWalletMart. All rights reserved.</footer>
</body>
</html>
