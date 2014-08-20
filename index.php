<?php
//timer
$ms = microtime(true);
//debug
error_reporting(E_ALL);
ini_set("display_errors", 1);
//version
define("CMS_VERSION","A.3.0");
//config (old | config->registry)
if(is_file("cms/config.php")){include("cms/config.php");}
elseif(is_file("config.php")){include("config.php");}
else{
echo "<html><head><title>MSC Web Site Engine ERROR</title></head><body><div style='height: 40%;'></div><div><center><h1>Íå íàéäåí ôàéë config.php</h1></center></div><div style='height: 40%;'></div></body></html>";
exit;
}


//cms
$cms = new cms();
$cms->start();
$cms->finish();
echo "<!-- total execution time: ".round((microtime(true)-$ms)*1000,2)." ms -->";

final class cms{

//data [
	private $module = array("name" => "", "action" => "main", "parameters" => array());
	private $page_data;
	private $url = "";
//data ]
	public function __construct(){//init
		$this->load_system_components();	
		//load_tmpl
		$this->detect_page_module();
		$this->load_page_module();
	}
//init [
	private function load_system_components(){
            require_once("cms/registry.php");
            require_once("cms/database.php");
            DataBase::connect();
            require_once("cms/templates.php");
            require_once("cms/loader.php");
            require_once("cms/modules.php");
	}

	private function detect_page_module(){
		$URI = $this->detect_page_uri();
                registry::set("cms.def_module",config::def_module);
		$this->module['name'] = registry::get("cms.def_module");
		if($URI != "/"){
			$parts = explode("/",ltrim($URI," /"));
			foreach($parts as $num=>$val){
				if($num == 0){$this->module['name'] = $val; continue;}
				if($num == 1){$this->module['action'] = $val; continue;}
				if(($num%2) == 0 && isset($parts[$num+1])){$this->module['parameters'][rawurldecode($val)] = rawurldecode($parts[$num+1]);}
			}
		}
	}

	private function load_page_module(){
		$module_class = "module_".$this->module['name'];
		if(class_exists($module_class)){
			$page_module = new $module_class();
			if($page_module instanceof int_module){
				$this->page_data['content'] = $page_module->processAction($this->module['action'],$this->module['parameters']);
				$this->page_data['title'] = $page_module->getPageTitle();
				$this->page_data['meta_desc'] = $page_module->getPageDescription();
				$this->page_data['meta_keys'] = $page_module->getPageKeyWords();
			}//else registry-404_message
		}else{
			$this->page_data['content'] = "<h2>Страница не найдена.</h2><p>Страница с адресом ".$this->url." не найдена.</p>";
			$this->page_data['title'] = "404";
		}
	}
//init ]

//start [
	public function start(){
		
            $page = file_get_contents("tmpl/".config::def_template."/main.tpl");//old
            $page = templates::addScripts($page,$this->page_data,"main.tpl");	
            $page = str_replace("%adress%", config::site_url, $page);		
            echo $page;
	}
//start ]

//finish [

        public function finish(){
            DataBase::disconnect();
        }
                
//finish ]        
        
//util [
	private function detect_page_uri(){
		if (isset($_SERVER['REQUEST_URI'])){ $uri = $_SERVER['REQUEST_URI']; }
		elseif(isset($_SERVER['argv'])){ $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['argv'][0]; }
		elseif (isset($_SERVER['QUERY_STRING'])){ $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING']; }
		else{ $uri = $_SERVER['SCRIPT_NAME']; }
		$uri = '/'. ltrim($uri, '/');
		$url = "http://".$_SERVER['SERVER_NAME'].$uri;
		$uri = str_replace(config::site_url,"",$url);
		$this->url = $url;
		return $uri;
	}
//util ]
}
//class autoload
function __autoload($class){

        $pos = strpos($class,"_");
        if($pos){$type = substr($class,0,$pos);$ext = substr($class,$pos+1);}

        switch($type){

                case "module": if(is_file(__DIR__."/cms/extensions/".$ext."/module.php")){include_once __DIR__."/cms/extensions/".$ext."/module.php";} break;

        }
}
