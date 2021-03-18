<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

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

    	$id_disposisi = mysqli_real_escape_string($config, $_REQUEST['id_disposisi']);

    	$query = mysqli_query($config, "SELECT * FROM tbl_disposisi LEFT JOIN tbl_user ON tbl_disposisi.tujuan=tbl_user.id_user
										RIGHT JOIN tbl_surat_masuk ON tbl_disposisi.id_surat=tbl_surat_masuk.id_surat
										WHERE id_disposisi='$id_disposisi'");

    	if(mysqli_num_rows($query) > 0){
            $no = 1;
            while($row = mysqli_fetch_array($query)){

    		  echo '<!-- Row form Start -->
    				<div class="row jarak-card">
    				    <div class="col m12">
                            <div class="card">
                                <div class="card-content">
            				        <table>
            				            <thead class="red lighten-5 red-text">
            				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
            				                Apakah Anda yakin akan mengirim notifikasi Whatsapp ke tujuan ini?</div>
            				            </thead>

            				            <tbody>
            				                <tr>
            				                    <td width="13%">Tujuan</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['nama'].' ('.$row['jabatan'].')</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Isi Disposis</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['isi_disposisi'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Sifat</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['sifat'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Batas Waktu</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.date('d M Y', strtotime($row['batas_waktu'])).'</td>
            				                </tr>
                                            <tr>
                                                <td width="13%">Catatan</td>
                                                <td width="1%">:</td>
                                                <td width="86%">'.$row['catatan'].'</td>
                                            </tr>
            				            </tbody>
            				   		</table>
        				        </div>
                                <div class="card-action">
        		                     <a href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'&sub=send&submit=yes&id_disposisi='.$row['id_disposisi'].'" class="btn-large deep-green waves-effect waves-light white-text">Kirim <i class="material-icons">send</i></a>
        		                    <a href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row form END -->';

                	if(isset($_REQUEST['submit'])){
                		$id_disposisi = $_REQUEST['id_disposisi'];
						// Send Message
						$my_apikey = "KBN1E2ZUMEZ3PL2V37NY";
						$destination = $row['nohp'];
						$message = "Anda menerima surat masuk baru dengan nomor surat *".$row['no_surat']."* yang bersifat *".$row['sifat']."* dari *".$row['asal_surat']."*. Batas waktu surat sampai dengan tanggal ".$row['batas_waktu'].". Silahkan login dan cek surat melalui web AMS berikut. http://localhost/esurat. ";
						$custom_data = $row['no_surat'];
						$api_url = "http://panel.rapiwha.com/send_message.php";
						$api_url .= "?apikey=". urlencode ($my_apikey);
						$api_url .= "&number=". urlencode ($destination);
						$api_url .= "&text=". urlencode ($message);
						$api_url .= "&custom_data=". urlencode ($custom_data);
						$my_result_object = json_decode(file_get_contents($api_url, false));
						echo "<br>Result: ". $my_result_object->success;
						echo "<br>Description: ". $my_result_object->description;
						echo "<br>Code: ". $my_result_object->result_code;
						if ($my_result_object->success==1){
							if ($my_result_object->result_code==0){
								$status='Tertunda';
							}else{
								$status='Terkirim';
							}
						}else{
							$status='Gagal';
						}
						$query2 = mysqli_query($config, "UPDATE tbl_disposisi SET status_wa='$status'WHERE id_disposisi='$id_disposisi'");

						if($query == true){
							$_SESSION['succDel'] = 'SUKSES! Data berhasil dikirim ';
							echo '<script language="javascript">
									window.location.href="./admin.php?page=tsm&act=disp&id_surat='.$row['id_surat'].'";
									</script>';
						}else{
							$_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
									window.location.href="./admin.php?page=tsm&act=disp&id_surat='.$row['id_surat'].'";
									</script>';
						}
                	}
    		    }
    	    }
        }
?>
