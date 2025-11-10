<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 40px;
        }
        .cards-container {
            display: flex;
            justify-content: space-around;
            gap: 30px;
            flex-wrap: wrap;
        }
        .card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
            max-width: 500px;
            text-align: center;
        }
        .card h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .admin-card {
            border-top: 4px solid #e74c3c;
        }
        .patient-card {
            border-top: 4px solid #2ecc71;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Clinic Management System</h1>
        <div class="cards-container">
            <div class="card admin-card">
                <h2>Admin Login</h2>
                <p>Access the clinic management dashboard</p>
                <a href="admin/login.php" class="button">Login as Admin</a>
            </div>
            <div class="card patient-card">
                <h2>Patient Check-up</h2>
                <p>Scan the QR code below to schedule your appointment</p>
                <img src="qrcode.gif" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 20px;">
                <div class="qr-code">
                <p class="qr-instruction">Scan the QR code to open the registration form</p>
            </div>
        </div>
    </div>
</body>
</html>
