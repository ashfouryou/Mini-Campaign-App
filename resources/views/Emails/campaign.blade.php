<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #dbeafe;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }
        .header img {
            max-height: 50px;
            margin-right: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mini Campaign Mailer</h1>
        </div>
        <div class="content">
            <h1>Hi, {{ $username }}</h1>
            <h2>Campaign Name: {{ $subject }}</h2>
            <h2> {{ $content }}</h2>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MiniCampaignMailer. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
