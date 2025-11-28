<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* body {
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            font-family: system-ui, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        } */
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: system-ui, Arial, sans-serif;
            width: 100%;
            height: 100%;
        }


        .card {
            width: 900px;
            height: 1200px;
            display: flex;
            flex-direction: column;
            background: #fff;
            color: #1a1a1a;
            overflow: hidden;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1);
        }

        .image-container {
            height: 50%;
            width: 100%;
            position: relative;
            display: flex;
            overflow: hidden;
        }

        .image-part {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background: #ff3b3b;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03rem;
            z-index: 10;
            box-shadow: 0 0.15rem 0.4rem rgba(0, 0, 0, 0.3);
        }

        .content {
            height: 50%;
            padding: 1rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .title {
            font-size: 3.1rem;
            /* 50px */
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            color: #000;
        }

        .desc {
            font-size: 2rem;
            /* 28px */
            line-height: 1.4;
            color: #555;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 1.8rem;
        }

        .disclaimer {
            font-style: italic;
            font-size: 1.3rem;
            opacity: 0.7;
            margin-top: 1.2rem;
            line-height: 1.45;
        }

        .footer {
            width: 100%;
            border-top: 1px solid #eee;
            padding-top: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .source-text {
            font-size: 1.4rem;
            /* 22px */
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            font-style: italic;
        }

        .logo-text {
            font-size: 1.5rem;
            /* 24px */
            font-weight: 800;
            color: #ff3b3b;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="image-container">

            @if(!empty($category))
                <div class="category">Viral News</div>
            @endif

            <div class="image-part">
                <img src="{{ $image_url }}"
                    class="bg" alt="Left side of the image">
            </div>

            

        </div>

        <div class="content">

            <div>
                <h1 class="title">{{ $title }}</h1>
                <p class="desc">{{ $description }}</p>
            </div>


            <div>
                <div class="disclaimer">
                    This image is auto-generated and may contain inaccuracies.
                    If you find any incorrect information, please let us know.
                </div>
                <div class="footer">
                    <span class="source-text {{ $source ? '' : 'visibiliy-hidden' }}">SOURCE: FREEPRESSJOURNAL.IN</span>
                    <span class="logo-text">{{ config('app.name') }}</span>
                </div>
            </div>

        </div>

    </div>

</body>

</html>