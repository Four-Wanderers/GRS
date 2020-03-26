<?php
    session_start();
    if(isset($_SESSION["invalid_login"])){
        $valid_login = $_SESSION["invalid_login"];
    }
    else
        $_SESSION["invalid_login"] = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\\style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <title>G-Login</title>
    <style>
        form{
            margin-top: 45%;
        }
    </style>
    <script>
        function toggleDisplay(bool){
            document.getElementById("invalidLogin").style.display = ((bool) ? "block" : "none");
        }
        function loginCheck(){
            var bool = <?php echo json_encode($valid_login);?>;
            toggleDisplay(bool);
        };
        (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
                }, false);
            });
        }, false);
        })();
    </script>
</head>
<body onload="loginCheck()">
<!-- <body> -->
    <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">  
        <form action="login_check.php" method="post" class="needs-validation" novalidate>
            <h1>Admin Login</h1>
            <div class="form-group">
                <input type="uemail" class="form-control" name="umail" placeholder="Username/Email" required>
                <div class="invalid-feedback">Username/Email field invalid</div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="pwd" placeholder="password" required>
                <div class="invalid-feedback">Password field invalid</div>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
            <div id="invalidLogin" style="display:none">Invalid Login</div>
        </form>
    </div>
    <div class="col-sm-4"></div>
</div>
</body>
</html>