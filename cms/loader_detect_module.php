<?php
if (isset($_SERVER['REQUEST_URI'])){
	$uri = $_SERVER['REQUEST_URI'];
}elseif(isset($_SERVER['argv'])) {
	$uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['argv'][0];
}elseif (isset($_SERVER['QUERY_STRING'])) {
	$uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
}else {
	$uri = $_SERVER['SCRIPT_NAME'];
}
$uri = '/'. ltrim($uri, '/');

$url = "http://".$_SERVER['SERVER_NAME'].$uri;
$self = str_replace(config::site_url,"",$url);

$module = config::def_module;
$action = "main";
$parameters = "";
if($self != "/"){
	$parts = explode("/",ltrim($self," /"));

	foreach($parts as $num=>$val){
		if($num == 0){$module = $val; continue;}
		if($num == 1){$action = $val; continue;}
		if(($num%2) == 0 && isset($parts[$num+1])){$parameters[rawurldecode($val)] = rawurldecode($parts[$num+1]);}
	}
}
//old
/*
$pg = explode("/",$self);
if(isset($pg['2'])){
$pg_comp = $pg['2'];
foreach($pg as $num=>$cont){
	
	if($num > 2) $pg_pars .= $pg[$num].",";
	
}
$pg_pars = substr($pg_pars, 0, -1);
}
else{$pg_comp = "";$pg_pars = "";}
*/
//old end
