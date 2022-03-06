<?php
session_start();
include("connect.php");
include("func.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $user_name = $_POST['user_name'];
    
    // Prepared Statement
    $sql = $conn->prepare("SELECT user_name,password,user_id FROM users WHERE user_name = ? limit 1");
    $sql->bind_param('s',$user_name);
    $sql->execute();
    $result = $sql->get_result();
    
    if($result->num_rows > 0){

        //verify hash of password from database against hash of password entered in login form.
        $user_data = mysqli_fetch_assoc($result);
        $hash = $user_data['password'];
        $hash  = hex2bin($hash);
        $verify = password_verify($_POST['password'],$hash); // where POST password is the password the user entered in the form

        if($verify){
            $_SESSION['user_id'] = $user_data['user_id'];       // this stops users entering index.php without logging in
            $_SESSION['plain_password'] = $_POST['password'];   // this allows users to decrypt data in index.php
            header("Location: index.php");
            die;
        } else {
            echo '<div class="d-flex alert alert-danger justify-content-center" role="alert">Incorrect Username or Password</div>';
        }
    } else {
        echo '<div class="d-flex alert alert-danger justify-content-center" role="alert">Incorrect Username or Password</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Login - HSE Website</title>
</head>

<body class="bg-warning">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="col-11 col-md-8 col-lg-5 col-xxl-4 p-5 bg-light rounded">
            <form method="post" enctype='multipart/form-data'>
                <h2>Login</h2>
                <div class="mb-3 pt-3">
                    <label for="input" class="form-label">Username</label>
                    <input type="text" class="form-control" id="user_name" name="user_name">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
                <br><br>
                <h5><a href="signup.php">Click to Sign Up</a></h5>
            </form>
            <img class="img-fluid" src="https://www.hmi.ie/wps/wp-content/uploads/2011/03/HSE-Logo.png" alt="HSE LOGO">
        </div>
    </div>
</body>

</html>