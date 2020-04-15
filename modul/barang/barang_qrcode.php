<?php
	include		 "config/bar128.php";	
		
	$KodeEdit		= isset($_GET['id']) ?  $_GET['id'] : $_POST['txtKode']; 
	$sqlShow 		= "SELECT * FROM ms_barang
						INNER JOIN ms_kategori ON ms_barang.id_kategori=ms_kategori.id_kategori
						WHERE ms_barang.id_barang='$KodeEdit'";
	$qryShow 		= mysqli_query($koneksidb, $sqlShow)  or die ("Query ambil data supplier salah : ".mysqli_error());
	$dataShow 		= mysqli_fetch_array($qryShow);
			
	$dataKode		= $dataShow['id_barang'];
	$dataNama		= $dataShow['nama_barang'];
	$dataKategori	= $dataShow['nama_kategori']; 
	$dataBeli		= format_angka($dataShow['harga_beli']); 
	$dataJual		= format_angka($dataShow['harga_jual']); 
	$dataBarcode	= $dataShow['kode_barcode']; 
	$namafile		= $dataBarcode.'.png';
	if(!file_exists("photo/".$namafile)){
		$dataQRCode	= 'File QR Code Belum Digenerate, silahkan <a href="?page=ubahbarang&amp;id='.$dataKode.'">edit data barang </a>';
	}else{
		$dataQRCode	= '<img src="photo/'.$namafile.'"></img>';
	}
?>
<div class="portlet box grey-cascade">
	<div class="portlet-title">
		<div class="caption"><span class="caption-subject uppercase bold"> Form Cetak Barcode Barang & Item</span></div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" class="form-horizontal">
			<div class="form-body">
				<input class="form-control" type="hidden" name="txtKode" value="<?php echo $dataKode; ?>" readonly="readonly"/>
				<div class="form-group">
					<label class="col-md-2 control-label">Nama Barang</label>
					<div class="col-md-5">
						<input class="form-control" value="<?php echo $dataNama; ?>" type="text" disabled="disabled"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Kategori</label>
					<div class="col-md-5">
						<input class="form-control" value="<?php echo $dataKategori; ?>" type="text" disabled="disabled"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Kode Barang</label>
					<div class="col-md-5">
						<input class="form-control" type="text" value="<?php echo $dataBarcode ?>" readonly="readonly"/>
						<?php echo $dataQRCode ?>
					</div>
				</div>
			</div>
			<div class="form-actions">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button name="bar" type="button" onclick="cetak()" class="btn blue"><i class="fa fa-print"></i> Cetak QR Code</button>
			                <a href="?page=databarang" class="btn yellow"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"> 
    function cetak()	 
    { 
    win=window.open('./cetak/qrcode.php?id=<?php echo $dataKode; ?>','win','width=1400, height=700, menubar=0, scrollbars=1, resizable=0, status=0'); 
    } 
</script>