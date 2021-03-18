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
				while($row = mysqli_fetch_array($query)){



					$id_disposisi = $_REQUEST['id_disposisi'];

					// Pull messages (for push messages please go to settings of the number)
					$my_apikey = "KBN1E2ZUMEZ3PL2V37NY";
					$number = $row['nohp'];
					$type = "OUT";
					$markaspulled = "1";
					$custom_data = $row['no_surat'];
					$getnotpulledonly = "1";
					$api_url  = "http://panel.rapiwha.com/get_messages.php";
					$api_url .= "?apikey=". urlencode ($my_apikey);
					$api_url .= "&number=". urlencode ($number);
					$api_url .= "&type=". urlencode ($type);
					$api_url .= "&custom_data=". urlencode ($custom_data);
					$api_url .= "&markaspulled=". urlencode ($markaspulled);
					$api_url .= "&getnotpulledonly=". urlencode ($getnotpulledonly);
					$my_json_result = file_get_contents($api_url, false);
					$my_php_arr = json_decode($my_json_result);
					var_dump($my_php_arr);
					foreach($my_php_arr as $item)
					{
						// $from_temp = $item->from;
						// $to_temp = $item->to;
						// $text_temp = $item->text;
						// $type_temp = $item->type;
						// echo "<br>". $from_temp ." -> ". $to_temp ." (". $type_temp ."): ". $text_temp;
						if ($item->process_date!=NULL){
							$status='Terkirim';
						}elseif ($item->failed_date!=NULL) {
							$status='Gagal';
						}else{
							$status='Tertunda';
						}
						$query2 = mysqli_query($config, "UPDATE tbl_disposisi SET status_wa='$status'WHERE id_disposisi='$id_disposisi'");
					}

					if($query == true){
						$_SESSION['succDel'] = 'SUKSES! Data berhasil dicek ';
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
?>
