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
                background: #262626;
                height: 100vh;
                color: #ffffff;
                padding-left: 25px;
                padding-right: 25px;
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
        </style>
    </head>
    <body class="antialiased">
        <div class="row">
            <div class="col-md-6 login-form-section">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="form-group">
                    <label class="label">Email</label><br>
                    <input class="form-control input-lg" type="text" name="email">
                </div>
                <br>
                <div class="form-group">
                    <label class="label">Password</label><br>
                    <input class="form-control input-lg" type="password" name="password">
                </div>
                <br>
                <div>
                    <button class="btn btn-primary rounded-0 btn-lg" id="btn-login">Login</button>
                </div>
            </div>
            <div class="col-md-6">
                <br>
                <br>
                <br>
                <br>
                <br>
                <iframe width="98%" height="400" src="https://www.youtube.com/embed/_UORzUfKR2c?si=cLZ1q9wnjbmFNog5&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
$(document).ready(function(){
    let searchParams = new URLSearchParams(window.location.search);

    var access_type = null;
    var brand_id = "f6f2202a-6d82-11ee-91c0-0242ac120005";
    var SIGNIN_ENDPOINT = 'http://localhost/api/login';

    if (searchParams.has('access')) {
        access_type = searchParams.get('access');
    }

    if (searchParams.has('brand')) {
        brand_id = searchParams.get('brand');
    }

    if (brand_id == null) {
        window.location.href = "404.html";
    }

    $("#btn-login").click(function(){
        var has_error = false;

        if ($("input[name='email']").val() == '') {
            $("input[name='email']").css('border-bottom', '1px solid red');
            has_error = true;
        }

        if ($("input[name='password']").val() == '') {
            $("input[name='password']").css('border-bottom', '1px solid red');
            has_error = true;
        }

        if (has_error) { return false; }

        $.ajax({
            type: 'POST',   
            url: SIGNIN_ENDPOINT,
            headers: {
                'Accept': 'application/json'
            },
            dataType: "json",
            data:   
            {
                "email":$("input[name='email']").val(),
                "password":$("input[name='password']").val(),
                "access_type":access_type,
                "brand_id":brand_id
            },
            success: function (data) {
                console.log(data.token);
                window.location.href = "/home";
            },
            error: function (data) {
                $("#btn-login").effect("shake");
            }
        });
    });

    $("input[name='email']").on('blur', function(){
        if ($(this).val() == '') {
            $(this).css('border-bottom', '3px solid red');
        } else {
            $(this).css('border-bottom', 'none');
        }
    });

    $("input[name='password']").on('blur', function(){
        if ($(this).val() == '') {
            $(this).css('border-bottom', '3px solid red');
        } else {
            $(this).css('border-bottom', 'none');
        }
    });
});
</script>