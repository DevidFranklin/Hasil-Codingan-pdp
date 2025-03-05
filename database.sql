
CREATE DATABASE kredit_db;
USE kredit_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE pengajuan_kredit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255),
    nik VARCHAR(50),
    harga_kendaraan DECIMAL(10,2),
    down_payment DECIMAL(10,2),
    lama_kredit INT,
    angsuran DECIMAL(10,2),
    dokumen VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'paid') DEFAULT 'pending'
);
