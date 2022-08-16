<?php 

	class ModelAdmin extends Model
	{
		private $db;
		
		function __construct()
		{
			$this->db = new Db();
		}

		function getStat()
		{
			$sql = "SELECT * from analysis_requests";
			$params = [];
			$pdoState = $this->db->query($sql, $params);

			$rows = $pdoState->fetchAll();

			return $rows;
		}
	}

?>