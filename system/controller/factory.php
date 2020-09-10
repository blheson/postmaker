<?php

namespace Controller;

include_once 'Debug.php';

use Debug;

class Factory extends Debug
{
    public function __construct()
    {
    }
    /**
     * @param array $class_details This contains 'class' name and 'namespace' name 
     */
    public function get_factory($class_details)
    {
        $class = $class_details['class'];
        $namespace = $class_details['namespace'];

        require $class . '.php';
        $ds = '\\';
        $full_namespace = $ds . $namespace . $class;
        return new $full_namespace;
    }
    public function get_unique_id()
    {
        return preg_replace('|[^0-9]|', '', session_id());
    }



    /**
     * Show success message
     * @return void
     */
    public function show_success()
    {
        if (!isset($_SESSION['success'])) return;
        echo '<div class="alert alert-success" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['success'] . '</p>
      </div>';
        unset($_SESSION['success']);
    }



    /**
     * Show error message
     * @return void
     */
    public function show_error()
    {
        if (!isset($_SESSION['error'])) return;
        echo '<div class="alert alert-error" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['error'] . '</p></div>';
        unset($_SESSION['error']);
    }
}
