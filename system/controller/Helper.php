<?php

namespace Controller;
class Helper
{

    /**
     * Show success message
     * @return void
     */
    public static function showSuccess():void
    {
        if (!isset($_SESSION['success'])) return;
        echo '<div class="alert alert-success" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['success'] . '</p>
      </div>';
        unset($_SESSION['success']);
    }

public static function parseLink ($newImagePath){
    return substr($newImagePath,strpos($newImagePath,'assets/images/'));
}

    /**
     * Show error message
     * @return void
     */
    public static function showError(): void
    {
        if (!isset($_SESSION['postmakerError'])) return;
        echo '<div class="alert alert-error" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['postmakerError'] . '</p></div>';
        unset($_SESSION['postmakerError']);
    }
}
