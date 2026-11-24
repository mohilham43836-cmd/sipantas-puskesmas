
<?php

require_once "../config/database.php";

$nama = "Administrator";
$username = "admin";
$email = "admin@sipantas.local";
$password = "123456";
$role = "admin";

// Periksa apakah akun sudah ada
$check = $pdo->prepare(
    "SELECT id FROM users WHERE username = ? OR email = ?"
);

$check->execute([$username, $email]);

if ($check->fetch()) {
    die("Akun admin sudah terdaftar.");
}

// Enkripsi password
$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

// Simpan akun
$sql = "INSERT INTO users
        (nama, username, email, password, role)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $nama,
    $username,
    $email,
    $hashedPassword,
    $role
]);

echo "Akun admin berhasil dibuat.";

