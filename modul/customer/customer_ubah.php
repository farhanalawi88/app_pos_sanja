<?php
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['txtNama'])=="") {
			$message[] = "<b>Nama customer</b> tidak boleh kosong!";		
		}
		if (trim($_POST['txtAlamat'])=="") {
			$message[] = "<b>Alamat </b> tidak boleh kosong";		
		}
		if (trim($_POST['txtTelpon'])=="") {
			$message[] = "<b>No. Telpon </b> tidak boleh kosong!";		
		}
		if (trim($_POST['cmbStatus'])=="") {
			$message[] = "<b>Status </b> tidak boleh kosong!";		
		}
		if (trim($_POST['cmbDefault'])=="") {
			$message[] = "<b>Default </b> tidak boleh kosong!";		
		}

		$txtNama		= $_POST['txtNama'];
		$txtAlamat		= $_POST['txtAlamat'];
		$txtTelpon		= $_POST['txtTelpon'];
		$cmbStatus		= $_POST['cmbStatus'];
		$cmbDefault		= $_POST['cmbDefault'];

		if($cmbDefault=='Y'){
			$qryUpdate=mysqli_query($koneksidb, "UPDATE ms_customer SET default_customer='N'") 
					   or die ("Gagal query update".mysqli_error());
		}
		
		if(count($message)==0){	
			$qryUpdate=mysqli_query($koneksidb, "UPDATE ms_customer SET nama_customer='$txtNama', 
								  							alamat_customer='$txtAlamat',
															telp_customer='$txtTelpon',
															default_customer='$cmbDefault',
															status_customer='$cmbStatus'
													WHERE id_customer ='".$_POST['txtKode']."'") 
					   or die ("Gagal query update".mysqli_error());
			if($qryUpdate){
				$_SESSION['pesan'] = 'Data customer berhasil diperbaharui';
				echo '<script>window.location="?page=datacustomer"</script>';
			}
			exit;
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
	$KodeEdit		= isset($_GET['id']) ?  $_GET['id'] : $_POST['txtKode']; 
	$sqlShow 		= "SELECT * FROM ms_customer WHERE id_customer='$KodeEdit'";
	$qryShow 		= mysqli_query($koneksidb, $sqlShow)  or die ("Query ambil data customer salah : ".mysqli_error());
	$dataShow 		= mysqli_fetch_array($qryShow);
	
	$dataKode		= $dataShow['id_customer'];
	$dataNama		= isset($dataShow['nama_customer']) ?  $dataShow['nama_customer'] : $_POST['txtNama'];
	$dataAlamat 	= isset($dataShow['alamat_customer']) ?  $dataShow['alamat_customer'] : $_POST['txtAlamat'];
	$dataTelpon 	= isset($dataShow['telp_customer']) ?  $dataShow['telp_customer'] : $_POST['txtTelpon'];
	$dataStatus 	= isset($dataShow['status_customer']) ?  $dataShow['status_customer'] : $_POST['cmbStatus'];
	$dataDefault 	= isset($dataShow['default_customer']) ?  $dataShow['default_customer'] : $_POST['cmbDefault'];
?>
		
<div class="portlet box grey-cascade">
	<div class="portlet-title">
		<div class="caption"><span class="caption-subject uppercase bold">Form Penambahan Customer</span></div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmadd" class="form-horizontal">
			<div class="form-body">
				<input class="form-control" type="hidden" value="<?php echo $dataKode; ?>" name="txtKode" readonly/>
					
				<div class="form-group">
					<label class="col-md-2 control-label">Nama Customer :</label>
					<div class="col-md-5">
						<input class="form-control" name="txtNama" value="<?php echo $dataNama; ?>" type="text"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Alamat :</label>
					<div class="col-md-10">
						<textarea class="form-control" name="txtAlamat" type="text"/><?php echo $dataAlamat; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">No. Telp :</label>
					<div class="col-md-3">
						<input class="form-control" name="txtTelpon" value="<?php echo $dataTelpon; ?>" type="text"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Default :</label>
					<div class="col-lg-2">
						<select data-placeholder="Pilih Status" class="form-control select2" name="cmbDefault">
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
			                <a href="?page=datacustomer" class="btn yellow"><i class="fa fa-undo"></i> Batalkan</a>
			            </div>
			        </div>
			    </div>
			</div>
		</form>
	</div>
</div>