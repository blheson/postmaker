<?php

namespace Controller\Common;

class Color
{
    /**
     * Convert Hex code to RGB
     * @param string $hex
     * @return array $bg
     */
    public static function convert_hex_to_rgb(string $hex): array
    {
        $strlen = strlen($hex);
        // if string lenght is not 6 terminate
        if ($strlen !== 6)
            exit;

        $i = 0;
        $bg = [];
        while ($i <= $strlen) {
            $char = substr($hex, $i, 2);

            if (is_numeric($char[0])) {
                $first = $char[0];
            } else {
                $first = self::convert_alphabet_to_number($char[0]);
            }
            if (is_numeric($char[1])) {
                $second = $char[1];
            } else {
                $second = self::convert_alphabet_to_number($char[1]);
            }
            $bg[] = ($first * 16) + $second;
            $i += 2;
            if ($i == 6)
                break;
        }
        return $bg;
    }
    /**
     * Convert alphabet to number
     * @param $char string
     * @return int $digit
     */
    public static function convert_alphabet_to_number(string $char): int
    {
        $num = [
            'a' => 10,
            'b' => 11,
            'c' => 12,
            'd' => 13,
            'e' => 14,
            'f' => 15
        ];
        $digit = $num[$char];
        return $digit;
    }
    /**
     * Get array of color
     * @param Resource $im
     * @param array $color
     * 
     * @return array
     */
    public static function get_color($im, $color = null)
    {
        $orange = imagecolorallocate($im, 220, 210, 60);
        $white  = imagecolorallocate($im, 255, 255, 255);
        $black  = imagecolorallocate($im, 0, 0, 0);
        if ($color != null) {
            return imagecolorallocate($im, $color[0], $color[1],$color[2]);
        }
        return ['orange' => $orange, 'white' => $white, 'black' => $black];
    }
}
