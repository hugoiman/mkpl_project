

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Stok</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_stok()"><i class="glyphicon glyphicon-plus"></i> Tambah Stok</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Kode Produksi</th>
                        <th>Status</th>
                        <th>Nama Produk</th>
                        <th>Nama Distributor</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Tanggal Datang</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Kode Produksi</th>
                    <th>Status</th>
                    <th>Nama Produk</th>
                    <th>Nama Distributor</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Tanggal Datang</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Tanggal Jatuh Tempo</th>
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
            "url": "<?php echo site_url('stok/mengambilStok')?>",
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

    $.ajaxSetup({ cache: false });

    $('#searchProduk').keyup(
      function(){
        $('#resultProduk').html('');
        var searchField = $('#searchProduk').val();
        var expression = new RegExp(searchField, "i");

        $.getJSON( "<?php echo site_url('stok/ajax_get_by_nama')?>/"+searchField,
        function(data) {
          $.each(data, function(key, value){
            if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
            {
               $('#resultProduk').append('<li class="list-group-item link-class">'+value.id_produk+' | <span class="text-muted">'+value.nama+'</span> | <span class="text-muted">'+value.pabrik+'</span></li>');
            }
          });
        });
      });

      $('#resultProduk').on('click', 'li', function() {
        var click_text = $(this).text();
        $('#searchProduk').val(click_text);
        $("#resultProduk").html('');
      });

      $('#searchDistributor').keyup(
        function(){
          $('#resultDistributor').html('');
          var searchField = $('#searchDistributor').val();
          var expression = new RegExp(searchField, "i");

          $.getJSON( "<?php echo site_url('distributor/ajax_get_by_nama')?>/"+searchField,
          function(data) {
            $.each(data, function(key, value){
              if (value.id_distributor.search(expression) != -1 || value.nama.search(expression) != -1)
              {
                 $('#resultDistributor').append('<li class="list-group-item link-class">'+value.id_distributor+' | <span class="text-muted">'+value.nama+'</span></li>');
              }
            });
          });
        });

        $('#resultDistributor').on('click', 'li', function() {
          var click_text = $(this).text();
          $('#searchDistributor').val(click_text);
          $("#resultDistributor").html('');
        });

        $("#harga").keyup(function(e) {
            var key = $('#harga').val();
            key = key.split(',').join('');

            if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
                $('#harga').val('');
            }
            else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#harga').val(key);
            }else{
                $('#harga').val('');
            }
        }); 

});

function tambah_stok()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Stok'); // Set Title to Bootstrap modal title

}

function ubah_stok(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('stok/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_stok"]').val(data.id_stok);
            $('[name="kode_produksi"]').val(data.kode_produksi);
            $('[name="id_produk"]').val(data.id_produk+' | '+data.nama_produk+' | '+data.satuan+' | '+data.pabrik);
            $('[name="id_distributor"]').val(data.id_distributor+' | '+data.nama_distributor);
            $('[name="jumlah"]').val(data.jumlah);

            data.harga = data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('[name="harga"]').val(data.harga);
            $('[name="tanggal_datang"]').datepicker('update',data.tanggal_datang);
            $('[name="tanggal_kadaluarsa"]').datepicker('update',data.tanggal_kadaluarsa);
            $('[name="tanggal_jatuh_tempo"]').datepicker('update',data.tanggal_jatuh_tempo);
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
    
    var harga = $('#harga').val();
    harga = harga.split(',').join('');
    $('#harga').val(harga);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('stok/tambahStok')?>";
    } else {
        url = "<?php echo site_url('stok/ubahStok')?>";
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

function hapus_stok(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('stok/hapusStok')?>/"+id,
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
                    <input type="hidden" value="" name="id_stok"/>
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
                            <label class="control-label col-md-3">Kode Produksi</label>
                            <div class="col-md-9">
                                <input type="text" name="kode_produksi" class="form-control"/>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select name="status" id="status" class="form-control">
                                    <option value="BUKAN KONSINYASI">BUKAN KONSINYASI</option>
                                    <option value="KONSINYASI">KONSINYASI</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Distributor</label>
                            <div class="col-md-9">
                                <input type="text" name="id_distributor" id="searchDistributor" class="form-control"/>
                                <ul class="list-group" id="resultDistributor">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">jumlah</label>
                            <div class="col-md-9">
                                <input name="jumlah" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">harga</label>
                            <div class="col-md-9">
                                <input name="harga" id="harga" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Tanggal Datang</label>
                          <div class="col-md-9">
                              <input name="tanggal_datang" class="form-control datepicker" type="text">
                              <span class="help-block"></span>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Kadaluarsa</label>
                            <div class="col-md-9">
                                <input name="tanggal_kadaluarsa" class="form-control datepicker" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                              <label class="control-label col-md-3">Tanggal Jatuh Tempo</label>
                              <div class="col-md-9">
                                  <input name="tanggal_jatuh_tempo" class="form-control datepicker" type="text">
                              </div>
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
