<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            font-size: 24px;
            color: #333333;
        }
        .email-body {
            font-size: 16px;
            color: #555555;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #999999;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Recovery</h1>
        </div>
        <div class="email-body">
            <p>Hi,</p>
            <p>You requested to reset your password. Click the button below to set a new password:</p>
            <p style="text-align: center;">
                <a href="{{ $mailData['recover-link'] }}" class="button">Reset Password</a>
            </p>
            <p>If you didn't request this, please ignore this email. Your password will remain unchanged.</p>
        </div>
        <div class="email-footer">
            <p>Thank you,<br>The EcoNest Team</p>
        </div>
    </div>
</body>
</html>
