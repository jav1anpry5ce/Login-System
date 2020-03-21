<?php
// Initialize the session
session_start();

$time = $_SERVER['REQUEST_TIME'];
$timeout = 300;

if (isset($_SESSION['LAST_ACTIVITY']) && 
 ($time - $_SESSION['LAST_ACTIVITY']) > $timeout) {
  session_unset();
  session_destroy();
  session_start(); 
}

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = $time;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="HandheldFriendly" content="true">
  <title>Client Details</title>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
  <link rel="icon" href="images/mlk.ico" type="image/xicon">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
  <style type="text/css">
    .wrapper{
      width: 96%;
      margin: auto;
    }
    .page-header h2{
      margin-top: 0;
    }
    table tr td:last-child a{
      margin-right: 15px;
    }
    .user {
      color: grey;
      display: inline-block;
      margin-top: 7px;
      margin-left: 5px;
    }
    .user a {
      color: grey;
      text-decoration: none;
    }
    .user a:hover {
      color: white;
      text-decoration: underline;
    }
    body {
      background-color: rgb(240, 240, 240);
    }
  </style>

</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-brand">
        <a href="welcome.php"><img src="images/mlk.png" height="40px" style="display: block; margin-top: -25%;"></a>
      </div>
      <button class="navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse navHeaderCollapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="welcome.php">Home</a></li>
          <li class="active"><a class="dropdown-toggle" data-toggle="dropdown" href="data.php">Client Management<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="create.php">Add new Client</a></li>
              <li><a href="data.php">View Client Details</a></li>
            </ul>
          </li>
          <li><a href="changePassword.php">Change Password</a></li>
          <li class="user"><h5>Hello, <a href="#"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></a></h5></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Add Admin</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>
    <div style="margin-top: 30px;">
      <div class="wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-20">
              <div class="page-header clearfix">
                <h2 class="pull-left">Client Details</h2>
                <a href="create.php" class="btn btn-success pull-right">Add New Client</a>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>DOB</th>
                      <th>Street Address</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Country</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php include 'conn.php';
                      $sql = "SELECT * FROM userdata";
                      $result=$link->query($sql);
                      if ($result->num_rows > 0) {
                        while ($userdata=$result->fetch_assoc()) {
                         ?>
                         <td><?= $userdata['id'] ?></td>
                         <td><?= $userdata['FirstName'] ?></td>
                         <td><?= $userdata['LastName'] ?></td>
                         <td><?= $userdata['email'] ?></td>
                         <td><?= $userdata['DOB'] ?></td>
                         <td><?= $userdata['StreetAddress'] ?></td>
                         <td><?= $userdata['City'] ?></td>
                         <td><?= $userdata['State'] ?></td>
                         <td><?= $userdata['Country'] ?></td> 
                         <td>
                          <a href="read.php?id= <?= $userdata['id'] ?>" title="View Record" data-toggle="tooltip"><i class="glyphicon glyphicon-eye-open"></i></a>
                          <a href="update.php?id= <?= $userdata['id'] ?>" title="Update Record" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a href="delete.php?id=<?= $userdata['id']; ?>" title="Delete Record" data-toggle="tooltip"><i class="glyphicon glyphicon-trash"></i></a>
                        </td>
                      </tr>
                    <?php  }} else {
                      echo "No Records Found!";
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="delete<?= $userdata['id']; ?>" role="dialog">
    <div class="modal-dialog" role="document">
      <form method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Delete Record</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php
// Process delete operation after confirmation
            if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
              require_once "conn.php";

    // Prepare a delete statement
              $sql = "DELETE FROM userdata WHERE id = ?";

              if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
                $param_id = trim($_POST["id"]);
              }

    // Close statement
              mysqli_stmt_close($stmt);

    // Close connection
              mysqli_close($link);
            }
            ?>
            <div class="wrapper">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <div class="alert alert-danger fade in">
                        <input type="" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                        <p>Are you sure you want to delete this record?</p><br>
                        <p>
                          <input type="submit" value="Yes" class="btn btn-danger">
                          <a href="data.php" class="btn btn-default">No</a>
                        </p>
                      </div>
                    </form>
                  </div>
                </div>        
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </form>
  </div><!-- Modal end -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('table').DataTable();
    });
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
  </script>
</body>
</html>