

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Pengaturan</h1>
            </center>
           <div class="modal-body form">
                <div class="form-horizontal">
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Bungkus</label>
                            <div class="col-md-6">
                                <select name="bungkus" id="bungkus" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnEdit" onclick="edit('bungkus')"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori Produk</label>
                            <div class="col-md-6">
                                <select name="kategori_produk" id="kategori_produk" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnEdit" onclick="edit('produk')"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori Dokter</label>
                            <div class="col-md-6">
                                <select name="kategori_dokter" id="kategori_dokter" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnEdit" onclick="edit('dokter')"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Satuan</label>
                            <div class="col-md-6">
                                <select name="satuan" id="satuan" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnEdit" onclick="edit('satuan')"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pabrik</label>
                            <div class="col-md-6">
                                <select name="pabrik" id="pabrik" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnEdit" onclick="edit('pabrik')"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Catatan Struk</label>
                            <div class="col-md-6">
                                <textarea name="catatan_struk" id="catatan_struk" class="form-control"></textarea>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" id="btnSave" onclick="simpan('catatan_struk')"><i class="glyphicon glyphicon-save"></i> Save</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Ip Printer</label>
                            <div class="col-md-6">
                                <input name="ip_printer" id="ip_printer" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnSave" onclick="simpan('print')"><i class="glyphicon glyphicon-save"></i> Save</button>
                                <button class="btn btn-success" id="btnSave" onclick="test_print()"><i class="glyphicon glyphicon-upload"></i> Test</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </body>



<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script src="<?php echo base_url('assets/datatables/js/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/jszip.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/vfs_fonts.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.print.min.js')?>"></script>


<script type="text/javascript">
var indeks_bungkus = 0;
var indeks_kategori = 0;
var indeks_dokter = 0;
var indeks_satuan = 0;
var indeks_pabrik = 0;

$(document).ready(function() {

    //set bungkus
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanBungkus')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            indeks_bungkus = 0;
            var i = 0;
            var bungkus = data.keterangan.split(',');
            var output = '';
            indeks_bungkus = bungkus.length;

            for(i = 0; i < bungkus.length; i++){
                output += '<option>'+bungkus[i]+'</option>';
            }

            $('#bungkus').append(output);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set kategori produk
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanKategori')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            indeks_kategori = 0;
            var i = 0;
            var kategori = data.keterangan.split(',');
            var output = '';
            indeks_kategori = kategori.length;

            for(i = 0; i < kategori.length; i++){
                output += '<option>'+kategori[i]+'</option>';
            }

            $('#kategori_produk').append(output);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set kategori dokter
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanDokter')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            indeks_dokter = 0;
            var i = 0;
            var dokter = data.keterangan.split(',');
            var output = '';
            indeks_dokter = dokter.length;

            for(i = 0; i < dokter.length; i++){
                output += '<option>'+dokter[i]+'</option>';
            }

            $('#kategori_dokter').append(output);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set kategori satuan
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanSatuan')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {
            indeks_satuan = 0;
            var i = 0;
            var satuan = data.keterangan.split(',');
            var output = '';
            indeks_satuan = satuan.length;

            for(i = 0; i < satuan.length; i++){
                output += '<option>'+satuan[i]+'</option>';
            }

            $('#satuan').append(output);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set kategori catatan struk
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanCatatanStruk')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {
            var i = 0;
            var catatan_struk = data.keterangan;

            $('#catatan_struk').val(catatan_struk);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set kategori ip printer
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanIpPrinter')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {
            var i = 0;
            var ip_printer = data.keterangan;
            $('#ip_printer').val(ip_printer);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

    //set pabrik
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanPabrik')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            indeks_pabrik = 0;
            var i = 0;
            var pabrik = data.keterangan.split(',');
            var output = '';
            indeks_pabrik = pabrik.length;

            for(i = 0; i < pabrik.length; i++){
                output += '<option>'+pabrik[i]+'</option>';
            }

            $('#pabrik').append(output);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });


});

