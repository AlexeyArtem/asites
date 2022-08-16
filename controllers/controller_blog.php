<?php 

class ControllerBlog extends Controller
{
    function actionIndex()
    {
        $this->view->generate('view_blog.php', 'view_template.php');
    }
}

?>