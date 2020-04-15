<?php
if($_POST) {
	if(isset($_POST['btnHapus'])){
		mysqli_query($koneksidb, "DELETE FROM tr_retur_jual_tmp WHERE id='".$_POST['btnHapus']."' AND id_user='".$_SESSION['id_user']."'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
	}
	if(isset($_POST['btnBatal'])){
		mysqli_query($koneksidb, "DELETE FROM tr_retur_jual_tmp WHERE id_user='".$_SESSION['id_user']."'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				
		$_SESSION['pesan'] = 'retur penjualan barang berhasil dibatalkan, seluruh item barang dihapus';
		echo '<script>window.location="?page=datareturjual"</script>';
	}
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['cmbBarang'])=="") {
			$message[] = "Kode barang belum diisi, silahkan pilih barang dan layanan terlebih dahulu !";		
		}
		if (trim($_POST['txtAlasan'])=="") {
			$message[] = "Alasan Retur tidak boleh kosong, silahkan isi terlebih dahulu !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data Jumlah barang (Qty) belum diisi, silahkan isi dengan angka !";		
		}
	
		$txtAlasan			= $_POST['txtAlasan'];
		$cmbBarang			= $_POST['cmbBarang'];
		$txtJumlah			= $_POST['txtJumlah'];
		$cmbPenjualan		= $_POST['cmbPenjualan'];


		
		if(count($message)==0){			
			$barangSql 		="SELECT * FROM tr_penjualan_item a
								INNER JOIN ms_barang b ON a.id_barang=b.id_barang
								INNER JOIN tr_penjualan c ON a.id_penjualan=c.id_penjualan
								WHERE b.kode_barcode='$cmbBarang'
								AND c.kode_penjualan='$cmbPenjualan'";
			$barangQry 		= mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Tmp".mysqli_error());
			$barangRow 		= mysqli_fetch_assoc($barangQry);
			$barangQty 		= mysqli_num_rows($barangQry);
			if ($barangQty >= 1) {
				$tmpSql = "INSERT INTO tr_retur_jual_tmp SET id_barang='$barangRow[id_barang]',
															harga_retur_jual='$barangRow[harga_penjualan]',
															alasan_retur_jual='$txtAlasan',
															jumlah_retur_jual='$txtJumlah', 
															diskon_retur_jual='$barangRow[diskon_penjualan]',
															id_user='".$_SESSION['id_user']."'";
				mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query detail barang : ".mysqli_error());
				$txtKode	= "";
				$txtJumlah	= "";
				$txtHargaBeli = "";
			}
			else {
				$message[] = "Tidak ada barang dengan kode $cmbBarang, silahkan ganti";
			}
		}

	}
	
	if(isset($_POST['btnSave'])){	
		$message = array();
		if (trim($_POST['cmbPenjualan'])=="") {
			$message[] = "ID penjualan kosong, silahkan pilih penjualan terlebih dahulu !";		
		}		
		$tmpSql ="SELECT COUNT(*) As qty FROM tr_retur_jual_tmp WHERE id_user='".$_SESSION['id_user']."'";
		$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
		$tmpRow = mysqli_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "Item barang belum ada yang dimasukan, minimal 1 barang & Item.";
		}
		
		$txtCatatan		= $_POST['txtCatatan'];
		$cmbPenjualan	= $_POST['cmbPenjualan'];
		$kodeBaru		= $_POST['txtNomor'];
		$jualSql 		= "SELECT * FROM tr_penjualan WHERE kode_penjualan='$cmbPenjualan'";
		$jualQry 		= mysqli_query($koneksidb, $jualSql) or die ("Gagal Query Tmp".mysqli_error());
		$jualRow 		= mysqli_fetch_array($jualQry);
				
		if(count($message)==0){			
			$qrySave		= mysqli_query($koneksidb, "INSERT INTO tr_retur_jual SET kode_retur_jual='$kodeBaru', 
																					tgl_retur_jual='".date('Y-m-d H:i:s')."',  
																					keterangan_retur_jual='$txtCatatan',
																					id_penjualan='$jualRow[id_penjualan]',
																					id_user='".$_SESSION['id_user']."'") 
								  or die ("Gagal query".mysqli_error());
			if($qrySave){
				$IDSql 	= "SELECT MAX(id_retur_jual) as id_retur_jual FROM tr_retur_jual WHERE id_user='".$_SESSION['id_user']."'";
				$IDQry 	= mysqli_query($koneksidb, $IDSql) or die ("Gagal Query Tmp".mysqli_error());
				$IDRow	= mysqli_fetch_array($IDQry);

				$tmpSql ="SELECT * FROM tr_retur_jual_tmp WHERE id_user='".$_SESSION['id_user']."'";
				$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
				while ($tmpRow = mysqli_fetch_array($tmpQry)) {
					$barangSql = "INSERT INTO tr_retur_jual_item SET id_retur_jual='$IDRow[id_retur_jual]', 
																	id_barang='$tmpRow[id_barang]', 
																	harga_retur_jual='$tmpRow[harga_retur_jual]', 
																	alasan_retur_jual='$tmpRow[alasan_retur_jual]',
																	diskon_retur_jual='$tmpRow[diskon_retur_jual]',  
																	jumlah_retur_jual='$tmpRow[jumlah_retur_jual]'";
					mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Simpan detail barang".mysqli_error());
					$barangSql = "UPDATE ms_barang SET stok_barang=stok_barang + $tmpRow[jumlah_retur_jual] WHERE id_barang='$tmpRow[id_barang]'";
					mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Edit Stok".mysqli_error());

					
				}
				mysqli_query($koneksidb, "DELETE FROM tr_retur_jual_tmp WHERE id_user='".$_SESSION['id_user']."'") 
						or die ("Gagal kosongkan tmp".mysqli_error());
						
				$_SESSION['pesan'] = 'Retur penjualan barang dengan nomor transaksi '.$kodeBaru.' berhasil dibuat';
				echo '<script>window.location="?page=detailreturjual&id='.$IDRow['id_retur_jual'].'"</script>';
			}
			else{
				$message[] = "Gagal penyimpanan ke database";
			}
		}	
	} 
	
	if (! count($message)==0 ){
		echo "<div class='alert alert-danger'>";
			$Num=0;
			foreach ($message as $indeks=>$pesan_tampil) { 
			$Num++;
				echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";	
			} 
		echo "</div>"; 
	}
} 
$nomorTransaksi 	= strtoupper(no_acak());
$tglTransaksi 		= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$dataPenjualan		= isset($_POST['cmbPenjualan']) ? $_POST['cmbPenjualan'] : '';

