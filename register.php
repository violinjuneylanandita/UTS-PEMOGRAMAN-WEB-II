<?php
// register.php
require 'db.php'; // Mengimpor koneksi database

// Proses pendaftaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi sederhana
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok!');</script>";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data ke database
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // Tampilkan alert dan redirect ke login
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href = 'login.php';</script>";
            exit();
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Jagowebdev</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-display: center;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            font-size: 12px;
            text-align: left;
        }
        .register-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            justify-content: center;
        }
        .register-container input[type="text"],
        .register-container input[type="password"] {
            width: 95%;
            padding: 10px;
            margin: center;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            justify-content: center;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: rgb(61, 122, 235);
            border: none;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: rgb(61, 122, 235);
        }
        .register-container a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Daftar Akun</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
        </form>
        <a href="login.php">Sudah punya akun? Login</a>
        <div class="footer">
            © 2021 Jagowebdev.com
        </div>
    </div>
</body>
</html>