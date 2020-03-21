<?php

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

// Include config file
require_once "conn.php";

// Define variables and initialize with empty values
$firstname = $lastname = $email = $dob = $streetaddress = $streetaddress2 = $city = $state = $zip = $country = "";
$firstname_err = $lastname_err = $email_err = $dob_err = $streetaddress_err = $streetaddress2_err = $city_err = $state_err = $zip_err = $country_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_firstname = trim($_POST["FirstName"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter a First Name.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid first name.";
    }
    else{
        $firstname = $input_firstname;
    }

    $input_lastname = trim($_POST["LastName"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a Last Name.";
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid last name.";
    }
    else{
        $lastname = $input_lastname;
    }

    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter a email.";
    } else{
        $email = $input_email;
    }

    $input_dob = trim($_POST["DOB"]);
    if(empty($input_dob)){
        $dob_err = "Please enter a Date of Birth.";
    } else{
        $dob = $input_dob;
    }

    $input_streetaddress = trim($_POST["StreetAddress"]);
    if(empty($input_streetaddress)){
        $streetaddress_err = "Please enter an Street address.";     
    } else{
        $streetaddress = $input_streetaddress;
    }

    $input_streetaddress2 = trim($_POST["StreetAddress2"]);
    $streetaddress2 = $input_streetaddress2;
    
    $input_city = trim($_POST["City"]);
    if(empty($input_city)){
        $city_err = "Please enter a City.";     
    } else{
        $city = $input_city;
    }

    $input_state = trim($_POST["State"]);
    if(empty($input_state)){
        $state_err = "Please enter a State.";     
    } else{
        $state = $input_state;
    }

    $input_zip = trim($_POST["Zip"]);
    if(empty($input_state)){
        $zip_err = "Please enter a zip code.";     
    } else{
        $zip = $input_zip;
    }

    $input_country = trim($_POST["Country"]);
    if(empty($input_country)){
        $country_err = "Please enter a Country.";     
    } else{
        $country = $input_country;
    }
    
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($dob_err) && empty($streetaddress_err) && empty($city_err) && empty($state_err) && empty($zip_err) && empty($country_err)){
        // Prepare an update statement
        $sql = "UPDATE userdata SET FirstName=?, LastName=?, email=?, DOB=?, StreetAddress=?, StreetAddress2=?, City=?, State=?, Zip=?, Country=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $param_firstname, $param_lastname, $param_email, $param_dob, $param_streetaddress, $param_streetaddress2, $param_city, $param_state, $param_zip, $param_country, $param_id);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_dob = $dob;
            $param_streetaddress = $streetaddress;
            $param_streetaddress2 = $streetaddress2;
            $param_city = $city;
            $param_state = $state;
            $param_zip = $zip;
            $param_country = $country;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("Location: data.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM userdata WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $firstname = $row["FirstName"];
                    $lastname = $row["LastName"];
                    $email = $row["email"];
                    $dob = $row["DOB"];
                    $streetaddress = $row["StreetAddress"];
                    $streetaddress2 = $row['StreetAddress2'];
                    $city = $row["City"];
                    $state = $row["State"];
                    $zip = $row['Zip'];
                    $country = $row["Country"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("Location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("Location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="icon" href="images/mlk.ico" type="image/xicon">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="FirstName" class="form-control" value="<?php echo $firstname; ?>">
                            <span class="help-block"><?php echo $firstname_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="LastName" class="form-control" value="<?php echo $lastname; ?>">
                            <span class="help-block"><?php echo $lastname_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                            <label>Date of Birth</label>
                            <input type="date" name="DOB" class="form-control" value="<?php echo $dob; ?>">
                            <span class="help-block"><?php echo $dob_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($streetaddress_err)) ? 'has-error' : ''; ?>">
                            <label>Street Address</label>
                            <textarea name="StreetAddress" class="form-control"><?php echo $streetaddress; ?></textarea>
                            <span class="help-block"><?php echo $streetaddress_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($streetaddress2_err)) ? 'has-error' : ''; ?>">
                            <label>Street Address 2</label>
                            <textarea name="StreetAddress2" class="form-control"><?php echo $streetaddress2; ?></textarea>
                            <span class="help-block"><?php echo $streetaddress2_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                            <label>City</label>
                            <input type="text" name="City" class="form-control" value="<?php echo $city; ?>">
                            <span class="help-block"><?php echo $city_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                            <label>State</label>
                            <input type="text" name="State" class="form-control" value="<?php echo $state; ?>">
                            <span class="help-block"><?php echo $state_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($zip_err)) ? 'has-error' : ''; ?>">
                            <label>Zip code</label>
                            <input type="text" name="Zip" class="form-control" value="<?php echo $zip; ?>">
                            <span class="help-block"><?php echo $zip_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($country_err)) ? 'has-error' : ''; ?>">
                            <label>Country</label>
                            <select name="Country" class="form-control" value="<?php echo $country; ?>">
                               <option value="<?php echo $country; ?>" selected hidden><?php echo $country ?></option>
                               <option value="Afganistan">Afghanistan</option>
                               <option value="Albania">Albania</option>
                               <option value="Algeria">Algeria</option>
                               <option value="American Samoa">American Samoa</option>
                               <option value="Andorra">Andorra</option>
                               <option value="Angola">Angola</option>
                               <option value="Anguilla">Anguilla</option>
                               <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                               <option value="Argentina">Argentina</option>
                               <option value="Armenia">Armenia</option>
                               <option value="Aruba">Aruba</option>
                               <option value="Australia">Australia</option>
                               <option value="Austria">Austria</option>
                               <option value="Azerbaijan">Azerbaijan</option>
                               <option value="Bahamas">Bahamas</option>
                               <option value="Bahrain">Bahrain</option>
                               <option value="Bangladesh">Bangladesh</option>
                               <option value="Barbados">Barbados</option>
                               <option value="Belarus">Belarus</option>
                               <option value="Belgium">Belgium</option>
                               <option value="Belize">Belize</option>
                               <option value="Benin">Benin</option>
                               <option value="Bermuda">Bermuda</option>
                               <option value="Bhutan">Bhutan</option>
                               <option value="Bolivia">Bolivia</option>
                               <option value="Bonaire">Bonaire</option>
                               <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                               <option value="Botswana">Botswana</option>
                               <option value="Brazil">Brazil</option>
                               <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                               <option value="Brunei">Brunei</option>
                               <option value="Bulgaria">Bulgaria</option>
                               <option value="Burkina Faso">Burkina Faso</option>
                               <option value="Burundi">Burundi</option>
                               <option value="Cambodia">Cambodia</option>
                               <option value="Cameroon">Cameroon</option>
                               <option value="Canada">Canada</option>
                               <option value="Canary Islands">Canary Islands</option>
                               <option value="Cape Verde">Cape Verde</option>
                               <option value="Cayman Islands">Cayman Islands</option>
                               <option value="Central African Republic">Central African Republic</option>
                               <option value="Chad">Chad</option>
                               <option value="Channel Islands">Channel Islands</option>
                               <option value="Chile">Chile</option>
                               <option value="China">China</option>
                               <option value="Christmas Island">Christmas Island</option>
                               <option value="Cocos Island">Cocos Island</option>
                               <option value="Colombia">Colombia</option>
                               <option value="Comoros">Comoros</option>
                               <option value="Congo">Congo</option>
                               <option value="Cook Islands">Cook Islands</option>
                               <option value="Costa Rica">Costa Rica</option>
                               <option value="Cote DIvoire">Cote DIvoire</option>
                               <option value="Croatia">Croatia</option>
                               <option value="Cuba">Cuba</option>
                               <option value="Curaco">Curacao</option>
                               <option value="Cyprus">Cyprus</option>
                               <option value="Czech Republic">Czech Republic</option>
                               <option value="Denmark">Denmark</option>
                               <option value="Djibouti">Djibouti</option>
                               <option value="Dominica">Dominica</option>
                               <option value="Dominican Republic">Dominican Republic</option>
                               <option value="East Timor">East Timor</option>
                               <option value="Ecuador">Ecuador</option>
                               <option value="Egypt">Egypt</option>
                               <option value="El Salvador">El Salvador</option>
                               <option value="Equatorial Guinea">Equatorial Guinea</option>
                               <option value="Eritrea">Eritrea</option>
                               <option value="Estonia">Estonia</option>
                               <option value="Ethiopia">Ethiopia</option>
                               <option value="Falkland Islands">Falkland Islands</option>
                               <option value="Faroe Islands">Faroe Islands</option>
                               <option value="Fiji">Fiji</option>
                               <option value="Finland">Finland</option>
                               <option value="France">France</option>
                               <option value="French Guiana">French Guiana</option>
                               <option value="French Polynesia">French Polynesia</option>
                               <option value="French Southern Ter">French Southern Ter</option>
                               <option value="Gabon">Gabon</option>
                               <option value="Gambia">Gambia</option>
                               <option value="Georgia">Georgia</option>
                               <option value="Germany">Germany</option>
                               <option value="Ghana">Ghana</option>
                               <option value="Gibraltar">Gibraltar</option>
                               <option value="Great Britain">Great Britain</option>
                               <option value="Greece">Greece</option>
                               <option value="Greenland">Greenland</option>
                               <option value="Grenada">Grenada</option>
                               <option value="Guadeloupe">Guadeloupe</option>
                               <option value="Guam">Guam</option>
                               <option value="Guatemala">Guatemala</option>
                               <option value="Guinea">Guinea</option>
                               <option value="Guyana">Guyana</option>
                               <option value="Haiti">Haiti</option>
                               <option value="Hawaii">Hawaii</option>
                               <option value="Honduras">Honduras</option>
                               <option value="Hong Kong">Hong Kong</option>
                               <option value="Hungary">Hungary</option>
                               <option value="Iceland">Iceland</option>
                               <option value="Indonesia">Indonesia</option>
                               <option value="India">India</option>
                               <option value="Iran">Iran</option>
                               <option value="Iraq">Iraq</option>
                               <option value="Ireland">Ireland</option>
                               <option value="Isle of Man">Isle of Man</option>
                               <option value="Israel">Israel</option>
                               <option value="Italy">Italy</option>
                               <option value="Jamaica">Jamaica</option>
                               <option value="Japan">Japan</option>
                               <option value="Jordan">Jordan</option>
                               <option value="Kazakhstan">Kazakhstan</option>
                               <option value="Kenya">Kenya</option>
                               <option value="Kiribati">Kiribati</option>
                               <option value="Korea North">Korea North</option>
                               <option value="Korea Sout">Korea South</option>
                               <option value="Kuwait">Kuwait</option>
                               <option value="Kyrgyzstan">Kyrgyzstan</option>
                               <option value="Laos">Laos</option>
                               <option value="Latvia">Latvia</option>
                               <option value="Lebanon">Lebanon</option>
                               <option value="Lesotho">Lesotho</option>
                               <option value="Liberia">Liberia</option>
                               <option value="Libya">Libya</option>
                               <option value="Liechtenstein">Liechtenstein</option>
                               <option value="Lithuania">Lithuania</option>
                               <option value="Luxembourg">Luxembourg</option>
                               <option value="Macau">Macau</option>
                               <option value="Macedonia">Macedonia</option>
                               <option value="Madagascar">Madagascar</option>
                               <option value="Malaysia">Malaysia</option>
                               <option value="Malawi">Malawi</option>
                               <option value="Maldives">Maldives</option>
                               <option value="Mali">Mali</option>
                               <option value="Malta">Malta</option>
                               <option value="Marshall Islands">Marshall Islands</option>
                               <option value="Martinique">Martinique</option>
                               <option value="Mauritania">Mauritania</option>
                               <option value="Mauritius">Mauritius</option>
                               <option value="Mayotte">Mayotte</option>
                               <option value="Mexico">Mexico</option>
                               <option value="Midway Islands">Midway Islands</option>
                               <option value="Moldova">Moldova</option>
                               <option value="Monaco">Monaco</option>
                               <option value="Mongolia">Mongolia</option>
                               <option value="Montserrat">Montserrat</option>
                               <option value="Morocco">Morocco</option>
                               <option value="Mozambique">Mozambique</option>
                               <option value="Myanmar">Myanmar</option>
                               <option value="Nambia">Nambia</option>
                               <option value="Nauru">Nauru</option>
                               <option value="Nepal">Nepal</option>
                               <option value="Netherland Antilles">Netherland Antilles</option>
                               <option value="Netherlands">Netherlands (Holland, Europe)</option>
                               <option value="Nevis">Nevis</option>
                               <option value="New Caledonia">New Caledonia</option>
                               <option value="New Zealand">New Zealand</option>
                               <option value="Nicaragua">Nicaragua</option>
                               <option value="Niger">Niger</option>
                               <option value="Nigeria">Nigeria</option>
                               <option value="Niue">Niue</option>
                               <option value="Norfolk Island">Norfolk Island</option>
                               <option value="Norway">Norway</option>
                               <option value="Oman">Oman</option>
                               <option value="Pakistan">Pakistan</option>
                               <option value="Palau Island">Palau Island</option>
                               <option value="Palestine">Palestine</option>
                               <option value="Panama">Panama</option>
                               <option value="Papua New Guinea">Papua New Guinea</option>
                               <option value="Paraguay">Paraguay</option>
                               <option value="Peru">Peru</option>
                               <option value="Phillipines">Philippines</option>
                               <option value="Pitcairn Island">Pitcairn Island</option>
                               <option value="Poland">Poland</option>
                               <option value="Portugal">Portugal</option>
                               <option value="Puerto Rico">Puerto Rico</option>
                               <option value="Qatar">Qatar</option>
                               <option value="Republic of Montenegro">Republic of Montenegro</option>
                               <option value="Republic of Serbia">Republic of Serbia</option>
                               <option value="Reunion">Reunion</option>
                               <option value="Romania">Romania</option>
                               <option value="Russia">Russia</option>
                               <option value="Rwanda">Rwanda</option>
                               <option value="St Barthelemy">St Barthelemy</option>
                               <option value="St Eustatius">St Eustatius</option>
                               <option value="St Helena">St Helena</option>
                               <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                               <option value="St Lucia">St Lucia</option>
                               <option value="St Maarten">St Maarten</option>
                               <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                               <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                               <option value="Saipan">Saipan</option>
                               <option value="Samoa">Samoa</option>
                               <option value="Samoa American">Samoa American</option>
                               <option value="San Marino">San Marino</option>
                               <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                               <option value="Saudi Arabia">Saudi Arabia</option>
                               <option value="Senegal">Senegal</option>
                               <option value="Seychelles">Seychelles</option>
                               <option value="Sierra Leone">Sierra Leone</option>
                               <option value="Singapore">Singapore</option>
                               <option value="Slovakia">Slovakia</option>
                               <option value="Slovenia">Slovenia</option>
                               <option value="Solomon Islands">Solomon Islands</option>
                               <option value="Somalia">Somalia</option>
                               <option value="South Africa">South Africa</option>
                               <option value="Spain">Spain</option>
                               <option value="Sri Lanka">Sri Lanka</option>
                               <option value="Sudan">Sudan</option>
                               <option value="Suriname">Suriname</option>
                               <option value="Swaziland">Swaziland</option>
                               <option value="Sweden">Sweden</option>
                               <option value="Switzerland">Switzerland</option>
                               <option value="Syria">Syria</option>
                               <option value="Tahiti">Tahiti</option>
                               <option value="Taiwan">Taiwan</option>
                               <option value="Tajikistan">Tajikistan</option>
                               <option value="Tanzania">Tanzania</option>
                               <option value="Thailand">Thailand</option>
                               <option value="Togo">Togo</option>
                               <option value="Tokelau">Tokelau</option>
                               <option value="Tonga">Tonga</option>
                               <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                               <option value="Tunisia">Tunisia</option>
                               <option value="Turkey">Turkey</option>
                               <option value="Turkmenistan">Turkmenistan</option>
                               <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                               <option value="Tuvalu">Tuvalu</option>
                               <option value="Uganda">Uganda</option>
                               <option value="United Kingdom">United Kingdom</option>
                               <option value="Ukraine">Ukraine</option>
                               <option value="United Arab Erimates">United Arab Emirates</option>
                               <option value="United States">United States</option>
                               <option value="Uraguay">Uruguay</option>
                               <option value="Uzbekistan">Uzbekistan</option>
                               <option value="Vanuatu">Vanuatu</option>
                               <option value="Vatican City State">Vatican City State</option>
                               <option value="Venezuela">Venezuela</option>
                               <option value="Vietnam">Vietnam</option>
                               <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                               <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                               <option value="Wake Island">Wake Island</option>
                               <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                               <option value="Yemen">Yemen</option>
                               <option value="Zaire">Zaire</option>
                               <option value="Zambia">Zambia</option>
                               <option value="Zimbabwe">Zimbabwe</option>
                           </select>
                           <span class="help-block"><?php echo $country_err;?></span>
                       </div>
                       <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                       <input type="submit" class="btn btn-primary" value="Submit">
                       <a href="data.php" class="btn btn-default">Cancel</a>
                       <br><br>
                   </form>
               </div>
           </div>        
       </div>
   </div>
</body>
</html>