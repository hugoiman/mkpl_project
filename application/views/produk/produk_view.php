

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Produk</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_produk()"><i class="glyphicon glyphicon-plus"></i> Tambah Produk</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat Rak</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok Kritis</th>
                        <th>Laba ( % )</th>
                        <th>PPN ( % )</th>
                        <th>Pabrik</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Nama</th>
                    <th>Alamats Rak</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Stok Kritis</th>
                    <th>Laba ( % )</th>
                    <th>PPN ( % )</th>
                    <th>Pabrik</th>
                    <th style="width:125px;">Action</th>
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

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        "dom": '<"show"l><"search"f><"container">rt<"tombol"B><"container"><"info"i><"container"><"halaman"p>',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('produk/mengambilProduk')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });

});

function tambah_produk()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Produk'); // Set Title to Bootstrap modal title
    $('#kategori').html('');    
    $('#satuan').html('');    
    $('#pabrik').html('');

    //set kategori Produk
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanKategori')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            var i = 0;
            var kategori = data.keterangan.split(',');
            var output = '<option value="">----pilih kategori---</option>';

            for(i = 0; i < kategori.length; i++){
                output += '<option value="'+kategori[i]+'">'+kategori[i]+'</option>';
            }

            $('#kategori').append(output);
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
            var i = 0;
            var satuan = data.keterangan.split(',');
            var output = '<option value="">----pilih satuan---</option>';

            for(i = 0; i < satuan.length; i++){
                output += '<option value="'+satuan[i]+'">'+satuan[i]+'</option>';
            }

            $('#satuan').append(output);
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
            var i = 0;
            var pabrik = data.keterangan.split(',');
            var output = '<option value="">----pilih pabrik---</option>';

            for(i = 0; i < pabrik.length; i++){
                output += '<option value="'+pabrik[i].toUpperCase()+'">'+pabrik[i].toUpperCase()+'</option>';
            }
            $('#pabrik').append(output);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });

}

function ubah_produk(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#kategori').html('');    
    $('#satuan').html('');
    $('#pabrik').html('');

    //set kategori produk
    $.ajax({
        url : "<?php echo site_url('pengaturan/mengambilPengaturanKategori')?>",
        type: "GET",
        dataType: "JSON",
        success: function (data) 
        {   
            var i = 0;
            var kategori = data.keterangan.split(',');
            var output = '<option value="">----pilih kategori---</option>';

            for(i = 0; i < kategori.length; i++){
                output += '<option value="'+kategori[i]+'">'+kategori[i]+'</option>';
            }

            $('#kategori').append(output);
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
            var i = 0;
            var satuan = data.keterangan.split(',');
            var output = '<option value="">----pilih satuan---</option>';

            for(i = 0; i < satuan.length; i++){
                output += '<option value="'+satuan[i]+'">'+satuan[i]+'</option>';
            }

            $('#satuan').append(output);
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
            var i = 0;
            var pabrik = data.keterangan.split(',');
            var output = '<option value="">---- pilih pabrik ---</option>';

            for(i = 0; i < pabrik.length; i++){
                output += '<option value="'+pabrik[i].toUpperCase()+'">'+pabrik[i].toUpperCase()+'</option>';
            }
            $('#pabrik').append(output);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }

    });


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('produk/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_produk"]').val(data.id_produk);
            $('[name="nama"]').val(data.nama);
            $('[name="alamat_rak"]').val(data.alamat_rak);
            $('[name="kategori"]').val(data.kategori);
            $('[name="satuan"]').val(data.satuan);
            $('[name="stok_kritis"]').val(data.stok_kritis);
            $('[name="laba"]').val(data.laba);
            $('[name="ppn"]').val(data.ppn);
            $('[name="pabrik"]').val(data.pabrik);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Ubah Produk'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function simpan()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('produk/tambahProduk')?>";
    } else {
        url = "<?php echo site_url('produk/ubahProduk')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }

            $('#btnSave').text('simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function hapus_produk(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('produk/hapusProduk')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
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
                    <input type="hidden" value="" name="id_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <input name="nama" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat Rak</label>
                            <div class="col-md-9">
                                <input name="alamat_rak" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori</label>
                            <div class="col-md-9">
                                <select name="kategori" id="kategori" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Satuan</label>
                            <div class="col-md-9">
                                <select name="satuan" id="satuan" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Stok Kirits</label>
                            <div class="col-md-9">
                                <input type="text" name="stok_kritis" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Laba</label>
                            <div class="col-md-9">
                                <input type="text" name="laba" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">PPN</label>
                            <div class="col-md-9">
                                <input type="text" name="ppn" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pabrik</label>
                            <div class="col-md-9">
                                <select name="pabrik" id="pabrik" class="form-control">
                                </select>
                            </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
