
<?php
	$pg=$_GET['page'];
		if($pg=="home"){ include"modul/home.php"; }
	// DATA KONFIGURASI
		elseif($pg=="confstore"){ include"modul/konfigurasi/conf_toko.php"; }
		elseif($pg=="confprofile"){ include"modul/konfigurasi/conf_profil.php"; }
		elseif($pg=="confpassword"){ include"modul/konfigurasi/conf_password.php"; }
		elseif($pg=="confbackup"){ include"modul/konfigurasi/conf_backup.php"; }
	// DATA USER
		elseif($pg=="datauser"){ include"modul/user/user_data.php"; }
		elseif($pg=="tambahuser"){ include"modul/user/user_tambah.php"; }
		elseif($pg=="ubahuser"){ include"modul/user/user_ubah.php"; }
	// DATA GROUP
		elseif($pg=="datagroup"){ include"modul/group/group_data.php"; }
		elseif($pg=="tambahgroup"){ include"modul/group/group_tambah.php"; }
		elseif($pg=="ubahgroup"){ include"modul/group/group_ubah.php"; }
	// DATA KATERGORI
		elseif($pg=="datakategori"){ include"modul/kategori/kategori_data.php"; }
		elseif($pg=="tambahkategori"){ include"modul/kategori/kategori_tambah.php"; }
		elseif($pg=="ubahkategori"){ include"modul/kategori/kategori_ubah.php"; }
	// DATA CUSTOMER
		elseif($pg=="datacustomer"){ include"modul/customer/customer_data.php"; }
		elseif($pg=="tambahcustomer"){ include"modul/customer/customer_tambah.php"; }
		elseif($pg=="ubahcustomer"){ include"modul/customer/customer_ubah.php"; }
	// DATA DATA MODUL
		elseif($pg=="datamodul"){ include"modul/modul/modul_data.php"; }
		elseif($pg=="tambahmodul"){ include"modul/modul/modul_tambah.php"; }
		elseif($pg=="ubahmodul"){ include"modul/modul/modul_ubah.php"; }
	// DATA SUPPLIER
		elseif($pg=="datamerk"){ include"modul/merk/merk_data.php"; }
		elseif($pg=="tambahmerk"){ include"modul/merk/merk_tambah.php"; }
		elseif($pg=="ubahmerk"){ include"modul/merk/merk_ubah.php"; }
	// DATA BARANG
		elseif($pg=="databarang"){ include"modul/barang/barang_data.php"; }
		elseif($pg=="tambahbarang"){ include"modul/barang/barang_tambah.php"; }
		elseif($pg=="ubahbarang"){ include"modul/barang/barang_ubah.php"; }
		elseif($pg=="barcodebarang"){ include"modul/barang/barang_barcode.php"; }
		elseif($pg=="qrcodebarang"){ include"modul/barang/barang_qrcode.php"; }
	// DATA PENJUALAN
		elseif($pg=="tambahpenjualan"){ include"modul/penjualan/penjualan_tambah.php"; }
		elseif($pg=="datapenjualan"){ include"modul/penjualan/penjualan_data.php"; }
		elseif($pg=="detailpenjualan"){ include"modul/penjualan/penjualan_detail.php"; }
	// DATA RETUR JUAL
		elseif($pg=="tambahreturjual"){ include"modul/retur_jual/retur_jual_tambah.php"; }
		elseif($pg=="datareturjual"){ include"modul/retur_jual/retur_jual_data.php"; }
		elseif($pg=="detailreturjual"){ include"modul/retur_jual/retur_jual_detail.php"; }
	// LAPORAN
		elseif($pg=="laporanreturjual"){ include"modul/laporan/laporan_retur_jual.php"; }
		elseif($pg=="laporandetailreturjual"){ include"modul/laporan/laporan_detail_retur_jual.php"; }
		elseif($pg=="laporanpenjualan"){ include"modul/laporan/laporan_penjualan.php"; }
		elseif($pg=="laporandetailpenjualan"){ include"modul/laporan/laporan_detail_penjualan.php"; }
		elseif($pg=="laporanbarang"){ include"modul/laporan/laporan_barang.php"; }
		elseif($pg=="laporanstok"){ include"modul/laporan/laporan_stok.php"; }
		elseif($pg=="laporancustomer"){ include"modul/laporan/laporan_customer.php"; }
		else {
		echo "<div class='col-md-12'><div class='alert alert-dismissable alert-warning'><i class='icon-exclamation-sign'></i> Belum Ada Modul</div></div>";
		}
?>
		
		