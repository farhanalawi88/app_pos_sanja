<?php
			
	if(isset($_POST['btnHapus'])){
		$txtID 		= $_POST['txtID'];
		foreach ($txtID as $id_key) {
				
			mysqli_query($koneksidb, "DELETE FROM user WHERE kode_user='$id_key'") 
				or die ("Gagal kosongkan tmp".mysqli_error());
					
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<div class="portlet box grey-cascade">
	    <div class="portlet-title">
	        <div class="caption">
	            <span class="caption-subject uppercase bold hidden-xs">Data Admin & Kasir</span>
	        </div>
	        <div class="actions">
				<a href="?page=tambahuser" class="btn blue"><i class="icon-plus"></i> Tambah Data </a>
				<button class="btn red" name="btnHapus" type="submit" onclick="return confirm('Anda yakin ingin menghapus data penting ini !!')"><i class="icon-trash"></i> Hapus Data</button>
			</div>
		</div>
    	<div class="portlet-body">
           <table class="table table-striped table-bordered table-hover" id="sample_2">
				<thead>
                    <tr class="active">
       	  	  	  	  	<th class="table-checkbox">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                <span></span>
                            </label>
                        </th>
                        <th width="25%">NAMA USER</th>
                        <th width="20%">USERNAME</th>
						<th width="20%">EMAIL</th>
						<th width="13%">GROUP LEVEL</th>
						<th width="5%">DEFAULT</th>
						<th width="9%"><div align="center">STATUS</div></th>
						<th width="7%"><div align="center">AKSI</div></th>
                    </tr>
				</thead>
				<tbody>
               <?php
						$dataSql = "SELECT * FROM ms_user a 
									INNER JOIN sys_group b ON a.user_group=b.group_id
									ORDER BY id_user DESC";
						$dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
						$nomor  = 0; 
						while ($data = mysqli_fetch_array($dataQry)) {
						$nomor++;
						$Kode = $data['id_user'];
				?>
                    <tr class="odd gradeX">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" value="<?php echo $Kode; ?>" name="txtID[<?php echo $Kode; ?>]" />
                                <span></span>
                            </label>
                        </td>
						<td><?php echo $data ['nama_user']; ?></td>
						<td><?php echo $data ['username_user']; ?></td>
						<td><?php echo $data ['email_user']; ?></td>
						<td><?php echo $data ['group_nama']; ?></td>
						<td><?php echo $data ['default_user']; ?></td>
						<td>
						  <div align="center">
						    <?php 
						if($data ['status_user']=='Active'){
							echo "<label class='label label-success'>Active</label>";
						}else{
							echo "<label class='label label-danger'>Non Active</label>";
						}
						?>						
				        </div></td>
						<td><div align="center"><a href="?page=ubahuser&amp;id=<?php echo $Kode; ?>" class="btn btn-xs blue"><i class="fa fa-edit"></i></a></div></td>

                    </tr>
                    <?php
                        
                    }
                    ?>
				</tbody>
            </table>
  		</div>
	</div>
</form>