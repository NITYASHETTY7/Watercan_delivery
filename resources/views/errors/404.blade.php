<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    {{-- Include bootstrap css --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include bootstrap css --}}
    <style>
        .page_404 {
            padding: 40px 0;
            background: #fff;
        }

        .page_404 img {
            width: 100%;
        }

        .company-logo img {
            width: 12rem;
            margin-top: 40px;
        }

        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #343f52;
            border: 1px solid #343f52;
            margin: 20px 0;
            border-radius: 8px;
            display: inline-block;
            font-size: 15px;
            font-weight: 600;

        }


        h3 {
            font-size: 30px;
        }

        .ip-address {
            background-color: #343f5217;
            padding: 7px 15px;
        }

        a:focus,
        a:hover {
            text-decoration: none;
            background: #222937;
        }
    </style>
</head>

<body>
    <div>
        <section class="page_404">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-offset-1 mx-auto text-center">
                        <div class="four_zero_four_bg">

                            <div class="company-logo">
                                <img class="" src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                    alt="">
                            </div>
                        </div>

                        <div class="contant_box_404">
                            <h3 class="fs-30">
                                Oops! Page Not Found.
                            </h3>

                            <p class="">It looks like the page you’re searching for doesn’t exist or may have
                                been moved. Double-check the URL or head back to the homepage to continue exploring.
                                Need help? Our support team is just a click away!</p>

                            <a href="{{ url('/') }}" class="link_404 text-primary" style="background-color: #3f78e0;">Go to Home</a>


                            <div>
                                <span class="ip-address">Your IP address: {{ request()->ip() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
