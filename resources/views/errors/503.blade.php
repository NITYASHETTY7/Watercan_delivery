<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Service Unavailable</title>
    {{-- Include bootstrap css --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include bootstrap css --}}
    <style>
        .content {
            margin-top: 100px;
            text-align: center;
        }

        .company-logo img {
            width: 10rem;
            margin-bottom: 2rem;
            margin-top: 4rem;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        a {
            color: #fff !important;
            padding: 10px 20px;
            background: #343f52;
            border: 1px solid #343f52;
            margin: 20px 0;
            border-radius: 8px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="company-logo">
            <img class="" src="{{ asset('site/v1/img/error/company_logo.png') }}" alt="503 Service Unavailable">
        </div>
        <p>Sorry, the service is temporarily unavailable. Please try again later.</p>
        <a href="{{ url('/') }}" class="link_404 text-primary" style="background-color: #3f78e0;">Go to Home</a>
    </div>
</body>

</html>
