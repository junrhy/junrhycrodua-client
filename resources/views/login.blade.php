<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JRC Admin</title>

        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">

        <style type="text/css">
            html, body {
                overflow: hidden;
            }

            .login-form-section {
                background: #393c44;
                height: 100vh;
                color: #ffffff;
            }

            .brand {
                background: #000000;
                height: 10vh;
            }

            .col2-header {
                background: #e5e5e5;
                height: 10vh;
            }

            label {
                font-size: 14pt;
                margin-bottom: 5px;
            }

            input {
                height: 50px;
                font-size: 16pt;
                -webkit-border-radius: 0 !important;
                -moz-border-radius: 0 !important;
                border-radius: 0 !important;
            }

            .form-group {
                margin-left: 15px;
                margin-right: 15px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="row">
            <div class="col-md-6 login-form-section">
                <div class="row brand"></div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="col-md-10 offset-md-1">
                    <div class="form-group">
                        <h3 class="h3">Member Login</h3>
                        <input class="form-control input-lg" type="text" name="email" placeholder="Email Address">
                    </div>
                    <br>
                    <div class="form-group">
                        <input class="form-control input-lg" type="password" name="password" placeholder="Password">
                    </div>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-success btn-lg" id="btn-login">LOGIN</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row col2-header"></div>
                <br>
                <br>
                <br>
                <!-- <iframe width="98%" height="400" src="https://www.youtube.com/embed/_UORzUfKR2c?si=cLZ1q9wnjbmFNog5&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe> -->
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
$(document).ready(function(){

    $("#btn-login").click(function(){
        var has_error = false;

        if ($("input[name='email']").val() == '') {
            $("input[name='email']").css('border', '1px solid red');
            has_error = true;
        }

        if ($("input[name='password']").val() == '') {
            $("input[name='password']").css('border', '1px solid red');
            has_error = true;
        }

        if (has_error) { return false; }

        $.ajax({
            type: 'POST',   
            url: "/login",
            headers: {
                'Accept': 'application/json'
            },
            data:   
            {
                "_token": "{{ csrf_token() }}",
                "email":$("input[name='email']").val(),
                "password":$("input[name='password']").val()
            },
            success: function (response) {
                if (response.success) { window.location.href = "/home" }

                if (!response.success) { 
                    $("input[name='email']").css('border', '1px solid red');
                    $("input[name='password']").css('border', '1px solid red');

                    has_error = true;

                    $("#btn-login").effect("shake") 
                }
            },
            error: function (response) {
                $("input[name='email']").css('border', '1px solid red');
                $("input[name='password']").css('border', '1px solid red');

                has_error = true;

                $("#btn-login").effect("shake");
            }
        });
    });

    $("input[name='email']").on('blur', function(){
        if ($(this).val() == '') {
            $(this).css('border', '1px solid red');
        } else {
            $(this).css('border', 'none');
        }
    });

    $("input[name='password']").on('blur', function(){
        if ($(this).val() == '') {
            $(this).css('border', '1px solid red');
        } else {
            $(this).css('border', 'none');
        }
    });
});
</script>