<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$nim     = "";
$nama    = "";
$alamat  = "";
$fakultas = "";
$error = "";
$sukses = "";


if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from mahasiswa where id='$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if ($q1) {
        $sukses = 'Berhasil Hapus Data';
    } else {
        $error = 'Gagal Menghapus Data';
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select *from mahasiswa where id='$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $fakultas = $r1['fakultas'];

    if ($nim == '') {
        $error = "Data Tidak Ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $fakultas = $_POST['fakultas'];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update mahasiswa set nim='$nim',nama='$nama',alamat='$alamat',fakultas='$fakultas' where id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data Berhasil di Update";
            } else {
                $error = "Data Gagal di Update";
            }
        } else { //untuk insert
            $sql1 = "insert into mahasiswa(nim, nama, alamat, fakultas) values ('$nim','$nama','$alamat','$fakultas')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil Memasukkan Data Baru";
            } else {
                $error = "Gagal Memasukkan Data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 900px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto  ">
        <!-- masukan data -->
        <div class="card">
            <div class="card-header">
                Create /Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:2;url=index.php"); //5 detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:2;url=index.php"); //5 detik
                }
                ?>
                <form action="" method="post">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="FTI" <?php if ($fakultas == "FTI") echo "selected" ?>>FTI </option>
                                <option value="FKIP" <?php if ($fakultas == "FKIP") echo "selected" ?>>FKIP</option>
                                <option value="Kesehatan" <?php if ($fakultas == "Kesehatan") echo "selected" ?>>Kesehatan</option>
                                <option value="Ekonomi" <?php if ($fakultas == "Ekonomi") echo "selected" ?>>Ekonomi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>


                </form>
            </div>
        </div>

        <!-- mengeluarkan data -->
        <div class="card ">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <th>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        <tbody>
                            <?php
                            $sql2 = "select * from mahasiswa order by id desc";
                            $q2 = mysqli_query($koneksi, $sql2);
                            $urut = 1;
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $id = $r2['id'];
                                $nim = $r2['nim'];
                                $nama = $r2['nama'];
                                $alamat = $r2['alamat'];
                                $fakultas = $r2['fakultas'];

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++ ?></th>
                                    <td scope="row"><?php echo $nim ?></td>
                                    <td scope="row"><?php echo $nama ?></td>
                                    <td scope="row"><?php echo $alamat ?></td>
                                    <td scope="row"><?php echo $fakultas ?></td>
                                    <td scope="row">
                                        <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Ingin Mengahpus Data?')"><button type="button" class="btn btn-danger">Delete</button></button></a>


                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </th>
                </table>

            </div>
        </div>
    </div>
</body>

</html>