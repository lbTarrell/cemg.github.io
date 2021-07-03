<?php
class DB {
	
	private static $_instance;
	private static $_connectSource;
	private $_dbConfig = array(
		'host' => '127.0.0.1',
		'user' => 'root',
		'password' => '%cemg300720',
		'database' => 'ABD_210701'


	);
	private $_fields = "*";
	private $_table;
	private $_where;
	private $_orderby;

	private function __construct() {

	}

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function connect() {
		if(!self::$_connectSource) {
			// self::$_connectSource = mysqli_connect($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password']);
			self::$_connectSource = new PDO("mysql:host=" . $this->_dbConfig['host'] . ";dbname=" . $this->_dbConfig['database'] . ";charset=utf8", $this->_dbConfig['user'], $this->_dbConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			self::$_connectSource->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			if(!self::$_connectSource) {
				die('mysql connect error');
			}
		}
		return $this;
	}

	public function from($table) {
		$this->_table = $table;
		return $this;
	}

	public function field($fields){
		$this->_fields = $fields;
		return $this;
	}

	public function orderBy($field, $sort = 'DESC') {
		if($this->_orderby) {
			$this->_orderby .= ' , ' . $field . ' ' . $sort;
		} else {
			$this->_orderby = ' ORDER BY ' . $field . ' ' . $sort;
		}
		return $this;
	}

	/**
	 * "id=1 AND name='test'"
	 * ["id" => 1, "name" => 'test']
	 * 
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function where($where){
		if(is_array($where)) {
			foreach ($where as $key => $value) {
				$str .= $key . '=' . $value . " AND ";
			}
			$where = rtrim($str, ' AND ');
		}
		if($this->_where) {
			$this->_where .= " AND " . $where;
		} else {
			$this->_where = $where;
		}
		return $this;
	}

	public function orWhere($where) {
		if(is_array($where)) {
			foreach ($where as $key => $value) {
				$str .= $key . '=' . $value . " OR ";
			}
			$where = rtrim($str, ' OR ');
		}
		if($this->_where) {
			$this->_where .= " OR " . $where;
		} else {
			$this->_where = $where;
		}
		return $this;
	}

	public function findOne() {
		$sql = "SELECT " . $this->_fields . " FROM " . $this->_table;
		if($this->_where) {
			$sql .= " WHERE " . $this->_where;
		}
		$sql .= " LIMIT 1";
		$rows = self::$_connectSource->query($sql);
		return $rows->FetchAll();
	}

	public function findAll() {
		$sql = "SELECT " . $this->_fields . " FROM " . $this->_table;
		if($this->_where) {
			$sql .=  " WHERE " . $this->_where;
		}
		$sql .= $this->_orderby;
		$rows = self::$_connectSource->query($sql);
		return $rows->FetchAll();
	}

	public function update($data) {
		if(is_array($data)) {
			foreach ($data as $key => $value) {
				$str .= '`' . $key . '`' . "=" . $value . " , ";
			}
		}
		$sql = "UPDATE `" . $this->_table . "` SET " . rtrim($str, " , ");
		if($this->_where) {
			$sql .=  " WHERE " . $this->_where;
		}
		return self::$_connectSource->exec($sql);
	}
}
?>