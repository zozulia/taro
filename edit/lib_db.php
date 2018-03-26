<?php
require_once(__DIR__ . '/dbconfig.php');
class DBMySQLPDO{
    public $_rows;
    protected $_db;
    protected $result;
    
    public function execute($sql, $bSelect=true, $params=array()){
		//echo $sql. "<br />";
		$bSuccess = false;
		if (isset($params[0]))
		{
			$this->result = $this->_db->prepare($sql);
			if ($this->result->execute($params)) $bSuccess = true;
		}
        elseif ($this->result = $this->_db->query($sql)) $bSuccess = true;
		if($bSuccess)
		{
          $this->_rows = array();
		  //print_r($this->result->errorInfo()); 
          if($bSelect && is_object($this->result)){
            if ($this->result->rowCount() > 0){
                $i = 0;
                while( $row = $this->result->fetch(PDO::FETCH_ASSOC) ) {
                    $this->_rows[$i]=$row;
                    $i++;
                }
            }
          }
          else {return $this->_db->lastInsertId();}
		  return true;
        }
		else{
			echo $sql; print_r($params); if (is_object($this->result)) print_r($this->result->errorInfo()); echo DBName2 . '; ';
			return FALSE;
		}
		//echo $this->_db->error;
    }
    
    public function get_results($sql)
    {
		$this->execute($sql);
		return $this->_rows;
	}
    
    public function __construct($DBHost = DBHost2, $DBUser=DBUser2, $DBPassword=DBPassword2, $DBName=DBName2, $DBCharSet=DBCharSet2) {
		$param = "mysql:host=$DBHost;dbname=$DBName;charset=$DBCharSet";
		$this->_db = new PDO($param, $DBUser, $DBPassword);
        $this->_db->query("SET NAMES 'utf8'");
        $this->_db->query("SET default-character-set '".$DBCharSet."'");
        $this->_db->query("SET CHARACTER_SET_CLIENT '".$DBCharSet."'");
        $this->_db->query("SET CHARACTER_SET_RESULTS '".$DBCharSet."'");
        $this->_db->query("SET AUTOCOMMIT=1");
    }
    
    public function prepare($sql)
    {
		return $sql;
	}
    
    public function get_var( $sql )
    {
		$this->execute( $sql, $column='page_id');
		if (isset($this->_rows[0]))
			return $this->_rows[0]->$column;
		else
			return false;
	}
    
    public function freeDB()
    {
       $this->_db->close();
    }
	
	public function updatedCount()
	{
		return $this->result->rowCount();
	}
}
?>
