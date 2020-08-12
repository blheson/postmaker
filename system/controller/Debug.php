<?php
class Debug {
    /** 
     * Pretty print data
     * @param mixed $data
     * 
     */
    public function dnd($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

}
?>