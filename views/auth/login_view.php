<form method="POST" action="../controllers/login.php">
    <?php if (isset($successMessage)): ?>
        <p style="color: green; font-weight: bold;">✓ <?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: bold;">✗ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <table>
        <tr>
            <td>Nama</td>
            <td> : </td>
            <td><input type="text" name="username" placeholder="namaAnda"></td>
        </tr>

        <tr>
            <td>Email</td>
            <td> : </td>
            <td><input type="email" name="email"></td>
        </tr>

        <tr>
            <td>Password</td>
            <td> : </td>
            <td><input type="password" name="password"></td>
        </tr>
    </table>
    <button type="submit">Login</button>
</form>