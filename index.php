<?php
session_start();
include("connect.php");
include("func.php");
$user_data = checking_for_login($conn);
$cipher = 'AES-128-CBC';
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>HSE - Authenticated Session</title>
</head>

<body>
    <?php
        $plain_password = $_SESSION['plain_password'];
        $iv_bin = hex2bin($user_data['iv']); // IV is stored on DB in hex, we need it in bin to decrypt

        $fullname_bin = hex2bin($user_data['full_name']);
        $unencrypted_fullname = openssl_decrypt($fullname_bin,$cipher,$plain_password,OPENSSL_RAW_DATA,$iv_bin);

        $address_bin = hex2bin($user_data['address']);
        $unencrypted_address = openssl_decrypt($address_bin,$cipher,$plain_password,OPENSSL_RAW_DATA,$iv_bin);

        $dob_bin = hex2bin($user_data['dob']);
        $unencrypted_dob = openssl_decrypt($dob_bin,$cipher,$plain_password,OPENSSL_RAW_DATA,$iv_bin);

        $phone_bin = hex2bin($user_data['phone_number']);
        $unencrypted_phone = openssl_decrypt($phone_bin,$cipher,$plain_password,OPENSSL_RAW_DATA,$iv_bin);

        $list_bin = hex2bin($user_data['list']);
        $unencrypted_list = openssl_decrypt($list_bin,$cipher,$plain_password,OPENSSL_RAW_DATA,$iv_bin);
        
        $image = ($user_data['img_contents']);
        $unencrypted_image = openssl_decrypt(hex2bin($image), $cipher, $plain_password, OPENSSL_RAW_DATA, $iv_bin);
        $display_unencrypted_image = '<img class="img-fluid" width="800" src="data:image/jpeg;base64,'.base64_encode( $unencrypted_image ).'"/>';
        //echo $unencrypted_image;

        // displays decrypted information to authenticated user using a list.
        echo '
        <div class="d-flex align-items-center justify-content-center pt-2">
        <div class="p-3 w-50 bg-light rounded">
        Currently logged in as: '.$user_data['user_name'].'<br><br>
        <ul class="list-group">
        <li class="list-group-item bg-secondary text-light"><h5">These are the details you entered:</h5></li>
        <li class="list-group-item">'.$unencrypted_fullname.'</li>
        <li class="list-group-item">'.$unencrypted_address.'</li>
        <li class="list-group-item">'.$unencrypted_dob.'</li>
        <li class="list-group-item">'.$unencrypted_phone.'</li>
        <li class="list-group-item">'.$unencrypted_list.'</li>
        <li class="list-group-item">'.$display_unencrypted_image.'</li>
        </ul>
        </div>
        </div>';
    ?>
    <br>
    <div class="d-flex align-items-center justify-content-center pt-2">
        <a href="logout.php">
            <button type="button" class="btn btn-primary">Logout</button>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
</body>

</html>