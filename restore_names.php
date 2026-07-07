<?php
// Script to scan the user's uploaded "application forms" folder and copy files to "forms/" directory
// maintaining their original Sinhala and English filenames.

$src_dir = __DIR__ . '/application forms';
$dest_dir = __DIR__ . '/forms';

if (!is_dir($dest_dir)) {
    mkdir($dest_dir, 0777, true);
}

if (!is_dir($src_dir)) {
    die("Source directory 'application forms' does not exist!\n");
}

$files = scandir($src_dir);
$count = 0;
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || $file === 'tamil') {
        continue;
    }
    
    $src_file = $src_dir . '/' . $file;
    if (is_file($src_file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
        $dest_file = $dest_dir . '/' . $file;
        if (copy($src_file, $dest_file)) {
            echo "Copied: '$file' to forms/\n";
            $count++;
        } else {
            echo "Failed to copy: '$file'\n";
        }
    }
}
echo "Total $count files copied to forms/ successfully.\n";
