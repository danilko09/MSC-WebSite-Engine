<?php

class skins{

	//script config
	private $local_scins = true;

	public function getSkinsPage(){
	
            if (scripts::checkScript("users")) {

            if (users::GetGroup() != "guest") {
                $arr['login'] = users::getLogin();
                $arr['skins_base'] = "%adress%/skins";
                $ret = templates::GetRTmpl("skins/page", $arr);
            } else {
                $ret = templates::getRTmpl("error", array("message" => translate::get("NEED_AUTH", "skins")/* "��� ����� ����� ���������������." */));
            }
        } else {
            $ret = templates::getRTmpl("error", array("message" => translate::get("NO_USERS", "skins")/* "�� ������� ��������� ���������� users" */));
        }

        return $ret;
	
	}

}