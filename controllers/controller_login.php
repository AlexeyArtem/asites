<?php 

class ControllerLogin extends Controller
{
    private $data;
    function __construct()
    {
        $this->view = new View();
        $this->model = new ModelLogin();
    }
    
    function actionIndex()
    {
        if(isset($_SESSION['logged_user']))
        {
            header("location: ../admin");
            return;
        }
        $this->view->generate('view_login.php', 'view_template.php', $this->data);
    }

    function actionLogin()
    {
        if(isset($_SESSION['logged_user']))
        {
            header("location: ../admin");
            return;
        }

        if(isset($_POST['login']) and isset($_POST['password']))
        {
            $login = $_POST['login'];
            $password = $_POST['password'];
        }
        else throw new Exception("Ошибка в передаче POST-параметров.");

        $status = $this->model->loginUser($login, $password);

        if($status['hasLogin'] == false or $status['hasPassword'] == false)
        {
            $this->data['login'] = $login;
            $this->data['password'] = $password;

            if($status['hasLogin'] == false) $this->data['error'] = "Логин не найден.";
            elseif($status['hasPassword'] == false) $this->data['error'] = "Неправильно введен пароль.";
            
            $this->actionIndex();
        }
        else
        {
            header("location: ../admin");
        }
    }

    function actionLogout()
    {
        if(isset($_SESSION['logged_user']))
        {
            $this->model->logoutUser();
            header("location: /");
        }
        else header("location: /");
    }

}

?>