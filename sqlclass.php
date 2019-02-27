<?php
	class SQL
	{
		private static $DB = null;
		
		public function connect(){
			self::$DB = mysqli_connect("localhost", "root", "123456", "yamap", 3306);
			return self::$DB;
		}
		
		public function query($querystr){
			if(self::$DB != null)
				return mysqli_query(self::$DB, $querystr);
			else
				return false;
		}
	}

	if(isset($_POST['action'])){
		if($_POST['action'] == 'query' and isset($_POST['query'])){
			$sql = new SQL();
			if($sql->connect())
				print(json_encode($sql->query($_POST['query'])));
		}
	}
?>
