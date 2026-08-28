<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #aaa;
            font-size: 12px;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{ $subject }}
        </div>

        <div class="content">
            <!-- Main email body content goes here -->
            {!! $body !!} <!-- Render the email body -->
        </div>

        <div class="footer">
            <p>Thank you for using {{ getSetting('app_name') }}!</p>
            <p>If you did not request this email, please ignore it.</p>
        </div>
    </div>
</body>

</html>
