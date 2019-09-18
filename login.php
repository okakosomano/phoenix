<?php
// Initialize the session
session_start();
 
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
        mysqli_stmt_close($stmt);
        }
        
        
    }
    
    // Close connection
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
  
    <body>
     <div class="container">
      <div class="row bg-warning">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <div class="card card-signin my-5">
            <div class="card-body">
              <div class="" style="height: 10rem; padding: 60px; margin:-13px; background-image: url(https://res.cloudinary.com/kngkay/image/upload/v1568674095/kngkay/laptop2.jpg)">
              <h5 class="card-title text-center text-uppercase text-white">Sign In</h5>
            </div>
  <br> <br>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-signi">
                <div class="form-label-group">
                  <label for="inputEmail">Username</label>
                  <input type="text" name="username" id="inputEmail" value="<?php echo $username; ?>" class="form-control" placeholder="Username" required autofocus>
                  </div>
  <br>
                <div class="form-label-group <?php echo (!empty(password_err)) ? 'has error' : '';?>">
                  <label for="inputPassword">Password</label>
                  <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password"  required>
                  <span><?php echo $password_err; ?></span>
               </div>
               
               <br>
               <div class="form-row text-center">
                <div class="custom-control custom-checkbox col-md-5 mb-3" >
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1">Remember me</label>
                </div>
                <div class="custom-control custom-checkbox col-md-7 mb-3" >
                  <label style="text-align: right"><a href="http:/#/">Forget password</a></label>
                </div>
               </div>
                <div class="text-center"><button class="btn btn-sm btn-dark text-uppercase" type="submit" style="width: 10rem; border-radius:20px">Log in</button></div>
                
  <br>
                <div class="form-row text-center">
                <div class="custom-control custom-checkbox col-md-6 mb-3" >
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="">Don't have an account?</label>
                </div>       
                  <div class="custom-control custom-checkbox col-md-6 mb-3" >
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label style="align-content: right"><a href="index.php">Sign up here</a></label>
                </div>
              </div>
            
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>


</body>
</html>