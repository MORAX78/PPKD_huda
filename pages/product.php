<?php
$selectProducts = mysqli_query($koneksi, "SELECT categories.category_name, products.* FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY id DESC");
$rowProducts = mysqli_fetch_all($selectProducts, MYSQLI_ASSOC);

if (isset($_GET['idDel'])) {
    $idDel = $_GET['idDel'];
    //Check Photo
    $foto = mysqli_query($koneksi, "SELECT product_photo FROM products WHERE id='$idDel'");
    $row = mysqli_fetch_assoc($foto);
    unlink("assets/img/" . $row['product_photo']);

    $delete = mysqli_query($koneksi, "DELETE FROM products WHERE id='$idDel'");
    if ($delete) {
        header("location:?page=product");
    }
}
?>
<div class="card table-responsive">
    <div class="card-header">
        <div class="card-title">
            <h4>Data Produk</h4>
        </div>
    </div>
    <div class="card-body">
        <div align="right">
            <a href="?page=tambah-edit-product" class="btn btn-primary my-2">Tambah Produk Baru</a>
        </div>
        <table class="table table-bordered text-center">
            <tr>
                <th>No</th>
                <th>Kategori Name</th>
                <th>Nama Produk</th>
                <th>Foto</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Tindakan</th>
            </tr>
            <?php
            $no = 1;
            foreach ($rowProducts as $v) {
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $v['category_name'] ?></td>
                    <td><?php echo $v['product_name'] ?></td>
                    <td><img src="assets/img/<?php echo $v['product_photo'] ?>" alt="" width="120"></td>
                    <td>Rp. <?php echo number_format($v['product_price'], 2, ',', '.')  ?></td>
                    <td><?php echo $v['qty'] ?></td>
                    <td><?php echo $v['product_description'] ?></td>
                    <td>
                        <a href="?page=tambah-edit-product&id=<?php echo base64_encode($v['id']) ?>" class="btn btn-success btn btn-sm">Ubah</a>
                        <form action="?page=product&idDel=<?php echo $v['id'] ?>" method="post" onclick="return confirm('Yakin ingin di delete?')" class="d-inline">
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>