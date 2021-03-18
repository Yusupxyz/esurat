<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['act'])){
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'fsk':
                    include "file_sk.php";
                    break;
            }
        } else {

            //pagging
            $limit = 8;
            $pg = @$_GET['pg'];
                if(empty($pg)){
                    $curr = 0;
                    $pg = 1;
                } else {
                    $curr = ($pg - 1) * $limit;
                }

                echo '
                    <!-- Row Start -->
                    <div class="row">
                        <!-- Secondary Nav START -->
                        <div class="col s12">
                            <div class="z-depth-1">
                                <nav class="secondary-nav">
                                    <div class="nav-wrapper blue-grey darken-1">
                                        <div class="col m12">
                                            <ul class="left">
                                                <li class="waves-effect waves-light"><a href="?page=gsk" class="judul"><i class="material-icons">image</i> Galeri File Surat Keluar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <!-- Secondary Nav END -->
                    </div>
                    <!-- Row END -->

                    <!-- Row form Start -->
                    <div class="row jarak-form">';

                    if(isset($_REQUEST['submit'])){

                        $dari_tanggal = $_REQUEST['dari_tanggal'];
                        $sampai_tanggal = $_REQUEST['sampai_tanggal'];

                        if($_REQUEST['dari_tanggal'] == "" || $_REQUEST['sampai_tanggal'] == ""){
                            header("Location: ./admin.php?page=gsk");
                            die();
                        } else {

                        $query = mysqli_query($config, "SELECT * FROM tbl_surat_keluar WHERE tgl_catat BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER By id_surat DESC");

                        echo '<!-- Row form Start -->
                            <div class="row jarak-form black-text">
                                <form class="col s12" method="post" action="">
                                    <div class="input-field col s3">
                                        <i class="material-icons prefix md-prefix">date_range</i>
                                        <input id="dari_tanggal" type="text" name="dari_tanggal" id="dari_tanggal" required>
                                        <label for="dari_tanggal">Dari Tanggal</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <i class="material-icons prefix md-prefix">date_range</i>
                                        <input id="sampai_tanggal" type="text" name="sampai_tanggal" id="sampai_tanggal" required>
                                        <label for="sampai_tanggal">Sampai Tanggal</label>
                                    </div>
                                    <div class="col s6">
                                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">FILTER <i class="material-icons">filter_list</i></button>&nbsp;&nbsp;

                                        <button type="reset" onclick="window.history.back()" class="btn-large deep-orange waves-effect waves-light">RESET <i class="material-icons">refresh</i></button>
                                    </div>
                                </form>
                            </div>
                            <!-- Row form END -->

                            <div class="row agenda">
                                <div class="col s12">';

                                    $y = substr($dari_tanggal,0,4);
                                    $m = substr($dari_tanggal,5,2);
                                    $d = substr($dari_tanggal,8,2);
                                    $y2 = substr($sampai_tanggal,0,4);
                                    $m2 = substr($sampai_tanggal,5,2);
                                    $d2 = substr($sampai_tanggal,8,2);

                                    if($m == "01"){
                                        $nm = "Januari";
                                    } elseif($m == "02"){
                                        $nm = "Februari";
                                    } elseif($m == "03"){
                                        $nm = "Maret";
                                    } elseif($m == "04"){
                                        $nm = "April";
                                    } elseif($m == "05"){
                                        $nm = "Mei";
                                    } elseif($m == "06"){
                                        $nm = "Juni";
                                    } elseif($m == "07"){
                                        $nm = "Juli";
                                    } elseif($m == "08"){
                                        $nm = "Agustus";
                                    } elseif($m == "09"){
                                        $nm = "September";
                                    } elseif($m == "10"){
                                        $nm = "Oktober";
                                    } elseif($m == "11"){
                                        $nm = "November";
                                    } elseif($m == "12"){
                                        $nm = "Desember";
                                    }

                                    if($m2 == "01"){
                                        $nm2 = "Januari";
                                    } elseif($m2 == "02"){
                                        $nm2 = "Februari";
                                    } elseif($m2 == "03"){
                                        $nm2 = "Maret";
                                    } elseif($m2 == "04"){
                                        $nm2 = "April";
                                    } elseif($m2 == "05"){
                                        $nm2 = "Mei";
                                    } elseif($m2 == "06"){
                                        $nm2 = "Juni";
                                    } elseif($m2 == "07"){
                                        $nm2 = "Juli";
                                    } elseif($m2 == "08"){
                                        $nm2 = "Agustus";
                                    } elseif($m2 == "09"){
                                        $nm2 = "September";
                                    } elseif($m2 == "10"){
                                        $nm2 = "Oktober";
                                    } elseif($m2 == "11"){
                                        $nm2 = "November";
                                    } elseif($m2 == "12"){
                                        $nm2 = "Desember";
                                    }
                                    echo '

                                    <p class="warna agenda">Galeri file surat keluar antara tanggal <strong>'.$d." ".$nm." ".$y.'</strong> sampai dengan tanggal <strong>'.$d2." ".$nm2." ".$y2.'</strong></p>
                                </div>
                            </div>';

                            if(mysqli_num_rows($query) > 0){
                                while($row = mysqli_fetch_array($query)){
                                if(empty($row['file'])){
                                    echo '';
                                } else {

                                    $ekstensi = array('jpg','png','jpeg');
                                    $ekstensi2 = array('doc','docx');
                                    $file = $row['file'];
                                    $x = explode('.', $file);
                                    $eks = strtolower(end($x));

                                    if(in_array($eks, $ekstensi) == true){
                                        echo '
                                            <div class="col m3">
                                                <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./upload/surat_keluar/'.$row['file'].'"/>
                                                <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Tampilkan Ukuran Penuh</a>
                                            </div>';
                                    } else {

                                        if(in_array($eks, $ekstensi2) == true){
                                            echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./asset/img/word.png"/>
                                                    <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Lihat Detail File</a>
                                                </div>';
                                        } else {
                                            echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./asset/img/pdf.png"/>
                                                    <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Lihat Detail File</a>
                                                </div>';
                                        }
                                    }
                                }
                                }
                            } else {
                                echo '<div class="col m12">
                                        <div class="card blue lighten-5">
                                            <div class="card-content notif">
                                                <span class="card-title lampiran"><center>Tidak ada file lampiran surat keluar yang ditemukan</center></span>
                                            </div>
                                        </div>
                                    </div>';
                            } echo '
                                </div>';
                            }
                    } else {

                        //script untuk menampilkan data
                        $query = mysqli_query($config, "SELECT * FROM tbl_surat_keluar ORDER BY id_surat DESC LIMIT $curr, $limit");
                        if(mysqli_num_rows($query) > 0){

                            echo '
                            <!-- Row form Start -->
                            <div class="row jarak-form black-text">
                                <form class="col s12" method="post" action="">
                                    <div class="input-field col s3">
                                        <i class="material-icons prefix md-prefix">date_range</i>
                                        <input id="dari_tanggal" type="text" name="dari_tanggal" id="dari_tanggal" required>
                                        <label for="dari_tanggal">Dari Tanggal</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <i class="material-icons prefix md-prefix">date_range</i>
                                        <input id="sampai_tanggal" type="text" name="sampai_tanggal" id="sampai_tanggal" required>
                                        <label for="sampai_tanggal">Sampai Tanggal</label>
                                    </div>
                                    <div class="col s6">
                                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">FILTER <i class="material-icons">filter_list</i></button>
                                    </div>
                                </form>
                            </div>
                            <!-- Row form END -->';

                            while($row = mysqli_fetch_array($query)){

                                if(empty($row['file'])){
                                    echo '';
                                } else {

                                    $ekstensi = array('jpg','png','jpeg');
                                    $ekstensi2 = array('doc','docx');
                                    $file = $row['file'];
                                    $x = explode('.', $file);
                                    $eks = strtolower(end($x));

                                    if(in_array($eks, $ekstensi) == true){
                                    echo '
                                        <div class="col m3">
                                            <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./upload/surat_keluar/'.$row['file'].'"/>
                                            <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Tampilkan Ukuran Penuh</a>
                                        </div>';
                                    } else {

                                        if(in_array($eks, $ekstensi2) == true){
                                        echo '
                                            <div class="col m3">
                                                <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./asset/img/word.png"/>
                                                <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Lihat Detail File</a>
                                            </div>';
                                        } else {
                                            echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="'.date('d M Y', strtotime($row['tgl_catat'])).'" src="./asset/img/pdf.png"/>
                                                    <a class="btn light-green darken-1" href="?page=gsk&act=fsk&id_surat='.$row['id_surat'].'">Lihat Detail File</a>
                                                </div>';
                                        }
                                    }
                                }
                            }
                        } else {
                            echo '<div class="col m12">
                                    <div class="card blue lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title lampiran"><center>Tidak ada data untuk ditampilkan</center></span>
                                        </div>
                                    </div>
                                </div>';
                        } echo '
                        </div>';

                        $query = mysqli_query($config, "SELECT * FROM tbl_surat_keluar");
                        $cdata = mysqli_num_rows($query);
                        $cpg = ceil($cdata/$limit);

                        echo '<!-- Pagination START -->
                              <ul class="pagination">';

                        if($cdata > $limit ){

                        //first and previous pagging
                        if($pg > 1){
                            $prev = $pg - 1;
                            echo '<li><a href="?page=gsk&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=gsk&pg='.$prev.'"><i class="material-icons md-48">chevron_left</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href=""><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">chevron_left</i></a></li>';
                        }

                        //looping pagging
                        for($i=1; $i <= $cpg; $i++)
                            if($i != $pg){
                                echo '<li class="waves-effect waves-dark"><a href="?page=gsk&pg='.$i.'"> '.$i.' </a></li>';
                            } else {
                                echo '<li class="active waves-effect waves-dark"><a href="?page=gsk&pg='.$i.'"> '.$i.' </a></li>';
                            }

                        //next and last pagging
                        if($pg < $cpg){
                            $next = $pg + 1;
                            echo '<li><a href="?page=gsk&pg='.$next.'"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=gsk&pg='.$cpg.'"><i class="material-icons md-48">last_page</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href=""><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">last_page</i></a></li>';
                        }
                        echo '
                        </ul>
                        <!-- Pagination END -->';
                    } else {
                        echo '';
                    }
                }
            }
        }
?>
