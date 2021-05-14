<?php

    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

            $id_hak_akses = $_REQUEST['id_hak_akses'];
            $hak_akses = $_REQUEST['hak'];

            // echo "UPDATE tbl_hak_user SET hak_akses='$hak_akses' WHERE id_hak_akses='$id_hak_akses'";
            $query = mysqli_query($config, "UPDATE tbl_hak_user SET hak_akses='$hak_akses' WHERE id_hak_akses='$id_hak_akses'");

            if($query == true){
                $_SESSION['succEdit'] = 'SUKSES! Hak Akses User berhasil diupdate';
                header("Location: ./admin.php?page=sett&sub=hak");
                die();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                echo '<script language="javascript">
                        window.location.href="./admin.php?page=sett&sub=hak&act=edit&id_hak_akses='.$id_hak_akses.'";
                        </script>';
            }
            
            
        } else {

            $id_hak_akses = mysqli_real_escape_string($config, $_REQUEST['id_hak_akses']);
            $query = mysqli_query($config, "SELECT * FROM tbl_hak_user WHERE id_hak_akses='$id_hak_akses'");
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
                                    <li class="waves-effect waves-light  tooltipped" data-position="right" data-tooltip="Menu ini hanya untuk mengedit tipe user. Username dan password bisa diganti lewat menu profil"><a href="#" class="judul"><i class="material-icons">mode_edit</i> Edit Tipe User</a></li>
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
                ?>

                <!-- Row form Start -->
                <div class="row jarak-form">

                    <!-- Form START -->
                    <form class="col s12" method="post" action="?page=sett&sub=hak&act=edit">

                        <!-- Row in form START -->
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="material-icons prefix md-prefix">text_fields</i>
                                <input id="hak" type="text" value="<?php echo $row['hak_akses'] ;?>"  >
                                <label for="hak">Nama</label>
                            </div>
                        </div>
                        <!-- Row in form END -->
                        <br/>
                        <div class="row">
                            <div class="col 6">
                                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                            </div>
                            <div class="col 6">
                                <a href="?page=sett&sub=hak" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
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
        
    
?>
