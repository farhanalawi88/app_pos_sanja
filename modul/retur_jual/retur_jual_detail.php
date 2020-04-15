<?php
	$kodeTransaksi = $_GET['id'];
				
	$beliSql = "SELECT
				a.kode_retur_jual,
				a.tgl_retur_jual,
				b.kode_penjualan,
				c.kode_customer,
				c.nama_customer,
				d.id_user,
				d.nama_user,
				a.keterangan_retur_jual
				FROM tr_retur_jual a
				INNER JOIN tr_penjualan b ON a.id_penjualan=b.id_penjualan
				LEFT JOIN ms_customer c ON b.id_customer=c.id_customer
				INNER JOIN ms_user d ON a.id_user=d.id_user
				AND a.id_retur_jual='$kodeTransaksi'";
	$beliQry = mysqli_query($koneksidb, $beliSql)  or die ("Query jual salah : ".mysqli_error());
	$beliRow = mysqli_fetch_array($beliQry);
?>
<div class="portlet box grey-cascade">
	<div class="portlet-title">
	<div class="caption"><span class="caption-subject uppercase bold">Detail Transaksi Retur Penjualan Barang</span></div>
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
							<input class="form-control" type="text" value="<?php echo $beliRow['kode_retur_jual']; ?>" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Tgl. Retur :</label>
						<div class="input-icon left">
							<i class="icon-calendar"></i>
							<input class="form-control" type="text" value="<?php echo $beliRow['tgl_retur_jual']; ?>" disabled/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">No. Penjualan :</label>
                        <input class="form-control" type="text" disabled value="<?php echo $beliRow['kode_penjualan'] ?>" />
					</div>
					<div class="form-group">
						<label class="control-label">Customer :</label>
						<div class="input-icon left">
							<i class="icon-user"></i>
							<input class="form-control" type="text" value="<?php echo $beliRow['nama_customer']; ?>" disabled="disabled"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Keterangan & Catatan :</label>
						<div class="controls">
							<textarea class="form-control" type="text" disabled rows="5"/><?php echo $beliRow['keterangan_retur_jual']; ?></textarea>
						</div>
					</div>
				</div>
				<div class="col-md-9">				
					
					<div class="scroller" data-height="400px">
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
								</tr>
							</thead>
							<tbody>
							<?php
									$tmpSql ="SELECT * FROM tr_retur_jual_item a
												INNER JOIN ms_barang b ON a.id_barang=b.id_barang
												WHERE a.id_retur_jual='$kodeTransaksi'
												ORDER BY b.id_barang";
									$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
									$total 	= 0; 
									$qtyBrg = 0; 
									$nomor	= 0;
									while($tmpRow = mysqli_fetch_array($tmpQry)) {
										$ID			= $tmpRow['id'];
										$subSotal 	= $tmpRow['jumlah_retur_jual'] * intval($tmpRow['harga_retur_jual']- ($tmpRow['harga_retur_jual']*$tmpRow['diskon_retur_jual']/100));
										$total 		= $total + $subSotal;
										$qtyBrg 	= $qtyBrg + $tmpRow['jumlah_retur_jual'];											
										$nomor++;
							?>
								<tr>
									<td><div align="center"><?php echo $nomor; ?></div></td>
									<td><?php echo $tmpRow['kode_barcode']; ?> - <?php echo $tmpRow['nama_barang']; ?></td>
									<td><div align="left"><?php echo $tmpRow['alasan_retur_jual']; ?></div></td>
									<td><div align="right"><?php echo number_format($tmpRow['harga_retur_jual'],2); ?></div></td>
									<td><div align="center"><?php echo number_format($tmpRow['diskon_retur_jual']); ?>%</div></td>
									<td><div align="center"><?php echo number_format($tmpRow['jumlah_retur_jual'],2); ?></div></td>
									<td><div align="right"><?php echo number_format($subSotal,2); ?></div></td>
								</tr>
								<?php }?>
							</tbody>
							<tfoot>
								<tr>
						  	  	  	<th colspan="5"><div align="right"><b>GRAND TOTAL</b></div></th>
						  	  	  	<th><div align="center"><b><?php echo number_format($qtyBrg,2); ?></b></div></th>
						  	  	  	<th><div align="right"><b><?php echo number_format($total,2); ?></b></div></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				
			</div>
		</div>
		<div class="form-actions">
			<a href="?page=tambahreturjual" class="btn blue" ><i class="icon-plus"></i> Tambah Baru</a>
			<button name="bar" type="button" onclick="cetak()" class="btn blue"><i class="icon-printer"></i> Cetak Nota</button>
		</div>	
  </div>
</div>
<script type="text/javascript"> 
    function cetak()	 
    { 
    win=window.open('./cetak/nota_retur_jual.php?id=<?php echo $kodeTransaksi; ?>','win','width=250, height=400, menubar=0, scrollbars=1, resizable=0, status=0'); 
    } 
</script>	
		  	