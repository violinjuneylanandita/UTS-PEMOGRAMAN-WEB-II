<?php
// peserta.php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Mengimpor koneksi database

// Query untuk mengambil data peserta kursus
$sql = "SELECT users.username AS full_name, users.email, courses.name AS course_name 
        FROM enrollments  
        JOIN users ON enrollments.user_id = users.id 
        JOIN courses ON enrollments.course_id = courses.id";
$stmt = $conn->query($sql);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT users.username AS full_name, users.email, courses.name AS course_name 
        FROM enrollments  
        JOIN users ON enrollments.user_id = users.id 
        JOIN courses ON enrollments.course_id = courses.id";

// Jika ada input pencarian, tambahkan filter ke query
if ($search) {
    $sql .= " WHERE users.username LIKE :search";
}

$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peserta Kursus - Kursus Online</title>
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
</head>
<body>
        <div class="sidebar">
            <h2>Peserta Kursus</h2>
            <a href="dashboard.php">Dashboard</a>
            <a href="pseudoklausen.php">Daftar Kursus</a>
            <a href="statistik.php">Statistik </a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="container">
        <div class="header">
            <h2>Daftar Peserta Kursus</h2>
            <form method="GET" action="peserta.php">
            <input type="text" name="search" placeholder="Cari Nama Peserta..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
            </form>
            <div class="content">
            <table>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Kursus</th>
                </tr>
                <?php foreach ($participants as $participant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($participant['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($participant['email']); ?></td>
                    <td><?php echo htmlspecialchars($participant['course_name']); ?></td>
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