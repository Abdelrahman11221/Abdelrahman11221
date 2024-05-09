<?php
require_once 'core/init.php';
$user = new user();
if (!$user->islogin()) {
	Redirect::to('index.php');
}

if(Input::exists()){
    if(Token::check(Input::get('token'))) {
        $validate = new validation();
        $validation = $validate->check($_POST , array(
            'username'=>array(
                'required'=>true,
                'min'=>2,
                'max'=>30,
                'unique'=>'user'
            )
        ));
        if($validation->passed()){
            try{
                $user->update(array(
                    'username' => Input::get('username')
                ));
            }catch(Exception $e){
                die($e->getMessage());
            }
        }
        else{
            foreach ($validation->error() as $error) {
                echo $error.'<br>';
            }
        }
    }
}
?>
<form action="" method="post">
    <div class="field">
        <label for="username">Username:
            <input type="text" name="username" value="<?php echo escape($user->data()->username); ?>">
        </label>
        <input type="submit" value="Update" >
        <input type="hidden" name="token" value="<?php echo token::generate() ;?>">
    </div>
</form>