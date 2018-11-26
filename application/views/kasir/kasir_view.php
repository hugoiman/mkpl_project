

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Kasir</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_penutupan_kasir()"><i class="glyphicon glyphicon-plus"></i> Tambah Penutupan Kasir</button>
            <span></span>
            <button class="btn btn-success" onclick="tambah_pembukaan_kasir()"><i class="glyphicon glyphicon-plus"></i> Tambah Pembukaan Kasir</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-responsive table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>logam 100</th>
                        <th>logam 200</th>
                        <th>logam 500</th>
                        <th>logam 1000</th>
                        <th>kertas 1000</th>
                        <th>kertas 2000</th>
                        <th>kertas 5000</th>
                        <th>kertas 10000</th>
                        <th>kertas 20000</th>
                        <th>kertas 50000</th>
                        <th>kertas 100000</th>
                        <th>tanggal</th>
                        <th>waktu</th>
                        <th>persetujuan</th>
                        <th style="width:150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Jenis</th>
                    <th>logam 100</th>
                    <th>logam 200</th>
                    <th>logam 500</th>
                    <th>logam 1000</th>
                    <th>kertas 1000</th>
                    <th>kertas 2000</th>
                    <th>kertas 5000</th>
                    <th>kertas 10000</th>
                    <th>kertas 20000</th>
                    <th>kertas 50000</th>
                    <th>kertas 100000</th>
                    <th>tanggal</th>
                    <th>waktu</th>
                    <th>persetujuan</th>
                    <th style="width:150px;">Action</th>
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
            "url": "<?php echo site_url('kasir/mengambilkasir')?>",
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

function tambah_penutupan_kasir()
{
    save_method = 'add';
    jenis = 'penutupan';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Penutupan Kasir'); // Set Title to Bootstrap modal title
}

function tambah_pembukaan_kasir()
{
    save_method = 'add';
    jenis = 'pembukaan';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Pembukaan Kasir'); // Set Title to Bootstrap modal title
}

function ubah_kasir(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('kasir/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_kasir"]').val(data.id_kasir);
            $('[name="logam_100"]').val(data.logam_100);
            $('[name="logam_200"]').val(data.logam_200);
            $('[name="logam_500"]').val(data.logam_500);
            $('[name="logam_500"]').val(data.logam_500);
            $('[name="logam_1000"]').val(data.logam_1000);
            $('[name="kertas_1000"]').val(data.kertas_1000);
            $('[name="kertas_2000"]').val(data.kertas_2000);
            $('[name="kertas_5000"]').val(data.kertas_5000);
            $('[name="kertas_10000"]').val(data.kertas_10000);
            $('[name="kertas_20000"]').val(data.kertas_20000);
            $('[name="kertas_50000"]').val(data.kertas_50000);
            $('[name="kertas_100000"]').val(data.kertas_100000);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Ubah Distributor'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax'+textStatus+" - "+errorThrown);
        }
    });
}

function setujui_kasir(id, setujui){

  if(confirm('Apakah anda yakin untuk '+setujui+' ?'))
  {
      // ajax delete data to database
      $.ajax({
          url : "<?php echo site_url('kasir/setujuiKasir')?>/"+id+"/"+setujui,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
              //if success reload ajax table
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function simpan()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add' && jenis == 'penutupan') {
        url = "<?php echo site_url('kasir/tambahPenutupanKasir')?>";
    } else if ( save_method == 'add' && jenis == 'pembukaan') {
        url = "<?php echo site_url('kasir/tambahPembukaanKasir')?>";
    } else {
        url = "<?php echo site_url('kasir/ubahKasir')?>";
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

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Kasir</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_kasir"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Logam 100</label>
                            <div class="col-md-9">
                                <input name="logam_100" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Logam 200</label>
                            <div class="col-md-9">
                                <input name="logam_200" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Logam 500</label>
                            <div class="col-md-9">
                                <input name="logam_500" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Logam 1000</label>
                            <div class="col-md-9">
                                <input name="logam_1000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 1000</label>
                            <div class="col-md-9">
                                <input name="kertas_1000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 2000</label>
                            <div class="col-md-9">
                                <input name="kertas_2000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 5000</label>
                            <div class="col-md-9">
                                <input name="kertas_5000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 10000</label>
                            <div class="col-md-9">
                                <input name="kertas_10000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 20000</label>
                            <div class="col-md-9">
                                <input name="kertas_20000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 50000</label>
                            <div class="col-md-9">
                                <input name="kertas_50000" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kertas 100000</label>
                            <div class="col-md-9">
                                <input name="kertas_100000" class="form-control" type="text">
                                <span class="help-block"></span>
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
