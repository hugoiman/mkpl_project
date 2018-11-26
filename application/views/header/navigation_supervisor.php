
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Supervisor</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Simple Sidebar - Start Bootstrap Template</title>

        <style type="text/css">
          .search {
            text-align:left;
          }
          .tombol {
            text-align: left;
          }
          .show {
            text-align: left;
          }
          .info {
            text-align: left;
          }
          .halaman {
            text-align: right;
          }

        </style>

        <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
      
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/simple-sidebar.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/w3.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/material-google/material-google.css" rel="stylesheet">

        <link href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
      </head>
  <body>

  <!-- Sidebar -->
  <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:15%">
    <h3 class="w3-bar-item">Menu</h3>
    <a href="<?php  echo base_url() ?>penjualan" class="w3-bar-item w3-button"><span class="material-icons">  shopping_basket</span> Penjualan</a>
    <a href="<?php  echo base_url() ?>kasir" class="w3-bar-item w3-button"><span class="material-icons">account_balance_wallet</span> Kasir</a>
    <a href="<?php  echo base_url() ?>tagihan" class="w3-bar-item w3-button"><span class="material-icons">receipt</span> Tagihan</a>
    <a href="<?php  echo base_url() ?>transaksi/transaksi_penjualan" class="w3-bar-item w3-button"><span class="material-icons">file_upload</span> Transaksi Penjualan</a>
    <a href="<?php  echo base_url() ?>transaksi/transaksi_pembelian" class="w3-bar-item w3-button"><span class="material-icons">file_download</span> Transaksi Pembelian</a>
    <a href="<?php  echo base_url() ?>resep" class="w3-bar-item w3-button"><span class="material-icons">description</span> Resep</a>
    <a href="<?php  echo base_url() ?>produk" class="w3-bar-item w3-button"><span class="material-icons ">extension</span> Produk</a>
    <a href="<?php  echo base_url() ?>distributor" class="w3-bar-item w3-button"><span class="material-icons ">local_shipping</span> Distributor</a>
    <a href="<?php  echo base_url() ?>stok" class="w3-bar-item w3-button"><span class="material-icons ">folder</span> Stok</a>
    <a href="<?php  echo base_url() ?>permintaan_produk" class="w3-bar-item w3-button"><span class="material-icons ">shopping_cart</span> Permintaan Produk</a>
    <a href="<?php  echo base_url() ?>pasien" class="w3-bar-item w3-button"><span class="material-icons ">person</span> Pasien</a>
    <a href="<?php  echo base_url() ?>dokter" class="w3-bar-item w3-button"><span class="material-icons ">person</span> Dokter</a>
    <a href="<?php  echo base_url() ?>user" class="w3-bar-item w3-button"><span class="material-icons ">person</span> User</a>
    <a href="<?php  echo base_url() ?>pengaturan" class="w3-bar-item w3-button"><span class="material-icons ">settings</span> Pengaturan</a>
    <a href="<?php  echo base_url() ?>authentication/logout" class="w3-bar-item w3-button"><span class="material-icons ">launch</span> Logout</a>
  </div>

  <!-- Page Content -->
  <div style="margin-left:15%">

  <div class="w3-container w3-teal">
    <h1>A-Poin</h1>
  </div>
