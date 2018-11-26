

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Tagihan</h1>
            </center>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kode Produksi</th>
                        <th>Distributor</th>
                        <th>Tagihan</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Tanggal Pelunasan</th>
                        <th>Waktu Pelunasan</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Produk</th>
                    <th>Kode Produksi</th>
                    <th>Distributor</th>
                    <th>Tagihan</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Tanggal Pelunasan</th>
                    <th>Waktu Pelunasan</th>
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
var id;

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
            "url": "<?php echo site_url('tagihan/mengambilTagihan')?>",
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

        $.getJSON( "<?php echo site_url('produk/ajax_get_by_nama')?>/"+searchField,
        function(data) {
          $.each(data, function(key, value){
            if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
            {
               $('#resultProduk').append('<li class="list-group-item link-class">'+value.id_produk+' | <span class="text-muted">'+value.nama+'</span></li>');
            }
          });
        });
      });

      $('#resultProduk').on('click', 'li', function() {
        var click_text = $(this).text();
        $('#searchProduk').val(click_text);
        $("#resultProduk").html('');
      });

      $("#jumlahTransfer").keyup(function(e) {
        var key = $('#jumlahTransfer').val();
        key = key.split(',').join('');
        
        if(key == "0" && (e.keyCode == 48 || e.keyCode == 96)){
            $('#jumlahTransfer').val('');
        }
        else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
            key = key.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#jumlahTransfer').val(key);
        }else{
            $('#jumlahTransfer').val('');
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

});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

//button total tagihan
function total_tagihan(temp_id) {

    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Bayar Tagihan');

    $('#divTransfer').attr("hidden", true);
    $('#btnTambahTransfer').attr("hidden", false);

    $('#btnBayar').attr('disabled', false);
    var total = '0';

    $.ajax({
            url : "<?php echo site_url('tagihan/ajax_get_by_id')?>/"+temp_id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                total = data.tagihan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#total').val(total);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error deleting data');
            }
        });

    $('#total').attr('disabled', true);
    id = temp_id;
}

function tambah_transfer() {
    $('#divTransfer').attr("hidden", false);
    $('#btnTambahTransfer').attr("hidden", true);
}

function bayar(){

    if(confirm('Apakah anda yakin untuk bayar tagihan ?'))
    {
        var tunai = "";
        var transfer = new Array();

        tunai = $('#tunai').val().split(',').join('');

        transfer[0] = $('#jumlahTransfer').val().split(',').join('');
        transfer[1] = $('#bankLawanTransaksi').val();
        transfer[2] = $('#nomorRekeningBankLawanTransaksi').val();

        $.ajax({
            url : "<?php echo site_url('tagihan/bayarTagihan')?>/"+id,
            type: "POST",
            data: {tunai: tunai, transfer: transfer},
            dataType: "JSON",
            success: function(data)
            {
            //if success reload ajax table
            $('#modal_form').modal('hide');
            reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error ketika membayar tagihan');
            }
        });

    }

}

</script>
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bayar Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
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
                        <div id="divTransfer" hidden="true">
                            <div class="form-group">
                                <label class="control-label col-md-3">Jumlah Transfer (Rp.)</label>
                                <div class="col-md-9">
                                    <input type="text" name="tunai" id="jumlahTransfer" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Bank</label>
                                <div class="col-md-9">
                                    <input type="text" name="tunai" id="bankLawanTransaksi" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">No. Rekenening</label>
                                <div class="col-md-9">
                                    <input type="text" name="tunai" id="nomorRekeningBankLawanTransaksi" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="btnTambahNonTunai">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <center>
                                    <button type="button" id="btnTambahTransfer" onclick="tambah_transfer()" class="btn btn-primary">Tambah Transfer</button>
                                </center>
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

<!-- End Bootstrap modal -->
</body>
</html>
