<?php
        if($_SESSION['comp_step'] == 0){
            if(filter_input(INPUT_POST,'ch') == null){
            $content .= "Установка компонентов.<br/><br/>Выберите, что хотите сделать: <br/>"
                    . "<form method='post'>"
                    . "<input type='radio' name='ch' value='yes'/>Я хочу выбрать нужные мне библиотеки и скрипты.<br/>"
                    . "<input type='radio' name='ch' value='no'/>Установить только основные скрипты и библиотеки.<br/>"
                    . "<input type='radio' name='ch' value='all' checked='' />Установить все.<br/>"
                    . "<input type='submit' value='' style='
                        text-decoration: none;
                        background: url(%adress%/setup/tmpl/next.png) no-repeat;
                        height: 47px;
                        width: 150px;
                        border: 0;
                        ' />"
                    . "</form>";
            }else{
                $ch = filter_input(INPUT_POST,'ch');
                if($ch == "yes"){$_SESSION['comp_step'] = 1;}
                elseif($ch == "no"){$_SESSION['comp_step'] = 2;$_SESSION['comp_set'] = "default";}
                else{$_SESSION['comp_step'] = 2;$_SESSION['comp_set'] = "all";}
            }
        }
        if($_SESSION['comp_step'] == 1 && filter_input(INPUT_POST,'step_up') != 1){
            $tmpl = getTmpl("components");
            
            $dir = opendir("sc_inst");
            
            while($d = readdir($dir)){if($d != "." && $d != ".." && !is_dir($d)){
                    $n = explode(".",$d);
                    if($n[1] == "php"){ 
                    $c = "";
                    require_once("sc_inst/".$d);
                    if(class_exists($n[0]) && method_exists(new $n[0](), "getTitle")){
                        $c = new $n[0]();
                        $comp[$n[0]]['title'] = $c->getTitle();
                    }}
                    
            }}
            
            $comp_str = "";
            foreach($pre_installed as $name=>$component){
                $comp_str .= "<input type='checkbox' name='$name' disabled='' checked='' />".$component['title']."<br/>\r\n";
            }foreach($comp as $name=>$component){
                $comp_str .= "<input type='checkbox' name='$name'";
                if($recommend[$name]['disabled'] == 1){$comp_str .= "disabled=''";}
                if($recommend[$name]['checked'] == 1){$comp_str .= "checked=''";}
                $comp_str .= " />".$component['title']."<br/>\r\n";
            }
            $_SESSION['comp_set'] = "custom";
            $content = str_replace("%comp%", $comp_str, $tmpl);
        }
        if($_SESSION['comp_step'] == 2 || filter_input(INPUT_POST,'step_up') == 1){
            if($_SESSION['comp_set'] == "default"){next_stage();}
            elseif($_SESSION['comp_set'] == "all"){
                while($d = readdir($dir)){if($d != "." && $d != ".." && !is_dir($d)){
                    $n = explode(".",$d);
                    if($n[1] == "php"){ 
                    $c = "";
                    require_once("sc_inst/".$d);
                    if(class_exists($n[0]) && method_exists(new $n[0](), "getTitle")){
                        $c = new $n[0]();
                        $c->install();
                    }}
                    
            }}next_stage();
            }elseif($_SESSION['comp_set'] == "custom"){
                foreach(filter_input_array(INPUT_POST) as $name=>$value){
                    if($name != "step_up" && $value == "on"){
                        require_once('sc_inst/'.$name.'.php');
                        $c = new $name();$c->install();
                    }
                }
                next_stage();
            }
        }
?>