<!-- resources/views/news-card.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .card {
            width: 1200px;
            height: 630px;
            position: relative;
            font-family: Arial, sans-serif;
        }
        .background {
            width: 100%;
            height: 100%;
            background-image: url('{{ $imageUrl }}');
            background-size: cover;
            background-position: center;
        }
        .overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 180px;
            background-color: rgba(0,0,0,0.6);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            font-size: 48px;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="background"></div>
        <div class="overlay">{{ $text }}</div>
    </div>
</body>
</html>
