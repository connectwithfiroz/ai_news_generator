<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: 'Georgia', serif; /* Use a classic font for quotes */
            width: 100%;
            height: 100%;
        }

        .card {
            width: 900px;
            height: 1200px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Gradient Background for Visual Appeal */
        .background-gradient {
            width: 100%;
            height: 100%;
            position: absolute;
            background: linear-gradient(135deg, #1f4068 0%, #162447 100%); /* Deep Blue/Purple Gradient */
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
            padding: 4rem 4rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
        }
        
        /* Optional image part - overlayed, subtle, and darkened */
        .image-part {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            opacity: 0.4; /* Make image very subtle */
        }
        
        .bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quote-icon {
            font-size: 8rem;
            line-height: 1;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 2rem;
            position: absolute;
            top: 3rem;
            left: 4rem;
            z-index: 1;
        }

        .main-text-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-top: 5rem;
            z-index: 2;
        }

        .title {
            font-size: 3.8rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 2rem;
            color: #ffd700; /* Gold color for the quote itself */
            text-align: center;
        }

        .desc {
            font-size: 2.2rem;
            line-height: 1.4;
            color: #ffffff;
            opacity: 1;
            text-align: center;
            font-style: italic;
            margin-top: 1rem;
        }

        .footer {
            width: 100%;
            border-top: 2px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }

        .source-text {
            font-size: 1.6rem;
            font-weight: 400;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            opacity: 0.7;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            opacity: 1;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .visibiliy-hidden {
            visibility: hidden;
        }
    </style>
</head>

<body>

    <div class="card">
        
        <div class="background-gradient"></div>
        
        <div class="image-part">
             <img src="{{ $image_url }}" class="bg" alt="Subtle background texture">
        </div>

        <div class="content-wrapper">
            
            <span class="quote-icon">“</span>

            <div class="main-text-area">
                <h1 class="title">{{ $title }}</h1>
                <p class="desc">— {{ $description }}</p> 
            </div>


            <div class="footer">
                <span class="source-text {{ empty($source) ? 'visibiliy-hidden' : '' }}">INFO: {{$source}}</span>
                <span class="logo-text">{{ config('app.name') ?? 'INSPIRATION HUB' }}</span>
            </div>

        </div>

    </div>

</body>

</html>