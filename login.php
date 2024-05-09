<?php
require_once 'core/init.php';
if(Input::exists()) {
    if(token::check(Input::get('token'))) {
        $validate = new validation();
        $validation =  $validate->check($_POST, array(
            'username'=>array('required'=>true),
            'password'=>array('required'=>true)
        ));
        if($validation->passed()){
            $user = new user();
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password') , $remember);
            if($login) {
                redirect::to('index.php');
            }
            else{
                echo "Failed to log in";
            }
            } 
            else {
                foreach ($validation->error() as $error) {
                    echo $error."<br>";
                }
        }
    }
}

?>


<form action="" method="post">
    <div class="feild">
        <div class="field">
            <label for = "username" >Username:</label>
            <input type ="text" id="username" name="username" >
        </div>
        
        <div class="field">
            <label for="password">Password: </label>
            <input type="password" name="password" id="password">
        </div>

        <div class="field">
            <label for="remember">
                <input type="checkbox" name="remember" id="remember">Remember me
            </label>
        </div>
    </div>
    <input type="hidden" name="token" value="<?php echo token::generate(); ?>" >
    <input type="submit" value="Login"> 
</form>