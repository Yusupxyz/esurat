<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        $id_hak_akses = mysqli_real_escape_string($config, $_REQUEST['id_hak_akses']);

                if(isset($_SESSION['errQ'])){
                    $errQ = $_SESSION['errQ'];
                    echo '<div id="alert-message" class="row jarak-card">
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

                $query = mysqli_query($config, "SELECT * FROM tbl_hak_user WHERE id_hak_akses='$id_hak_akses'");

            	if(mysqli_num_rows($query) > 0){
                    $no = 1;
                    while($row = mysqli_fetch_array($query)){

        		 echo '
                    <!-- Row form Start -->
    				<div class="row jarak-card">
    				    <div class="col m12">
                            <div class="card">
                                <div class="card-content">
            				        <table>
            				            <thead class="red lighten-5 red-text">
            				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
            				                Apakah Anda yakin akan menghapus user ini?</div>
            				            </thead>

            				            <tbody>
            				                <tr>
            				                    <td width="13%">Hak Akses</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['hak_akses'].'</td>
            				                </tr>
            				            </tbody>
            				   		</table>
    				            </div>
                                <div class="card-action">
            		                <a href="?page=sett&sub=hak&act=del&submit=yes&id_hak_akses='.$row['id_hak_akses'].'" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
            		                <a href="?page=sett&sub=hak" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
            		            </div>
                            </div>
                        </div>
                    </div>
        			<!-- Row form END -->';

                	if(isset($_REQUEST['submit'])){
                		$id_hak_akses = $_REQUEST['id_hak_akses'];

                        $query = mysqli_query($config, "DELETE FROM tbl_hak_user WHERE id_hak_akses='$id_hak_akses'");

                		if($query == true){
                            $_SESSION['succDel'] = 'SUKSES! Hak Akses User berhasil dihapus<br/>';
                            header("Location: ./admin.php?page=sett&sub=hak");
                            die();
                		} else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
                                    window.location.href="./admin.php?page=sett&sub=hak&act=del&id_hak_akses='.$id_hak_akses.'";
                                  </script>';
                		}
                	}
    		        }
    	        }
            }
       
?>
