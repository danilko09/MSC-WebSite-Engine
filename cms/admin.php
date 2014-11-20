<?php
$title = "���������������� ������";
admin_panel::auth();
$content = admin_panel::makeContent();

final class admin_panel {

    private static $auth = "0";
    private static $login = "";
    private static $group = "guest";
    private static $message = "";
    
    public static function auth(){
        if(scripts::checkScript("users")){//�����������
            self::$auth = users::IsAuthorized();
            if(self::$auth != "1"){ users::TryLogin(); self::$auth = users::IsAuthorized();}
            self::$login = users::GetLogin();
            self::$group = users::GetGroup();
        }else{
            self::$auth = "no_lib";
        }
    }
    
    public static function makeContent(){   
        $content = "";
        
        if(self::$auth == "1" && self::$group == "admin"){//����� � ����� ����������� ��������
            $content = self::main();
        }elseif(self::$auth == "1" && self::$group != "admin"){//���� �� �����
                self::$message .= "<div class='warning-box'>�� �� �������� � ������ '��������������' � �� ������ �������� ������ � ���� ��������.</div>";
        }elseif(self::$auth == "0" || self::$auth == null){//���� �� �������������
                self::$message .= "<div class='warning-box'>�� �� ���������������.</div><div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
        }elseif(self::$auth == "no_lib"){//���� ��� ����������
                self::$message = "<div class='warning-box'>�� ������� ���������� 'users', ��� ������������ ��� ����������� ����������� �����, ��� ��� ������ � ���������������� ������ �� ��������.</div>";
        }
        return (self::$message != "") ? self::$message.$content : $content;
    }
    private static function main(){
        global $adm;$content = "";
        
        var_dump(scripts::checkScript($adm[2]));
        if(!isset($adm[2]) || $adm[2] == ""){//���� � �������
        $links = self::genMenu();
        foreach($links as $group=>$cnt){
            $content .= str_replace("[title]", $group,str_replace("[content]",$cnt,templates::GetTmpl("admin/post")));
        }
    }elseif(scripts::checkScript($adm[2])){
    
        $tmp = "admin_".$adm[2];
        $content = $tmp::genContent();
        
    }else{//404 ��� �����-�� �������� � �������
        
            $content = "<div><h5>������ 404 | <a href='%adress%/index.php/admin'>� ���������������� ������</a></h5>�� ������ ���� '%adress%/scripts/admin/".$adm[2].".php'.<br/><br/>�������� �� ������ �� ��� �������� �������� ������ �� ������ ����� ��������, ��� ����������, � �������� ����������� ���� ����, ���� �������.</div>";
        
    }
    return $content;
    }
    
    private static function genMenu(){
        $links = array();
        $sc = scripts::getAllScriptsInfo();
        foreach($sc as $alias=>$info){
            if(!isset($info['a_title']) || !isset($info['a_category'])){continue;}
            if(isset($links[$info['a_category']])){
                $links[$info['a_category']] .= "<a href='%adress%/index.php/admin/".$alias."'>".$info['a_title']."</a><br/>";
            }else{
            $links[$info['a_category']] = "<a href='%adress%/index.php/admin/".$alias."'>".$info['a_title']."</a><br/>";
            }            
        }
        return $links;
    }
    
}
