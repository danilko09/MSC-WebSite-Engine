<?php

final class DataBase {

    private static $mysqli = null;

    public static function connect(){
        self::$mysqli = new mysqli(config::db_host, config::db_user,config::db_pass, config::db_name);
        self::$mysqli->query("SET NAMES 'utf8'");
    }

    private static function query($query){
        if(self::$mysqli == null){
            self::connect();
        }
        return self::$mysqli->query($query);
    }

    private static function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = ""){
        for($i = 0; $i < count($fields); $i++){
            if((strpos($fields[$i], "(") === false) && ($fields[$i] != "*" )){$fields[$i] = "`".$fields[$i]."`";}
        }
        $fields = implode(",", $fields);
        $table_name = config::db_pref.$table_name;
        if(!$order){$order = "ORDER BY `id`";}
        else{
            if($order != "RAND()"){
                $order = "ORDER BY `$order`"; if(!$up){$order .= " DESC";}
            }else{$order = "ORDER BY $order";}
        }
        if($limit){$limit = "LIMIT $limit";}
        if($where){$query = "SELECT $fields FROM $table_name WHERE $where $order $limit";}
        else{$query = "SELECT $fields FROM $table_name $order $limit";}
        $result_set = self::query($query);
        if(!$result_set){return false;}
        $i = 0; $data = null;
        while($row = $result_set->fetch_assoc()){ $data[$i] = $row; $i++; }
        $result_set->close();
        return $data;
    }

    public static function insert($table_name, $new_values){
        $table_name = config::db_pref.$table_name;
        $fields = ""; $values = "";
        foreach($new_values as $field=> $value){$fields .= "`".$field."`,";}
        foreach($new_values as $value){$values .= "'".addslashes($value)."',";}
        return self::query("INSERT INTO ".$table_name." (".substr($fields, 0, -1).") VALUES (".substr($values, 0, -1).")");
    }

    private static function update($table_name, $upd_fields, $where){
        $table_name = config::db_pref.$table_name;
        $query = "";
        foreach($upd_fields as $field=> $value){$query .= "`$field` = '".addslashes($value)."',";}
        if($where){
            return self::query("UPDATE ".$table_name." SET ".substr($query, 0, -1)." WHERE ".$where);
        }else{return false;}
    }

    /* public static function delete($table_name, $where = "")
      {
      $table_name = config::db_pref.$table_name;
      if($where)
      {
      $query = "DELETE FROM $table_name WHERE $where";
      return self::query($query);
      }
      else return false;
      }

      public static function deleteAll($table_name)
      {
      $table_name = config::db_pref.$table_name;
      $query = "TRUNCATE TABLE `$table_name`";
      return self::query($query);
      } */

    public static function getField($table_name, $field_out, $field_in, $value_in){
        $data = self::select($table_name, array($field_out),
                        "`$field_in`='".addslashes($value_in)."'");
        if(count($data) != 1){return false;}
        return $data[0][$field_out];
    }

    public static function getFieldOnID($table_name, $id, $field_out){
        if(!self::existsID($table_name, $id)){return false;}
        return self::getField($table_name, $field_out, "id", $id);
    }

    public static function getAll($table_name, $order, $up){
        return self::select($table_name, array("*"), "", $order, $up);
    }

    public static function deleteOnID($table_name, $id){
        if(!self::existsID($table_name, $id)){return false;}
        return self::delete($table_name, "`id` = '$id'");
    }

    public static function setField($table_name, $field, $value, $field_in, $value_in){
        return self::update($table_name, array($field=>$value),
                        "`$field_in`='".addslashes($value_in)."'");
    }

    public static function setFieldOnID($table_name, $id, $field, $value){
        if(!self::existsID($table_name, $id)){return false;}
        return self::setField($table_name, $field, $value, "id", $id);
    }

    public static function getElementOnID($table_name, $id){
        if(!self::existsID($table_name, $id)){return false;}
        $arr = self::select($table_name, array("*"), "`id` = '$id'");
        return $arr[0];
    }

    public static function getAllOnField($table_name, $field, $value, $order, $up){
        return self::select($table_name, array("*"),
                        "`$field` = '".addslashes($value)."'", $order, $up);
    }

    public static function getLastID($table_name){
        $data = self::select($table_name, array("MAX(`id`)"));
        return $data[0]["MAX(`id`)"];
    }

    public static function getRandomElements($table_name, $count){
    return self::select($table_name, array("*"), "", "RAND()", true, $count);
    }

    public static function getCount($table_name){
        $data = self::select($table_name, array("COUNT(`id`)"));
        return $data[0]["COUNT(`id`)"];
    }

    public static function isExists($table_name, $field, $value){
        $data = self::select($table_name, array("id"),
                        "`$field` = '".addslashes($value)."'");
        if(count($data) === 0){return false;}
        return true;
    }

    private static function existsID($table_name, $id){
        if(!self::validID($id)){return false;}
        $data = self::select($table_name, array("id"),
                        "`id`='".addslashes($id)."'");
        if(count($data) === 0){return false;}
        return true;
    }

    public static function search($table_name, $words, $fields){
        $words = quotemeta(trim(mb_strtolower($words)));
        if($words == ""){return false;}
        $where = ""; $logic = "OR"; $arraywords = explode(" ", $words);

        foreach($arraywords as $key=> $value){ if(isset($arraywords[$key - 1])){$where .= $logic;}
            for($i = 0; $i < count($fields); $i++){ $where .= "`".$fields[$i]."` LIKE '%".addslashes($value)."%'";
                if(($i + 1) != count($fields)){$where .= " OR";}
            }
        }
        $results = self::select($table_name, array("*"), $where);
        if(!$results){return false;}
        $k = 0; $data = array();
        for($i = 0; $i < count($results); $i++){
            for($j = 0; $j < count($results); $j++){
                $results[$i][$fields[$j]] = mb_strtolower(strip_tags($results[$i][$fields[$j]]));
            }
            $data[$k] = $results[$i];
            $data[$k]["relevant"] = self::getRelevantForSearch($results[$i], $fields, $words);
            $k++;
        }
        return self::OrderResultSearch($data, "relevant");
    }

    private static function getRelevantForSearch($result, $fields, $words){
        $relevant = 0;
        $arraywords = explode(" ", $words);
        for($i = 0; $i < count($fields); $i++){
            for($j = 0; $j < count($arraywords); $j++){
                $relevant += substr_count($result[$fields[$i]], $arraywords[$j]);
            }
        }
        return $relevant;
    }

    private static function OrderResultSearch($data, $order){
        for($i = 0; $i < count($data) - 1; $i++){
            $k = $i;
            for($j = i + 1; $i < count($data); $i++){
                if($data[$j][$order] > $data[$k][$order]){$k = $j;}
            }
            $temp = $data[$k];
            $data[$k] = $data[$i];
            $data[$i] = $temp;
        }
        return $data;
    }

    public static function disconnect(){
        if(self::mysqli){self::$mysqli->close();}
    }

    private static function validID($id){
        if(!self::isIntNumber($id)){return false;}
        if($id <= 0){return false;}
        return true;
    }

    private static function isIntNumber($number){
        if(!is_int($number) && !is_string($number)){return false;}
        if(!preg_match("/^-?(([1-9][0-9]*|0))$/", $number)){return false;}
        return true;
    }

}