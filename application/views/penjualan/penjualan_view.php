
<div class="w3-container">
    <center>
        <h1 style="font-size:20pt">Penjualan</h1>
    </center>

    <button class="btn btn-success" onclick="tambah_bebas()"><i class="material-icons">toll</i> Tambah Bebas</button>    
    <button class="btn btn-warning" onclick="tambah_resep('tunggal')"><i class="material-icons">description</i> Tambah Resep Tunggal</button>
    <button class="btn btn-danger" onclick="tambah_resep('racikan')"><i class="material-icons">description</i> Tambah Resep Racikan</button>
    <button class="btn btn-primary" onclick="tambah_tarik_resep()"><i class="material-icons">description</i> Tarik Copy Resep</button>
    <button class="btn btn-info" onclick="total_tagihan()"><i class="material-icons">receipt</i> Total Tagihan & Bayar</button>

    <br />
    <br />
    <br />
    <center>
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Jenis Penjualan</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>Jenis Penjualan</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </tfoot>

        </table>
    </center>

    <br />
    <br />
    <br />
</div>
</div>
</body>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>

<script src="<?php echo base_url('assets/datatables/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?php echo base_url('assets/ajax/jszip.min.js') ?>"></script>
<script src="<?php echo base_url('assets/ajax/pdfmake.min.js') ?>"></script>
<script src="<?php echo base_url('assets/ajax/vfs_fonts.js') ?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.html5.min.js') ?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.print.min.js') ?>"></script>

