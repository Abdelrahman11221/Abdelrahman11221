<?php

require_once 'core/init.php';

if(Input::exists()){
    if(token::check(Input::get('token'))){
    // var_dump(token::check(Input::get('token')));
        $validate = new validation();

        $validation = $validate->check($_POST , array(
    
            'username' => array (
                'required' => true,
                'min'      => 2,
                'max'      => 50,
                'unique'   => 'user'
            ),
            'email' => array( 
                'required' => true,
            ),
            'password' => array (
                'required' => true,
                'min'      => 6,
                'max'      => 50
            ),
            'password_again' => array(
                'required' => true,
                'matches'=> 'password'
            ),
    
        ));
    
        if($validate->passed()){
            $user = new user();
            $salt = Hash::salt(32);
            
            try{
                $user->create(array(
                    'username' => Input::get('username'),
                    'email' => Input::get('email'),
                    'password' => Hash::make(Input::get('password')),
                    'salt' => $salt
                ));
                // echo Hash::make(Input::get('password'));die;
                session::flash( "success", "Registration Successful!" );
                redirect::to('index.php');
            
            } catch(Exception $e){
                die($e->getMessage());
            }
        }
        else{
            foreach($validate->error() as $error){
                echo $error . '<br>';
            }
        }
    }
}


// echo Input::get('username')
?>

<form action="" method="post">
    <div class="field">
        <label for="username">username</label>
        <input type="text" name="username" id="username" value="<?php Input::get('username') ?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="name">your email</label>
        <input type="text" name="email" value="<?php Input::get('email') ?>" id="name">
    </div>
    <div class="field">
        <label for="password">password</label>
        <input type="password" name="password" >
    </div>
    <div class="field">
        <label for="password_again">confirm password</label>
        <input type="password" name="password_again" 3>
    </div>
    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
    <input type="submit" value="Register">
</form>
