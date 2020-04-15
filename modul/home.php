<?php 
$dataTanggal	= date('Y');

$userSql = "SELECT * FROM ms_user WHERE kode_user='".$_SESSION['kode_user']."'";
$userQry = mysqli_query($koneksidb, $userSql)  or die ("Query penjualan salah : ".mysqli_error());
$userRow = mysqli_fetch_array($userQry);
?>


<div class="row">
	<div class="col-sm-3 responsive" data-tablet="span3" data-desktop="span3">
		<div class="dashboard-stat blue">
			<div class="visual">
				<i class="fa fa-qrcode"></i>
			</div>
			
			<?php
			$itemALatSql 	= "SELECT * FROM ms_barang";
			$itemAlatQry 	= mysqli_query($koneksidb, $itemALatSql)  or die ("Query pembelian salah : ".mysqli_error());
			$totalAlat	    = mysqli_num_rows($itemAlatQry);
			
			?>
			<div class="details">
				<div class="number"><?php echo $totalAlat; ?></div>
				<div class="desc">Data Barang</div>
			</div>
			<a class="more" >Total Data Barang <i class="m-icon-swapright m-icon-white"></i></a>						
		</div>
	</div>
	<div class="col-sm-3 responsive" data-tablet="span3" data-desktop="span3">
		<div class="dashboard-stat green">
			<div class="visual">
				<i class="icon-user"></i>
			</div>
			<?php
			
			$itemTypeSql 	= "SELECT * FROM ms_customer";
			$itemTypeQry 	= mysqli_query($koneksidb, $itemTypeSql)  or die ("Query type salah : ".mysqli_error());
			$totalType		= mysqli_num_rows($itemTypeQry);
			
			?>
			<div class="details">
				<div class="number"><?php echo $totalType ?></div>
				<div class="desc">Data Customer</div>
			</div>
			<a class="more">Total Data Customer <i class="m-icon-swapright m-icon-white"></i></a>						
		</div>
	</div>
	<div class="col-sm-3 responsive" data-tablet="span3" data-desktop="span3">
		<div class="dashboard-stat purple">
			<div class="visual">
				<i class="icon-bar-chart"></i>
			</div>
			<?php
			$itemTerjualSql 	 = "SELECT IFNULL(SUM(jumlah_penjualan),0) as jumlah FROM tr_penjualan_item";
			$itemTerjualQry 	 = mysqli_query($koneksidb, $itemTerjualSql)  or die ("Query pembelian salah : ".mysqli_error());
			$totalItemTerjual    = mysqli_fetch_array($itemTerjualQry);
			
			?>
			<div class="details">
				<div class="number"><?php echo number_format($totalItemTerjual['jumlah']); ?></div>
				<div class="desc">Qty Terjual</div>
			</div>
			<a class="more">Total Jumlah Penjualan <i class="m-icon-swapright m-icon-white"></i></a>						
		</div>
	</div>
	<div class="col-sm-3 responsive" data-tablet="span3" data-desktop="span3">
		<div class="dashboard-stat yellow">
			<div class="visual">
				<i class="fa fa-sitemap"></i>
			</div>
			<?php
			$penjualanSql 	= "SELECT
                                    ifnull( SUM( jumlah_penjualan * (harga_penjualan-harga_penjualan*diskon_penjualan/100) ), 0 ) AS total 
                                FROM
                                    tr_penjualan_item";
			$penjualanQry 	= mysqli_query($koneksidb, $penjualanSql)  or die ("Query penjualan salah : ".mysqli_error());
			$totalPenjualan = mysqli_fetch_array($penjualanQry);
			
			?>
			<div class="details">
				<div class="number"><?php echo number_format($totalPenjualan['total']); ?></div>
				<div class="desc">Data Penjualan</div>
			</div>
			<a class="more">Total Data Penjualan <i class="m-icon-swapright m-icon-white"></i></a>						
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="portlet box grey-cascade">
			<div class="portlet-title">
				<div class="caption">10 Barang Terlaris</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="sample_2">
                    <thead>
                        <tr class="active">
                            <th width="2%"><div align="center">NO </div></th>
                            <th width="24%">NAMA BARANG</th>
                            <th width="10%"><div align="center">JUMLAH</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dataSql = "SELECT * FROM (SELECT 
                                        a.nama_barang,
                                        SUM(jumlah_penjualan) as terjual
                                        FROM ms_barang a
                                        LEFT JOIN tr_penjualan_item b ON a.id_barang=b.id_barang
                                        GROUP BY a.nama_barang) as tbl order by terjual DESC LIMIT 10";
                            $dataQry = mysqli_query($koneksidb, $dataSql)  or die ("Query petugas salah : ".mysqli_error());
                            $nomor  = 0; 
                            while ($data = mysqli_fetch_array($dataQry)) {
                            $nomor++;
                            $Kode = $data['id_barang'];
                        ?>
                        <tr class="odd gradeX">
                            <td><div align="center"><?php echo $nomor ?></div></td>
                            <td><?php echo $data ['nama_barang']; ?></td>
                            <td><div align="center"><?php echo number_format($data ['terjual']); ?></div></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="portlet box grey-cascade">
			<div class="portlet-title">
				<div class="caption">Nilai Penjualan</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body">
				<div id='order_2'></div>
			</div>
		</div>
	</div>
</div>
<div class="portlet box grey-cascade">
    <div class="portlet-title">
        <div class="caption">Jumlah Terjual</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body">
        <div id='order_1'></div>
    </div>
</div>
<script src="./assets/scripts/jquery.min.js" type="text/javascript"></script>
<script src="./assets/scripts/highcharts.js" type="text/javascript"></script>
<script src="./assets/scripts/highcharts-3d.js" type="text/javascript"></script>
<script src="./assets/scripts/exporting.js"></script>
<script type="text/javascript">

    Highcharts.chart('order_1', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Grafik Barang Terjual',
            style: {
                fontSize: '14px',
                fontFamily: 'abel'
            }
        },
        subtitle: {
            text: 'Sampai Tahun <?php echo date('Y') ?>',
            style: {
                fontSize: '14px',
                fontFamily: 'abel'
            }
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Terjual',
            data: [

                <?php
                    $tmp2Sql ="SELECT
                                    a.nama_barang,
                                    SUM(b.jumlah_penjualan) jml 
                                FROM
                                    ms_barang a
                                    INNER JOIN tr_penjualan_item b ON a.id_barang = b.id_barang
                                    INNER JOIN tr_penjualan c on b.id_penjualan=c.id_penjualan
                                GROUP BY
                                    a.nama_barang ";
                    $tmp2Qry = mysqli_query($koneksidb, $tmp2Sql) or die ("Gagal Query Tmp".mysqli_error()); 
                    while($tmp2Row = mysqli_fetch_array($tmp2Qry)) {    
                ?>
                    ['<?php echo $tmp2Row['nama_barang'] ?>',<?php echo $tmp2Row['jml'] ?>],
               
                <?php } ?>
                
            ]
        }]
    });
