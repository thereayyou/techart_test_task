<?
class DB {
    private $host = "localhost";
    private $login = "root";
    private $password = "";
    private $dbname = "news";
    public $conn;

    public function __construct() {
      $this->conn = new mysqli($this->host, $this->login, $this->password, $this->dbname);
    }

    private function Filter($filter=[]) {
        $filter_sql = '';
        if($filter){
            foreach($filter as $key => $val){
                if(is_array($val)){
					$filter_sql .= ($filter_sql? ' AND ' : ''). '`' . $key . '`' . ' IN ("' . implode('","', $val) . '")';
                }else{
					if(preg_match('/([^a-zA-Z0-9_\\s][^a-zA-Z0-9_\\s])|[^a-zA-Z0-9_\\s]/mi', $key, $matches)){
                        if($matches[0]=="%"){
                            $operator = "LIKE";
                            $val = "%" . $val . "%";
                        }else{
                            $operator = $matches[0]=="!"? "<>" : $matches[0];
                        }
                        $key = str_replace($operator, '', $key);
					}else{
                        $operator = '=';
                    }
					$filter_sql .= ($filter_sql? ' AND ' : '') . '`' . $key . '`' . $operator . '"' . $val . '"';
                }
            }
            return 'WHERE ' . $filter_sql;
        }
        return '';

    }

    private function Select($select=[]) {
		if($select){
            $select_sql = '';
            foreach($select as $val){
				$select_sql .= ($select_sql ? ', ' : '') . "`" . $val . "`";
            }
            return $select_sql;
        }
        return '*';
    }

    private function Order($order=[]) {
		$order_sql = '';
        if($order){
            foreach($order as $key => $val){
                if(in_array(strtoupper(trim($val)), ["ASC","DESC","RAND"])) $order_sql .= ($order_sql? ', ' : '') . '`' . $key . '`' . ' ' . strtoupper(trim($val));
            }
            return 'ORDER BY ' . $order_sql;
        }
        return '';
    }

    private function Limit($top=0, $limit=10) {
		if((int)$limit > 0) return 'LIMIT ' . ((int)$top ? (int)$top . ', ' : '') . (int)$limit;
        else return 'LIMIT 10';
    }

    public function GetList($tablename = '', $filter=[], $select = [], $order=[], $top=0, $limit=0) {
        if(!$tablename) return 'ERROR: Название таблицы не определено!';

        $sql = '';
        $filter_sql = $this->Filter($filter);
		$select_sql = $this->Select($select);
		$order_sql = $this->Order($order);
		$limit_sql = $this->Limit($top, $limit);

        $sql = 'SELECT ' . $select_sql . " FROM `" . $tablename . "` " . $filter_sql . ' ' . $order_sql . ' ' . $limit_sql;

        if($result = $this->conn->query($sql)){ 
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{ 
            return $this->conn->error; 
        }
    }

    public function GetById($tablename = '', $id=0, $select = []) {
        if(!$tablename) return 'ERROR: Название таблицы не определено!';
		if(!(int)$id) return 'ERROR: ID не определено!';
		
        $sql = '';
		$select_sql = $this->Select($select);

        $sql = 'SELECT ' . $select_sql . " FROM `" . $tablename . "` WHERE ID=" . (int)$id;

        if($result = $this->conn->query($sql)){ 
            $result = $result->fetch_all(MYSQLI_ASSOC);
            return $result[0];
        }else{ 
            return $this->conn->error; 
        }
    }

    public function GetId($tablename = '', $filter = []) {
        if(!$tablename) return 'ERROR: Название таблицы не определено!';

        $sql = '';
        $filter_sql = $this->Filter($filter);

        $sql = "SELECT ID FROM `" . $tablename . "` " . $filter_sql;

        if($result = $this->conn->query($sql)){ 
            $result = $result->fetch_all(MYSQLI_ASSOC);
            return $result[0]['ID'];
        }else{ 
            return $this->conn->error; 
        }
    }

    // public function Update($tablename, $id, $arFields=[]) {
    //     if(!$tablename) return 'ERROR: Название таблицы не определено!';
	// 	if(!(int)$id) return 'ERROR: ID не определено!';
    //     $sql = '';

    //     foreach($arFields as $key => $field){
	// 		$sql .= ($sql? ', ' : '') . '`' . $key . '`' . '="' . $field . '"';
    //     }

    //     $sql = "UPDATE `" . $tablename . "` SET " . $sql . ' WHERE ID=' . (int)$id;

    //     if($result = $this->conn->query($sql)){ 
    //         return $id; 
    //     }else{ 
    //         return $this->conn->error; 
    //     }
    // }

    // public function Add($tablename, $arFields=[]) {
    //     if(!$tablename) return 'ERROR: Название таблицы не определено!';
	// 	$sql = $keys_sql = $values_sql = '';

    //     foreach($arFields as $key => $field){
	// 		if(in_array($key, array('ID','id'))) continue;
	// 		$keys_sql .= ($keys_sql ? ', ' : '') . '`' . $key . '`';
	// 		$values_sql .= ($values_sql ? ', ' : '') . '"' . $field . '"';
			
    //     }

    //     $sql = "INSERT INTO `" . $tablename . "` (" . $keys_sql . ') VALUES (' . $values_sql . ')';

    //     if($result = $this->conn->query($sql)){ 
    //         return $this->conn->insert_id; 
    //     }else{ 
    //         return $this->conn->error; 
    //     }
    // }

    // public function Remove($tablename = '', $id=0) {
    //     return '';
    //     if(!$tablename) return 'ERROR: Название таблицы не определено!';
    //     if(!(int)$id) return 'ERROR: ID не определено!';
        
    //     $sql = '';
    //     if(is_array($id)) $sql .= '`' . $key . '`' . ' IN ("' . implode('","', $id) . '")';

	// 	$sql = "DELETE FROM `" . $tablename . "` WHERE `" . $tablename . "`.ID" . (is_array($id)? $sql : '='.(int)$id);
		
    //     if($result = $this->conn->query($sql)){ 
    //         return 'success'; 
    //     }else{ 
    //         return $this->conn->error; 
    //     }
    // }

    public function Count($tablename = '', $filter = []) {
        if(!$tablename) return 'ERROR: Название таблицы не определено!';

        $sql = '';
        $filter_sql = $this->Filter($filter);

        $sql = "SELECT COUNT(*) FROM `" . $tablename . "` " . $filter_sql;

        if($result = $this->conn->query($sql)){ 
            $result = $result->fetch_all(MYSQLI_ASSOC);
            return $result[0]["COUNT(*)"];
        }else{ 
            return $this->conn->error; 
        }
    }
}
?>