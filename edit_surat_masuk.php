<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

            //validasi form kosong
            if($_REQUEST['no_surat'] == "" || $_REQUEST['asal_surat'] == "" 
                || $_REQUEST['kode'] == "" || $_REQUEST['tgl_surat'] == ""  || $_REQUEST['keterangan'] == ""
                || $_REQUEST['tujuan'] == "" || $_REQUEST['jenis'] == ""){
                $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                $no_surat = $_REQUEST['no_surat'];
                $asal_surat = $_REQUEST['asal_surat'];
                $kode = substr($_REQUEST['kode'],0,30);
                $nkode = trim($kode);
                $tgl_surat = $_REQUEST['tgl_surat'];
                $keterangan = $_REQUEST['keterangan'];
                $id_user = $_SESSION['id_user'];
                $tujuan_surat = $_REQUEST['tujuan'];
                $jenis = $_REQUEST['jenis'];

                //validasi input data

                if(!preg_match("/^[a-zA-Z0-9.\/ -]*$/", $no_surat)){
                    $_SESSION['eno_surat'] = 'Form No Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), minus(-) dan garis miring(/)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if(!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $asal_surat)){
                        $_SESSION['easal_surat'] = 'Form Asal Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {
                        if(!preg_match("/^[a-zA-Z0-9., ]*$/", $nkode)){
                            $_SESSION['ekode'] = 'Form Kode Klasifikasi hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if(!preg_match("/^[0-9.-]*$/", $tgl_surat)){
                                $_SESSION['etgl_surat'] = 'Form Tanggal Surat hanya boleh mengandung angka dan minus(-)';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                if(!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $keterangan)){
                                    $_SESSION['eketerangan'] = 'Form Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan kurung()';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    $ekstensi = array('jpg','png','jpeg','doc','docx','pdf');
                                    $file = $_FILES['file']['name'];
                                    $x = explode('.', $file);
                                    $eks = strtolower(end($x));
                                    $ukuran = $_FILES['file']['size'];
                                    $target_dir = "upload/surat_masuk/";

                                //jika form file tidak kosong akan mengeksekusi script dibawah ini
                                if($file != ""){

                                    $rand = rand(1,10000);
                                    $nfile = $rand."-".$file;

                                    //validasi file
                                    if(in_array($eks, $ekstensi) == true){
                                        if($ukuran < 2300000){

                                            $id_surat = $_REQUEST['id_surat'];
                                            $query = mysqli_query($config, "SELECT file FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
                                            list($file) = mysqli_fetch_array($query);

                                            //jika file tidak kosong akan mengeksekusi script dibawah ini
                                            if(!empty($file)){
                                                unlink($target_dir.$file);

                                                move_uploaded_file($_FILES['file']['tmp_name'], $target_dir.$nfile);

                                                $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_surat='$no_surat',asal_surat='$asal_surat',tujuan_surat='$tujuan_surat',isi='$isi',kode='$nkode',tgl_surat='$tgl_surat',file='$nfile',keterangan='$keterangan',jenis_surat='$jenis_surat',id_user='$id_user' WHERE id_surat='$id_surat'");

                                                if($query == true){
                                                    $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                                    header("Location: ./admin.php?page=tsm");
                                                    die();
                                                } else {
                                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                    echo '<script language="javascript">window.history.back();</script>';
                                                }
                                            } else {

                                                //jika file kosong akan mengeksekusi script dibawah ini
                                                move_uploaded_file($_FILES['file']['tmp_name'], $target_dir.$nfile);

                                                $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_surat='$no_surat',asal_surat='$asal_surat',tujuan_surat='$tujuan_surat',isi='$isi',kode='$nkode',tgl_surat='$tgl_surat',file='$nfile',keterangan='$keterangan',jenis_surat='$jenis_surat',id_user='$id_user' WHERE id_surat='$id_surat'");

                                                if($query == true){
                                                    $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                                    header("Location: ./admin.php?page=tsm");
                                                    die();
                                                } else {
                                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                    echo '<script language="javascript">window.history.back();</script>';
                                                }
                                            }
                                        } else {
                                            $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                                            echo '<script language="javascript">window.history.back();</script>';
                                        }
                                    } else {
                                        $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    }
                                } else {

                                    //jika form file kosong akan mengeksekusi script dibawah ini
                                    $id_surat = $_REQUEST['id_surat'];

                                    $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_surat='$no_surat',asal_surat='$asal_surat',tujuan_surat='$tujuan_surat',kode='$nkode',tgl_surat='$tgl_surat',keterangan='$keterangan',jenis_surat='$jenis',id_user='$id_user' WHERE id_surat='$id_surat'");

                                    if($query == true){
                                        $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                        header("Location: ./admin.php?page=tsm");
                                        die();
                                    } else {
                                        $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    }
                                }
                            }
                        }
                            
                        
                    }
                }
                
            }
        }
    } else {

        $id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);
        $query = mysqli_query($config, "SELECT id_surat, no_surat, asal_surat, tujuan_surat, kode, tgl_surat, file, keterangan,jenis_surat, id_user FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
        list($id_surat, $no_surat, $asal_surat, $tujuan_surat, $kode, $tgl_surat, $file, $keterangan, $jenis, $id_user) = mysqli_fetch_array($query);

        if($_SESSION['id_user'] != $id_user AND $_SESSION['id_user'] != 1){
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
                    window.location.href="./admin.php?page=tsm";
                  </script>';
        } else {?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">edit</i> Edit Data Surat Masuk</a></li>
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
                <form class="col s12" method="POST" action="?page=tsm&act=edit" enctype="multipart/form-data">

                    <!-- Row in form START -->
                    <div class="row">
 
                        <div class="input-field col s6 tooltipped" data-position="top" data-tooltip="Diambil dari data klasifikasi kode klasifikasi">
                                        <i class="material-icons prefix md-prefix">bookmark</i><label>Kode Klasifikasi</label><br/>
                                        <div class="input-field col s11 right">
                            <select class="validate" name="kode" id="kode" required>
                                <option disabled selected> Pilih </option>
                                <?php 
                                $sql=mysqli_query($config,"SELECT * FROM tbl_klasifikasi");
                                while ($data=mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value="<?=$data['id_klasifikasi']?>" selected="<?=$data['id_klasifikasi']==$kode?'Selected':''; ?>"><?=$data['kode']?></option> 
                                    <?php
                                }
                                ?>
                            </select>
                            </div>
                                <?php
                                    if(isset($_SESSION['ekode'])){
                                        $ekode = $_SESSION['ekode'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$ekode.'</div>';
                                        unset($_SESSION['ekode']);
                                    }
                                ?>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">place</i>
                            <input id="asal_surat" type="text" class="validate" name="asal_surat" value="<?php echo $asal_surat ;?>" required>
                                <?php
                                    if(isset($_SESSION['easal_surat'])){
                                        $easal_surat = $_SESSION['easal_surat'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$easal_surat.'</div>';
                                        unset($_SESSION['easal_surat']);
                                    }
                                ?>
                            <label for="asal_surat">Asal Surat</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">looks_two</i>
                            <input id="no_surat" type="text" class="validate" name="no_surat" value="<?php echo $no_surat ;?>" required>
                                <?php
                                    if(isset($_SESSION['eno_surat'])){
                                        $eno_surat = $_SESSION['eno_surat'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$eno_surat.'</div>';
                                        unset($_SESSION['eno_surat']);
                                    }
                                ?>
                            <label for="no_surat">Nomor Surat</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tgl_surat" type="text" name="tgl_surat" class="datepicker" value="<?php echo $tgl_surat ;?>" required>
                                <?php
                                    if(isset($_SESSION['etgl_surat'])){
                                        $etgl_surat = $_SESSION['etgl_surat'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$etgl_surat.'</div>';
                                        unset($_SESSION['etgl_surat']);
                                    }
                                ?>
                            <label for="tgl_surat">Tanggal Surat</label>
                        </div>
                        <div class="input-field col s6 " data-position="top" >
                            <i class="material-icons prefix md-prefix">flag</i><label>Tujuan Surat</label><br/>
                            <div class="input-field col s11 right">
                                <select class="validate" name="tujuan" id="tujuan" required>
                                    <option value="<?php echo $tujuan_surat ;?>"> <?php echo $tujuan_surat ;?> </option>
                                    <option value="Dekan"> Dekan </option>
                                    <option value="Wakil Dekan 1"> Wakil Dekan 1 </option>
                                    <option value="Wakil Dekan 2"> Wakil Dekan 2 </option>
                                    <option value="Wakil Dekan 3"> Wakil Dekan 3 </option>
                                </select>
                            </div>
                            <?php
                                if(isset($_SESSION['tujuan'])){
                                    $tujuan = $_SESSION['tujuan'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$tujuan.'</div>';
                                    unset($_SESSION['tujuan']);
                                }
                            ?>
                        </div>
                        <div class="input-field col s6 " data-position="top" >
                            <i class="material-icons prefix md-prefix">format_list_bulleted</i><label>Jenis Surat</label><br/>
                            <div class="input-field col s11 right">
                                <select class="validate" name="jenis" id="jenis" required>
                                    <option value="<?php echo $jenis ;?>"> <?php echo $jenis ;?> </option>
                                    <option value="internal"> Internal </option>
                                    <option value="external"> Eksternal </option>
                                </select>
                            </div>
                            <?php
                                if(isset($_SESSION['jenis'])){
                                    $jenis = $_SESSION['jenis'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$jenis.'</div>';
                                    unset($_SESSION['jenis']);
                                }
                            ?>
                        </div>
                        <!-- <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">description</i>
                            <textarea id="isi" class="materialize-textarea validate" name="isi" required><?php echo $isi ;?></textarea>
                                <?php
                                    if(isset($_SESSION['eisi'])){
                                        $eisi = $_SESSION['eisi'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$eisi.'</div>';
                                        unset($_SESSION['eisi']);
                                    }
                                ?>
                            <label for="isi">Isi Ringkas</label>
                        </div> -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <input id="keterangan" type="text" class="validate" name="keterangan" value="<?php echo $keterangan ;?>" required>
                                <?php
                                    if(isset($_SESSION['eketerangan'])){
                                        $eketerangan = $_SESSION['eketerangan'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$eketerangan.'</div>';
                                        unset($_SESSION['eketerangan']);
                                    }
                                ?>
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="input-field col s6">
                            <div class="file-field input-field tooltipped" data-position="top" data-tooltip="Jika tidak ada file/scan gambar surat, biarkan kosong">
                                <div class="btn light-green darken-1">
                                    <span>File</span>
                                    <input type="file" id="file" name="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" value="<?php echo $file ;?>" placeholder="Upload file/scan gambar surat masuk">
                                        <?php
                                            if(isset($_SESSION['errSize'])){
                                                $errSize = $_SESSION['errSize'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$errSize.'</div>';
                                                unset($_SESSION['errSize']);
                                            }
                                            if(isset($_SESSION['errFormat'])){
                                                $errFormat = $_SESSION['errFormat'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$errFormat.'</div>';
                                                unset($_SESSION['errFormat']);
                                            }
                                        ?>
                                    <small class="red-text">*Format file yang diperbolehkan *.JPG, *.PNG, *.DOC, *.DOCX, *.PDF dan ukuran maksimal file 2 MB!</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col 6">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                        </div>
                        <div class="col 6">
                            <a href="?page=tsm" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
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
