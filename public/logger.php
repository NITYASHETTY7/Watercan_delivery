<?php
// Get the file name from the query parameter
$fileName = isset($_GET['file_name']) ? $_GET['file_name'] : null;

// Set the base directory where logs are stored
// echo $baseDir ='/home/social-vibes-core/storage/logs/';
$baseDir = __DIR__ . '/../social-vibes-core/storage/logs/';

// Validate the file name
if ($fileName && preg_match('/^[a-zA-Z0-9_\-\.]+$/', $fileName)) {
    $logFilePath = $baseDir . $fileName;
} else {
    die("Invalid or missing file name.");
}

// Handle log file clearing
if (isset($_POST['clear_log'])) {
    file_put_contents($logFilePath, ''); // Clear the log file
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Check if the log file exists
if (!file_exists($logFilePath)) {
    die("Log file does not exist.");
}

// Read the log file content
$logContent = file_get_contents($logFilePath);

// Split log content into lines and reverse the order
$logLines = array_reverse(explode(PHP_EOL, $logContent));

// Function to format log lines with enhanced color coding
function formatLogLine($line) {
    if (strpos($line, '[ERROR]') !== false) {
        return '<span style="color:#FF5555; background-color:#FFEFEF; font-weight:bold;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[INFO]') !== false) {
        return '<span style="color:#55AA55; background-color:#F0FFF0;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[WARNING]') !== false) {
        return '<span style="color:#FFAA00; background-color:#FFFBEF;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[DEBUG]') !== false) {
        return '<span style="color:#5555FF; background-color:#EFEFFF;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[CRITICAL]') !== false) {
        return '<span style="color:#FF0000; background-color:#FFE5E5; font-weight:bold;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[ALERT]') !== false) {
        return '<span style="color:#FF4500; background-color:#FFEFE0; font-weight:bold;">' . htmlentities($line) . '</span>';
    } elseif (strpos($line, '[NOTICE]') !== false) {
        return '<span style="color:#1E90FF; background-color:#E5F5FF;">' . htmlentities($line) . '</span>';
    } else {
        return htmlentities($line);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities($fileName); ?> | Book My Water Log Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .log-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: scroll;
            border-left: 5px solid black;
        }
        .log-line {
            margin-bottom: 10px;
        }
        .log-line code {
            white-space: pre-wrap;
            word-wrap: break-word;
            padding: 10px;
            border-radius: 5px;
            display: block;
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
        }
        .controls {
            margin-bottom: 20px;
        }
        .clear-log-button {
            background-color: #FF5555;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .clear-log-button:hover {
            background-color: #FF3333;
        }
    </style>
</head>
<body>

<div class="controls">
    <form method="post">
        <button type="submit" name="clear_log" class="clear-log-button">Clear Log</button>
    </form>
</div>

<div class="log-container">
    <h1>Book My Water Log Viewer - <?php echo htmlentities($fileName); ?></h1>
    <?php foreach ($logLines as $line): ?>
        <?php if (trim($line) != ''): ?>
            <div class="log-line"><code><?php echo formatLogLine($line); ?></code></div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

</body>
</html>
