<?php

	class module_script extends abs_module implements int_module{
		
		public static function processInsertion($ins){

			return "script_".$ins;

		}

	}
