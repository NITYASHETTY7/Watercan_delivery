<?php

// ob_start();

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
ini_set('memory_limit','2G');
set_time_limit(120); // Sets the maximum execution time to 120 seconds
define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

function isFileTypeAllowed($fileName) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    return in_array($fileExtension, $allowedExtensions);
}

// For Detecting file is uploaded in write extension
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if there are any files uploaded
    if (!empty($_FILES)) {
        foreach ($_FILES as $inputName => $file) {
            // Check if the file has been uploaded without errors
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Get the original name of the uploaded file
                $fileName = $file['name'];

                // Get the file extension
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Check if the file extension is insecure
                $insecureExtensions = array('sql', 'php', 'zip', 'exe', 'dll', 'js'); // add more extensions as needed

                if (in_array($fileExtension, $insecureExtensions)) {
                    http_response_code(403);
                    echo '<h1>Threat Detected!</h1>';
                    echo '<p>You have uploaded a file with an insecure file type.</p>';
                    echo '<p>This incident has been reported to the Cyber Bureau of Investigation (CBI).</p>';
                    echo '<p>Your IP address has been logged and will be monitored for further suspicious activity.</p>';
                    echo '<p>Please refrain from uploading malicious files to our system.</p>';
                    exit;
                }
            }
        }
    }
}
/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
