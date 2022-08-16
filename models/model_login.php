<?php 

	class ModelLogin extends Model
	{
        private $db;
		
		function __construct()
		{
			$this->db = new Db();
		}

		function loginUser(string $login, string $password)
		{
            $status['hasLogin'] = false;
            $status['hasPassword'] = false;

            $sql = "SELECT * from admins where Login = '$login'";
            $result = $this->db->query($sql);
            $rowsArr = $result->fetchAll();

            if(count($rowsArr) == 0) return $status;
            $status['hasLogin'] = true;
            
            if($rowsArr[0]['Password'] != $password) return $status;
            $status['hasPassword'] = true;

            //старт новой сессии и запись в суперглобальный массив данных об авторизованном пользователе
            //session_start();
            $_SESSION['logged_user'] = $login;

            return $status;
        }
        
        function logoutUser()
        {
            unset($_SESSION['logged_user']);
            session_destroy();
        }
	}

?>