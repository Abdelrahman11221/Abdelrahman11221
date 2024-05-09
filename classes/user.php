<?php
class user{
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_islogin;
    public function __construct($user = null) {
        $this->_db = DB::getinstanceof();
        $this->_sessionName = config::get('session/session_name');
        $this->_cookieName = config::get('remember/cookie_name');
        if(!$user){
            if(session::exists($this->_sessionName)){
                $user = session::get($this->_sessionName);
                if($this->find($user)) {
                    $this->_islogin = true;
                } else {
                    // $this->logout();
                }
            }
        }
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('user' , $fields)){   //line 9
            throw new Exception("Error Processing Request");
        }
    }

    public function update($field = array() , $id = null){
        if(!$id && $this->islogin()){
            $id = $this->data()->id;
        }
        if(!$this->_db->update('user' , $id , $field)){
            throw new Exception("There was a problem updating the database.");
        }
    }
    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('user' , array($field , '=' , $user));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null , $password = null , $remember = false){
        if(!$username && !$password && $this->exists()){
            session::put($this->_sessionName , $this->data()->id);
        }else{
            $user = $this->find($username);
            if($user) 
            {
                if($this->data()->password === Hash::make($password)){
                    session::put($this->_sessionName , $this->data()->id);
                    if($remember){
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('user_session' , array('user_id' , '=' , $this->data()->id));
                        if(!$hashCheck->count()){
                            $this->_db->insert('user_session' , array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        }
                        else{
                            $hash = $hashCheck->first();
                        }
                        cookie::put($this->_cookieName , $hash , config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
                else{
                    echo "NO!";
                }
            }
        }
        
        return false;
    }

    public function exists(){
        return (!empty($this->_data)) ? true : false;
    }

    public function data(){
        return $this->_data;
    }

    public function islogin(){
        return $this->_islogin;
    }

    public function logout(){
        $this->_db->delete('user_session' , array('user_id' , '=' , $this->data()->id));
        session::delete($this->_sessionName);
        cookie::delete($this->_cookieName);
    }
}