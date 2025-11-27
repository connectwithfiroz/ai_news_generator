<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        .card {
            width: 1200px;
            height: 630px;
            position: relative;
            background: #000;
            color: #fff;
            overflow: hidden;
        }

        .bg {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.35;
        }

        .content {
            position: absolute;
            top: 40px;
            left: 60px;
            right: 60px;
        }

        .title {
            font-size: 58px;
            font-weight: bold;
            margin-bottom: 25px;
            line-height: 1.2;
        }

        .desc {
            font-size: 34px;
            line-height: 1.5;
            opacity: 0.9;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 60px;
            font-size: 26px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="card">
    <img src="{{ $image }}" class="bg">
    <div class="content">
        <div class="title">{{ $title }}</div>
        <div class="desc">{{ $description }}</div>
    </div>
    <div class="footer">Powered by QuizSagar.com</div>
</div>
</body>
</html>
