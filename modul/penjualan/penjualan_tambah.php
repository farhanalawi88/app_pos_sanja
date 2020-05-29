<?php	
	foreach ($_POST['id'] as $key=>$val) {
        $txtID              = (int) $_POST['id'][$key];
        $txtQty             = $_POST['txtQty'][$key];
        $txtDisc            = $_POST['txtDisc'][$key];
        $txtIDBarang        = $_POST['txtIDBarang'][$key];
        $txtHarga        	= $_POST['txtHarga'][$key];

      
			$stokSql	= "SELECT * FROM ms_barang WHERE id_barang='$txtIDBarang'";
			$stokQry	= mysqli_query($koneksidb, $stokSql);
			$stokRow 	= mysqli_fetch_array($stokQry);
			if($txtQty>$stokRow['stok_barang']){
				echo '<script>alert("Jumlah barang '.$stokRow['nama_barang'].' yang anda masukkan melebihi stok tersedia, sisa stok '.$stokRow['stok_barang'].'")</script>';
			}else{
				mysqli_query($koneksidb, "UPDATE tr_penjualan_tmp SET jumlah_penjualan='$txtQty',
																harga_penjualan='$txtHarga',
        														diskon_penjualan='$txtDisc'
                                                            WHERE id='$txtID'") 
            	or die ("Gagal kosongkan tmp".mysqli_error());
			}
		

        
    }

    
	if(isset($_POST['btnSaveCustomer'])){
		$message = array();
		if (trim($_POST['txtNamaCustomer'])=="") {
			$message[] = "Nama customer belum diisi, silahkan isi terlebih dahulu !";		
		}
		if (trim($_POST['txtAlamatCustomer'])=="") {
			$message[] = "Aamat customer belum diisi, silahkan isi terlebih dahulu !";		
		}
		if(count($message)==0){	
		$qrySave	= mysqli_query($koneksidb, "INSERT INTO ms_customer SET nama_customer='".$_POST['txtNamaCustomer']."', 
												  							alamat_customer='".$_POST['txtAlamatCustomer']."',
																			telp_customer='".$_POST['txtTelpCustomer']."',
																			dibuat_customer='".date('Y-m-d H:i:s')."',
																			dibuat_oleh_customer='".$_SESSION['id_user']."',
																			status_customer='Active'") or die ("Gagal query".mysqli_error());
		}

	}
	if(isset($_POST['btnUpdate'])){
		 foreach ($_POST['id'] as $key=>$val) {
            $txtID              = (int) $_POST['id'][$key];
            $txtQty             = $_POST['txtQty'][$key];
            $txtDisc            = $_POST['txtDisc'][$key];
        	$txtHarga        	= $_POST['txtHarga'][$key];

            mysqli_query($koneksidb, "UPDATE tr_penjualan_tmp SET jumlah_penjualan='$txtQty',
            														harga_penjualan='$txtHarga',
            														diskon_penjualan='$txtDisc'
                                                                WHERE id='$txtID'") 
                or die ("Gagal kosongkan tmp".mysqli_error());
        }

	}
	if(isset($_POST['btnHapus'])){
		mysqli_query($koneksidb, "DELETE FROM tr_penjualan_tmp 
								WHERE id='".$_POST['btnHapus']."' 
								AND id_user='".$_SESSION['id_user']."'") 
				or die ("Gagal kosongkan tmp".mysqli_error());



        foreach ($_POST['id'] as $key=>$val) {
            $txtID              = (int) $_POST['id'][$key];
            $txtQty             = $_POST['txtQty'][$key];
            $txtDisc            = $_POST['txtDisc'][$key];
        	$txtHarga        	= $_POST['txtHarga'][$key];

            mysqli_query($koneksidb, "UPDATE tr_penjualan_tmp SET jumlah_penjualan='$txtQty',
            														harga_penjualan='$txtHarga',
            														diskon_penjualan='$txtDisc'
                                                                WHERE id='$txtID'") 
                or die ("Gagal kosongkan tmp".mysqli_error());
        }
	}
	if(isset($_POST['btnBatal'])){
		mysqli_query($koneksidb, "DELETE FROM tr_penjualan_tmp WHERE id_user='".$_SESSION['id_user']."'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				
		$_SESSION['pesan'] = 'Transaksi penjualan berhasil dibatalkan, seluruh item barang dihapus';
		echo '<script>window.location="?page=datapenjualan"</script>';
	}
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['cmbBarang'])=="") {
			$message[] = "Kode barang belum diisi, silahkan pilih barang dan layanan terlebih dahulu !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data Jumlah barang (Qty) belum diisi, silahkan isi dengan angka !";		
		}
	
		$cmbBarang			= $_POST['cmbBarang'];
		$txtJumlah			= $_POST['txtJumlah'];

		foreach ($_POST['id'] as $key=>$val) {
            $txtID              = (int) $_POST['id'][$key];
            $txtQty             = $_POST['txtQty'][$key];
            $txtDisc            = $_POST['txtDisc'][$key];
        	$txtHarga        	= $_POST['txtHarga'][$key];

            mysqli_query($koneksidb, "UPDATE tr_penjualan_tmp SET jumlah_penjualan='$txtQty',
            														harga_penjualan='$txtHarga',
            														diskon_penjualan='$txtDisc'
                                                                WHERE id='$txtID'") 
                or die ("Gagal kosongkan tmp".mysqli_error());
        }

		if(count($message)==0){			
			$barangSql 		="SELECT *, COUNT(*) as total FROM ms_barang WHERE kode_barcode='$cmbBarang'";
			$barangQry 		= mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Tmp".mysqli_error());
			$barangRow 		= mysqli_fetch_assoc($barangQry);
			if ($barangRow['total'] >= 1) {

				$itmSql 		="SELECT *, COUNT(*) as total FROM tr_penjualan_tmp WHERE id_barang='$barangRow[id_barang]' 
																						AND id_user='".$_SESSION['id_user']."'";
				$itmQry 		= mysqli_query($koneksidb, $itmSql) or die ("Gagal Query Tmp".mysqli_error());
				$itmRow 		= mysqli_fetch_assoc($itmQry);

				if($itmRow['total'] >=1){
					$tmpSql = "UPDATE tr_penjualan_tmp SET jumlah_penjualan=jumlah_penjualan+'$txtJumlah' 
														WHERE id_user='".$_SESSION['id_user']."'
														AND id_barang='$itmRow[id_barang]'";
					mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query detail barang : ".mysqli_error());
					$cmbBarang	= "";
					$txtJumlah	= "";
				}else{
					$tmpSql = "INSERT INTO tr_penjualan_tmp SET id_barang='$barangRow[id_barang]',
															harga_penjualan='$barangRow[harga_jual]',
															jumlah_penjualan='$txtJumlah', 
															id_user='".$_SESSION['id_user']."'";
					mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query detail barang : ".mysqli_error());
					$cmbBarang	= "";
					$txtJumlah	= "";
				}


				
			}
			else {
				$message[] = "Tidak ada barang dengan kode $cmbBarang, silahkan ganti";
			}
		}

	}
	
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['txtPembayaran'])=="") {
			$message[] = "Pembayaran tidak boleh kosong, silahkan isi terlebih dahulu !";		
		}
		if (trim($_POST['cmbJenis'])=="") {
			$message[] = "Jenis Pembayaran tidak boleh kosong, silahkan isi terlebih dahulu !";		
		}
		
		$tmpSql ="SELECT COUNT(*) As qty FROM tr_penjualan_tmp WHERE id_user='".$_SESSION['id_user']."'";
		$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
		$tmpRow = mysqli_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "Item barang belum ada yang dimasukan, minimal 1 barang.";
		}
		
		foreach ($_POST['id'] as $key=>$val) {
            $txtID              = (int) $_POST['id'][$key];
            $txtQty             = $_POST['txtQty'][$key];
        	$txtHarga        	= $_POST['txtHarga'][$key];



            mysqli_query($koneksidb, "UPDATE tr_penjualan_tmp SET jumlah_penjualan='$txtQty',     
            														harga_penjualan='$txtHarga'
                                                                WHERE id='$txtID'") 
                or die ("Gagal kosongkan tmp".mysqli_error());
        }
		
		$txtTanggal 	= $_POST['txtTanggal'];
		$cmbCustomer	= $_POST['cmbCustomer'];
		$cmbKasir		= $_POST['cmbKasir'];
		$txtPembayaran	= $_POST['txtPembayaran'];
		$txtPembayaran	= str_replace(".","",$txtPembayaran);
		$txtTotal		= $_POST['txtTotal'];
		$txtTotal		= str_replace(".","",$txtTotal);
		$txtPotongan	= $_POST['txtPotongan'];
		$txtPotongan	= str_replace(".","",$txtPotongan);
		$txtCatatan		= $_POST['txtCatatan'];
		$txtReferensi	= $_POST['txtReferensi'];
		$cmbJenis		= $_POST['cmbJenis'];

		// CEK STOK

		$tmpSql 		= "SELECT * FROM tr_penjualan_tmp WHERE id_user='".$_SESSION['id_user']."'";
		$tmpQry 		= mysqli_query($koneksidb, $tmpSql) or die ("gaga show tmp".mysqli_error());
		while ($tmpRow	= mysqli_fetch_array($tmpQry)) {
			$stokSql	= "SELECT * FROM ms_barang WHERE id_barang='$tmpRow[id_barang]'";
			$stokQry	= mysqli_query($koneksidb, $stokSql);
			$stokRow 	= mysqli_fetch_array($stokQry);
			if($tmpRow['jumlah_penjualan']>$stokRow['stok_barang']){
				$message[] = "Jumlah barang <b>".$stokRow['nama_barang']."</b> yang anda masukkan melebihi stok tersedia, sisa stok <b>".$stokRow['stok_barang']."</b>";
			}
		}

		// JUMLAH PENJUALAN
		


				
		if(count($message)==0){	
			$sumSql 		= "SELECT
									IFNULL( SUM( (harga_penjualan - ( harga_penjualan * diskon_penjualan / 100 )) * jumlah_penjualan ), 0 ) AS total 
								FROM
									tr_penjualan_tmp 
								WHERE
									id_user='".$_SESSION['id_user']."'";
			$sumQry 		= mysqli_query($koneksidb, $sumSql) or die ("gaga show tmp".mysqli_error());
			$sumRow 		= mysqli_fetch_array($sumQry);
			if($sumRow['total']>$txtPembayaran){

				echo '<script>alert("Pembayaran yang anda masukkan kurang, total penjualan '.number_format($sumRow['total']).'")</script>';
			}else{
				$qrySave		= mysqli_query($koneksidb, "INSERT INTO tr_penjualan SET kode_penjualan='".$_POST['txtNomor']."', 
																			tgl_penjualan='".InggrisTgl($txtTanggal)."', 
																			total_pembayaran='$txtPembayaran', 
																			keterangan_penjualan='$txtCatatan',
																			total_penjualan='$sumRow[total]',
																			jenis_pembayaran='$cmbJenis',
																			no_referensi='$txtReferensi',
																			total_potongan='$txtPotongan',
																			id_customer='$cmbCustomer',
																			id_user='$cmbKasir'") 
								  or die ("Gagal query".mysqli_error());
				if($qrySave){
					$IDSql 	= "SELECT MAX(id_penjualan) as id_penjualan FROM tr_penjualan WHERE id_user='$cmbKasir'";
					$IDQry 	= mysqli_query($koneksidb, $IDSql) or die ("Gagal Query Tmp".mysqli_error());
					$IDRow	= mysqli_fetch_array($IDQry);
					$tmpSql ="SELECT * FROM tr_penjualan_tmp WHERE id_user='".$_SESSION['id_user']."'";
					$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
					while ($tmpRow = mysqli_fetch_array($tmpQry)) {
						$barangSql = "INSERT INTO tr_penjualan_item SET id_penjualan='$IDRow[id_penjualan]', 
																		id_barang='$tmpRow[id_barang]', 
																		harga_penjualan='$tmpRow[harga_penjualan]', 
																		diskon_penjualan='$tmpRow[diskon_penjualan]', 
																		jumlah_penjualan='$tmpRow[jumlah_penjualan]'";
						mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Simpan detail barang".mysqli_error());
						$barangSql = "UPDATE ms_barang SET stok_barang=stok_barang - $tmpRow[jumlah_penjualan] 
										WHERE id_barang='$tmpRow[id_barang]'";
						mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Edit Stok".mysqli_error());
					}
					mysqli_query($koneksidb, "DELETE FROM tr_penjualan_tmp WHERE id_user='".$_SESSION['id_user']."'") 
							or die ("Gagal kosongkan tmp".mysqli_error());	
					$_SESSION['pesan'] = 'Transaksi penjualan barang dengan nomor transaksi '.$kodeBaru.' berhasil dibuat';
					echo '<script>window.location="?page=detailpenjualan&id='.$IDRow['id_penjualan'].'"</script>';
				}
				else{
					$message[] = "Gagal penyimpanan ke database";
				}
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
	
	

$cusSql 			= "SELECT * FROM ms_customer WHERE default_customer='Y'";
$cusQry				= mysqli_query($koneksidb, $cusSql);
$cusRow				= mysqli_fetch_array($cusQry);

$kasSql 			= "SELECT * FROM ms_user WHERE default_user='Y'";
$kasQry				= mysqli_query($koneksidb, $kasSql);
$kasRow				= mysqli_fetch_array($kasQry);


$dataKasir			= isset($_POST['cmbKasir']) ? $_POST['cmbKasir'] : $kasRow['id_user'] ;
$dataNamaKasir		= isset($_POST['txtNamaKasir']) ? $_POST['txtNamaKasir'] : $kasRow['nama_user'];

$dataCustomer		= isset($_POST['cmbCustomer']) ? $_POST['cmbCustomer'] : $cusRow['id_customer'] ;
$dataNama 			= isset($_POST['txtNama']) ? $_POST['txtNama'] : $cusRow['nama_customer'];
$dataAlamat			= isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : $cusRow['alamat_customer'] ;
$dataTelp 			= isset($_POST['txtTelp']) ? $_POST['txtTelp'] : $cusRow['telp_customer'] ;



$nomorTransaksi 	= strtoupper(no_acak());
$dataTanggal 		= isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : date('d-m-Y');
$dataCatatan		= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
$dataPembayaran		= isset($_POST['txtPembayaran']) ? $_POST['txtPembayaran'] : '';
$dataPotongan		= isset($_POST['txtPotongan']) ? $_POST['txtPotongan'] : '';
$dataJenis			= isset($_POST['cmbJenis']) ? $_POST['cmbJenis'] : '';
$dataReferensi		= isset($_POST['txtReferensi']) ? $_POST['txtReferensi'] : '';
?>
<SCRIPT language="JavaScript">
function submitform() {
    document.form1.submit();
}
</SCRIPT>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="fieldset-form" autocomplete="off" name="form1">	
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption"><span class="caption-subject uppercase bold">Form Transaksi Penjualan Barang</span></div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="row">
					<div class="col-md-9">						
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<label>Barcode / QR Code :</label>
									<div class="input-group">
		                                <input class="form-control" type="text" name="cmbBarang" id="kode_barang" autofocus="on"/>
		                                <span class="input-group-btn">
		                                    <a class="btn blue btn-block" data-toggle="modal" data-target="#barang"><i class="icon-magnifier"></i></a>
		                                </span>
		                            </div>
								</div>
								<div class="col-md-6">
									<label>Jumlah :</label>
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
						<div class="scroller" data-height="380px">
							<table class="table table-hover table-condensed table-bordered" width="100%" id="sample_5">
								<thead>
									<tr class="active">
								  	  	<th width="23"><div align="center">NO</div></th>
										<th width="100">KODE</th>
										<th width="600">NAMA BARANG </th>
								  	  	<th width="150"><div align="center">HARGA </div></th>
							  	 	  	<th width="100"><div align="center">DISC(%)</div></th>
							  	 	  	<th width="100"><div align="center">JUMLAH</div></th>
							  	  	  	<th width="150"><div align="center">SUBTOTAL</div></th>
								  	  	<th width="100"><div align="center">AKSI</div></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$tmpSql ="SELECT * FROM tr_penjualan_tmp a
													INNER JOIN ms_barang b ON a.id_barang=b.id_barang
													WHERE id_user='".$_SESSION['id_user']."'
													ORDER BY a.id DESC";
										$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
										$total 	= 0; 
										$nomor	= 0;
										$diskon = 0;
										while($tmpRow = mysqli_fetch_array($tmpQry)) {
											$ID			= $tmpRow['id'];
											$diskon 	= $tmpRow['harga_penjualan'] - ($tmpRow['harga_penjualan']*$tmpRow['diskon_penjualan']/100);
											$subSotal 	= $tmpRow['jumlah_penjualan'] * intval($diskon);
											$total 		= $total + $subSotal;
											
											$nomor++;
								?>
									<tr>

	                        			<input type="hidden" name="id[]" value="<?php echo $ID; ?>">
	                        			<input type="hidden" name="txtIDBarang[]" value="<?php echo $tmpRow['id_barang']; ?>">
										<td><div align="center"><?php echo $nomor; ?></div></td>
										<td><?php echo $tmpRow['kode_barcode']; ?></td>
										<td><?php echo $tmpRow['nama_barang']; ?></td>
										<td><div align="right"><input type="text" class="form-control input-sm" onChange="javascript:submitform();" value="<?php echo $tmpRow['harga_penjualan'] ?>" name="txtHarga[]"></div></td>
										<td><div align="center"><input class="form-control input-sm" type="number" onChange="javascript:submitform();" name="txtDisc[]" value="<?php echo ($tmpRow['diskon_penjualan']); ?>"/></div></td>
										<td><div align="center"><input class="form-control input-sm" type="number" onChange="javascript:submitform();" name="txtQty[]" value="<?php echo $tmpRow['jumlah_penjualan']; ?>"/></div></td>
										<td><input type="text" class="form-control input-sm" name="txtSubtotal" value="<?php echo number_format($subSotal) ?>" readonly></td>
										<td><div align="center">
										<div class="btn-group">
											<button type="submit" class="btn btn-xs yellow" name="btnUpdate"><i class="icon-note"></i></button>
											<button type="submit" name="btnHapus" class="btn btn-xs red" value="<?php echo $ID; ?>"><i class="icon-trash"></i></button>
										</div></div>
										</td>
									</tr>
								<?php }?>
								</tbody>
								<tfoot>
									<tr class="active">
										<th colspan="5"></th>
										<th><div align="right"><b>GRAND TOTAL</b></div></th>
										<th><div align="right"><input type="text" class="form-control input-sm" value="<?php echo number_format($total) ?>" readonly></div></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label>Keterangan & Catatan :</label>
									<textarea class="form-control" type="text" name="txtCatatan" rows="3"/><?php echo $dataCatatan; ?></textarea>
								</div>
							</div>
							<div class="col-md-5 form-horizontal">
								<hr>
								<input class="form-control" type="hidden" id="txtTotal" value="<?php echo $total; ?>" name="txtTotal"/>
								<div class="form-group">
									<label class="col-md-5 control-label">Bayar (Rp.) :</label>
									<div class="col-md-7">
										<input class="form-control" type="text" name="txtPembayaran" id="txtPembayaran" onkeyup="sum();" onkeydown="return numbersonly(this, event);" value="<?php echo $dataPembayaran ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-5 control-label">Kembali (Rp.) :</label>
									<div class="col-md-7">
										<input class="form-control" type="text" id="txtKembalian" readonly="readonly"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Kasir :</label>
							<div class="input-group">
	                            <input class="form-control" type="text" name="txtNamaKasir" value="<?php echo $dataNamaKasir ?>" id="nama_kasir" readonly />
	                            <input type="hidden" name="cmbKasir" id="id_kasir" value="<?php echo $dataKasir ?>">
	                            <span class="input-group-btn">
	                                <a class="btn blue btn-block" data-toggle="modal" data-target="#kasir"><i class="icon-magnifier"></i></a>
	                            </span>
	                        </div>
						</div>
						<div class="form-group">
							<label>No. Transaksi :</label>
							<div class="input-icon left">
								<i class="fa fa-qrcode"></i>
								<input class="form-control" type="text" name="txtNomor" value="<?php echo $nomorTransaksi; ?>" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label>Tgl. Transaksi :</label>
							<div class="input-icon left">
								<i class="icon-calendar"></i>
								<input class="form-control date-picker" data-date-format="dd-mm-yyyy" type="text" value="<?php echo $dataTanggal; ?>" name="txtTanggal" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label>Customer :</label>
							<div class="input-group">
	                            <input class="form-control" type="text" name="txtNama" value="<?php echo $dataNama ?>" id="nama_customer" readonly />
	                            <input type="hidden" name="cmbCustomer" id="id_customer" value="<?php echo $dataCustomer ?>">
	                            <span class="input-group-btn">
	                                <a class="btn blue btn-block" data-toggle="modal" data-target="#customer"><i class="icon-magnifier"></i></a>
	                            </span>
	                        </div>
						</div>
						<div class="form-group">
							<label>Alamat Customer :</label>
							<textarea class="form-control" rows="5" name="txtAlamat" id="alamat_customer" readonly><?php echo $dataAlamat ?></textarea>
						</div>
						<div class="form-group">
							<label>No. Telp :</label>
							<div class="input-icon left">
								<i class="fa fa-phone"></i>
								<input class="form-control" id="telp_customer" readonly type="text" value="<?php echo $dataTelp; ?>" name="txtTelp"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Jenis Pembayaran :</label>
							<select name="cmbJenis" class="form-control select2" data-placeholder="Pilih Jenis">
								<?php
									$arrJenis	= array("Cash","Debit");
								  	foreach ($arrJenis as $index => $value) {
										if ($value==$dataJenis) {
											$cek="selected";
										} else { $cek = ""; }
									echo "<option value='$value' $cek>$value</option>";
								  	}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>No. Referensi :</label>
							<div class="input-icon left">
								<i class="fa fa-qrcode"></i>
								<input class="form-control" type="text" value="<?php echo $dataReferensi; ?>" name="txtReferensi" id="tot"/>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn blue" name="btnSave"><i class="fa fa-save"></i> Simpan & Lihat</button>
				<a data-toggle="modal" data-target="#formcustomer" class="btn blue"><i class="fa fa-user"></i> Tambah Customer</a>
				<button type="submit" class="btn blue" name="btnBatal"><i class="fa fa-remove"></i> Batalkan</button>
			</div>
		</div>
	</div>

	<div class="modal fade bs-modal" id="formcustomer" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	                <h4 class="modal-title">Form Customer</h4>
	            </div>
	            <div class="modal-body"> 
					<div class="form-group">
						<label class="control-label">Nama Customer :</label>
						<input class="form-control" name="txtNamaCustomer" type="text"/>
					</div>
					<div class="form-group">
						<label class="control-label">Alamat :</label>
						<textarea class="form-control" name="txtAlamatCustomer" type="text" /></textarea>
					</div>
					<div class="form-group">
						<label class="control-label">No. Telp :</label>
						<input class="form-control" name="txtTelpCustomer" type="text"/>
					</div>
						
			    </div>
	            <div class="modal-footer">
	                <button type="submit" class="btn blue" name="btnSaveCustomer">Simpan</button>
	                <button type="button" class="btn blue" data-dismiss="modal">Tutup</button>
	            </div>
	        </div>
	    </div>
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
			                <tr class="active">
			                  	<th width="30"><div align="center">NO</div></th>
			                  	<th width="100">KODE</th>
			                    <th width="550">NAMA BARANG</th>
			                    <th width="150">UKURAN</th>
			                  	<th width="100"><div align="center">STOK</div></th>
			                  	<th width="150"><div align="right">HARGA SATUAN</div></th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                //Data mentah yang ditampilkan ke tabel    
			                $query = mysqli_query($koneksidb, "SELECT * FROM ms_barang a
			                						LEFT JOIN ms_kategori b ON a.id_kategori=b.id_kategori
			                						WHERE a.status_barang='Active'");
			               	$nomor = 0;
			                while ($data = mysqli_fetch_array($query)) {
			                	$nomor ++;
			                    ?>
			                    <tr class="pilihBarang" data-dismiss="modal" aria-hidden="true" data-kode="<?php echo $data['kode_barcode']; ?>">
			                        <td><div align="center"><?php echo $nomor; ?></div></td>
			                        <td><?php echo $data['kode_barcode']; ?></td>
			                        <td><?php echo $data['nama_barang']; ?></td>
			                        <td><?php echo $data['ukuran_barang']; ?></td>
			                        <td><div align="center"><?php echo number_format($data['stok_barang']); ?></div></td>
			                        <td><div align="right"><?php echo number_format($data['harga_jual']); ?></div></td>
			                    </tr>
			                    <?php
			                }
			                ?>
			            </tbody>
			        </table>
			    </div>
	            <div class="modal-footer">
	                <button type="button" class="btn blue" data-dismiss="modal">Tutup</button>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="modal fade bs-modal-lg" id="kasir" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	                <h4 class="modal-title">Data User</h4>
	            </div>
	            <div class="modal-body"> 
	            	<table class="table table-hover table-bordered table-striped table-condensed" width="100%" id="sample_4">
			            <thead>
			                <tr class="active">
			                  	<th width="20"><div align="center">NO</div></th>
			                    <th width="400">NAMA USER</th>
			                    <th width="300">EMAIL</th>
			                  	<th width="200">TELP</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                //Data mentah yang ditampilkan ke tabel    
			                $query = mysqli_query($koneksidb, "SELECT * FROM ms_user a
			                						WHERE a.status_user='Active'");
			               	$nomor = 0;
			                while ($data = mysqli_fetch_array($query)) {
			                	$nomor ++;
			                    ?>
			                    <tr class="pilihKasir" data-dismiss="modal" aria-hidden="true" 
			                    id-kasir="<?php echo $data['id_user']; ?>"
			                    nama-kasir="<?php echo $data['nama_user']; ?>">
			                        <td><div align="center"><?php echo $nomor; ?></div></td>
			                        <td><?php echo $data['nama_user']; ?></td>
			                        <td><?php echo $data['email_user']; ?></td>
			                        <td><?php echo $data['telp_user']; ?></td>
			                    </tr>
			                    <?php
			                }
			                ?>
			            </tbody>
			        </table>
			    </div>
	            <div class="modal-footer">
	                <button type="button" class="btn blue" data-dismiss="modal">Tutup</button>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="modal fade bs-modal-lg" id="customer" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	                <h4 class="modal-title">Data Customer</h4>
	            </div>
	            <div class="modal-body"> 
	            	<table class="table table-hover table-bordered table-striped table-condensed" width="100%" id="sample_1">
			            <thead>
			                <tr class="active">
			                  	<th width="20"><div align="center">NO</div></th>
			                    <th width="200">NAMA CUSTOMER</th>
			                    <th width="700">ALAMAT</th>
			                  	<th width="80">TELP</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                //Data mentah yang ditampilkan ke tabel    
			                $query = mysqli_query($koneksidb, "SELECT * FROM ms_customer a
			                						WHERE a.status_customer='Active'");
			               	$nomor = 0;
			                while ($data = mysqli_fetch_array($query)) {
			                	$nomor ++;
			                    ?>
			                    <tr class="pilihCustomer" data-dismiss="modal" aria-hidden="true" 
			                    id-customer="<?php echo $data['id_customer']; ?>"
			                    nama-customer="<?php echo $data['nama_customer']; ?>"
			                    alamat-customer="<?php echo $data['alamat_customer']; ?>"
			                    telp-customer="<?php echo $data['telp_customer']; ?>">
			                        <td><div align="center"><?php echo $nomor; ?></div></td>
			                        <td><?php echo $data['nama_customer']; ?></td>
			                        <td><?php echo $data['alamat_customer']; ?></td>
			                        <td><?php echo $data['telp_customer']; ?></td>
			                    </tr>
			                    <?php
			                }
			                ?>
			            </tbody>
			        </table>
			    </div>
	            <div class="modal-footer">
	                <button type="button" class="btn blue" data-dismiss="modal">Tutup</button>
	            </div>
	        </div>
	    </div>
	</div>
</form>
<script src="./assets/scripts/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
	function sum() {
	      var txtTotal 			= document.getElementById('txtTotal').value;
	      var txtPembayaran 	= document.getElementById('txtPembayaran').value;
	      var bayar				= txtPembayaran.replace(".", ""); 
	      var bayar2			= bayar.replace(".", ""); 
	      var result 			= (bayar2) - (txtTotal);
	      if (!isNaN(result)) {
	         document.getElementById('txtKembalian').value = tandaPemisahTitik(result);
	      }
	}
//            jika dipilih, nim akan masuk ke input dan modal di tutup
    $(document).on('click', '.pilihBarang', function (e) {
        document.getElementById("kode_barang").value = $(this).attr('data-kode');
    });
     $(document).on('click', '.pilihCustomer', function (e) {
        document.getElementById("id_customer").value = $(this).attr('id-customer');
        document.getElementById("nama_customer").value = $(this).attr('nama-customer');
        document.getElementById("alamat_customer").value = $(this).attr('alamat-customer');
        document.getElementById("telp_customer").value = $(this).attr('telp-customer');
    });
     $(document).on('click', '.pilihKasir', function (e) {
        document.getElementById("id_kasir").value = $(this).attr('id-kasir');
        document.getElementById("nama_kasir").value = $(this).attr('nama-kasir');
    });


//            tabel lookup mahasiswa
</script>	