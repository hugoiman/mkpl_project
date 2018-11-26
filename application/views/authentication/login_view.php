<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>">
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/jquery/jquery.md5.js')?>"></script>

<script>
    $(document).ready(
        function(){
            $('#username').keypress(function(e){
                if(e.keyCode == 13){
                    $>masuk();
                }
            });
            $('#password').keypress(function(e){
                if(e.keyCode == 13){
                    $>masuk();
                }
            });
            $('#show_password').on('click', function(){
                if($('#show_password').html() == "Show Password"){
                    $('#password').attr('type', 'text');
                    $('#show_password').html('Hide Password');
                }else{
                    $('#password').attr('type', 'password');
                    $('#show_password').html('Show Password');
                }
            });
    });

    function masuk(){
        var url = "<?php echo site_url('authentication/login')?>";
        var username = $('#username').val();
        var password = $.md5($('#password').val());
        var data = {"username" : username, "password" : password};

        $.ajax({
            url : url,
            type : "POST",
            dataType : "JSON",
            data : data,
            success : function (data)
            {   
                if(data.status){
                    window.location.href = "<?php echo site_url('authentication')?>";
                }else{
                    $('#pesan').html(data.pesan);
                }
            }
        });
    }

</script>

<div class="container" style="margin-top: 10%;">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-primary">
            <div class="panel-heading">LOGIN - A-poin</div>
            <div class="panel-body">

                <div class="row">
                    <div class="form-group col-xs-12">
                    <label for="username"><span class="text-danger" style="margin-right:5px;">*</span>Username :</label>
                        <div class="input-group">
                            <input class="form-control" id="username" type="text" name="username" placeholder="Username" required/>
                            <span class="input-group-btn">
                                <label class="btn btn-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></label>
                            </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Field -->
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label for="password"><span class="text-danger" style="margin-right:5px;">*</span>Password :</label>
                        <div class="input-group">
                            <input class="form-control" id="password" type="password" name="password" placeholder="Password" required/>
                            <span class="input-group-btn">
                                <label class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></label>
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <a href="#" id="show_password">Show Password</a>
                    </div>
                </div>

                <!-- Login Button -->
                <div class="row">
                    <div class="form-group col-xs-4">
                        <button class="btn btn-primary" id="login" type="submit" onclick="masuk()">GO!!!!</button>
                    </div>
                </div>
                <p id="pesan"></p>

            <!-- End of Login Form -->

        </div>
    </div>
</div>
