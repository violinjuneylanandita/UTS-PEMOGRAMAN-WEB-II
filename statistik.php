<?php
// statistik.php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Mengimpor koneksi database

// Query untuk menghitung jumlah pendaftar per kursus
$sql = "SELECT courses.name AS course_name, COUNT(enrollments.user_id) AS total_participants 
        FROM enrollments 
        JOIN courses ON enrollments.course_id = courses.id 
        GROUP BY courses.name";
$stmt = $conn->query($sql);
$statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik - Kursus Online</title>
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
        .container {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
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
        .content table {
            width: 100%;
            border-collapse: collapse;  
        }
        .content table, .content th, .content td {
            border: 1px solid #ddd;
        }
        .content th, .content td {
            padding: 8px;
            text-align: center;
        }
        .content th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    </style>
</head>
<body>
        <div class="sidebar">
            <h2>Statisik</h2>
            <a href="dashboard.php">Dashboard</a>
            <a href="pseudoklausen.php">Kursus</a>
            <a href="peserta.php">Peserta Kursus</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="container">
        <div class="header">
            <h2>Statistik Peserta</h2>
            <div class="content">
            <table>
                <tr>
                    <th>Nama Kursus</th>
                    <th>Jumlah Pendaftar</th>
                </tr>
                <?php foreach ($statistics as $statistic): ?>
                <tr>
                    <td><?php echo htmlspecialchars($statistic['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($statistic['total_participants']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="footer">
            © 2021 Jagowebdev.com
        </div>
    </div>
</body>
</html>