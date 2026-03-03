<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = sha1($_POST['password']);

    $insertUser = mysqli_query($koneksi, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')");
    if ($insertUser) {
        header("location:?page=user");
    }
}

if (isset($_GET['id'])) {
    $idEdit = base64_decode($_GET['id']);
    $selectUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$idEdit'");
    $row = mysqli_fetch_assoc($selectUser);

    if (isset($_POST['edit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = sha1($_POST['password']);
        if ($pass != null) {
            $updateUser = mysqli_query($koneksi, "UPDATE users SET name='$name', email='$email', password='$pass', updated_at=now() WHERE id='$idEdit'");
        } else {
            $updateUser = mysqli_query($koneksi, "UPDATE users SET name='$name', email='$email', updated_at=now() WHERE id='$idEdit'");
        }

        if ($updateUser) {
            header("location:?page=user");
        }
    }
}
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h4><?php echo (isset($_GET['id'])) ? 'EDIT' : 'Tambah' ?> Pengguna</h4>
        </div>
    </div>
    <form action="" method="post">
        <div class="card-body">
            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap *</label>
                <input placeholder="Masukkan nama lengkap" type="text" class="form-control" id="nama" name="name" value="<?php echo (isset($_GET['id'])) ? $row['name'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input placeholder="cth: admin@gmail.com" type="email" class="form-control" id="email" name="email" value="<?php echo (isset($_GET['id'])) ? $row['email'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password *</label>
                <input placeholder="Masukkan password" type="password" class="form-control" name="password" <?php echo (!isset($_GET['id'])) ? 'required' : '' ?>>
            </div>
        </div>
        <div class="card-action">
            <button type="submit" class="btn btn-primary" name="<?php echo (isset($_GET['id'])) ? 'edit' : 'add' ?>">
                <?php echo (isset($_GET['id'])) ? 'Simpan Perubahan' : 'Simpan' ?>
            </button>
            <a href="?page=user" class="btn btn-danger">Batalkan</a>
        </div>
    </form>
</div>