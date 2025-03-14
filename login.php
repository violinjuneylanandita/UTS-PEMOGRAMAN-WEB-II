<?php
session_start();
require 'db.php'; // Mengimpor koneksi database

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data pengguna dari database
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Simpan user ID ke dalam sesi
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; // Set session username
            
            header("Location: dashboard.php"); // Redirect ke dashboard
            exit();
        } else {
            echo "<script>alert('Username atau password salah!'); window.location.href = 'login.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jagowebdev</title>
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
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            font-size: 12px;
            text-align: left;
            

            
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            justify-content: center;
            
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 95%;
            padding: 10px;
            margin: center;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            justify-content: center;
            
        }
        .login-container input[type="checkbox"] {
            margin-right: 5px;
            margin-bottom: 15px;
            align-items: center;
            gap: 5px; /* Jarak antara checkbox dan teks */
            display: inline;
}
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: rgb(61, 122, 235);
            border: none;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
            
        }
        .login-container button:hover {
            background-color:rgb(61, 122, 235);
        }
        .login-container a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            
        }
        .login-container a:hover {
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
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>
                <input type="checkbox" name="remember"> Remember me
                </label>
            <button type="submit">Submit</button>
        </form>
        <a href="#">Reset password</a>
        <a href="register.php">Daftar akun</a>
        <div class="footer">
            © 2021 Jagowebdev.com
        </div>
    </div>
</body>
</html>