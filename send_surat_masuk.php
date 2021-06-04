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

    	$id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);

    	$query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk LEFT JOIN tbl_user ON tbl_surat_masuk.tujuan_surat=tbl_user.id_user
										WHERE id_surat='$id_surat'");

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
            				                    <td width="13%">No. Surat</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['no_surat'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Asal Surat</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['asal_surat'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Tanggal Surat</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.date('d M Y', strtotime($row['tgl_surat'])).'</td>
            				                </tr>
                                            <tr>
                                                <td width="13%">Keterangan</td>
                                                <td width="1%">:</td>
                                                <td width="86%">'.$row['keterangan'].'</td>
                                            </tr>
            				            </tbody>
            				   		</table>
        				        </div>
                                <div class="card-action">
        		                     <a href="?page=tsm&act=send&id_surat='.$row['id_surat'].'&sub=send&submit=yes" class="btn-large deep-green waves-effect waves-light white-text">Kirim <i class="material-icons">send</i></a>
        		                    <a href="?page=tsm&act=send&id_surat='.$row['id_surat'].'" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row form END -->';

                	if(isset($_REQUEST['submit'])){
                		$id_surat = $_REQUEST['id_surat'];
						// Send Message
						$my_apikey = "KBN1E2ZUMEZ3PL2V37NY";
						$destination = $row['nohp'];
						$message = "Anda menerima surat masuk baru dengan nomor surat *".$row['no_surat']."* yang berasal dari *".$row['asal_surat']."*. Tanggal surat ialah ".$row['tgl_surat'].". Silahkan login dan cek surat melalui web AMS berikut. http://localhost/esurat. ";
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
						$query2 = mysqli_query($config, "UPDATE tbl_surat_masuk SET status_wa='$status'WHERE id_surat='$id_surat'");

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
