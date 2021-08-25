<?php

namespace Controller;

class UsersController
{
    public static function get_unique_id(): int
    {
        return preg_replace('|[^0-9]|', '', session_id());
    }
}
