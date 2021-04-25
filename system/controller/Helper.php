<?php

namespace Controller;

use Controller\Constant as constant;

class Helper
{

    /**
     * Show success message
     * @return void
     */
    public static function showSuccess(): void
    {
        if (!isset($_SESSION['success'])) return;
        echo '<div class="alert alert-success" style="color:#fff">
        <button type="button" style="color:#fff" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>' . $_SESSION['success'] . '</p>
      </div>';
        unset($_SESSION['success']);
    }
    /**
     * 
     */
    public static function parseLink(string $newImagePath)
    {
        return substr($newImagePath, strpos($newImagePath, constant::rootImgPath()));
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
    /**
     * Delete image
     * @param string $src File URL e.g https://domain.com/path_to_file.png
     * @return bool
     */
    public static function deleteImage(string $src)
    {

        if (is_bool(strpos($src, 'http')))
            return false;



        $src = constant::rootDir() . DIRECTORY_SEPARATOR . constant::relativeImgPath($src);
        if (!file_exists($src)) return false;

        return unlink($src);
    }
}
