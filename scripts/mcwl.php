<?php

class launcher{
    
    public static function showMainPage(){
        $content = "";
        templates::$template_name = "launcher";
        if(scripts::checkScript("users")){//�����������
            $auth = users::IsAuthorized();
            if($auth != "1"){ users::TryLogin(); $auth = users::IsAuthorized();}
            $login = users::GetLogin();
        }else{
            $auth = "no_lib";
        }
        if($auth == "1"){//����� � ����� ����������� ��������
            $content .= templates::GetRTmpl("mcwl/launcher_content",array("username"=>$login));
        }elseif($auth == "0" || $auth == null){//���� �� �������������
            $content .= templates::getRTmpl("error", array("message" => translate::get("NEED_AUTH", "mcwl")))."<div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
                //$content .= "<div class='warning-box'>��� ��������� ������� � ���� ���������� ������ ���� ������� ������.</div><div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
        }elseif($auth == "no_lib"){//���� ��� ����������
            $content .= templates::getRTmpl("error", array("message" => translate::get("NO_USERS", "mcwl")));
                //$content = "<div class='warning-box'>�� ������� ���������� 'users', ��� ������������ ��� ����������� ����������� �����, ��� ��� ������� �� ����� ��������� ����������� �����������.</div>";
        }
        return $content;
    }
    
}