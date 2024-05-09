<?php
require_once 'core/init.php';
$user = new user();
if (!$user->islogin()) {
    Redirect::to('index.php');
}
if(Input::exists()){
    if(Token::check(Input::get('token'))) {
        $validate = new validation();
        $validation = $validate->check($_POST, array(
            'current_password' => array(
                'required' => true,
                'min' => 6
            ),
            'new_password' => array(
                'required' => true,
                'min' => 6,
            ),
            'new_password_again' => array(
                'required' => true,
                'min' => 6,
                'matches'=>'new_password'
            )
        ));

        if ($validate->passed()){
            if(Hash::make(Input::get('current_password')) !== $user->data()->password){
                die("Current password is incorrect");
            }
            else{
                try{
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password' => Hash::make(Input::get('new_password')),
                        'salt' => $salt
                    ));
                    session::flash('success' , 'password update successfully');
                    redirect::to('index.php');
                }catch(Exception $e){
                    die($e->getMessage());
                }
            }
        }
        else{
            foreach($validation->error() as $error) {
                echo $error.'<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <div class="feild">
        <div class="field">
            <label for="password">Current Password: </label>
            <input type="password" name="current_password" id="password">
        </div>
        <div class="field">
            <label for="password">New Password: </label>
            <input type="password" name="new_password" id="password">
        </div>
        <div class="field">
            <label for="password">New Password again: </label>
            <input type="password" name="new_password_again" id="password">
        </div>
    </div>
    <input type="hidden" name="token" value="<?php echo token::generate(); ?>" >
    <input type="submit" value="change password"> 
</form>