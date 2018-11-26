

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Permintan Produk</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_permintaan_produk()"><i class="glyphicon glyphicon-plus"></i> Tambah Permintaan Produk</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Perkiraan Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Persetujuan</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Perkiraan Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Persetujuan</th>
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
            "url": "<?php echo site_url('permintaan_produk/mengambilPermintaanProduk')?>",
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

    $('#searchProduk').keyup(
      function(){
        $('#resultProduk').html('');
        var searchField = $('#searchProduk').val();
        var expression = new RegExp(searchField, "i");

        $.getJSON( "<?php echo site_url('permintaan_produk/ajax_get_by_nama')?>/"+searchField,
        function(data) {
          $.each(data, function(key, value){
            if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
            {
               $('#resultProduk').append('<li class="list-group-item link-class">'+value.nama+'</li>');
            }
          });
        });
      });

      $('#resultProduk').on('click', 'li', function() {
        var click_text = $(this).text();
        $('#searchProduk').val(click_text);
        $("#resultProduk").html('');
      });

      $("#perkiraan_harga").keyup(function(e) {
            var key = $('#perkiraan_harga').val();
            key = key.split(',').join('');

            if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
                $('#perkiraan_harga').val('');
            }
            else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#perkiraan_harga').val(key);
            }else{
                $('#perkiraan_harga').val('');
            }

        });

});

function tambah_permintaan_produk()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Permintaan Produk'); // Set Title to Bootstrap modal title
}

function ubah_permintaan_produk(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('permintaan_produk/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_permintaan_produk"]').val(data.id_permintaan_produk);
            $('[name="nama"]').val(data.nama);
            $('[name="jumlah"]').val(data.jumlah);
            data.perkiraan_harga = data.perkiraan_harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('[name="perkiraan_harga"]').val(data.perkiraan_harga);
            $('[name="status"]').val(data.status);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

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

    var perkiraan_harga = $('#perkiraan_harga').val();
    perkiraan_harga = perkiraan_harga.split(',').join('');
    $('#perkiraan_harga').val(perkiraan_harga);

    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('permintaan_produk/tambahPermintaanProduk')?>";
    } else {
        url = "<?php echo site_url('permintaan_produk/ubahPermintaanProduk')?>";
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
                alert("sudah diubah");
            }else{
                alert("gagal diubah / error database");
            }

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {   

            alert('Error adding / update data '+textStatus);
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}


function setujui_permintaan_produk(id, setujui){

  if(confirm('Apakah anda yakin untuk '+setujui+' ?'))
  {
      // ajax delete data to database
      $.ajax({
          url : "<?php echo site_url('permintaan_produk/setujuiPermintaanProduk')?>/"+id+"/"+setujui,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              $('#modal_form').modal('hide');
              reload_table();
            }else{
              alert('harus supervisor');
            }
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
                    <input type="hidden" value="" name="id_permintaan_produk"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Produk</label>
                            <div class="col-md-9">
                                <input type="text" name="nama" id="searchProduk" class="form-control"/>
                                <ul class="list-group" id="resultProduk">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-9">
                                <input name="jumlah" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Perkiraan Harga</label>
                            <div class="col-md-9">
                                <input name="perkiraan_harga" id="perkiraan_harga" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select name="status" class="form-control">
                                    <option value="">--Pilih Kategori--</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="biasa">Biasa</option>
                                    <option value="saran">Saran</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
