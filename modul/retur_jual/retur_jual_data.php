<?php
			
	if(isset($_POST['btnHapus'])){
		$txtID 		= $_POST['txtID'];
		foreach ($txtID as $id_key) {
				
			$hapus=mysqli_query($koneksidb, "DELETE FROM tr_retur_jual WHERE id_retur_jual='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
			
			if($hapus){	

				$itemQry	= mysqli_query($koneksidb, "SELECT * FROM tr_retur_jual_item WHERE id_retur_jual='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				while ($itemRow = mysqli_fetch_array($itemQry)) {
					$barangSql = "UPDATE ms_barang SET stok_barang=stok_barang - $itemRow[jumlah_retur_jual] 
									WHERE id_barang='$itemRow[id_barang]'";
					$barangQry = mysqli_query($koneksidb, $barangSql) or die ("Gagal Query Edit Stok".mysqli_error());
				}
				if($barangQry){
					$itemHapus=mysqli_query($koneksidb, "DELETE FROM tr_retur_jual_item WHERE id_retur_jual='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
				}


				
				
				$_SESSION['pesan'] = 'Data retur penjualan barang berhasil dihapus';
				echo '<script>window.location="?page=datareturjual"</script>';
			}
			
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
		<div class="caption"><span class="caption-subject uppercase bold">Data Retur Penjualan Barang</span></div>
			<div class="actions">
				<a href="?page=tambahreturjual" class="btn blue"><i class="icon-plus"></i> Tambah Data</a>	
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
                        <th width="11%"><div align="center">NO. RETUR JUAL </div></th>
                      	<th width="11%"><div align="center">TGL. RETUR JUAL</div></th>
                      	<th width="11%"><div align="center">NO. PENJUALAN </div></th>
						<th width="30%">NAMA CUSTOMER</th>
						<th width="25%">KASIR & ADMIN</th>
                    </tr>
				</thead>
				<tbody>
                    <?php
						
						$dataSql = "SELECT
									a.kode_retur_jual,
									a.tgl_retur_jual,
									b.kode_penjualan,
									c.nama_customer,
									c.id_customer,
									a.id_retur_jual,
									d.id_user,
									d.nama_user
									FROM tr_retur_jual a
									INNER JOIN tr_penjualan b ON a.id_penjualan=b.id_penjualan
									LEFT JOIN ms_customer c ON b.id_customer=c.id_customer
									INNER JOIN ms_user d ON a.id_user=d.id_user
									ORDER BY a.id_retur_jual";
						$dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
						$nomor  = 0; 
						while ($data = mysqli_fetch_array($dataQry)) {
						$nomor++;
						$Kode 		= $data['id_retur_jual'];
					?>
                    <tr class="odd gradeX">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" value="<?php echo $Kode; ?>" name="txtID[<?php echo $Kode; ?>]" />
                                <span></span>
                            </label>
                        </td>
						<td><div align="center"><a href="?page=detailreturjual&amp;id=<?php echo $Kode; ?>"><?php echo $data['kode_retur_jual']; ?></a></div></td>
						<td><div align="center"><?php echo date('d/m/Y H:i', strtotime($data ['tgl_retur_jual'])); ?></div></td>
						<td><div align="center"><?php echo $data ['kode_penjualan']; ?></div></td>
						<td><?php echo $data ['nama_customer']; ?></td>
						<td><?php echo $data ['nama_user']; ?></td>
                    </tr>
                    <?php } ?>
				</tbody>
            </table>
			</div>
		</div>
	</div>
</form>