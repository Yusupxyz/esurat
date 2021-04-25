<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if($_SESSION['admin'] != 1 AND $_SESSION['admin'] != 2){
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                    window.location.href="./logout.php";
                  </script>';
        } else {



                if(isset($_REQUEST['submit'])){

                    //validasi form kosong
                    if ($_REQUEST['surat_masuk'] == "" || $_REQUEST['surat_keluar'] == "" || $_REQUEST['klasifikasi'] == ""){
                        $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                        header("Location: ././admin.php?page=sett&sub=data");
                        die();
                    } else {

                        $id_setting = "1";
                        $surat_masuk = $_REQUEST['surat_masuk'];
                        $surat_keluar = $_REQUEST['surat_keluar'];
                        $klasifikasi = $_REQUEST['klasifikasi'];
                        $id_user = $_SESSION['id_user'];

                        $query = mysqli_query($config, "UPDATE tbl_sett SET surat_masuk='$surat_masuk',surat_keluar='$surat_keluar',klasifikasi='$klasifikasi', id_user='$id_user' WHERE id_sett='$id_setting'");

                        if($query == true){
                            $_SESSION['succEdit'] = 'SUKSES! Data Setting berhasil diupdate';
                            header("Location: ././admin.php?page=sett&sub=data");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">window.history.back();</script>';
                        }
                    }
                } else {

                    $query = mysqli_query($config, "SELECT * FROM tbl_sett");
                    if(mysqli_num_rows($query) > 0){
                        $no = 1;
                        while($row = mysqli_fetch_array($query)){?>

                        <!-- Row Start -->
                        <div class="row">
                            <!-- Secondary Nav START -->
                            <div class="col s12">
                                <nav class="secondary-nav">
                                    <div class="nav-wrapper blue-grey darken-1">
                                        <ul class="left">
                                            <li class="waves-effect waves-light"><a href="?page=sett" class="judul"><i class="material-icons">description</i> Manajemen Data Ditampilkan Perhalaman</a></li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                            <!-- Secondary Nav END -->
                        </div>
                        <!-- Row END -->

                        <?php
                            if(isset($_SESSION['errEmpty'])){
                                $errEmpty = $_SESSION['errEmpty'];
                                echo '<div id="alert-message" class="row">
                                        <div class="col m12">
                                            <div class="card red lighten-5">
                                                <div class="card-content notif">
                                                    <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errEmpty.'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                unset($_SESSION['errEmpty']);
                            }
                            if(isset($_SESSION['succEdit'])){
                                $succEdit = $_SESSION['succEdit'];
                                echo '<div id="alert-message" class="row">
                                        <div class="col m12">
                                            <div class="card green lighten-5">
                                                <div class="card-content notif">
                                                    <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succEdit.'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                unset($_SESSION['succEdit']);
                            }
                            if(isset($_SESSION['errQ'])){
                                $errQ = $_SESSION['errQ'];
                                echo '<div id="alert-message" class="row">
                                        <div class="col m12">
                                            <div class="card red lighten-5">
                                                <div class="card-content notif">
                                                    <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errQ.'</span>
                                            </div>
                                            </div>
                                        </div>
                                    </div>';
                                unset($_SESSION['errQ']);
                            }
                        ?>

                        <!-- Row form Start -->
                        <div class="row jarak-form">

                            <!-- Form START -->
                            <form class="col s12" method="post" action="?page=sett&sub=data" >

                                <!-- Row in form START -->
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input type="hidden" value="<?php echo $id_setting; ?>" name="id_setting">
                                        <i class="material-icons prefix md-prefix">archive</i>
                                        <input id="surat_masuk" type="number" min="5" max="50" class="validate" name="surat_masuk" value="<?php echo $row['surat_masuk']; ?>" required>
                                            <?php
                                                if(isset($_SESSION['namains'])){
                                                    $namains = $_SESSION['namains'];
                                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$namains.'</div>';
                                                    unset($_SESSION['namains']);
                                                }
                                            ?>
                                        <label for="nama">Data Surat Masuk</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <i class="material-icons prefix md-prefix">unarchive</i>
                                        <input id="surat_keluar" type="number" min="5" max="50" class="validate" name="surat_keluar" value="<?php echo $row['surat_keluar']; ?>" required>
                                            <?php
                                                if(isset($_SESSION['institusi'])){
                                                    $institusi = $_SESSION['institusi'];
                                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$institusi.'</div>';
                                                    unset($_SESSION['institusi']);
                                                }
                                            ?>
                                        <label for="institusi">Data Surat Keluar</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <i class="material-icons prefix md-prefix">class</i>
                                        <input id="klasifikasi" type="number" min="5" max="50" class="validate" name="klasifikasi" value='<?php echo $row['klasifikasi']; ?>' required>
                                            <?php
                                                if(isset($_SESSION['klasifikasi'])){
                                                    $status = $_SESSION['klasifikasi'];
                                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$klasifikasi.'</div>';
                                                    unset($_SESSION['klasifikasi']);
                                                }
                                            ?>
                                        <label for="klasifikasi">Data Klasifikasi</label>
                                    </div>
                                </div>
                                <!-- Row in form END -->

                                <div class="row">
                                    <div class="col 6">
                                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                                    </div>
                                    <div class="col 6">
                                        <a href="./admin.php" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                                    </div>
                                </div>

                            </form>
                            <!-- Form END -->

                        </div>
                        <!-- Row form END -->

<?php
                        }
                    }
                }
            }
        }
?>
