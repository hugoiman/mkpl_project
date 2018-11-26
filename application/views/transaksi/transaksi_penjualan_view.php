

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Transaksi Penjualan</h1>
            </center>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Jenis</th>
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
                      <th>Jenis</th>
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
            "url": "<?php echo site_url('transaksi/mengambilTransaksiPenjualan')?>",
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function setujui_transaksi(id, setujui){

  if(confirm('Apakah anda yakin untuk '+setujui+' ?'))
  {
      // ajax delete data to database
      $.ajax({
          url : "<?php echo site_url('transaksi/setujuiTransaksi')?>/"+id+"/"+setujui,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
              if(data.status == false){
                alert("Bukan Hak Akses");
              }

              //if success reload ajax table
              $('#modal_form').modal('hide');
              reload_table();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error database');
          }
      });

    }
  }

  function detail_transaksi(id){

    $('#detail > tbody').html('');
    $.getJSON("<?php echo site_url('transaksi/detailTransaksiPenjualan')?>/"+id,
        function (data) {
            console.log(data);
            $.each(data, function (key, value) {           
            $('#detail > tbody').append("<tr><td>"+value.nama_produk+"</td><td>"+value.jumlah+"</td></tr>");
        })
    })

    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Detail Penjualan'); // Set Title to Bootstrap modal title

  }

  function uang_format(uang){
    var panjang_uang = uang.length;
    var format_uang = "";
    var iterasi = 0;

    if(panjang_uang > 3){
      for(var i = panjang_uang-1; i >= 0; i--){
        if(iterasi % 3 == 0 && i != panjang_uang-1){
          format_uang = uang[i] + ',' + format_uang;
        }else{
          format_uang = uang[i] + format_uang;
        }
        iterasi++;
      }
    }


    return format_uang;
  }


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Judul</h3>
            </div>
            <div class="modal-body form">
                <table id="detail" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                </tr>
                </tfoot>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
