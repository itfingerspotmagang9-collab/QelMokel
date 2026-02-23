<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Selamat datang, <?= htmlspecialchars($user['username']) ?>!</p>
    <a href="?page=logout">Logout</a>
</body>
</html>
