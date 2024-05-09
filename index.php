<?php

use config as GlobalConfig;
use PSpell\Config;
require_once 'core/init.php';

if(session::exists('success')){
    echo session::flash('success');
}
$user = new user();
if($user->islogin()){
    ?>

    <p>Hello <a href="#" > <?php echo $user->data()->username; ?> </a> </p>
    <ul>
        <li>
            <a href="logout.php">Log Out</a>
        </li>
        <li>
            <a href="update.php">Update</a>
        </li>
        <li>
            <a href="changepassword.php">Change Password</a>
        </li>
    </ul>

    <?php
}
else {
    echo 'you need to <a href = "login.php">log in</a> or <a href = "register.php">Register</a>';
}





