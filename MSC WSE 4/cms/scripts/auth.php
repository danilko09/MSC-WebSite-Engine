<?php

final class auth{
    
    private static $global_integration = 1;
    private static $users_cache = null;
    
    public static function onLoad(){
        self::$users_cache = is_file(__DIR__.DIRECTORY_SEPARATOR.'auth_users.ser') ? unserialize(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'auth_users.ser')) : array();
    }
    
    public static function autoload(){
        self::onLoad();
        WSE_ENGINE::RegisterTagHandler("auth", "auth");
    }
    
    public static function handleTag($tag){
        if(self::$users_cache == null){self::onLoad();}
        switch($tag['name']){
            case "auth_block": return self::authBlock();
            case "reg_form": return self::regBlock();
        }
    }
    
    public static function regBlock(){
    
        if(isset($_SESSION['auth']) && $_SESSION['auth'] == true){
            return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getTmpl("auth/cant_reg"));
        }elseif(filter_input(INPUT_POST,'reg_post') == null){
            return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getTmpl("auth/registration"));
        }else{
            if(self::$global_integration == 1){return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getRTmpl("auth/registration", self::globalReg()));}
            elseif(self::$global_integration == 0){return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getRTmpl("auth/registration", self::localReg()));}
        }
    }
    
    public static function authBlock(){
        $message = '';
        if(filter_input(INPUT_POST,'logout_posted')){$_SESSION['auth'] = false; $_SESSION['email'] = null; header("Location: ".WSE_ENGINE::getIndex());}
        if(filter_input(INPUT_POST, "auth_posted")){
            if(self::$global_integration == 0){self::makeOfflineAuth();}
            elseif(self::$global_integration == 1){$message = self::makeGlobalAuth();}
        }
        if(isset($_SESSION['auth']) && $_SESSION['auth']){
            $user = self::getByMail($_SESSION['email']);
            return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getRTmpl('auth'.DIRECTORY_SEPARATOR.'authorised',$user));
        }else{
            return WSE_ENGINE::PrepearHTML(WSE_ENGINE::getRTmpl('auth'.DIRECTORY_SEPARATOR.'not_authorised',array("message"=>$message)));
        }
    }
    
    private static function globalReg(){
        $ans = file_get_contents("http://msc-auth.16mb.com/reg.php?email=".urlencode(filter_input(INPUT_POST,'email'))."&username=".urlencode(filter_input(INPUT_POST,'login'))."&password=".urlencode(filter_input(INPUT_POST,'pass')));
        if($ans == false){
            return array("message"=>"Сервер регистрации на данный момент не доступен, попробуйте позже");
        }else{
            switch($ans){
                case "bad_email": return array("message"=>"Не верный формат E-mail");
                case "login_busy": return array("message"=>"Логин уже занят");
                case "already_registred": return array("message"=>"Игрок с таким E-mail уже зарегистрирован");
                default:
                    if(strpos($ans,':') != 37){return array("message"=>"Ошибка сервера регистрации");}//Если сервер вернул какой-то мусор
                    $_SESSION['auth'] = true;
                    $_SESSION['email'] = filter_input(INPUT_POST,'email');
                    //Создание локального кеша
                    self::updateByMail(filter_input(INPUT_POST,'email'), filter_input(INPUT_POST,'pass'), substr($ans,38), substr($ans,0,36));
                    header("Location: ".WSE_ENGINE::getIndex());
                    break;
            }
        }
        
    }
    
    private static function makeGlobalAuth(){
        $ans = file_get_contents("http://msc-auth.16mb.com/auth.php?email=".urlencode(filter_input(INPUT_POST,'email'))."&password=".urlencode(filter_input(INPUT_POST,'pass')));
        if($ans == false){return self::makeOfflineAuth();}//Если не удалось прочитать данные, то пишем об ошибке
        switch($ans){
            case "bad_auth": return "Не верный логин или пароль";
            case "not_registred": return "Ваш E-mail не зарегистрирован";
            default:
                if(strpos($ans,':') != 37){return self::makeOfflineAuth();}//Если сервер вернул какой-то мусор
                $_SESSION['auth'] = true;
                $_SESSION['email'] = filter_input(INPUT_POST,'email');
                //Обновление локального кеша
                self::updateByMail(filter_input(INPUT_POST,'email'), filter_input(INPUT_POST,'pass'), substr($ans,38), substr($ans,0,36));
                header("Location: ".WSE_ENGINE::getURL());
                break;
        }
    }
    
    private static function makeOfflineAuth(){
        $user = self::getByMail(filter_input(INPUT_POST,'email'));
        if($user !== null && $user['password'] === filter_input(INPUT_POST,'pass')){
            $_SESSION['auth'] = true;
            $_SESSION['email'] = $user['email'];
            header("Location: ".WSE_ENGINE::getURL());
        }
    }
    
    private static function getByMail($mail){
        foreach(self::$users_cache as $id=>$user){
            
            if(!isset($user['email'])){
                unset(self::$users_cache[$id]);
                continue;
            }
            if($mail === $user['email']){
                return $user;
            }
        }
        return null;
    }
    
    public static function getIdByMail($email){
        foreach(self::$users_cache as $id=>$user){
            if(isset($user['email']) && $email === $user['email']){
                return $id;
            }
        }
        return null;
    }
    
    public static function getById($id){
        return isset(self::$users_cache[$id]) ? self::$users_cache[$id] : null;
    }
    
    public static function getGroupById($id){
        return isset(self::$users_cache[$id]['group']) ? self::$users_cache[$id]['group'] : null;
    }
    
    public static function getGroupByEmail($email){
        return self::getGroupById(self::getIdByMail($email));
    }
    
    public static function setGroup($id,$group){
        self::$users_cache[$id]['group'] = $group;
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'auth_users.ser', serialize(self::$users_cache));
    }
    
    public static function setGroupByEmail($email,$group){
        self::setGroup(self::getIdByMail($email),$group);
    }
    
    private static function updateByMail($email,$pass,$username,$uuid){
        foreach(self::$users_cache as $id=>$user){
            if(isset($user['email']) && $email === $user['email']){
                self::$users_cache[$id]['uuid'] = $uuid;
                self::$users_cache[$id]['login'] = $username;
                self::$users_cache[$id]['password'] = $pass;
                file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'auth_users.ser', serialize(self::$users_cache));
                break;
            }
        }
        
        self::$users_cache[]['email'] = $email;
        self::$users_cache[]['uuid'] = $uuid;
        self::$users_cache[]['login'] = $username;
        self::$users_cache[]['password'] = $pass;
        self::$users_cache[]['group'] = 'user';
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'auth_users.ser', serialize(self::$users_cache));

    }
    
}