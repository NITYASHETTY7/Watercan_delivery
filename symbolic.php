<?php
$targetFolder = __DIR__ . '/core/storage/app/public';
$linkFolder = __DIR__ . '/storage';

// Uncomment the following lines for debugging
// echo $targetFolder . "\n";
// echo $linkFolder;
// return;

// Check if the target folder exists
if (!is_dir($targetFolder)) {
    echo 'Error: Target folder does not exist';
    return;
} 

// Check if there is no existing symlink or file at the destination
if (file_exists($linkFolder)) {
    echo 'Error: Symlink or file already exists at the destination';
    return;
}


// Create symbolic link and store the result in $link
$link = symlink($targetFolder, $linkFolder);

// Check the result and provide feedback
if ($link !== false) {
    echo 'Symlink process successfully completed';
} else {
    echo 'Symlink process failed';
}
?>