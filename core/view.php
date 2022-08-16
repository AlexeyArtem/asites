<?php 

class View {

    function generate($content_view, $template, $data = null)
    {
        include 'views/' . $template;
    }

}

?>