<script type="text/javascript">

        var index_penjualan_bebas = 0;
        var index_penjualan_resep = 0;
        var index_penjualan_tarik_resep = 0;

        var index_detail_produk_resep = 0;

        var bebas = new Array();
        var tarik_resep = new Array();
        var resep = new Array();

        var detail_resep = {jenis:"", bungkus:"", jumlah_bungkus:0, cara_minum:"", nomor:"", id_pasien:"", id_dokter:"", harga:0, produk_jumlah:new Array()};
        var detail_tarik_resep = {id_resep:"0", harga:0};
        var detail_bebas = {id_produk:"0", jumlah:0, harga:0};

        var total = 0;
        var isi = new Array();
        var table;

        $(document).ready(
                function () {
                    table = $('#table');

                    $('#searchProduk').keyup(
                            function () {
                                $('#resultProduk').html('');
                                var searchField = $('#searchProduk').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama')?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultProduk').append('<li class="list-group-item link-class">' + value.id_produk + ' | <span class="text-muted">' + value.nama + ' | <span class="text-muted">' + value.satuan + ' | <span class="text-muted">' +value.pabrik + '</span> | <span class="text-muted">' + Math.ceil(value.harga) + '</span> | <span class="text-muted">' + value.jumlah + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#searchProdukResep').keyup(
                            function () {
                                $('#resultProdukResep').html('');
                                var searchField = $('#searchProdukResep').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama') ?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultProdukResep').append('<li class="list-group-item link-class">' + value.id_produk + ' | <span class="text-muted">' + value.nama + ' | <span class="text-muted">' + value.satuan + ' | <span class="text-muted">' +value.pabrik + '</span> | <span class="text-muted">' + Math.ceil(value.harga) + '</span> | <span class="text-muted">' + value.jumlah + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#searchPasienResep').keyup(
                            function (e) {
                                $('#resultPasienResep').html('');
                                var searchField = $('#searchPasienResep').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama_pasien') ?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_pasien.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultPasienResep').append('<li class="list-group-item link-class">' + value.id_pasien + ' | <span class="text-muted">' + value.nama + '</span> | <span class="text-muted">' + value.alamat + '</span> | <span class="text-muted">' + value.nomor_telepon + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#searchDokterResep').keyup(
                            function () {
                                $('#resultDokterResep').html('');
                                var searchField = $('#searchDokterResep').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama_dokter') ?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_dokter.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultDokterResep').append('<li class="list-group-item link-class">' + value.id_dokter + ' | <span class="text-muted">' + value.nama + '</span> | <span class="text-muted">' + value.alamat + '</span> | <span class="text-muted">' + value.kategori + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#searchPasienTarikResep').keyup(
                            function () {
                                $('#resultPasienTarikResep').html('');
                                var searchField = $('#searchPasienTarikResep').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama_pasien') ?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_pasien.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultPasienTarikResep').append('<li class="list-group-item link-class">' + value.id_pasien + ' | <span class="text-muted">' + value.nama + '</span> | <span class="text-muted">' + value.alamat + '</span> | <span class="text-muted">' + value.nomor_telepon + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#searchDokterTarikResep').keyup(
                            function () {
                                $('#resultDokterTarikResep').html('');
                                var searchField = $('#searchDokterTarikResep').val();
                                var expression = new RegExp(searchField, "i");

                                $.getJSON("<?php echo site_url('penjualan/ajax_get_by_nama_dokter') ?>/" + searchField,
                                        function (data) {
                                            $.each(data, function (key, value) {
                                                if (value.id_dokter.search(expression) != -1 || value.nama.search(expression) != -1)
                                                {
                                                    $('#resultDokterTarikResep').append('<li class="list-group-item link-class">' + value.id_dokter + ' | <span class="text-muted">' + value.nama + '</span> | <span class="text-muted">' + value.alamat + '</span> | <span class="text-muted">' + value.kategori + '</span></li>');
                                                }
                                            });
                                });
                    });

                    $('#resultDokterResep').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchDokterResep').val(click_text);
                        $("#resultDokterResep").html('');
                    });

                    $('#resultPasienResep').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchPasienResep').val(click_text);
                        $("#resultPasienResep").html('');
                    });
                    
                    $('#resultDokterTarikResep').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchDokterTarikResep').val(click_text);
                        $("#resultDokterTarikResep").html('');
                    });

                    $('#resultPasienTarikResep').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchPasienTarikResep').val(click_text);
                        $("#resultPasienTarikResep").html('');
                    });

                    $('#resultProduk').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchProduk').val(click_text);
                        $("#resultProduk").html('');
                    });

                    $('#resultProdukResep').on('click', 'li', function () {
                        var click_text = $(this).text();
                        $('#searchProdukResep').val(click_text);
                        $("#resultProdukResep").html('');
                    });

                    $('#jumlah').keyup(
                            function (e) {
                                var produk = $('#searchProduk').val().split("|");
                                var input_jumlah = parseInt($('#jumlah').val());
                                var jumlah = parseInt(produk[5]);
                                if (input_jumlah > jumlah) {
                                    alert('jumlah terlalu banyak');
                                    $('#jumlah').val('');
                                } else if (input_jumlah <= 0) {
                                    alert('jumlah terlalu mustahil ');
                                    $('#jumlah').val('');
                                }

                                var key = $('#jumlah').val();
                                key = key.split(',').join('');
                                
                                if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                                    key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    $('#jumlah').val(key);
                                }else{
                                    $('#jumlah').val('');
                                }
                    });

                    $('#jumlahResep').keyup(
                            function (e) {
                                var produk = $('#searchProdukResep').val().split("|");
                                var input_jumlah = parseInt($('#jumlahResep').val());
                                var jumlah = parseInt(produk[5]);
                                if (input_jumlah > jumlah) {
                                    alert('jumlah terlalu banyak');
                                    $('#jumlahResep').val('');
                                } 

                                var key = $('#jumlahResep').val();
                                key = key.split(',').join('');
                                
                                if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 190 || e.keyCode == 110) {
                                    key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    $('#jumlahResep').val(key);
                                }else{
                                    $('#jumlahResep').val('');

                                }
                    });

                    $("#debit").keyup(function(e) {
                        var key = $('#debit').val();
                        key = key.split(',').join('');
                        
                        if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
                            $('#debit').val('');
                        }
                        else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                            key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#debit').val(key);
                        }else{
                            $('#debit').val('');
                        }

                    });

                    $("#kredit").keyup(function(e) {
                        var key = $('#kredit').val();
                        key = key.split(',').join('');
                        
                        if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
                            $('#kredit').val('');
                        }
                        else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                            key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#kredit').val(key);
                        }else{
                            $('#kredit').val('');
                        }

                    });


                    $("#tunai").keyup(function(e) {
                        var key = $('#tunai').val();
                        key = key.split(',').join('');
                        
                        if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
                            $('#tunai').val('');
                        }
                        else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                            key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#tunai').val(key);
                        }else{
                            $('#tunai').val('');
                        }

                    });

                }

            );

        //button tambah bebas
        function tambah_bebas()
        {
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Penjualan'); // Set Title to Bootstrap modal title

            $('#resultProduk').html('');
        }
        
        //button total tagihan
        function total_tagihan() {

            $('#form2')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form2').modal('show'); // show bootstrap modal
            $('.modal-title').text('Bayar');

            $('#div_non_tunai_debit').attr("hidden", true);
            $('#btnTambahDebit').attr("hidden", false);
        
            $('#div_non_tunai_kredit').attr("hidden", true);
            $('#btnTambahKredit').attr("hidden", false);


            $('#btnBayar').attr('disabled', false);

            var myTable = document.getElementById('table').tBodies[0];
            var baris = myTable.rows.length;

            total = 0;

            $('#total').val(total);

            for (var r = 0; r < baris; r++) {
                total += parseInt(myTable.rows[r].cells[4].innerHTML.split(',').join(''));
            }

            total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $('#total').val(total);
            $('#total').attr('disabled', true);
            $('#kembalian').val('0');
            $('#kembalian').attr('disabled', true);
        }

        //button tambah resep
        function tambah_resep(jenis)
        {
            
            index_detail_produk = 0;

            $('#form3')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form3').modal('show'); // show bootstrap modal
            $('#resultPasienResep').html('');
            $('#resultDokterResep').html('');
            $('#table3 > tbody').html('');
            $('#bungkus').html('');

            if(jenis == "racikan"){
                $('.modal-title').text('Tambah Resep Racikan'); // Set Title to Bootstrap modal title
                $('#bungkus').attr('disabled', false);
                $('#jumlahBungkus').attr('disabled', false);
                $('#btnSaveResep').attr('onclick', 'simpan_resep("racikan")');
                
                $.ajax({
                    url : "<?php echo site_url('pengaturan/mengambilPengaturanBungkus')?>",
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) 
                    {   
                        indeks_bungkus = 0;
                        var i = 0;
                        var bungkus = data.keterangan.split(', ');
                        var output = '';
                        indeks_bungkus = bungkus.length;

                        for(i = 0; i < bungkus.length; i++){
                            var temp_bungkus = bungkus[i].split(' harga ');
                            output += '<option value="'+temp_bungkus[0]+'">'+bungkus[i]+'</option>';
                        }

                        $('#bungkus').append(output);
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }

                });

            }else{
                $('.modal-title').text('Tambah Resep Tunggal'); // Set Title to Bootstrap modal title
                $('#bungkus').attr('disabled', true);
                $('#jumlahBungkus').attr('disabled', true);
                $('#btnSaveResep').attr('onclick', 'simpan_resep("tunggal")');   
            }

            $('#table3 > tbody').html();

        }

        //button tambah tarik resep
        function tambah_tarik_resep(id_pasien, id_dokter){

            $('#form4')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form4').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Tarik Resep'); // Set Title to Bootstrap modal title
            $('#resultPasienTarikResep').html('');
            $('#resultDokterTarikResep').html('');
            
            $('#table4 > tbody').html('');
            $('#table5 > tbody').html('');
        }

        //cari untuk tarik resep
        function cari_resep(){
            var pasien = $('#searchPasienTarikResep').val().split('|');
            var dokter = $('#searchDokterTarikResep').val().split('|');
            var nomor_resep = $('#nomorTarikResep').val();

            $('#table4 > tbody').html('');
            $('#table5 > tbody').html('');

            $.getJSON("<?php echo site_url('penjualan/ajax_get_by_id_pasien_id_dokter_nomor_resep_to_detail')?>/"+pasien[0]+'/'+dokter[0]+'/'+nomor_resep,
                function (data) {
                    $.each(data, function (key, value) {           
                    $('#table4 > tbody').append("<tr><td>Id Resep</td><td id='idResep'>"+value.id_resep+"</td></tr><tr><td>Jenis</td><td id='jenisTarik'>"+value.jenis+"</td></tr><tr><td>Nomor Resep</td><td id='nomorTarikResep'>"+value.nomor+"</td></tr><tr><td>Tarik</td><td>"+value.tarik+"</td></tr><tr><td>Bungkus</td><td id='bungusTarikResep'>"+value.bungkus+"</td></tr><tr><td>Jumlah Bungkus</td><td id='jumlahBungkusTarikResep'>"+value.jumlah_bungkus+"</td></tr><tr><td>Cara Minum</td><td id='caraMinumTarikResep'>"+value.cara_minum+"</td></tr><tr><td>Harga Resep</td><td id='hargaTarikResep'>"+value.harga+"</td></tr><tr><td>Tanggal Terakhir Tarik Resep</td><td>"+value.tanggal+"</td></tr>");
                })
            })

            $.getJSON("<?php echo site_url('penjualan/ajax_get_by_id_pasien_id_dokter_nomor_resep_to_produk')?>/"+pasien[0]+'/'+dokter[0]+'/'+nomor_resep,
                function (data) {
                    $.each(data, function (key, value) {           
                    $('#table5 > tbody').append("<tr><td>"+value.id_produk+"</td><td>"+value.nama_produk+"</td><td>"+value.jumlah+"</td></tr>");
                })
            })

        }

        //simpan produk detail per resep
        function simpan_produk_pada_resep(){
            var produk = $('#searchProdukResep').val();
            var temp = produk.split('|');
            var jumlah = $('#jumlahResep').val();

            if(produk == "" || (jumlah == "" || jumlah == "0")){
                alert("produk / jumlah tidak valid");
            }else{
                var output = '<tr id="produk_resep'+index_detail_produk+'"><td>'+temp[0]+'</td><td>'+temp[1]+'</td><td>'+temp[4]+'</td><td>'+jumlah+'</td><td><a class="btn btn-sm btn-danger" onclick="hapus_produk_pada_resep('+index_detail_produk+')"><i class="glyphicon glyphicon-trash"></i> Delete</a></td></tr>';

                index_detail_produk += 1;
                $('#table3 > tbody').append(output);
                $('#searchProdukResep').val('');
                $('#jumlahResep').val('');
            }
        }

        //hapus produk detail per resep
        function hapus_produk_pada_resep(index){
            var parse = '#produk_resep'+index;
            $(parse).remove();
        }

        //simpan penjualan resep racikan/tunggal
        function simpan_resep(jenis){
            
            var myTable = document.getElementById('table3').tBodies[0];
            var kolom = document.getElementById('table3').rows[0].cells.length;
            var baris = myTable.rows.length;

            var harga = 0;
            var ongkos = 0;
            var bungkus = "";

            index_detail_produk_resep_tunggal = 0;

            for (var r = 0; r < baris; r++) {
                detail_resep.produk_jumlah[r] = new Array();
                detail_resep.produk_jumlah[r][0] = myTable.rows[r].cells[0].innerHTML;
                detail_resep.produk_jumlah[r][1] = myTable.rows[r].cells[1].innerHTML;
                detail_resep.produk_jumlah[r][2] = myTable.rows[r].cells[2].innerHTML;
                detail_resep.produk_jumlah[r][3] = myTable.rows[r].cells[3].innerHTML;

                harga += parseInt(myTable.rows[r].cells[2].innerHTML);
            }
            
            if(jenis == "racikan"){
                detail_resep.jenis = "racikan";            
                detail_resep.bungkus = $('#bungkus').val();
                console.log(detail_resep.bungkus);
                detail_resep.jumlah_bungkus = parseInt($('#jumlahBungkus').val());

                bungkus = $('#bungkus').html().split(' harga ');

                harga += ( parseInt(detail_resep.jumlah_bungkus) * parseInt(bungkus[1]) );
            }else{
                detail_resep.jenis = "tunggal";            
                detail_resep.bungkus = "";
                detail_resep.jumlah_bungkus = 0;
            }

            if(harga > 15000){
                ongkos = 6000;
            }else{
                ongkos = 3500;
            }

            harga += ongkos;

            detail_resep.cara_minum = $('#caraMinum').val();
            detail_resep.nomor = $('#nomorResep').val();

            pasien = $('#searchPasienResep').val();
            dokter =$('#searchDokterResep').val();

            detail_resep.id_pasien = pasien[0];
            detail_resep.id_dokter = dokter[0];

            detail_resep.harga = harga;

            $('#table > tbody').append(
                        '<tr id="penjualan_resep' + index_penjualan_resep + '"><td>resep ' + index_penjualan_resep + '</td><td> ' + detail_resep.jenis + '</td><td>' + detail_resep.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td>' + 1 + '</td><td>' + detail_resep.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td><button type="button" id="btn_hapus" onclick="hapus_penjualan_resep(' + index_penjualan_resep + ')" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> Hapus</button><button type="button" id="btn_detail" onclick="detail_penjualan_resep(' + index_penjualan_resep + ')" class="btn btn-info"><i class="glyphicon glyphicon-eye-open"></i> Detail</button></td></tr>'
                        );

            resep[index_penjualan_resep] = detail_resep;    
            index_penjualan_resep += 1;

            $('#modal_form3').modal("hide");
            $('#table3 > tbody').html('');

            detail_resep = {jenis:"", bungkus:"", jumlah_bungkus:0, cara_minum:"", nomor:"", id_pasien:"", id_dokter:"", harga:0, produk_jumlah:new Array()};
        }

        //simpan penjualan tarik resep
        function simpan_tarik_resep(){

            var myTable = document.getElementById('table4').tBodies[0];

            var id_resep = myTable.rows[0].cells[1].innerHTML;
            var harga = myTable.rows[7].cells[1].innerHTML;     
            var jenis = myTable.rows[1].cells[1].innerHTML;

            detail_tarik_resep.id_resep = id_resep;
            detail_tarik_resep.harga = parseInt(harga);    

            $('#table > tbody').append(
                        '<tr id="penjualan_tarik_resep' + index_penjualan_tarik_resep + '"><td>tarik resep ' + id_resep + '</td><td>' + jenis + '</td><td>' + detail_tarik_resep.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td>' + 1 + '</td><td>' + detail_tarik_resep.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td><button type="button" id="btn_hapus" onclick="hapus_penjualan_tarik_resep(' + index_penjualan_tarik_resep + ')" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> Hapus</button></td></tr>'
                        );
            

            tarik_resep[index_penjualan_tarik_resep] = detail_tarik_resep;  
            index_penjualan_tarik_resep += 1;

            $('#modal_form4').modal("hide");

            detail_tarik_resep = {id_resep:"0", harga:0};
        }

        //simpan penjualan bebas
        function simpan_bebas() {

            var produk = $('#searchProduk').val();
            var jumlah = $('#jumlah').val();

            if (jumlah == '') {
                alert('jumlah terlalu mustahil ');
            } else {
                var nama = produk.split('|');
                var harga = parseInt(jumlah) * parseInt(nama[4]);

                detail_bebas.id_produk = nama[0];
                detail_bebas.jumlah = parseInt(jumlah);
                detail_bebas.harga = harga;

                $('#table > tbody').append(
                        '<tr id="penjualan_bebas' + index_penjualan_bebas + '"><td>bebas ' + index_penjualan_bebas + '</td><td>' + nama[1] + '</td><td>' + nama[4].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td>' + jumlah + '</td><td>' + harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td><td><button type="button" id="btn_hapus" onclick="hapus_penjualan_bebas(' + index_penjualan_bebas + ')" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> Hapus</button></td></tr>'
                        );

                $('#modal_form').modal('hide');

                bebas[index_penjualan_bebas] = detail_bebas;
                index_penjualan_bebas += 1;

                detail_bebas = {id_produk:"0", jumlah:0, harga:0};
            }

        }

        //hapus penjualan bebas
        function hapus_penjualan_bebas(index) {
            var parse = '#penjualan_bebas'+index;
            var index_bebas = $(parse+' td:nth-child(1)').html().split(' ');
            
            $(parse).remove();

            bebas[index_bebas[1]] = {id_produk:"0", jumlah:0, harga:0};
        }


        //hapus penjualan tarik resep
        function hapus_penjualan_tarik_resep(index) {
            var parse = '#penjualan_tarik_resep'+index;
            var index_tarik_resep = $(parse+' td:nth-child(1)').html().split(' ');
            
            $(parse).remove();

            tarik_resep[index_tarik_resep[2]] = {id_resep:"0", harga:0};
        }


        //hapus penjualan resep
        function hapus_penjualan_resep(index) {
            var parse = '#penjualan_resep'+index;
            var index_resep = $(parse+' td:nth-child(1)').html().split(' ');
            
            $(parse).remove();

            resep[index_resep[1]] = {jenis:"", bungkus:"", jumlah_bungkus:0, cara_minum:"", nomor:"", id_pasien:"", id_dokter:"", produk_jumlah:new Array()};
        }

        function detail_penjualan_resep(index){

            $('#table6 > tbody').html('');
            $('#table7 > tbody').html('');

            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form5').modal('show'); // show bootstrap modal
            $('.modal-title').text('Detail Resep'); // Set Title to Bootstrap modal title

            var value = resep[index];

            var output = "<tr><td>Jenis</td><td id='jenisTarik'>"+value.jenis+"</td></tr><tr><td>Nomor Resep</td><td id='nomorTarikResep'>"+value.nomor+"</td></tr><tr><td>Bungkus</td><td id='bungusTarikResep'>"+value.bungkus+"</td></tr><tr><td>Jumlah Bungkus</td><td id='jumlahBungkusTarikResep'>"+value.jumlah_bungkus+"</td></tr><tr><td>Cara Minum</td><td id='caraMinumTarikResep'>"+value.cara_minum+"</td></tr><tr><td>Harga Resep</td><td id='hargaTarikResep'>"+value.harga+"</td></tr>";

            $('#table6 > tbody').append(output);

            output = "";
            for(i = 0; i < value.produk_jumlah.length; i++){
                output += '<tr><td>'+value.produk_jumlah[i][0]+'</td><td>'+value.produk_jumlah[i][1]+'</td><td>'+value.produk_jumlah[i][2].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+value.produk_jumlah[i][3]+'</td></tr>';
            }

            $('#table7 > tbody').append(output);
        }

        //tambah form not tunai
        function tambah_non_tunai(jenis) {
            if(jenis == 'debit'){
                $('#div_non_tunai_debit').attr("hidden", false);
                $('#btnTambahDebit').attr("hidden", true);
            }else{
                $('#div_non_tunai_kredit').attr("hidden", false);
                $('#btnTambahKredit').attr("hidden", true);
            }
        }

        //button bayar
        function bayar() {

            var url = "<?php echo site_url('penjualan/tambahPenjualan')?>";
            var url_nota = "<?php echo site_url('receipt_print/printNota')?>";

            var myTable = document.getElementById('table').tBodies[0];
            var baris = myTable.rows.length;

            var tunai = new Array();
            var debit = new Array();
            var kredit = new Array();

            var data_nota = new Array();

            var status_tunai = true;
            var total_tagihan = 0; 
            var total_bayar = 0;
            var panjang_nota = baris;

            tunai = $('#tunai').val().split(',').join('');
            
            debit[0] = $('#debit').val().split(',').join('');
            debit[1] = $('#nomorTransaksiDebit').val();
            debit[2] = $('#kartuDebit').val();
            debit[3] = $('#nomorKartuDebit').val();

            kredit[0] = $('#kredit').val().split(',').join('');
            kredit[1] = $('#nomorTransaksiKredit').val();
            kredit[2] = $('#kartuKredit').val();
            kredit[3] = $('#nomorKartuKredit').val();

            for(var i = 0; i < panjang_nota; i++){
                data_nota[i] = new Array();
                data_nota[i][0] = myTable.rows[i].cells[0].innerHTML;
                data_nota[i][1] = myTable.rows[i].cells[1].innerHTML;
                data_nota[i][2] = myTable.rows[i].cells[2].innerHTML;
                data_nota[i][3] = myTable.rows[i].cells[3].innerHTML;
                data_nota[i][4] = myTable.rows[i].cells[4].innerHTML;
            }

            if(debit[0] != ""){
                total_bayar += parseInt(debit[0]);
                status_tunai = false;
            } 

            if(kredit[0] != ""){
                total_bayar += parseInt(kredit[0]);
                status_tunai = false; 
            }

            if(tunai != ""){
                total_bayar += parseInt(tunai);
            }
            
            temp = $('#total').val().split(',').join('');
            total_tagihan = parseInt(temp);

            var kembalian = total_bayar - total_tagihan;

            if(status_tunai == true){
                tunai = total_tagihan;
            }
            
            if(total_bayar == "" || kembalian < 0){
                alert("tidak ada masukan / uang kurang");
            }else{

                $.ajax({
                    url: url_nota,
                    type: "POST",
                    data: { data_nota: data_nota, total_tagihan: total_tagihan, kembalian: kembalian },
                    dataType: "JSON",
                    success: function (data)
                    {
                        
                    }
                });


                $.ajax({
                    url: url,
                    type: "POST",
                    data: {resep: resep, bebas: bebas, tarik_resep: tarik_resep, tunai: tunai, debit: debit, kredit: kredit},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status) //if success close modal and reload ajax table
                        {
                            kembalian = kembalian.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#kembalian').val(kembalian);
                            $('#kembalian').attr('disabled', true);
                            $('#table > tbody').html('');
                        }

                        $('#btnSave').text('simpan'); //change button text
                        $('#btnSave').attr('disabled', false); //set button enable

                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error adding / update data');
                        $('#btnSave').text('simpan'); //change button text
                        $('#btnSave').attr('disabled', false); //set button enable

                    }
                });

                $('#btnBayar').attr('disabled', true);
            }

        }

