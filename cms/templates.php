<?php

class templates{
	
		public static function insertion($m,$i){
			$f = "module_".$m;
			if(class_exists($f)){
				echo $f::processInsertion($i);
			}else{
				echo "<!-- can`t show insertion '".$m."_".$i."' -->";
			}
		}

		public static function getTmpl($file, $reply){
		
			if(is_file("tmpl/custom/".$file.".html")){
				$tmpl = file_get_contents("tmpl/custom/".$file.".html");
				foreach($reply as $pr => $vl){
				
					$tmpl = str_replace("%$pr%", $vl, $tmpl);
				
				}
			}else debug("���� ������� '$file.html' �� ������.");
			
			return $tmpl;
		
		}
		
		public static function addScripts($where,$vars = array(),$mark = "undefined"){
		
			$microtime = microtime(true);
			if(count($vars > 0)){
				foreach($vars as $var=>$val){$$var = $val;}
			}

			$compiled = preg_replace("/\[(.*)_(.*)\]/U","<?php templates::insertion('$1','$2'); ?>",$where);
			ob_start();
			if(@eval("?>".$compiled."<?php return true;")){
				ob_clean();
				eval("?>".$compiled);
				$ret = ob_get_clean();
				ob_end_clean();
				return "\r\n<!--tmpl mark: ".$mark." | vars count: ".count($vars)." | OK | START -->\r\n".$ret."\r\n<!--tmpl mark: ".$mark." | OK | END | time: ".round((microtime(true)-$microtime) * 1000, 2)."ms -->\r\n";
			}else{
				ob_end_clean();
				return "<!--tmpl mark: ".$mark." | compilation error -->";			
			}
		}
	
	}