$dataSql			= "SELECT * FROM tr_penjualan
						LEFT JOIN ms_customer ON tr_penjualan.id_customer=ms_customer.id_customer
						WHERE kode_penjualan='$dataPenjualan'";
$dataQry			= mysqli_query($koneksidb, $dataSql) or die ("Gagal Query".mysqli_error());
$dataShow			= mysqli_fetch_assoc($dataQry);

$dataCatatan		= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
$dataKodeCustomer	= $dataShow['id_customer'];
$dataNamaCustomer	= $dataShow['nama_customer'];
$dataIDPenjualan	= $dataShow['id_penjualan'];
?>
<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
</SCRIPT>
<form action="<?php $_SERVER['PHP_SELF']; ?>" name="form1" method="post" class="fieldset-form" autocomplete="off">	
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption"><span class="caption-subject uppercase bold">Form Transaksi Retur Penjualan</span></div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
		
			<div class="form-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">No. Retur :</label>
							<div class="input-icon left">
								<i class="fa fa-qrcode"></i>
								<input class="form-control" type="text" name="txtNomor" value="<?php echo $nomorTransaksi; ?>" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Tgl. Retur :</label>
							<div class="input-icon left">
								<i class="icon-calendar"></i>
								<input class="form-control" type="text" name="cmbTanggal" value="<?php echo $tglTransaksi; ?>" readonly="readonly"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">No. Penjualan :</label>
							<div class="input-group">
	                            <input class="form-control" type="text" name="cmbPenjualan" value="<?php echo $dataPenjualan ?>" readonly/>
	                            <span class="input-group-btn">
	                                <a class="btn blue btn-block" data-toggle="modal" data-target="#penjualan"><i class="icon-magnifier-add"></i></a>
	                            </span>
	                        </div>
						</div>
						<div class="form-group">
							<label class="control-label">Customer :</label>
							<div class="input-icon left">
								<i class="icon-user"></i>
								<input class="form-control" type="text" value="<?php echo $dataNamaCustomer; ?>" disabled="disabled"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Keterangan & Catatan :</label>
							<div class="controls">
								<textarea class="form-control" type="text" name="txtCatatan" rows="5"/><?php echo $dataCatatan; ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-9">				
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<label>Kode Barang :</label>
									<div class="input-group">
		                                <input class="form-control" type="text" name="cmbBarang" id="id_barang"/>
		                                <span class="input-group-btn">
		                                    <a class="btn blue btn-block" data-toggle="modal" data-target="#barang"><i class="icon-magnifier-add"></i></a>
		                                </span>
		                            </div>
								</div>
								<div class="col-md-4">
									<label>Nama Barang :</label>
									<input class="form-control" type="text" id="nama_barang" disabled="disabled" />
								</div>
								<div class="col-md-3">
									<label>Alasan Retur :</label>
									<input class="form-control" type="text" name="txtAlasan" />
								</div>
								<div class="col-md-2">
									<label>Jumlah Retur :</label>
									<div class="input-group">
		                                <input type="tel" class="form-control" name="txtJumlah" value="1" onblur="if (value == '') {value = '1'}" onfocus="if (value == '1') {value =''}"/>
		                                <span class="input-group-btn">
		                                    <button type="submit" class="btn blue btn-block" name="btnPilih"><i class="icon-plus"></i></button>
		                                </span>
		                            </div>
								</div>
							</div>
						</div>
						<hr />
						<div class="scroller" data-height="330px">
							<table class="table table-hover table-condensed table-bordered" width="100%" id="sample_5">
								<thead>
									<tr class="active">
							  	  	  	<th width="29"><div align="center">NO</div></th>
										<th width="527">NAMA BARANG </th>
									  	<th width="307"><div align="left">ALASAN</div></th>
								  	  	<th width="133"><div align="right">HARGA </div></th>
								  	  	<th width="133"><div align="center">DISC </div></th>
							  	  	  	<th width="104"><div align="center">JUMLAH</div></th>
							  	  	  	<th width="87"><div align="right">SUBTOTAL</div></th>
							  	  	  	<th width="79"><div align="center">HAPUS</div></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$tmpSql ="SELECT * FROM tr_retur_jual_tmp
													INNER JOIN ms_barang ON tr_retur_jual_tmp.id_barang=ms_barang.id_barang
													WHERE id_user='".$_SESSION['id_user']."'
													ORDER BY ms_barang.id_barang";
										$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
										$total 	= 0; 
										$qtyBrg = 0; 
										$nomor	= 0;
										while($tmpRow = mysqli_fetch_array($tmpQry)) {
											$ID			= $tmpRow['id'];
											$subSotal 	= $tmpRow['jumlah_retur_jual'] * intval($tmpRow['harga_retur_jual'] - ($tmpRow['harga_retur_jual']*$tmpRow['diskon_retur_jual']/100));
											$total 		= $total + $subSotal;
											$qtyBrg 	= $qtyBrg + $tmpRow['jumlah_retur_jual'];											
											$nomor++;
								?>
									<tr>
										<td><div align="center"><?php echo $nomor; ?></div></td>
										<td><?php echo $tmpRow['kode_barcode']; ?> - <?php echo $tmpRow['nama_barang']; ?></td>
										<td><div align="left"><?php echo $tmpRow['alasan_retur_jual']; ?></div></td>
										<td><div align="right"><?php echo number_format($tmpRow['harga_retur_jual']); ?></div></td>
										<td><div align="center"><?php echo number_format($tmpRow['diskon_retur_jual']); ?>%</div></td>
										<td><div align="center"><?php echo number_format($tmpRow['jumlah_retur_jual']); ?></div></td>
										<td><div align="right"><?php echo number_format($subSotal); ?></div></td>
										<td>
										<div align="center">
											<button type="submit" name="btnHapus" class="btn btn-xs red" value="<?php echo $ID; ?>"><i class="icon-trash"></i></button>
										</div>										
										</td>
									</tr>
									<?php }?>
								</tbody>
								<tfoot>
									<tr>
							  	  	  	<th colspan="5"><div align="right"><b>GRAND TOTAL</b></div></th>
							  	  	  	<th><div align="center"><b><?php echo number_format($qtyBrg); ?></b></div></th>
							  	  	  	<th><div align="right"><b><?php echo number_format($total); ?></b></div></th>
							  	  	  	<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn blue" name="btnSave"><i class="fa fa-save"></i> Simpan & Lihat</button>
				<button type="submit" class="btn blue" name="btnBatal"><i class="fa fa-remove"></i> Batalkan</button>
			</div>
		</div>
	</div>
	<div class="modal fade bs-modal-lg" id="penjualan" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	                <h4 class="modal-title"><b>History Penjualan</b></h4>
	            </div>
	            <div class="modal-body"> 
	                <table class="table table-hover table-bordered table-striped table-condensed" width="100%" id="sample_1">
	                    <thead>
	                        <tr class="active">
	                            <th width="2%"><div align="center">NO.</div></th>
		                        <th width="10%"><div align="center">NO. TRANSAKSI </div></th>
		                        <th width="12%"><div align="center">TGL. TRANSAKSI</div></th>
								<th width="25%">NAMA CUSTOMER</th>
							  	<th width="12%"><div align="right">TOTAL PENJUALAN</div></th>
                                <th width="50"><div align="center">PILIH</div></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <?php
								$dataSql = "SELECT * FROM tr_penjualan a
											LEFT JOIN ms_user b ON a.id_user=b.id_user
											LEFT JOIN ms_customer c ON a.id_customer=c.id_customer
											ORDER BY a.id_penjualan ASC";
								$dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
								$nomor  = 0; 
								while ($data = mysqli_fetch_array($dataQry)) {
								$nomor++;
								$dataPenjualan 		= $data['kode_penjualan'];
								$subtotal	= ($data['total_penjualan']-$data['total_potongan']);

								if(empty($data['nama_customer'])){
									$dataCustomer	 = 'Umum';
								}else{
									$dataCustomer	= $data['nama_customer'];
								}
							?>
		                    <tr class="odd gradeX">
								<td><div align="center"><?php echo $nomor; ?></div></td>
								<td><div align="center"><?php echo $data['kode_penjualan']; ?></div></td>
								<td><div align="center"><?php echo date("d/m/Y H:i", strtotime($data ['tgl_penjualan'])); ?></div></td>
								<td><?php echo $dataCustomer; ?></td>
								<td><div align="right"><?php echo number_format($subtotal,2); ?></div></td>
                                <td><div align="center"><button type="submit" name="cmbPenjualan" value="<?php echo $dataPenjualan ?>" class="btn btn-xs red"><i class="fa fa-check"></i></button></div></td>
		                    </tr>
		                    <?php } ?>
		                </tbody>
	                </table> 
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn green" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	        <!-- /.modal-content -->
	    </div>
	    <!-- /.modal-dialog -->
	</div>
	<div class="modal fade bs-modal-lg" id="barang" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	                <h4 class="modal-title">Data Barang</h4>
	            </div>
	            <div class="modal-body"> 
	            	<table class="table table-hover table-bordered table-striped table-condensed" width="100%" id="sample_2">
			            <thead>
			                <tr>
			                  	<th width="4%"><div align="center">Kode</div></th>
			                    <th width="70%">Nama Barang</th>
			                    <th width="10%"><div align="right">Harga</div></th>
			                    <th width="10%"><div align="center">Diskon</div></th>
			                    <th width="10%"><div align="center">Jumlah</div></th>
			                    <th width="10%"><div align="right">Subtotal</div></th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                //Data mentah yang ditampilkan ke tabel    
			                $query = mysqli_query($koneksidb, "SELECT * FROM tr_penjualan_item a
			                					INNER JOIN ms_barang b ON a.id_barang=b.id_barang
			                					WHERE a.id_penjualan='$dataIDPenjualan'");
			                while ($data = mysqli_fetch_array($query)) {
			                	$harga_fix 	= $data['harga_penjualan']-($data['harga_penjualan']*$data['diskon_penjualan']/100)
			                    ?>
			                    <tr class="pilihBarang" data-dismiss="modal" aria-hidden="true" 
									data-kode="<?php echo $data['kode_barcode']; ?>"
									data-nama="<?php echo $data['nama_barang']; ?>">
			                        <td><div align="center"><?php echo $data['kode_barcode']; ?></div></td>
			                        <td><?php echo $data['nama_barang']; ?></td>
			                        <td><div align="right"><?php echo number_format($data['harga_penjualan']); ?></div></td>
			                        <td><div align="center"><?php echo $data['diskon_penjualan']; ?>%</div></td>
			                        <td><div align="center"><?php echo $data['jumlah_penjualan']; ?></div></td>
			                        <td><div align="right"><?php echo number_format($harga_fix*$data['jumlah_penjualan']); ?></div></td>
			                    </tr>
			                    <?php
			                }
			                ?>
			            </tbody>
			        </table> 
				</div>
	            <div class="modal-footer">
	                <button type="button" class="btn blue" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>
</form>
<script src="./assets/scripts/jquery-1.11.2.min.js"></script>
<script src="./assets/scripts/bootstrap.js"></script>
<script type="text/javascript">
    $(document).on('click', '.pilihBarang', function (e) {
        document.getElementById("id_barang").value = $(this).attr('data-kode');
		document.getElementById("nama_barang").value = $(this).attr('data-nama');
    });
</script>	

				
										
								