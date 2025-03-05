<?php
// 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kredit_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registrasi Pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil. Silakan login.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Login Pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        echo "Login berhasil!";
    } else {
        echo "Login gagal. Periksa email dan password.";
    }
}

// Pengajuan Kredit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $harga_kendaraan = $_POST['harga_kendaraan'];
    $down_payment = $_POST['down_payment'];
    $lama_kredit = $_POST['lama_kredit'];
    $angsuran = $_POST['angsuran'];

    $dokumen = $_FILES['dokumen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($dokumen);
    move_uploaded_file($_FILES['dokumen']['tmp_name'], $target_file);

    $sql = "INSERT INTO pengajuan_kredit (nama, nik, harga_kendaraan, down_payment, lama_kredit, angsuran, dokumen, status) 
            VALUES ('$nama', '$nik', '$harga_kendaraan', '$down_payment', '$lama_kredit', '$angsuran', '$dokumen', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "Pengajuan berhasil dikirim.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Simulasi Integrasi dengan Bank (Validasi Pembayaran)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bayar'])) {
    $pengajuan_id = $_POST['pengajuan_id'];
    $amount = $_POST['amount'];

    $sql = "UPDATE pengajuan_kredit SET status='paid' WHERE id=$pengajuan_id";
    if ($conn->query($sql) === TRUE) {
        echo "Pembayaran berhasil diverifikasi oleh bank.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Digital Kredit System</title>
</head>
<body>
    <h2>Registrasi</h2>
    <form method="POST">
        Nama: <input type="text" name="nama" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="register" value="Daftar">
    </form>

    <h2>Login</h2>
    <form method="POST">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Masuk">
    </form>

    <h2>Form Pengajuan Kredit</h2>
    <form method="POST" enctype="multipart/form-data">
        Nama: <input type="text" name="nama" required><br>
        NIK: <input type="text" name="nik" required><br>
        Harga Kendaraan: <input type="text" name="harga_kendaraan" required><br>
        Down Payment: <input type="text" name="down_payment" required><br>
        Lama Kredit: <input type="text" name="lama_kredit" required><br>
        Angsuran: <input type="text" name="angsuran" required><br>
        Dokumen: <input type="file" name="dokumen" required><br>
        <input type="submit" name="submit" value="Kirim Pengajuan">
    </form>

    <h2>Pembayaran Kredit</h2>
    <form method="POST">
        ID Pengajuan: <input type="text" name="pengajuan_id" required><br>
        Jumlah Bayar: <input type="text" name="amount" required><br>
        <input type="submit" name="bayar" value="Bayar ke Bank">
    </form>
</body>
</html>
