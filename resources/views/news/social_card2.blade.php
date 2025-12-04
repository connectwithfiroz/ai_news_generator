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
            font-family: system-ui, Arial, sans-serif;
            width: 100%;
            height: 100%;
        }

        /* Card dimensions remain the same */
        .card {
            width: 900px;
            height: 1200px;
            display: flex;
            flex-direction: column;
            background: #fff;
            color: #fff; /* Default text color is white for overlay */
            overflow: hidden;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1);
            position: relative; /* Essential for absolute positioning of text/footer */
        }

        .image-container {
            height: 100%; /* Image takes up the whole card */
            width: 100%;
            position: relative;
            display: flex;
            overflow: hidden;
        }

        .bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dark overlay for better text readability on top of any image */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3); /* Semi-transparent black */
            z-index: 1;
        }

        .category {
            position: absolute;
            top: 2rem;
            left: 2rem;
            background: #ff3b3b;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            z-index: 10;
            box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.4);
        }

        /* Main content area, centered for the title */
        .content {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, -50%); /* Center horizontally and vertically */
            width: 80%;
            text-align: center;
            z-index: 5;
        }

        .title {
            font-size: 5rem; /* Extra large font for viral impact */
            font-weight: 900;
            line-height: 1.1;
            color: #fff;
            text-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.8); /* Strong shadow for visibility */
            text-transform: uppercase;
        }

        .footer {
            position: absolute;
            bottom: 2rem;
            width: calc(100% - 4rem); /* 100% minus padding on both sides */
            left: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 2px solid rgba(255, 255, 255, 0.3); /* White separator line */
            z-index: 5;
        }

        .source-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9); /* Subtle white */
            text-transform: uppercase;
            letter-spacing: 0.08rem;
            font-style: italic;
            text-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.5);
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 900;
            color: #ffcc00; /* A contrasting color for the logo */
            text-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.5);
        }

        .visibiliy-hidden {
            visibility: hidden;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="image-container">
            <img src="{{ $image_url }}" class="bg" alt="Background image for the viral post">
            <div class="overlay"></div>
        </div>

        @if(!empty($category))
            <div class="category">{{ $category }}</div>
        @endif

        <div class="content">
            <h1 class="title">{{ $title }}</h1>
        </div>

        <div class="footer">
            <span class="source-text {{ empty($source) ? 'visibiliy-hidden' : '' }}">SOURCE: {{ $source }}</span>
            <span class="logo-text">{{ config('app.name') ?? 'YOUR BRAND' }}</span>
        </div>

    </div>

</body>

</html>