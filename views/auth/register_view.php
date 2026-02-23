<form method="POST" action="../../controllers/register.php">
    <table>
        <tr>
            <td>Nama</td>
            <td> : </td>
            <td><input type="text" name="username"></td>
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

        <tr>
            <td>Konfirmasi Password</td>
            <td> : </td>
            <td><input type="password" name="confirm"></td>
        </tr>
    </table>

    <button type="submit">Login</button>
</form>