</script>
<style type="text/css">
${demo.css}
        </style>
       <script type="text/javascript">
$(function () {
    Highcharts.chart('order_2', {
        chart: {
            type: 'column'
        },

        title: {
            text: 'Data Penjualan Setiap Bulan',
             style: {
                     fontFamily: 'Abel',
                    fontSize: '15px'
                }
        },
        subtitle: {
            text: 'Pada Tahun <?php echo date('Y') ?>',
            style: {
                    fontFamily: 'Abel',
                    fontSize: '13px'
                }
        },
        xAxis: {
            type: 'category',
            labels: {
                style: {
                    fontSize: '13px',
                     fontFamily: 'Abel'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nilai Penjualan',
                style: {
                    fontFamily: 'Abel',
                    fontSize: '14px'
                }
            }
        },

        legend: {
            enabled: false
        },
       
        series: [{
            name: 'Nilai Penjualan ',
            type: 'column',
            colorByPoint: true,
            data: [
            <?php 
                $dataTahun      = date('Y');
                $pilBulan       = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                foreach ($pilBulan as $bulan) {
                $dtSql          = "SELECT
                                      IFNULL(SUM( b.jumlah_penjualan*(b.harga_penjualan-(b.harga_penjualan*b.diskon_penjualan/100) )),0) AS total 
                                    FROM
                                        tr_penjualan a
                                    INNER JOIN tr_penjualan_item b ON a.id_penjualan=b.id_penjualan
                                    WHERE YEAR (a.tgl_penjualan ) = '$dataTahun' 
                                    AND MONTH (a.tgl_penjualan ) = '$bulan'";        
                $dtQry          = mysqli_query($koneksidb, $dtSql) or die(mysqli_error());
                while( $dtRow = mysqli_fetch_array($dtQry)){
                   $jml_omset = $dtRow['total'];                 
                }             
            ?>
                  
                       ['<?php echo $bulan; ?>',<?php echo $jml_omset; ?>],
                  
                  <?php } ?>
                  
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#000',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Abel'
                }
            }
        }]
    });
});
</script>
