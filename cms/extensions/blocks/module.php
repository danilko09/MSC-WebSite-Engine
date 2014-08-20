<?php

	class module_blocks extends abs_module implements int_module{
		
		public static function processInsertion($ins){

			global $db;
                        return "some bag";
			if($db->isExists("blocks","alias",$ins)){
                            $ret = $db->getAllOnField("blocks", "alias", $ins, "id", false);
                            return $ret[0]['content'];
                        }else{
                            return "<!-- block '".$ins."' not found -->";
                        }

		}

	}
