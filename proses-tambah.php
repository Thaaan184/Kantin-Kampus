<?php

require('config.php');

$error = '';
$sukses = '';

if (isset($_POST['submit'])) {
    $balance = stripslashes($_POST['balance']);
    $balance = mysqli_real_escape_string($koneksi, $balance);
    $role = stripslashes($_POST['role']);
    $role = mysqli_real_escape_string($koneksi, $role);
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $name     = stripslashes($_POST['name']);
    $name     = mysqli_real_escape_string($koneksi, $name);
    $email    = stripslashes($_POST['email']);
    $email    = mysqli_real_escape_string($koneksi, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);


    if (!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($role))) {
        if (cek_nama($username, $koneksi) == 0) {
            $pass  = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (balance, role, username, name, email, password ) VALUES ('$balance','$role','$username','$name','$email','$pass')";
            $result   = mysqli_query($koneksi, $query);
            if ($result) {
                header('Location: tambah-user.php?status=sukses');
            } else {
                $error =  'Registrasi Gagal !!';
            }
        } else {
            $error =  'Username sudah terdaftar !!';
        }
    } else {
        $error =  'Data tidak boleh kosong !!';
    }
}

function cek_nama($username, $koneksi)
{
    $username = mysqli_real_escape_string($koneksi, $username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    if ($result = mysqli_query($koneksi, $query)) return mysqli_num_rows($result);
}
