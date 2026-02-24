<form method="POST" action="../../controllers/register.php">
    <table>
        <tr>
            <td>Nama</td>
            <td> : </td>
            <td><input type="text" name="username" placeholder="namaAnda"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td> : </td>
            <td><input type="email" name="email" placeholder="emailAnda@contoh.com"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td> : </td>
            <td><input type="password" name="password" placeholder="passwordAnda"></td>
        </tr>
        <tr>
            <td>Konfirmasi Password</td>
            <td> : </td>
            <td><input type="password" name="confirm" placeholder="konfirmasiPasswordAnda"></td>
        </tr>
    </table>
    <button type="submit">Login</button>
</form>