<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            /* Black background like the example */
            font-family: system-ui, Arial, sans-serif;
            width: 100%;
            height: 100%;
        }

        .card {
            width: 900px;
            height: 1200px;
            background: #000;
            color: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 12px solid #ffcc00;
            /* Main thick golden border */
        }

        .header {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: flex-end;
            /* Logo/Brand on the right */
            align-items: center;
            border-bottom: 2px solid #ffcc00;
        }

        .logo-box {
            background: #ffcc00;
            /* Golden box for the logo/brand text */
            color: #000;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 1.5rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .image-container {
            width: 100%;
            padding: 1.5rem 0;
            /* Vertical spacing */
            display: flex;
            justify-content: center;
        }

        .image-frame {
            width: 90%;
            height: 450px;
            /* Fixed height for the image area */
            overflow: hidden;
            border-radius: 0.5rem;
            border: 4px solid #ffcc00;
            /* Inner golden frame */
        }

        .bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fact-label {
            background: #ffcc00;
            color: #000;
            font-size: 2rem;
            font-weight: 800;
            padding: 0.5rem 2rem;
            margin: 0 auto 2rem auto;
            border-radius: 0.5rem;
            box-shadow: 0 0.2rem 0.5rem rgba(255, 204, 0, 0.5);
            width: fit-content;
        }

        .content {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex-grow: 1;
            /* Allows content to fill remaining space */
            padding-top: 0;
        }

        .title {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1.25;
            color: #ffcc00;
            /* Gold text for the primary title/fact highlight */
            margin-bottom: 0;
        }

        .desc {
            font-size: 2rem;
            line-height: 1.5;
            color: #f0f0f0;
            /* Use -webkit-line-clamp to control text length if needed */
        }

        .footer {
            padding: 1.5rem 2rem;
            border-top: 2px solid #ffcc00;
            text-align: center;
            font-size: 1.2rem;
            font-style: italic;
            opacity: 0.8;
        }

        .visibiliy-hidden {
            visibility: hidden;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="header">
            <span class="logo-box">{{ config('app.name') ?? 'FACT HUB' }}</span>
        </div>

        <div class="image-container">
            <div class="image-frame">
                <img src="{{ $image_url }}" class="bg" alt="Image relevant to the fact">
            </div>
        </div>



        <div class="content">
            <h1 class="title">{{ $title }}</h1>

            <p class="desc">{{ $description }}</p>
        </div>

        <div class="footer">
            <span class="source-text {{ empty($source) ? 'visibiliy-hidden' : '' }}">Source: {{$source}} | </span>
            <span>Knowledge is power. Share this fact!</span>
        </div>

    </div>

</body>

</html>