<?php
//Include config file
require_once "config.php";

//define variables as empty
$username = $email = $password = $confirm_password ="";
$username_err = $email_err = $password_err = $confirm_password_err = "";

//processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter your username";
    } else{
        //prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
       
        if($stmt = mysqli_prepare($link, $sql)){
            //Bind variables to the prepared ststement as paraameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            //set parameters
            $param_username = trim($_POST["username"]);

            //Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                //store result
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
             } else{
                    echo "Oops! Something went wrong. Please try again.";
                }
                 //close statement
            mysqli_stmt_close($stmt);
            }
           
        }

        //Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email address";
    } else{
        //prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
       
        if($stmt = mysqli_prepare($link, $sql)){
            //Bind variables to the prepared ststement as paraameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
       
            //set parameters
            $param_email = trim($_POST["email"]);
       
            //Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
       
                //store result
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email already exists.";
                } else{
                    $email = trim($_POST["email"]);
                }
             } else{
                    echo "Oops! Something went wrong. Please try again.";
                }
                 //close statement
            mysqli_stmt_close($stmt);
            }
           
        }
       
        //Validate password
        if(empty(trim($_POST['password']))){
            $password_err = "Please enter a valid password.";
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have at least 6 characters";
        } else{
            $password = trim($_POST["password"]);
        }
       
        //Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Passwords do not match.";
            }
        }
      
        //Check for input errors before inserting in database
        if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
      
            //prepare insert statement
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
          
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
      
                //Set parameters
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); //CREATES A PASSWORD HASH
      
                //Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    //redirect to login page
                    header("location: login.php");
                } else{
                    echo "Something went wrong, please try again.";
                }
          
                //Close statement
            mysqli_stmt_close($stmt);
            }
          
        }
        //Close connection
        mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Team Phoenix</title>
</head>
<body>
    
    <div class="container">
      <div class="row bg-warning">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <div class="card card-signin my-5">
            <div class="card-body">
              <div class="" style="height: 15rem; padding: 60px; margin:-13px; background-image: url(https://res.cloudinary.com/kngkay/image/upload/v1568674095/kngkay/laptop2.jpg)">
              <h4 class="card-title text-center text-uppercase text-white">Sign Up</h4>
              <p class="card-title text-center text-white">Please fill this form to create an account.</p>
            </div>
<br> <br>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>   
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
             
            <div class="text-center">
                 <input class="btn btn-sm btn-primary text-uppercase" type="submit" style="width: 10rem; border-radius:20px">
                <input type="reset" class="btn btn-default" value="Reset" style="width: 10rem; border-radius:20px">
            </div>
            <br>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>   
            
                <div class="social-btn text-center col-md-12 mb-3">
                        <div class="or-seperator"><b>or</b></div>
                        <p class="hint-text">Sign up with your social media account or email address</p>
                <a href="#" class="btn btn-primary btn-lg"><i class="fa fa-facebook"></i> Facebook</a>
                <a href="#" class="btn btn-info btn-lg"><i class="fa fa-twitter"></i> Twitter</a>
                <a href="#" class="btn btn-danger btn-lg"><i class="fa fa-google"></i> Google</a>
                </div>
            
        </form>
    </div>    
</body>
</html>