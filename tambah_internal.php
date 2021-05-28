<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if($_SESSION['admin'] != 1 AND $_SESSION['admin'] != 2){
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk menambahkan data");
                    window.location.href="./admin.php?page=internal";
                  </script>';
        } else {

            if(isset($_REQUEST['submit'])){

                //validasi form kosong
                if($_REQUEST['unit_kerja'] == "" || $_REQUEST['kode'] == "" ){
                    $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    $kode = $_REQUEST['kode'];
                    $unit_kerja = $_REQUEST['unit_kerja'];
                    $id_user = $_SESSION['admin'];

                    //validasi input data
                    if(!preg_match("/^[a-zA-Z0-9. ]*$/", $kode)){
                        $_SESSION['kode'] = 'Form Kode hanya boleh mengandung karakter huruf, angka, spasi dan titik(.)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if(!preg_match("/^[a-zA-Z0-9.,\/ -]*$/", $unit_kerja)){
                            $_SESSION['namaref'] = 'Form Unit Kerja hanya boleh mengandung karakter huruf, spasi, titik(.), koma(,) dan minus(-)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            $cek = mysqli_query($config, "SELECT * FROM tbl_kode_internal WHERE kode='$kode' OR unit_kerja='$unit_kerja'");
                            $result = mysqli_num_rows($cek);

                            if($result > 0){
                                $_SESSION['duplikasi'] = 'Kode/Unit Kerja sudah ada, pilih yang lainnya!';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                $query = mysqli_query($config, "INSERT INTO tbl_kode_internal(unit_kerja,kode,id_user) VALUES('$unit_kerja','$kode','$id_user')");

                                if($query != false){
                                    $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                                    header("Location: ./admin.php?page=internal");
                                    die();
                                } else {
                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                    echo '<script language="javascript">window.history.back();</script>';
                                }
                            }
                            
                        }
                    }
                }
            } else {?>
                <!-- Row Start -->
                <div class="row">
                    <!-- Secondary Nav START -->
                    <div class="col s12">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <ul class="left">
                                    <li class="waves-effect waves-light"><a href="?page=internal&act=add" class="judul"><i class="material-icons">bookmark</i> Tambah Klasifikasi Surat</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <!-- Secondary Nav END -->
                </div>
                <!-- Row END -->

                <?php
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
                ?>

                <!-- Row form Start -->
                <div class="row jarak-form">

                    <!-- Form START -->
                    <form class="col s12" method="post" action="?page=internal&act=add">

                        <!-- Row in form START -->
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="material-icons prefix md-prefix">text_fields</i>
                                <input id="unit_kerja" type="text" class="validate" name="unit_kerja" required>
                                    <?php
                                        if(isset($_SESSION['unit_kerja'])){
                                            $namaref = $_SESSION['unit_kerja'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$unit_kerja.'</div>';
                                            unset($_SESSION['unit_kerja']);
                                        }
                                    ?>
                                <label for="nama">Unit Kerja</label>
                            </div>
                            <div class="input-field col s3 tooltipped" data-position="top" data-tooltip="Isi dengan huruf, angka, spasi dan titik(.)">
                                <i class="material-icons prefix md-prefix">font_download</i>
                                <input id="kd" type="text" class="validate" maxlength="30" name="kode" required>
                                    <?php
                                        if(isset($_SESSION['kode'])){
                                            $kode = $_SESSION['kode'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$kode.'</div>';
                                            unset($_SESSION['kode']);
                                        }
                                        if(isset($_SESSION['duplikasi'])){
                                            $duplikasi = $_SESSION['duplikasi'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$duplikasi.'</div>';
                                            unset($_SESSION['duplikasi']);
                                        }
                                    ?>
                                <label for="kd">Kode</label>
                            </div>
                           
                           
                        </div>
                        <!-- Row in form END -->
                        <div class="row">
                            <div class="col 6">
                                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                            </div>
                            <div class="col 6">
                                <a href="?page=internal" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
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
?>
