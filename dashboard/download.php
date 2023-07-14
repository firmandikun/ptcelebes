<?php
session_start();

if (isset($_GET['path'])) {
    $file = "file_decrypt/" . $_GET['path'];
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
    // header("Content-Length: " . filesize($file));

    // Read the file and output its contents
    $handle = fopen($file, "rb");
    if ($handle) {
        while (!feof($handle)) {
            echo fread($handle, 8192);
            ob_flush();
            flush();
        }
        fclose($handle);
    }
    header("Location: history.php");
    exit();
}
