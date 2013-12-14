<?php

	class templates{
		
		var $blocks;
		var $file_name = "index";
		public $template_name = "default";
		var $out = "0";
		public $parse_content = "1";
		
		public function SiteOut($content){
		
			$db = libs::GetLib("database");$this->blocks = $db->getAll("blocks",true,true);$this->out = "1";
			
                        if(!is_dir("tmpl")){die("Не существует папка 'tmpl', вывод страницы не возможен.");}
		
                        if($this->template_name == ""){$this->template_name="default";}
			
                        if(!is_dir('tmpl/'.$this->template_name) && !is_dir('tmpl/default')){die('Не существует папки текущего шаблона и файлов стандартного шаблона.');}
		
                        if(!is_file("tmpl/".$this->template_name."/".$this->file_name.".html")){die('Не удалось найти основной файл шаблона, вывод страницы не возможен.');}
			$tmpl = file_get_contents("tmpl/".$this->template_name."/".$this->file_name.".html");
                        if($this->parse_content === "1"){$tmpl = str_replace("<content/>",$content, $tmpl);}
			$out = $this->PrepearHTML($tmpl);
                        if($this->parse_content === "0"){$out = str_replace("<content/>",$content, $out);}
			echo str_replace("%adress%",config::site_url,str_replace("%tmpl_root%",config::site_url."/tmpl/".$this->template_name,$out));
			
		}
                
                public function GetContent(){
                    
                }
		
		public function PrepearHTML($text){
		
                        $text_reply = "";
                        preg_match_all("|<content (.*)/>|U", $text, $text_reply, PREG_SPLIT_NO_EMPTY);
                        
			foreach($text_reply[1] as $num=>$args){
				$arr = explode(" ", $args);
				foreach($arr as $value){
                                    $tag_tmp = explode("=",$value);$tag[$tag_tmp[0]] = str_replace("\"","",str_replace("'","",$tag_tmp[1]));
				}
				$text = str_replace($text_reply[0][$num],libs::GetLib("templates_types")->GetContentByTag($tag),$text);$tag = "";
                        }
			if(count($text_reply[0]) > 0){$text = $this->PrepearHTML($text);}return $text;
		}
		
		public function SetPageTmpl($name){
	
			if($this->out == "0"){
                                if(is_file("tmpl/".$this->template_name."/".$name.".html")){$this->file_name = $name;}
				elseif(is_file("tmpl/default/".$name.".html")){$this->template_name = "default"; $this->file_name = $name;}
                                else{return false;}
                        }else{return false;}
		
		}
		
		
		public function getTmpl($name){
			
                        if(is_file("tmpl/".$this->template_name."/".$name.".html")){$return = file_get_contents("tmpl/".$this->template_name."/".$name.".html");}
                        elseif(is_file("tmpl/scripts/".$name.".html")){$return = file_get_contents("tmpl/scripts/".$name.".html");}
                        else{$return = str_replace("[file]", "%tmpl_root%/".$name.".html",file_get_contents("tmpl/".$this->template_name."/tmpl_not_found.html"));}
		
			return $return;
		
		}
		
		public function getRTmpl($name,$arr){
		
			$return = $this->getTmpl($name);
			if($return == str_replace("[file]", "%tmpl_root%/".$name.".html",file_get_contents("tmpl/".$this->template_name."/tmpl_not_found.html"))){
			
				$return = "<div><p>Во вроемя вывода шаблона возникла ошибка, но вам было передано следующее:</p>";
			
				foreach( $arr as $code=>$val ){$return .= "<p>$code: <br/>$val</p>";}
				
				$return .= "</div>";
			
				return $return;
			
			}else{
			
				foreach($arr as $code=>$val){$return = str_replace("[".$code."]",$val,$return);}
				
				return $return;
			
			}
		
		}
	
	}

?>