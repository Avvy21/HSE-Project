<?php
session_start();
include("connect.php");
include("func.php");
$cipher = 'AES-128-CBC';

if(isset($_POST['new_post'])){
    //something was posted
    $user_name = $_POST['user_name'];
    $key = $_POST['password'];

    //encrypt user input
    $iv = random_bytes(16);
    $iv_hex = bin2hex($iv);

    if(!empty($_FILES['img']['tmp_name'])){ //image required to upload
        $image_contents = file_get_contents($_FILES['img']['tmp_name']);
        $img_name = $_FILES['img']['name'];
        // encrypt image
        //$iv = random_bytes(16);
        $encrypted_img = openssl_encrypt($image_contents, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $img_hex = bin2hex($encrypted_img);
    } else {
        echo '<div class="d-flex alert alert-danger justify-content-center" role="alert">
        ERROR: Please add an antigen test photo!</div>';
    }
    
    $escaped_user = $conn -> real_escape_string($_POST['user_name']); // only real_escape for user input
    $encrypted_user = openssl_encrypt($escaped_user, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_user = bin2hex($encrypted_user);

    $escaped_fullname = $conn -> real_escape_string($_POST['full_name']);
    $encrypted_fullname = openssl_encrypt($escaped_fullname, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_fullname = bin2hex($encrypted_fullname);

    $escaped_address = $conn -> real_escape_string($_POST['address']);
    $encrypted_address = openssl_encrypt($escaped_address, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_address = bin2hex($encrypted_address);

    $escaped_dob = $conn -> real_escape_string($_POST['dob']); // could cause issues
    $encrypted_dob = openssl_encrypt($escaped_dob, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_dob = bin2hex($encrypted_dob);

    $escaped_phone = $conn -> real_escape_string($_POST['phone_number']);
    $encrypted_phone = openssl_encrypt($escaped_phone, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_phone = bin2hex($encrypted_phone);

    $escaped_list = $conn -> real_escape_string($_POST['list']);
    $encrypted_list = openssl_encrypt($escaped_list, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted_list = bin2hex($encrypted_list);

    //hash values
    $key = hash_this($key);

    if(!empty($user_name) && !empty($key) && !is_numeric($user_name)){
        //saving to DB
        $user_id = random_number(20);
        $sql = $conn->prepare("INSERT INTO users (user_id,user_name,full_name,address,dob,phone_number,password,iv,img_file_name,img_contents,list) values 
        (?,?,?,?,?,?,?,?,?,?,?)");
        $sql->bind_param("issssssssss",$user_id,$escaped_user,$encrypted_fullname,$encrypted_address,$encrypted_dob,$encrypted_phone,$key,$iv_hex,$img_name,$img_hex,$encrypted_list);

        if($sql->execute()){
            header("Location: login.php");
            die;
        }
    } else {
        echo "<h3>Please enter username and password.</h3>";
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
    <title>Sign Up</title>
</head>

<body class="bg-warning">
    <div class="d-flex align-items-center justify-content-center pt-3">
        <div class="col-11 col-md-8 col-lg-5 col-xxl-4 p-5 bg-light rounded">
            <form method="post" enctype='multipart/form-data'>
                <h3>Sign Up</h3>
                <div class="mb-3 pt-3">
                    <label for="input" class="form-label">Username</label>
                    <input type="text" class="form-control" id="user_name" name="user_name">
                </div>
                <div class="mb-3">
                    <label for="input" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name">
                </div>
                <div class="mb-3">
                    <label for="input" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
                <div class="mb-3">
                    <label for="input" class="form-label">Date of Birth</label>
                    <input type="text" class="form-control" id="dob" name="dob">
                </div>
                <div class="mb-3">
                    <label for="input" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="input-group">
                    <span class="input-group-text">List of Close Contacts:</span>
                    <textarea class="form-control" aria-label="With textarea" name="list"></textarea>
                </div>
                <br>
                <input type="file" id="img" name="img" accept="image/*">
                <br><br>
                <button type="submit" class="btn btn-primary" name="new_post">Submit</button>
                <button type="reset" class="btn btn-primary" name="new_post">Reset</button>
                <br><br>
                <a href="login.php">Click Here to Login</a>
            </form>
        </div>
    </div>
</body>

</html>