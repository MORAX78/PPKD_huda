<?php
$selectCategories = mysqli_query($koneksi, "SELECT * FROM categories");
$rowCategories = mysqli_fetch_all($selectCategories, MYSQLI_ASSOC);

if (isset($_POST['add'])) {
    $category_id = $_POST['category_id'];
    $product_name = $_POST['product_name'];
    $product_photo = $_FILES['product_photo'];
    $product_price = $_POST['product_price'];
    $qty = $_POST['qty'];
    $product_description = $_POST['product_description'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    if ($product_photo['error'] == 0) {
        $fileName = uniqid() . "_" . basename($product_photo['name']);
        $filePath = "assets/img/" . $fileName;
        move_uploaded_file($product_photo['tmp_name'], $filePath);
        $insertProduct = mysqli_query($koneksi, "INSERT INTO products (category_id, product_name, product_price, qty, product_description, is_active, product_photo) VALUES ('$category_id', '$product_name', '$product_price', '$qty', '$product_description', '$is_active', '$fileName')");
        if ($insertProduct) {
            header("location:?page=product");
        }
    }
}

if (isset($_GET['id'])) {
    $idEdt = base64_decode($_GET['id']);

    $selectProduct = mysqli_query($koneksi, "SELECT * FROM products WHERE id='$idEdt'");
    $rowProduct = mysqli_fetch_assoc($selectProduct);
    if (isset($_POST['edit'])) {
        $category_id = $_POST['category_id'];
        $product_name = $_POST['product_name'];
        $product_photo = $_FILES['product_photo'];
        $product_price = $_POST['product_price'];
        $qty = $_POST['qty'];
        $product_description = $_POST['product_description'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $uploadFoto = "";
        if ($product_photo['error'] == 0) {
            $fileName = uniqid() . "_" . basename($product_photo['name']);
            $filePath = "assets/img/" . $fileName;
            if (move_uploaded_file($product_photo['tmp_name'], $filePath)) {
                if (file_exists("assets/img/" . $rowProduct['product_photo'])) {
                    unlink("assets/img/" . $rowProduct['product_photo']);
                }
                $uploadFoto = "product_photo='$fileName',";
            } else {
                echo "GAGAL UPLOAD FOTO BARU!";
            }
        }
        $update = mysqli_query($koneksi, "UPDATE products SET category_id='$category_id', product_name='$product_name', $uploadFoto product_price='$product_price', qty='$qty', product_description='$product_description', is_active='$is_active' WHERE id='$idEdt'");
        if ($update) {
            header("location:?page=product");
            exit;
        }
    }
}
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h4><?php echo (isset($_GET['id'])) ? 'Ubah' : 'Tambah' ?> Produk</h4>
        </div>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group">
                <label for="" class="form-label">Kategori Produk</label>
                <select name="category_id" class="form-select" required>
                    <option value="">--Pilih Kategori--</option>
                    <?php
                    foreach ($rowCategories as $v) {

                        if ($v['category_name'] !== 'All Menus') {
                    ?>
                            <option value="<?php echo $v['id'] ?>" <?php echo isset($_GET['id']) && $v['id'] ==  $rowProduct['category_id'] ? 'selected' : '' ?>><?php echo $v['category_name'] ?></option>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Nama Produk *</label>
                <input placeholder="Masukkan nama produk" required type="text" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['product_name'] : '' ?>" name="product_name">
            </div>
            <div class="form-group">
                <?php
                if (isset($_GET['id'])) {
                ?>
                    <img src="assets/img/<?php echo $rowProduct['product_photo'] ?>" width="120" alt="">
                <?php
                }
                ?>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Foto *</label>
                <input type="file" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['product_photo'] : '' ?>" name="product_photo" <?php echo (!isset($_GET['id'])) ? 'required' : '' ?>>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Harga</label>
                <input placeholder="Masukkan harga" type="number" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['product_price'] : '' ?>" name="product_price">
            </div>
            <div class="form-group">
                <label for="" class="form-label">Stok</label>
                <input placeholder="Masukkan stok" type="number" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['qty'] : '' ?>" name="qty">
            </div>
            <div class="form-group">
                <label for="" class="form-label">Deskripsi</label>
                <textarea placeholder="Masukkan deskripsi produk" name="product_description" class="form-control" cols="30" rows="5"><?php echo isset($_GET['id']) ? $rowProduct['product_description'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Status Product</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" <?php echo (isset($_GET['id']) && $rowProduct['is_active'] == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="switchCheckChecked">Checked switch checkbox input</label>
                </div>
            </div>
        </div>
        <div class="card-action">
            <button type="submit" class="btn btn-primary my-2" name="<?php echo isset($_GET['id']) ? 'edit' : 'add' ?>"><?php echo isset($_GET['id']) ? 'Simpan Perubahan' : 'Simpan' ?></button>
            <a href="?page=product" class="btn btn-danger">Batalkan</a>
        </div>
    </form>

</div>