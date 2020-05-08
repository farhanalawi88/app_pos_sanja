<?php
 //Define relative path from this script to mPDF
 $nama_file='Laporan_Penjualan'; //Beri nama file PDF hasil.
define('_MPDF_PATH','../plugin/mpdf60/');
//define("_JPGRAPH_PATH", '../mpdf60/graph_cache/src/');

//define("_JPGRAPH_PATH", '../jpgraph/src/'); 
 
include(_MPDF_PATH . "mpdf.php");
//include(_MPDF_PATH . "graph.php");

//include(_MPDF_PATH . "graph_cache/src/");

$mpdf=new mPDF('utf-8', 'A4-L', 10.5, 'arial'); // Membuat file mpdf baru
 
//Beginning Buffer to save PHP variables and HTML tags
ob_start(); 

$mpdf->useGraphs = true;

?>
<?php
	include_once "../config/inc.connection.php";
	include_once "../config/inc.library.php";
	
	$tokoSql = "SELECT * FROM ms_toko ";
	$tokoQry = mysqli_query($koneksidb, $tokoSql)  or die ("Query toko salah : ".mysqli_error());
	$tokoRow = mysqli_fetch_array($tokoQry);	
	
	
?>
<div align="center" style="margin-bottom:15px">
<h3 style="margin:0px 0px 0px 0px; font-weight:bold"><strong><?php echo $tokoRow['nama_toko']; ?></strong></h3>
<h4 style="margin:0px 0px 0px 0px"><?php echo $tokoRow['alamat_toko'] ?>, Telp: <?php echo $tokoRow['telp_toko'] ?>, Email: <?php echo $tokoRow['email_toko'] ?></h4>
<h4 style="margin:0px 0px 0px 0px; font-weight:bold"><b>LAPORAN PENJUALAN</b></h4>
<h4 style="margin:0px 0px 0px 0px">PERIODE : <?php echo date('d/m/Y', strtotime($_GET['awal']))?> S/D <?php echo date('d/m/Y', strtotime($_GET['akhir']))?></h4>
</div>
<br>
<style>
        *
        {
            margin:0;
            padding:0;
            font-family: calibri;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family: calibri;
            font-size:8pt;
            margin:0;
            padding:0;
        }
         
        p
        {
            margin:0;
            padding:0;
            margin-left: 200px;
        }
         
        table
        {
            font-family: calibri; 
            border-spacing:0;
            border-collapse: collapse; 
             
        }
         
        table td 
        {
            padding: 1mm;
            
        }
.style2 {
    font-size: 14pt;
    font-weight: bold;
    outline:dashed
}
.style5 {
    font-size: 13pt;
    font-weight: bold
}
</style>
<table border="1" width="100%">
	<tr>
		<th width="20%">NAMA CUSTOMER</th>
        <th width="10%"><div align="center">TGL. TRANSAKSI</div></th>
        <th width="10%"><div align="center">NO. TRANSAKSI</div></th>
		<th width="12%"><div align="center">KODE BARANG</div></th>
		<th width="30%">NAMA BARANG</th>
        <th width="5%"><div align="center">DISKON</div></th>
	  	<th width="5%"><div align="right">HARGA</div></th>
		<th width="5%"><div align="center">JUMLAH</div></th>
		<th width="5%"><div align="right">SUBTOTAL</div></th>
	</tr>
	<?php
		
		$tglAwal		= $_GET['awal'];
		$tglAkhir		= $_GET['akhir'];		
		$dataSql 		= mysqli_query($koneksidb, "SELECT * FROM tr_penjualan_item a 
														INNER JOIN tr_penjualan b ON a.id_penjualan=b.id_penjualan
														INNER JOIN ms_barang c ON a.id_barang=c.id_barang
														LEFT JOIN ms_customer d ON b.id_customer=d.id_customer
														WHERE date(b.tgl_penjualan) BETWEEN '$tglAwal' AND '$tglAkhir' 
														ORDER BY b.tgl_penjualan DESC");
		$nomor  		= 0;
		$subtotal 		= 0;
		$total			= 0;
		$harga			= 0;
		$jumlah			= 0;
		while($dataRow	= mysqli_fetch_array($dataSql)){	
			$nomor ++;
			$total		= intval($dataRow ['jumlah_penjualan']*($dataRow['harga_penjualan']-($dataRow['harga_penjualan']*$dataRow['diskon_penjualan']/100)));
			$subtotal 	= $subtotal + $total;
			$jumlah 	= $jumlah + $dataRow ['jumlah_penjualan'];
			$harga	 	= $harga + $dataRow ['harga_penjualan'];
			if($dataRow['nama_customer']==''){
				$dataCustomer ='UMUM';
			}else{
				$dataCustomer = $dataRow['nama_customer'];
			}
	?>
	<tr>
        <td><?php echo $dataCustomer;?></td>
		<td><div align="center"><?php echo indonesiaTgl($dataRow ['tgl_penjualan']); ?> </div></td>
		<td><div align="center"><?php echo $dataRow ['kode_penjualan']; ?></div></td>
		<td><div align="center"><?php echo $dataRow ['kode_barcode']; ?></div></td>
		<td><?php echo $dataRow ['nama_barang']; ?></td>
        <td><div align="center"><?php echo number_format($dataRow ['diskon_penjualan']); ?>%</div></td>
		<td><div align="right"><?php echo number_format($dataRow ['harga_penjualan']); ?></div></td>
		<td><div align="center"><?php echo number_format($dataRow ['jumlah_penjualan']); ?></div></td>
		<td><div align="right"><?php echo number_format($total); ?></div></td>
    </tr>
    <?php } ?>
    <tr>
        <th colspan="6"><div align="right">GRAND TOTAL : </div></th>
		<th width="10%"><div align="right"><?php echo number_format($harga) ?></div></th>
		<th width="8%"><div align="center"><?php echo number_format($jumlah) ?></div></th>
		<th width="8%"><div align="right"><?php echo number_format($subtotal) ?></div></th>
    </tr>
</table>
<?php



$html = ob_get_contents(); //Proses untuk mengambil data
ob_end_clean();
//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);

$mpdf->WriteHTML(utf8_encode($html));
// LOAD a stylesheet

$mpdf->Output($nama_file."_".date('ymd').".pdf" ,'I');

 


exit; 
?>