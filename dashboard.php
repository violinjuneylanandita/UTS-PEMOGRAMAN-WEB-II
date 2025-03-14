<?php
// dashboard.php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kursus Online</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color:rgb(61, 122, 235);
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            text-align: center;
            
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 5px;
            font-weight: bold;
            margin-bottom: 15px;
            display: block;
            border: 1px solid #ccc;
            border-radius: 1px;
            justify-content: center;
            text-align: left;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
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
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="pseudoklausen.php">Kursus</a>
        <a href="peserta.php">Peserta Kursus</a>
        <a href="statistik.php">Statistik</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="content">
        <div class="header">
            <h1>Dashboard - Kursus Online</h1>
        </div>
        <div class="content">
        <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>Gunakan menu di samping untuk mengakses berbagai fitur.</p>
        <div class="footer">
            © 2021 Jagowebdev.com
        </div>
    </div>
</body>
</html>
