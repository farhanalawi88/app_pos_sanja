<?php				
	$kodeTransaksi = $_GET['id'];				
	$beliSql = "SELECT * FROM tr_penjualan a
				LEFT JOIN ms_customer b ON a.id_customer=b.id_customer
				INNER JOIN ms_user c ON a.id_user=c.id_user
				AND a.id_penjualan='$kodeTransaksi'";
	$beliQry = mysqli_query($koneksidb, $beliSql)  or die ("Query pembelian salah : ".mysqli_error());
	$beliRow = mysqli_fetch_array($beliQry);
	if(empty($beliRow['nama_customer'])){
		$dataCustomer	= 'Customer Umum';
	}else{
		$dataCustomer	= $beliRow['nama_customer'];
	}
?>
<div class="portlet box grey-cascade">
	<div class="portlet-title">
	<div class="caption"><span class="caption-subject uppercase bold">Nota Pembayaran Penjualan</span></div>
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
					<div class="scroller" data-height="470px">
						<table class="table table-hover table-condensed table-bordered" width="100%" id="sample_5">
							<thead>
								<tr class="active">
							  	  	<th width="23"><div align="center">NO</div></th>
									<th width="100">KODE</th>
									<th width="600">NAMA BARANG </th>
							  	  	<th width="117"><div align="right">HARGA </div></th>
							  	 	<th width="150"><div align="center">DISC(%)</div></th>
						  	 	  	<th width="150"><div align="center">JUMLAH</div></th>
						  	  	  	<th width="84"><div align="right">SUBTOTAL</div></th>
								</tr>
							</thead>
							<tbody>
							<?php
									$tmpSql ="SELECT * FROM tr_penjualan_item a
												INNER JOIN ms_barang b ON a.id_barang=b.id_barang
												WHERE a.id_penjualan='$kodeTransaksi'
												ORDER BY a.id DESC";
									$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
									$total 	= 0; 
									$nomor	= 0;
									while($tmpRow = mysqli_fetch_array($tmpQry)) {
										$ID			= $tmpRow['id'];
										$diskon 	= $tmpRow['harga_penjualan'] - ($tmpRow['harga_penjualan']*$tmpRow['diskon_penjualan']/100);
											$subSotal 	= $tmpRow['jumlah_penjualan'] * intval($diskon);
										$total 		= $total + $subSotal;
										
										$nomor++;
							?>
								<tr>

                        			<input type="hidden" name="id[]" value="<?php echo $ID; ?>">
									<td><div align="center"><?php echo $nomor; ?></div></td>
									<td><?php echo $tmpRow['kode_barcode']; ?></td>
									<td><?php echo $tmpRow['nama_barang']; ?></td>
									<td><div align="right"><?php echo number_format($tmpRow['harga_penjualan']); ?></div></td>
									<td><div align="center"><?php echo $tmpRow['diskon_penjualan']; ?> %</div></td>
									<td><div align="center"><?php echo $tmpRow['jumlah_penjualan']; ?></div></td>
									<td><div align="right"><?php echo number_format($subSotal); ?></div></td>
								</tr>
							<?php }?>
							</tbody>
							<tfoot>
								<tr class="active">
									<th colspan="5"></th>
									<th><div align="right"><b>GRAND TOTAL</b></div></th>
									<th><div align="right"><b><?php echo number_format($total); ?></b></div></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="row">
						<div class="col-md-7">
							<div class="form-group">
								<label>Keterangan & Catatan :</label>
								<textarea class="form-control" type="text" disabled rows="3"/><?php echo $beliRow['keterangan_penjualan']; ?></textarea>
							</div>
						</div>
						<div class="col-md-5 form-horizontal">
							<hr>
							<div class="form-group">
								<label class="col-md-5 control-label">Bayar (Rp.) :</label>
								<div class="col-md-7">
									<input class="form-control" type="text" disabled value="<?php echo number_format($beliRow['total_pembayaran']) ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-5 control-label">Kembali (Rp.) :</label>
								<div class="col-md-7">
									<input class="form-control" type="text" disabled value="<?php echo number_format($beliRow['total_pembayaran']-$beliRow['total_penjualan']) ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama Kasir :</label>
						<div class="input-icon left">
							<i class="fa fa-user"></i>
							<input class="form-control" type="text" value="<?php echo $beliRow['nama_user']; ?>" disabled/>
						</div>
					</div>
					<div class="form-group">
						<label>No. Transaksi :</label>
						<div class="input-icon left">
							<i class="fa fa-qrcode"></i>
							<input class="form-control" type="text" value="<?php echo $beliRow['kode_penjualan']; ?>" disabled/>
						</div>
					</div>
					<div class="form-group">
						<label>Tgl. Transaksi :</label>
						<div class="input-icon left">
							<i class="icon-calendar"></i>
							<input class="form-control" type="text" value="<?php echo date('d/m/Y H:i', strtotime($beliRow['tgl_penjualan'])); ?>" disabled/>
						</div>
					</div>
					<div class="form-group">
						<label>Customer :</label>
                        <input class="form-control" type="text" value="<?php echo $dataCustomer ?>" disabled/>
                            
					</div>
					<div class="form-group">
						<label>Alamat Customer :</label>
						<textarea class="form-control" rows="5" disabled><?php echo $beliRow['alamat_customer'] ?></textarea>
					</div>
					<div class="form-group">
						<label>No. Telp :</label>
						<div class="input-icon left">
							<i class="fa fa-phone"></i>
							<input class="form-control" disabled type="text" value="<?php echo $beliRow['telp_customer']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Jenis Pembayaran :</label>
						<select name="cmbJenis" class="form-control select2" disabled>
							<option value=""> </option>
							<?php
								$arrJenis	= array("Cash","Debit");
							  	foreach ($arrJenis as $index => $value) {
									if ($value==$beliRow['jenis_pembayaran']) {
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
							<input class="form-control" type="text" value="<?php echo $beliRow['no_referensi']; ?>" disabled/>
						</div>
					</div>
				</div>
				
				
			</div>
		</div>
		<div class="form-actions">
			<a href="?page=tambahpenjualan" class="btn blue" ><i class="icon-plus"></i> Tambah Baru</a>
			<button name="bar" type="button" onclick="cetak()" class="btn blue"><i class="icon-printer"></i> Cetak Invoice</button>
			<button name="bar" type="button" onclick="cetak_nota()" class="btn blue"><i class="icon-printer"></i> Cetak Nota</button>
		</div>	
  </div>
</div>
<script type="text/javascript"> 
    function cetak()	 
    { 
    win=window.open('./cetak/nota_penjualan.php?id=<?php echo $kodeTransaksi; ?>','win','width=900, height=600, menubar=0, scrollbars=1, resizable=0, status=0'); 
    } 
    function cetak_nota()	 
    { 
    win=window.open('./cetak/nota_penjualan_2.php?id=<?php echo $kodeTransaksi; ?>','win','width=300, height=600, menubar=0, scrollbars=1, resizable=0, status=0'); 
    } 
</script>	
		  	