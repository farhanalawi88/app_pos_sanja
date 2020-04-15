<?php
			
	if(isset($_POST['btnHapus'])){
		$txtID 		= $_POST['txtID'];
		foreach ($txtID as $id_key) {
				
			$hapus=mysqli_query($koneksidb, "DELETE FROM ms_barang WHERE id_barang='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
			
			if($hapus){	
				$_SESSION['pesan'] = 'Data barang dan item berhasil dihapus';
				echo '<script>window.location="?page=databarang"</script>';
			}else{
				$_SESSION['pesan'] = 'Tidak ada data yang dihapus';
				echo '<script>window.location="?page=databarang"</script>';
			}	
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
		<div class="caption"><span class="caption-subject uppercase bold">Data Barang & Item</span></div>
			<div class="actions">
				<a href="?page=tambahbarang" class="btn blue"><i class="icon-plus"></i> Tambah Data</a>	
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
                      	<th width="2%"><div align="center">NO </div></th>
                      	<th width="5%"><div align="center">KODE </div></th>
                        <th width="24%">NAMA BARANG</th>
						<th width="15%">KATEGORI BARANG</th>
						<th width="10%">MERK</th>
						<th width="5%">UKURAN</th>
						<th width="5%"><div align="center">STOCK</div></th>
						<th width="8%"><div align="right">HARGA</div></th>
					  	<th width="8%"><div align="center">STATUS</div></th>
					  	<th width="10%"><div align="center">AKSI</div></th>
                    </tr>
				</thead>
				<tbody>
                    <?php
						$dataSql = "SELECT * FROM ms_barang a
									INNER JOIN ms_kategori b ON a.id_kategori=b.id_kategori
									LEFT JOIN ms_merk c ON a.id_merk=c.id_merk
									ORDER BY a.id_barang DESC";
						$dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
						$nomor  = 0; 
						while ($data = mysqli_fetch_array($dataQry)) {
						$nomor++;
						$Kode = $data['id_barang'];
					?>
                    <tr class="odd gradeX">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" value="<?php echo $Kode; ?>" name="txtID[<?php echo $Kode; ?>]" />
                                <span></span>
                            </label>
                        </td>
						<td><div align="center"><?php echo $nomor ?></div></td>
						<td><div align="center"><?php echo $data ['kode_barcode']; ?></div></td>
						<td><?php echo $data ['nama_barang']; ?></td>
						<td><?php echo $data ['nama_kategori']; ?></td>
						<td><?php echo $data ['nama_merk']; ?></td>
						<td><?php echo $data ['ukuran_barang']; ?></td>
						<td><div align="center"><?php echo number_format($data ['stok_barang']); ?></div></td>
						<td><div align="right"><?php echo number_format($data ['harga_jual']); ?></div></td>
                        <td>
						  
					      <div align="center">
					        <?php 
							if($data ['status_barang']=='Active'){
								echo "<label class='label label-success'>Active</label>";
							}else{
								echo "<label class='label label-danger'>Non Active</label>";
							}
							?>						
			              </div></td>
						  <td>
						<div class="box-tools pull-center" align="center">
							<div class="btn-group">
								<a href="?page=ubahbarang&amp;id=<?php echo $Kode; ?>" class="btn btn-xs yellow"><i class="fa fa-edit"></i></a>
								<a href="?page=barcodebarang&amp;id=<?php echo $Kode; ?>" class="btn btn-xs blue"><i class="fa fa-barcode"></i></a>
								<a href="?page=qrcodebarang&amp;id=<?php echo $Kode; ?>" class="btn btn-xs green"><i class="fa fa-qrcode"></i></a>
						</div>
                    </tr>
                    <?php } ?>
				</tbody>
            </table>
		</div>
	</div>
</form>
