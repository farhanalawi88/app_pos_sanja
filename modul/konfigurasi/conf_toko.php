<?php		
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['txtNama'])=="") {
			$message[] = "Nama salon tidak boleh kosong";		
		}
		if (trim($_POST['txtMoto'])=="") {
			$message[] = "slogan salon tidak boleh kosong !";		
		}
		if (trim($_POST['txtAlamat'])=="") {
			$message[] = "alamat salon tidak boleh kosong";		
		}
		if (trim($_POST['txtTelp'])=="") {
			$message[] = "no. telp salon tidak boleh kosong";		
		}
						
		$txtNama		= $_POST['txtNama'];
		$txtMoto		= $_POST['txtMoto'];
		$txtTelp		= $_POST['txtTelp'];
		$txtAlamat		= $_POST['txtAlamat'];
		$txtEmail		= $_POST['txtEmail'];
		$txtKeterangan	= $_POST['txtKeterangan'];
							
		if(count($message)==0){			
			$sqlSave="UPDATE ms_toko SET nama_toko='$txtNama', 
										telp_toko='$txtTelp', 
										alamat_toko='$txtAlamat', 
										email_toko='$txtEmail',
					  					moto_toko='$txtMoto', 
										keterangan_toko='$txtKeterangan'";
			$qrySave=mysqli_query($koneksidb, $sqlSave);
			if($qrySave){
				$_SESSION['pesan'] = 'Data toko berhasil diperbaharui';
						echo '<script>window.location="?page=confstore"</script>';
				
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
	$sqlShow 		= "SELECT * FROM ms_toko";
	$qryShow 		= mysqli_query($koneksidb, $sqlShow)  or die ("Query ambil data user salah : ".mysqli_error());
	$dataShow 		= mysqli_fetch_array($qryShow);
	
	$dataNama		= isset($dataShow['nama_toko']) ?  $dataShow['nama_toko'] : $_POST['txtNama'];
	$dataTelp		= isset($dataShow['telp_toko']) ?  $dataShow['telp_toko'] : $_POST['txtTelp'];
	$dataAlamat		= isset($dataShow['alamat_toko']) ?  $dataShow['alamat_toko'] : $_POST['txtAlamat'];
	$dataEmail		= isset($dataShow['email_toko']) ?  $dataShow['email_toko'] : $_POST['txtEmail'];
	$dataMoto		= isset($dataShow['moto_toko']) ?  $dataShow['moto_toko'] : $_POST['txtMoto'];
	$dataKeterangan = isset($dataShow['keterangan_toko']) ?  $dataShow['keterangan_toko'] : $_POST['txtKeterangan'];
			
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal">
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption"><span class="caption-subject uppercase bold">Konfigurasi Toko</span></div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-2 control-label">Nama Toko :</label>
					<div class="col-md-5">
						<input type="text" class="form-control" name="txtNama" value="<?php echo $dataNama; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Slogan & Moto :</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="txtMoto"  value="<?php echo $dataMoto; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Alamat :</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="txtAlamat" value="<?php echo $dataAlamat; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">No. Telp :</label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="txtTelp" value="<?php echo $dataTelp; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Email :</label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="txtEmail" value="<?php echo $dataEmail; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Keterangan Struck :</label>
					<div class="col-md-10">
						<textarea name="txtKeterangan" cols="2" rows="2" class="form-control"><?php echo $dataKeterangan; ?></textarea>
					</div>
				</div>
		 	</div>
		 	<div class="form-actions">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn blue"><i class="fa fa-save"></i> Simpan Data</button>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</form>