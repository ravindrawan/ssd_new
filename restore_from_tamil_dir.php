<?php
// Script to scan the user's uploaded "application forms/tamil" folder and copy files to "forms/tamil/" directory
// maintaining their original Tamil filenames.

$src_dir = __DIR__ . '/application forms/tamil';
$dest_dir = __DIR__ . '/forms/tamil';

if (!is_dir($dest_dir)) {
    mkdir($dest_dir, 0777, true);
}

if (!is_dir($src_dir)) {
    die("Source directory 'application forms/tamil' does not exist!\n");
}

$files = scandir($src_dir);
$count = 0;
foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    $src_file = $src_dir . '/' . $file;
    if (is_file($src_file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
        $dest_file = $dest_dir . '/' . $file;
        if (copy($src_file, $dest_file)) {
            echo "Copied: '$file' to forms/tamil/\n";
            $count++;
        } else {
            echo "Failed to copy: '$file'\n";
        }
    }
}
echo "Total $count files copied to forms/tamil/ successfully.\n";
