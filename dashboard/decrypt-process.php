<?php
session_start();
include "../config.php";   //memasukan koneksi
include "AES256.php"; //memasukan file AES
include "download.php";
// jangan diubah
$key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

$idfile    = $_POST['fileid'];
$pwdfile   = md5($_POST["pwdfile"]);
$query     = "SELECT password FROM file WHERE id_file='$idfile' AND password='$pwdfile'";
$sql       = mysqli_query($connect, $query);
if (isset($_POST["decrypt_now"])) {
    $query1     = "SELECT * FROM file WHERE id_file='$idfile'";
    $sql1       = mysqli_query($connect, $query1);
    $data       = mysqli_fetch_assoc($sql1);

    $file_path  = $data["file_url"];
    $key        = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $file_name  = $data["file_name_source"];
    $size       = $data["file_size"];

    $query2     = "UPDATE file SET status='2' WHERE id_file='$idfile'";
    $sql2       = mysqli_query($connect, $query2);

    $path      = "file_decrypt/" . $file_name;
    decryptFile($file_path, $key, $path);
    echo ("<script language='javascript'>
       window.location.href='decrypt.php';
       window.alert('Berhasil mendekripsi file.');
       </script>
       ");
} else {
    echo ("<script language='javascript'>
    window.location.href='decrypt-file.php?id_file=$idfile';
    window.alert('Maaf, Password tidak sesuai.');
    </script>");
}