</script>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_stok_opname"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Produk</label>
                            <div class="col-md-9">
                                <input type="text" name="id_produk" id="searchProduk" class="form-control"/>
                                <ul class="list-group" id="resultProduk">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-9">
                                <input type="text" name="jumlah" id="jumlah" class="form-control"/>
                                <ul class="list-group" id="resultDistributor">
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan_bebas()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bayar Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Total</label>
                            <div class="col-md-9">
                                <input type="text" name="total" id="total" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tunai (Rp.)</label>
                            <div class="col-md-9">
                                <input type="text" name="tunai" id="tunai" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group" id="div_non_tunai_debit" hidden="true">
                            <label class="control-label col-md-3">Debit (Rp.)</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="debit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-1">No</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="nomorTransaksiDebit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-3">Bank</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="kartuDebit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-1">No</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="nomorKartuDebit" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group" id="div_non_tunai_kredit" hidden="true">
                            <label class="control-label col-md-3">Kredit (Rp.)</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="kredit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-1">No</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="nomorTransaksiKredit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-3">Bank</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="kartuKredit" class="form-control"/>
                            </div>
                            <label class="control-label col-md-1">No</label>
                            <div class="col-md-4">
                                <input type="text" name="tunai" id="nomorKartuKredit" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group" id="btnTambahNonTunai">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <center>
                                    <button type="button" id="btnTambahDebit" onclick="tambah_non_tunai('debit')" class="btn btn-primary">Tambah Debit</button>
                                    <button type="button" id="btnTambahKredit" onclick="tambah_non_tunai('kredit')" class="btn btn-primary">Tambah Kredit</button>
                                </center>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Disc. (%)</label>
                            <div class="col-md-9">
                                <input type="text" name="tunai" id="diskon" vale="0" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bunga (%)</label>
                            <div class="col-md-9">
                                <input type="text" name="tunai" id="bunga" vale="0" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Kembalian</label>
                            <div class="col-md-9">
                                <input type="text" name="kembalian" id="kembalian" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnBayar" onclick="bayar()" class="btn btn-primary">Bayar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Resep Form</h3>
            </div>

            <div class="modal-body form">
                <form action="#" id="form3" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Pasien</label>
                            <div class="col-md-9">
                                <input placeholder="pasien baru silahkan tambah pada menu pasien"  type="text" name="id_pasien" id="searchPasienResep" class="form-control"/>
                                <ul class="list-group" id="resultPasienResep">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dokter</label>
                            <div class="col-md-9">
                                <input placeholder="dokter baru silahkan tambah pada menu dokter" type="text" name="id_dokter" id="searchDokterResep" class="form-control"/>
                                <ul class="list-group" id="resultDokterResep">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cara Minum</label>
                            <div class="col-md-9">
                                <input type="text" name="cara_minum" id="caraMinum" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bungkus</label>
                            <div class="col-md-9">
                                <select name="bungkus" id="bungkus" class="form-control">
                                </select>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah Bungkus</label>
                            <div class="col-md-9">
                                <input type="text" name="jumlah_bungkus" id="jumlahBungkus" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nomor Resep</label>
                            <div class="col-md-9">
                                <input type="text" name="nomor_resep" id="nomorResep" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Produk</label>
                            <div class="col-md-9">
                                <input type="text" name="id_produk" id="searchProdukResep" class="form-control"/>
                                <ul class="list-group" id="resultProdukResep">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-9">
                                <input type="text" name="jumlah_resep" id="jumlahResep" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="form-horizontal">
                    <center>
                        <button type="button" id="btnSaveProdukResep" onclick="simpan_produk_pada_resep()" class="btn btn-primary">Simpan Produk</button>
                    </center>
                </div>

                </br>
                <center>
                    <table id="table3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Id Produk</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </center>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnSaveResep" onclick="" class="btn btn-primary">Simpan Resep</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<div class="modal fade" id="modal_form4" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tarik Resep Form</h3>
            </div>

            <div class="modal-body form">
                <form action="#" id="form4" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Pasien</label>
                            <div class="col-md-9">
                                <input placeholder="pastikan pasien sudah pernah tarik resep" type="text" name="id_pasien" id="searchPasienTarikResep" class="form-control"/>
                                <ul class="list-group" id="resultPasienTarikResep">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dokter</label>
                            <div class="col-md-9">
                                <input placeholder="pastikan dokter sudah benar" type="text" name="id_dokter" id="searchDokterTarikResep" class="form-control"/>
                                <ul class="list-group" id="resultDokterTarikResep">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nomor Copy Resep</label>
                            <div class="col-md-9">
                                <input type="text" name="id_dokter" id="nomorTarikResep" class="form-control"/>
                                <ul class="list-group" id="nomorTarikResep">
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="form-horizontal">
                    <center>
                        <button type="button" id="btnSaveProdukResep" onclick="cari_resep()" class="btn btn-primary">Cari Resep</button>
                    </center>
                </div>

                </br>
                <center>
                    <table id="table4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </center>

                </br>
                <center>
                    <table id="table5" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Id Produk</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </center>

            </div>
            <div class="modal-footer">
                <button type="button" id="btnTarikResep" onclick="simpan_tarik_resep()" class="btn btn-primary">Tarik Resep</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form5" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Resep Form</h3>
            </div>

            <div class="modal-body form">

                <center>
                    <table id="table6" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </center>

                </br>
                <center>
                    <table id="table7" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Id Produk</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </center>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
