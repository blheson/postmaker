<?php
namespace Controller;
class Debug {
    /** 
     * Pretty print data
     * @param mixed $data
     * @return void
     * 
     */
    public static function dd($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

}
?>