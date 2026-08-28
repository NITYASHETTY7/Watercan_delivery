<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: Custom utility to ensure the container takes the full viewport height if needed */
        .min-h-screen-custom {
            min-height: 100vh;
        }
        body{
        font-family: "Open Sans", sans-serif;
        }
    </style>

</head>

<body class="bg-gray-50 min-h-screen-custom flex items-center justify-center">

    <div class="container mx-auto px-4 py-16">
        <section class="flex flex-col items-center justify-center text-center">

            <div class="max-w-md w-full bg-white p-8 sm:p-12 rounded-xl shadow-2xl border border-gray-100">

                <div class="four_zero_four_bg mb-6">
                    <img src="{{ asset('site/v1/img/error/403.png') }}" alt="403 Forbidden"
                        class="w-32 h-auto mx-auto mb-4" />

                    <h1 class="text-5xl font-extrabold text-blue-600 mb-0 leading-none">403</h1>
                </div>

                <div class="contant_box_404 mt-2">
                    <h2 class="text-2xl sm:text-2xl font-bold text-gray-800">
                        Access Denied
                    </h2>

                    <p class="text-sm text-gray-700 font-medium">
                        You don't have the necessary access rights to view this page.
                    </p>

                    <a href="{{ url('/') }}"
                        class="link_404 mt-6 inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Go to Home
                    </a>
                </div>
            </div>

            {{-- <div class="mt-8 text-sm text-gray-400">
                &copy; {{ date('Y') }} YourAppName
            </div> --}}

        </section>
    </div>

</body>

</html>