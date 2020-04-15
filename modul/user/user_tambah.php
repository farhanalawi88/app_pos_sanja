<?php
		
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['txtNama'])=="") {
			$message[] = "Nama Lengkap tidak boleh kosong!";		
		}
		if (trim($_POST['txtUsername'])=="") {
			$message[] = "Username tidak boleh kosong!";		
		}
		if (trim($_POST['txtPassword'])=="") {
			$message[] = "Password tidak boleh kosong!";		
		}
		if (trim($_POST['cmbLevel'])=="BLANK") {
			$message[] = "Level tidak boleh kosong, silahkan pilih terlebih dahulu!";		
		}
		if (trim($_POST['cmbStatus'])=="") {
			$message[] = "status tidak boleh kosong!";		
		}
		if (trim($_POST['cmbKelamin'])=="") {
			$message[] = "jenis kelamin tidak boleh kosong!";		
		}
		if (trim($_POST['cmbDefault'])=="") {
			$message[] = "<b>Default </b> tidak boleh kosong!";		
		}
		
		
		$txtNama		= $_POST['txtNama'];
		$txtUsername	= $_POST['txtUsername'];
		$txtTelp		= $_POST['txtTelp'];
		$txtAlamat		= $_POST['txtAlamat'];
		$txtPassword	= $_POST['txtPassword'];
		$cmbLevel		= $_POST['cmbLevel'];
		$cmbStatus		= $_POST['cmbStatus'];
		$cmbKelamin		= $_POST['cmbKelamin'];
		$txtEmail		= $_POST['txtEmail'];
		$cmbDefault		= $_POST['cmbDefault'];
		if($cmbDefault=='Y'){
			$qryUpdate=mysqli_query($koneksidb, "UPDATE ms_user SET default_user='N'") 
					   or die ("Gagal query update".mysqli_error());
		}

		$sqlCek="SELECT COUNT(*) as total FROM ms_user WHERE username_user='$txtUser'";
		$qryCek=mysqli_query($koneksidb, $sqlCek) or die ("Eror Query".mysqli_error()); 
		$rowCek 		= mysqli_fetch_array($qryCek);
		if($rowCek['total']>=1){
			$message[] = "Maaf, Username <b> $txtUsername </b> sudah ada, ganti dengan username lain";
		}
		
		if(count($message)==0){		
			$sqlSave="INSERT INTO ms_user SET nama_user='$txtNama', 
											 username_user='$txtUsername', 
											 telp_user='$txtTelp', 
											 email_user='$txtEmail',
											 alamat_user='$txtAlamat',  
											 default_user='$cmbDefault',
											 password_user='".md5($txtPassword)."', 
											 user_group='$cmbLevel',
											 status_user='$cmbStatus',
											 kelamin_user='$cmbKelamin'";
			$qrySave=mysqli_query($koneksidb, $sqlSave) or die ("Gagal query".mysqli_error());
			if($qrySave){
				$_SESSION['pesan'] = 'Data admin & kasir berhasil ditambahkan';
			echo '<script>window.location="?page=datauser"</script>';

			}
		}	
		
		if (! count($message)==0 ){
			echo "<div class='alert alert-danger alert-dismissable'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>";
				$Num=0;
				foreach ($message as $indeks=>$pesan_tampil) { 
				$Num++;
					echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";	
				} 
			echo "</div>"; 
		}
	} 
	$dataNama		= isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
	$dataTelp		= isset($_POST['txtTelp']) ? $_POST['txtTelp'] : '';
	$dataEmail		= isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
	$dataAlamat		= isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : '';
	$dataUsername	= isset($_POST['txtUsername']) ? $_POST['txtUsername'] : '';
	$dataLevel		= isset($_POST['cmbLevel']) ? $_POST['cmbLevel'] : '';
	$dataStatus		= isset($_POST['cmbStatus']) ? $_POST['cmbStatus'] : '';
	$dataKelamin	= isset($_POST['cmbKelamin']) ? $_POST['cmbKelamin'] : '';
	$dataDefault 	= isset($_POST['cmbDefault']) ? $_POST['cmbDefault'] : '';
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="post" class="form-horizontal" enctype="multipart/form-data">
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption">
	            <span class="caption-subject uppercase bold hidden-xs">Form Kasir & Admin</span>
	        </div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<label class="col-lg-2 control-label">Nama Lengkap :</label>
					<div class="col-lg-5">
						<input class="form-control" type="text" name="txtNama" value="<?php echo $dataNama; ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Alamat :</label>
					<div class="col-lg-10">
						<textarea class="form-control" name="txtAlamat" type="text"/><?php echo $dataAlamat; ?></textarea>
					</div>
				</div>
				<div class="form-group">
	                <label class="col-md-2 control-label">Jenis Kelamin :</label>
	                <div class="col-md-10">
	                    <div class="md-radio-list">
	                    	<?php
								if($dataKelamin=='Pria'){
				                    echo " 	<div class='md-radio'>
				                    			<input type='radio' id='radio50' name='cmbKelamin' value='Pria' class='md-radiobtn' checked>
				                            	<label for='radio50'><span></span><span class='check'></span><span class='box'></span> Pria </label>
				                            </div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio51' name='cmbKelamin' value='Wanita' class='md-radiobtn'>
				                            	<label for='radio51'><span></span><span class='check'></span><span class='box'></span> Wanita </label>
				                        	</div>";
				                }elseif($dataKelamin=='Wanita'){
				                	echo "	<div class='md-radio'>
				                            	<input type='radio' id='radio50' name='cmbKelamin' value='Pria' class='md-radiobtn'>
				                            	<label for='radio50'><span></span><span class='check'></span><span class='box'></span> Pria </label>
				                        	</div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio51' name='cmbKelamin' value='Wanita' class='md-radiobtn' checked>
				                            	<label for='radio51'><span></span><span class='check'></span><span class='box'></span> Wanita </label>
				                            </div>";
				                }else{
				                	echo "	<div class='md-radio'>
				                            	<input type='radio' id='radio50' name='cmbKelamin' value='Pria' class='md-radiobtn'>
				                            	<label for='radio50'><span></span><span class='check'></span><span class='box'></span> Pria </label>
				                        	</div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio51' name='cmbKelamin' value='Wanita' class='md-radiobtn'>
				                            	<label for='radio51'><span></span><span class='check'></span><span class='box'></span> Wanita </label>
				                            </div>";
				                }
				            ?>
	                    </div>
	                </div>
	            </div>
	           
				<div class="form-group">
					<label class="col-lg-2 control-label">Username & Userid :</label>
					<div class="col-lg-4">
						<input class="form-control" type="text" name="txtUsername"  value="<?php echo $dataUsername; ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Password :</label>
					<div class="col-lg-3">
						<input class="form-control" name="txtPassword" type="password"  value=""/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">No. Telp :</label>
					<div class="col-lg-3">
						<input class="form-control" name="txtTelp" type="text" value="<?php echo $dataTelp; ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Email :</label>
					<div class="col-lg-3">
						<input class="form-control" name="txtEmail" type="text" value="<?php echo $dataEmail; ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Level Group :</label>
					<div class="col-lg-2">
						<select name="cmbLevel" class="select2 form-control" data-placeholder="Pilih Level">
							<option value="BLANK"> </option>
							<?php
							  $dataSql = "SELECT * FROM sys_group ORDER BY group_id DESC";
							  $dataQry = mysqli_query($koneksidb, $dataSql) or die ("Gagal Query".mysqli_error());
							  while ($dataRow = mysqli_fetch_array($dataQry)) {
								if ($dataLevel == $dataRow['group_id']) {
									$cek = " selected";
								} else { $cek=""; }
								echo "<option value='$dataRow[group_id]' $cek>$dataRow[group_nama]</option>";
							  }
							  $sqlData ="";
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-2 control-label">Default :</label>
					<div class="col-lg-2">
						<select data-placeholder="Pilih Default" class="form-control select2" name="cmbDefault">
							<option value=""></option> 
							   <?php
							  $pilihan	= array("Y", "N");
							  foreach ($pilihan as $nilai) {
								if ($dataDefault==$nilai) {
									$cek=" selected";
								} else { $cek = ""; }
								echo "<option value='$nilai' $cek>$nilai</option>";
							  }
							  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
	                <label class="col-md-2 control-label">Status User :</label>
	                <div class="col-md-10">
	                    <div class="md-radio-list">
	                    	<?php
								if($dataStatus=='Active'){
				                    echo " 	<div class='md-radio'>
				                    			<input type='radio' id='radio53' name='cmbStatus' value='Active' class='md-radiobtn' checked>
				                            	<label for='radio53'><span></span><span class='check'></span><span class='box'></span> Active </label>
				                            </div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio54' name='cmbStatus' value='Non Active' class='md-radiobtn'>
				                            	<label for='radio54'><span></span><span class='check'></span><span class='box'></span> Non Active </label>
				                        	</div>";
				                }elseif($dataStatus=='Non Active'){
				                	echo "	<div class='md-radio'>
				                            	<input type='radio' id='radio53' name='cmbStatus' value='Active' class='md-radiobtn'>
				                            	<label for='radio53'><span></span><span class='check'></span><span class='box'></span> Active </label>
				                        	</div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio54' name='cmbStatus' value='Non Active' class='md-radiobtn' checked>
				                            	<label for='radio54'><span></span><span class='check'></span><span class='box'></span> Non Active </label>
				                            </div>";
				                }else{
				                	echo "	<div class='md-radio'>
				                            	<input type='radio' id='radio53' name='cmbStatus' value='Active' class='md-radiobtn'>
				                            	<label for='radio53'><span></span><span class='check'></span><span class='box'></span> Active </label>
				                        	</div>
				                        	<div class='md-radio'>
				                            	<input type='radio' id='radio54' name='cmbStatus' value='Non Active' class='md-radiobtn'>
				                            	<label for='radio54'><span></span><span class='check'></span><span class='box'></span> Non Active </label>
				                            </div>";
				                }
				            ?>
	                    </div>
	                </div>
	            </div>
	         </div>
	         <div class="form-actions">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn blue"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?page=datauser" class="btn yellow"><i class="fa fa-undo"></i> Batalkan</a>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</form>