<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 Session Expired</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        .page_404 {
            padding: 40px 0;
            background: #fff;
        }

        .page_404 img {
            width: 100%;
        }

        /* .four_zero_four_bg {

            background-image: url('{{ asset('site/v1/img/error/funny-404-error-page-design.gif') }}');
            height: 400px;
            background-position: center;
        } */

        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #65b530;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_404 {
            margin-top: -50px;
        }

        .h1 {
            margin-bottom: 0px !important;
        }
    </style>
</head>

<body>
    <div>
        <section class="page_419">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-10 col-sm-offset-1 text-center mx-auto">
                            <div class="four_zero_four_bg" style="margin-top: 70px;">
                                <img src="{{ asset('site/v1/img/lock.png') }}" class="logo-light" alt="logo"
                                    style="width: 150px;">
                                <h1 class="text-center mb-0" style="margin-bottom: 50px !important;">419</h1>
                            </div>

                            <div class="contant_box_404 mt-1">
                                <p>Sorry, your session has expired.
                                    <br>
                                    Please Go to Dashboard and try again.
                                </p>
                                <h4>We prioritize protecting your security and privacy</h4>

                                 <a href="{{ url('/') }}" class="link_404 text-primary" style="background-color: #3f78e0;">Go to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>

</html>
