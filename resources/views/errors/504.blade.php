<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway Timeout</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        .page_504 {
            padding: 40px 0;
            background: #fff;
        }

        .page_504 img {
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

        .link_504 {
            color: #fff !important;
            padding: 10px 20px;
            background: #65b530;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_504 {
            margin-top: 50px;
        }

        .h1 {
            margin-bottom: 0px !important;
        }
    </style>
</head>

<body>

    <div>
        <section class="page_504 mt-4">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-10 col-sm-offset-1 text-center mx-auto mt-4">
                            <div class="four_zero_four_bg">
                                <h1 class="text-center" style="margin-bottom: 0px !important;">504</h1>
                                {{-- <img src="{{ asset('site/v1/images/504-gateway.png') }}" class="logo-light" alt="logo" style="width: 300px;"> --}}
                            </div>

                            <div class="contant_box_504 mt-3">
                                <h3 class="h2">
                                    504 Gateway Timeout
                                </h3>
                                <p>The server took too long to respond. Please try again later!</p>
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
