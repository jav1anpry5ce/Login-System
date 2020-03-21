<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <title>Forgot password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <style type="text/css">
        html, body {
            height: 100%;
            background: #007bff;
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            color: #ffffff;
        }
        span {
         color: #0099ff;
     }
 </style>
 <script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
</script>
</head>
<body>
    <?php
    if (isset($_GET["reset"])) {
        if ($_GET["reset"] == "success") {
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sucess!</strong> Email sent successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php
        } elseif ($_GET["reset"] == "fail") {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Something went wrong please try again later.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php
        } else {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Something went wrong please try again later.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php
        }
    } elseif(isset($_GET["validate"])) {
        if($_GET["validate"] == "couldnotvalidate") {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Your request could not be validated. Please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php
        }
    }

    ?>
    <div>&nbsp;</div>
    <div class="container padding-bottom-3x mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="forgot">
                    <h2>Forgot your password?</h2>
                    <p>Change your password in three easy steps. This will help you to secure your password!</p>
                    <ol class="list-unstyled">
                        <li><span>1. </span>Enter your email address below.</li>
                        <li><span>2. </span>Our system will send you a temporary link</li>
                        <li><span>3. </span>Use the link to reset your password</li>
                    </ol>
                </div>
                <form class="card mt-4" action="resetrequest.php" method="POST">
                    <div class="card-body">
                        <div class="form-group"> 
                            <label for="email-for-pass">Enter your email address</label> 
                            <input class="form-control" type="email" id="email-for-pass" required="" name="email">
                            <small class="form-text text-muted">Enter the email address you used during the registration on localhost. Then we'll email a link to this address.</small> 
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="submit" name="reset-request-submit">Get New Password</button>
                        <a class="btn btn-danger" href="login.php">Back to Login</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>