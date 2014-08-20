<?php


class module_db_setup extends abs_module implements int_module {
    
    public function processAction($action, $parameters) {
        switch($action){
            case "change": return $this->changeCurrent();
            default: return $this->showCurrent();
        }
    }
    
    public function getPageTitle() {
        return "database setup";
    }
    
    private function showCurrent(){
        return "current db parameters:<br/>"
        ."host: ".registry::get("cms.db.host")."<br/>"
        ."username: ".registry::get("cms.db.user")."<br/>"
        ."password: ".registry::get("cms.db.pass")."<br/>"
        ."base name: ".registry::get("cms.db.name")."<br/>"
        ."<a href='%adress%/db_setup/change'>change parameters</a>";
    }
    
    private function changeCurrent(){
        if(filter_input(INPUT_POST,"db_parameters") == "setup"){
            registry::set("cms.db.host", filter_input(INPUT_POST,"host"));
            registry::set("cms.db.user", filter_input(INPUT_POST,"user"));
            registry::set("cms.db.pass", filter_input(INPUT_POST,"pass"));
            registry::set("cms.db.name", filter_input(INPUT_POST,"name"));
            header("Location: ".config::site_url."/db_setup");
        }
        return "<form method='POST'>"
        . "<input type='hidden' name='db_parameters' value='setup'>"
        . "Host: <input type='text' name='host' value='".registry::get("cms.db.host")."'/><br/>"
        . "username: <input type='text' name='user' value='".registry::get("cms.db.user")."'/><br/>"
        . "password: <input type='password' name='pass' value='".registry::get("cms.db.pass")."'/><br/>"
        . "base name: <input type='text' name='name' value='".registry::get("cms.db.name")."'/><br/>"
        
        ."<input type='submit' value='save'/></form>";
    }
    
}