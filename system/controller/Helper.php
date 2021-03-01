<?php

namespace Controller;
class Helper
{

    /**
     * Show success message
     * @return void
     */
    public static function show_success():void
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
    public static function show_error(): void
    {
        if (!isset($_SESSION['error'])) return;
        echo '<div class="alert alert-error" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['error'] . '</p></div>';
        unset($_SESSION['error']);
    }
}
