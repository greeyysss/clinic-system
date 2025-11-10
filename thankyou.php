<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Registration Received</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; padding:40px; }
        .card { max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); text-align:center; }
        a.button { display:inline-block; margin-top:20px; padding:10px 18px; background:#3498db; color:#fff; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Registration Received</h1>
        <p>Thank you — your check-up request was received. Please wait while an admin reviews your request. You may close this page.</p>
        <a class="button" href="/clinic%20system/">Back to Home</a>
    </div>
</body>
</html>