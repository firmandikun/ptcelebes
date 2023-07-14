<?php
session_start();
include "../config.php";   //memasukan koneksi
include "AES256.php"; //memasukan file AES

// jangan diubah
$key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$user          = $_SESSION['username'];

if (isset($_POST['encrypt_now'])) {
  $deskripsi = $_POST['desc'];
  $file_tmpname   = $_FILES['file']['tmp_name'];
  //untuk nama file url
  $file           = rand(1000, 100000) . "-" . $_FILES['file']['name'];
  $new_file_name  = strtolower($file);
  $final_file     = str_replace(' ', '-', $new_file_name);
  //untuk nama file
  $filename       = rand(1000, 100000) . "-" . pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
  $new_filename  = strtolower($filename);
  $finalfile     = str_replace(' ', '-', $new_filename);
  $size           = filesize($file_tmpname);
  $size2          = (filesize($file_tmpname)) / 1024;
  $info           = pathinfo($final_file);
  $file_source        = fopen($file_tmpname, 'rb');
  $ext            = $info["extension"];

  $pass = md5($_POST["pwdfile"]);
  $sql1   = "INSERT INTO file VALUES ('', '$user', '$final_file', '$finalfile.encrypted', '', '$size2', '$pass', now(), '1', '$deskripsi')";
  $query1  = mysqli_query($connect, $sql1) or die(mysqli_connect_error());

  $sql2   = "select * from file where file_url =''";
  $query2  = mysqli_query($connect, $sql2) or die(mysqli_connect_error());

  $url   = $finalfile . ".encrypted";
  $file_url = "file_encrypt/" . $url;
  // move_uploaded_file($file_tmpname, $file_url);
  $sql3   = "UPDATE file SET file_url ='$file_url' WHERE file_name_source='$final_file'";
  $query3  = mysqli_query($connect, $sql3) or die(mysqli_connect_error());

  encryptFile($file_tmpname, $key, $file_url);

  echo "<script language='javascript'>
            window.location.href='encrypt.php';
            alert('Enkripsi Berhasil..');

          </script>";
}


// proses enkripsi lagi ketika habis di dekrip

if (isset($_GET['id_enkrip'])) {

  $id_enkrip = $_GET['id_enkrip'];
  // get data enkripsi terakhir
  $file_sql   = "UPDATE file SET status='1' WHERE id_file='$id_enkrip'";
  mysqli_query($connect, $file_sql) or die(mysqli_connect_error());

  echo "<script language='javascript'>
            window.location.href='decrypt.php';
            alert('Enkripsi Berhasil..');
          </script>";
}
