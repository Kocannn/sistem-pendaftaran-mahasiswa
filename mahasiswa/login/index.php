<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: /sistem-informasi-pendaftaran/mahasiswa");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Login Mahasiswa</h2>

        <form class="space-y-4" method="post" action="../../service/mahasiswa/login.php">
            <div>
                <label for="nisn" class="block font-medium">NISN</label>
                <input type="text" id="nisn" class="w-full p-2 border rounded" placeholder="Masukkan NISN" name="nisn" />
            </div>
            <div>
                <label for="password" class="block font-medium">Password</label>
                <input type="password" id="password" class="w-full p-2 border rounded" placeholder="Masukkan Password" name="password" />
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>

        <p class="text-sm text-center mt-4">Belum punya akun? <a href="/sistem-informasi-pendaftaran/mahasiswa/register" class="text-blue-500 hover:underline">Daftar di sini</a></p>
    </div>
</body>

</html>