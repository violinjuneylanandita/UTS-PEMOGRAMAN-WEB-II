<?php
// pseudoklausen.php
session_start();


// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
require 'db.php'; // Mengimpor koneksi database

$sql = "SELECT users.username AS full_name, users.email, courses.name AS course_name 
        FROM registrations 
        JOIN users ON registrations.user_id = users.id 
        JOIN courses ON registrations.course_id = courses.id";
$stmt = $conn->query($sql);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission untuk menambahkan kursus
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $instructor_name = $_POST['instructor_name'];
    
    // Cek apakah instruktur sudah ada
    $stmt = $conn->prepare("SELECT id FROM instructors WHERE name = ?");
    $stmt->execute([$instructor_name]);
    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instructor) {
        // Jika instruktur belum ada, tambahkan instruktur baru
        $stmt = $conn->prepare("INSERT INTO instructors (name) VALUES (?)");
        $stmt->execute([$instructor_name]);
        $instructor_id = $conn->lastInsertId();
    } else {
        $instructor_id = $instructor['id'];
    }

    // Tambahkan kursus
    $stmt = $conn->prepare("INSERT INTO courses (name, instructor_id) VALUES (?, ?)");
    $stmt->execute([$course_name, $instructor_id]);
    header("Location: pseudoklausen.php");
    exit();
}

// Handle pengambilan kursus oleh peserta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrollments_course'])) {
    $course_id = $_POST['course_id'];

    // Cek apakah user sudah terdaftar dalam kursus ini
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    if ($stmt->rowCount() == 0) {
        // Jika belum terdaftar, masukkan ke dalam database
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
    }
    
    header("Location: pseudoklausen.php");
    exit();
}

// Query untuk mengambil data kursus dan instruktur
$sql = "SELECT courses.id AS course_id, courses.name AS course_name, instructors.name AS instructor_name 
        FROM courses 
        JOIN instructors ON courses.instructor_id = instructors.id";
$stmt = $conn->query($sql);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil kursus yang diikuti peserta
$sql = "SELECT courses.name FROM enrollments 
        JOIN courses ON enrollments.course_id = courses.id 
        WHERE enrollments.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kursus - Kursus Online</title>
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
        <h1>Kursus</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="peserta.php">Peserta Kursus</a>
        <a href="statistik.php">Statistik</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <div class="header">
            <h2>Daftar Kursus dan Instruktur</h2>
        </div>
        <form method="POST">
            <input type="text" name="course_name" placeholder="Nama Kursus" required>
            <input type="text" name="instructor_name" placeholder="Nama Instruktur" required>
            <button type="submit" name="add_course">Tambah Kursus</button>
        </form>
        <div class="content">
        <table>
            <tr>
                <th>Nama Kursus</th>
                <th>Instruktur</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                <td><?php echo htmlspecialchars($course['instructor_name']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                        <button type="submit" name="enrollments_course">Ambil Kursus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Kursus yang Anda Ambil:</h3>
<table>
    <tr>
        <th>No</th>
        <th>Nama Kursus</th>
    </tr>
    <?php 
    $no = 1;
    foreach ($enrolled_courses as $course): ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($course['name']); ?></td>
    </tr>
    <?php endforeach; ?>
</table></div>
        <div class="footer">
            © 2021 Jagowebdev.com
        </div>
    </div>
</body>
</html>
