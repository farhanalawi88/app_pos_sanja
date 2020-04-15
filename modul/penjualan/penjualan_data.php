<?php			
	if(isset($_POST['btnHapus'])){
		$txtID 		= $_POST['txtID'];
		foreach ($txtID as $id_key) {
				
			$hapus=mysqli_query($koneksidb, "DELETE FROM tr_penjualan WHERE id_penjualan='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
			
			if($hapus){	

				$itemQry	= mysqli_query($koneksidb, "SELECT * FROM tr_penjualan_item WHERE id_penjualan='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				while ($itemRow = mysqli_fetch_array($itemQry)) {
					$barangSql = "UPDATE ms_barang SET stok_barang=stok_barang + $itemRow[jumlah_penjualan] 
									WHERE id_barang='$itemRow[id_barang]'";
					$barangQry = mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Edit Stok".mysqli_error());
				}
				if($barangQry){
					$itemHapus=mysqli_query($koneksidb, "DELETE FROM tr_penjualan_item WHERE id_penjualan='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				}
				
				
				$_SESSION['pesan'] = 'Data transaksi penjualan berhasil dihapus';
				echo '<script>window.location="?page=datapenjualan"</script>';
			}
			
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
		<div class="caption"><span class="caption-subject uppercase bold">History Penjualan Barang</span></div>
			<div class="actions">
				<a href="?page=tambahpenjualan" class="btn blue"><i class="icon-plus"></i> Tambah Data</a>	
				<button class="btn red" name="btnHapus" type="submit" onclick="return confirm('Anda yakin ingin menghapus data penting ini !!')"><i class="icon-trash"></i> Hapus Data</button>
			</div>
		</div>
		<div class="portlet-body">     	
            <table class="table table-striped table-bordered table-hover" id="sample_2">
				<thead>
                    <tr class="active">
       	  	  	  	  	<th class="table-checkbox" width="3%">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                <span></span>
                            </label>
                        </th>
                        <th width="2%"><div align="center">NO.</div></th>
                        <th width="10%"><div align="center">NO. TRANSAKSI </div></th>
                        <th width="12%"><div align="center">TGL. TRANSAKSI</div></th>
						<th width="25%">NAMA CUSTOMER</th>
						<th width="11%">CARA BAYAR</th>
					  	<th width="12%"><div align="right">TOTAL PENJUALAN</div></th>
						<th width="17%"><div align="left">KASIR & ADMIN</div></th>
                    </tr>
				</thead>
				<tbody>
                    <?php
						$dataSql = "SELECT * FROM tr_penjualan a
									LEFT JOIN ms_user b ON a.id_user=b.id_user
									LEFT JOIN ms_customer c ON a.id_customer=c.id_customer
									ORDER BY a.id_penjualan DESC";
						$dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
						$nomor  = 0; 
						while ($data = mysqli_fetch_array($dataQry)) {
						$nomor++;
						$Kode 		= $data['id_penjualan'];
						$subtotal	= ($data['total_penjualan']-$data['total_potongan']);

						if(empty($data['nama_customer'])){
							$dataCustomer	 = 'Umum';
						}else{
							$dataCustomer	= $data['nama_customer'];
						}
					?>
                    <tr class="odd gradeX">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" value="<?php echo $Kode; ?>" name="txtID[<?php echo $Kode; ?>]" />
                                <span></span>
                            </label>
                        </td>
						<td><div align="center"><?php echo $nomor; ?></div></td>
						<td><div align="center"><a href="?page=detailpenjualan&amp;id=<?php echo $Kode; ?>"><i class="fa fa-file-text-o"></i> <?php echo $data['kode_penjualan']; ?></a></div></td>
						<td><div align="center"><?php echo date("d/m/Y H:i", strtotime($data ['tgl_penjualan'])); ?></div></td>
						<td><?php echo $dataCustomer; ?></td>
						<td><?php echo $data ['jenis_pembayaran']; ?></td>
						<td><div align="right"><?php echo number_format($subtotal); ?></div></td>
                        <td><?php echo $data ['nama_user']; ?></td>
                    </tr>
                    <?php } ?>
				</tbody>
            </table>
			</div>
		</div>
	</div>
</form>