function edit(sub){

    if(sub == 'bungkus'){
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Edit Bungkus'); // Set Title to Bootstrap modal title

        var isi = "";
        for(var i = 1; i <= indeks_bungkus; i++){
            var output = $('#bungkus option:nth-child('+i+')').val();
            if( i == indeks_bungkus ){
                isi += output; 
            }else{
                isi += output + ',\n';   
            }
        }

        $('#keterangan_bungkus').val(isi);

    }else if(sub == 'dokter'){

        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form2').modal('show'); // show bootstrap modal
        $('.modal-title').text('Edit Kategori Dokter'); // Set Title to Bootstrap modal title


        var isi = "";
        for(var i = 1; i <= indeks_dokter; i++){
            var output = $('#kategori_dokter option:nth-child('+i+')').val();
            if( i == indeks_dokter ){
                isi += output; 
            }else{
                isi += output + ',\n';   
            }
        }

        $('#keterangan_dokter').val(isi);

       
    }else if(sub == 'produk'){

        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form3').modal('show'); // show bootstrap modal
        $('.modal-title').text('Edit Kategori Produk'); // Set Title to Bootstrap modal title

        var isi = "";
        for(var i = 1; i <= indeks_kategori; i++){
            var output = $('#kategori_produk option:nth-child('+i+')').val();
            if( i == indeks_kategori ){
                isi += output; 
            }else{
                isi += output + ',\n';   
            }
        }


        $('#keterangan_produk').val(isi);
        
    }else if(sub == 'satuan'){

        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form4').modal('show'); // show bootstrap modal
        $('.modal-title').text('Edit satuan'); // Set Title to Bootstrap modal title

        var isi = "";
        for(var i = 1; i <= indeks_satuan; i++){
            var output = $('#satuan option:nth-child('+i+')').val();
            if( i == indeks_satuan ){
                isi += output; 
            }else{
                isi += output + ',\n';   
            }
        }


        $('#keterangan_satuan').val(isi);
        
    }else if(sub == 'pabrik'){

        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form5').modal('show'); // show bootstrap modal
        $('.modal-title').text('Edit satuan'); // Set Title to Bootstrap modal title

        var isi = "";
        for(var i = 1; i <= indeks_pabrik; i++){
            var output = $('#pabrik option:nth-child('+i+')').val();
            if( i == indeks_pabrik ){
                isi += output; 
            }else{
                isi += output + ',\n';   
            }
        }


        $('#keterangan_pabrik').val(isi);
        
    }
}

function simpan(sub_pengaturan){

    if(sub_pengaturan == 'bungkus'){
        var data = $('#keterangan_bungkus').val().replace(/\r?\n|\r/g, " ");
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanBungkus')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                $('#modal_form').modal('hide');
                location.reload();
            }
        });
    }else if(sub_pengaturan == 'dokter'){
        var data = $('#keterangan_dokter').val().replace(/\r?\n|\r/g, " ");
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanDokter')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                $('#modal_form2').modal('hide');
                location.reload();
            }
        });
    }else if(sub_pengaturan == 'satuan'){
        var data = $('#keterangan_satuan').val().replace(/\r?\n|\r/g, "");
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanSatuan')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                $('#modal_form4').modal('hide');
                location.reload();
            }
        });
    }else if(sub_pengaturan == 'produk'){
        var data = $('#keterangan_produk').val().replace(/\r?\n|\r/g, "");
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanKategori')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                $('#modal_form3').modal('hide');
                location.reload();
            }
        });
    }else if(sub_pengaturan == 'pabrik'){
        var data = $('#keterangan_pabrik').val().replace(/\r?\n|\r/g, "");
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanPabrik')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                $('#modal_form5').modal('hide');
                location.reload();
            }
        });
    }else if(sub_pengaturan == 'print'){
        var data = $('#ip_printer').val();
        $.ajax({
            url: "<?php echo site_url('pengaturan/ubahPengaturanIpPrinter')?>",
            data: {data: data}, 
            type: "POST",
            dataType: "JSON",
            success: function(html){
                alert('sudah tersimpan');
                location.reload();
            }
        }); 
    }
}

function test_print(){
    var ip_printer = $('#ip_printer').val();
    $.ajax({
            url: "<?php echo site_url('receipt_print/cobaPrint')?>", 
            type: "POST",
            dataType: "JSON",
            success: function(){
                
            }
        });
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_OP"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Bungkus Harga</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="keterangan_bungkus"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan('bungkus')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Dokter</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="keterangan_dokter"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan('dokter')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form3" class="form-horizontal">
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Produk</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="keterangan_produk"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan('produk')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form4" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Satuan Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form3" class="form-horizontal">
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Satuan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="keterangan_satuan"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan('satuan')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form5" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Pabrik Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form5" class="form-horizontal">
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Pabrik</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="keterangan_pabrik"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan('pabrik')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
