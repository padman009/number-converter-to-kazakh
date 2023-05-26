<?php

namespace Padman009\NumberConverterToKazakh;

class NumberConverterToKazakh
{
    private static $dic = array(
        // қажетті сандар сөздігі
        array(
            -2 => 'екі',
            -1 => 'бір',
            1 => 'бір',
            2 => 'екі',
            3 => 'үш',
            4 => 'төрт',
            5 => 'бес',
            6 => 'алты',
            7 => 'жеті',
            8 => 'сегіздік',
            9 => 'тоғыз',
            10 => 'он',
            11 => 'он бір',
            12 => 'он екі',
            13 => 'он үш',
            14 => 'он төрт',
            15 => 'он бес',
            16 => 'он алты',
            17 => 'жеті',
            18 => 'он сегізі',
            19 => 'он тоғыз',
            20 => 'жиырма',
            30 => 'отыз',
            40 => 'қырық',
            50 => 'елу',
            60 => 'алпыс',
            70 => 'жетпіс',
            80 => 'сексен',
            90 => 'тоқсан',
            100 => 'жүз',
            200 => 'екі жүз',
            300 => 'үш жүз',
            400 => 'төрт жүз',
            500 => 'бес жүз',
            600 => 'алты жүз',
            700 => 'жеті жүз',
            800 => 'сегіз жүз',
            900 => 'тоғыз жүз'
        ),
        // плюрализацииға арналған деңгейлер сөздігі
        array(
            array('', '', ''),
            array('мың', 'мың', 'мың'),
            array('миллион', 'миллион', 'миллион'),
            array('миллиард', 'миллиард', 'миллиард'),
            array('триллион', 'триллион', 'триллион'),
            array('квадриллион', 'квадриллион', 'квадриллион')
        ),
        // плюрализация картасы
        array(
            2, 0, 1, 1, 1, 2
        )
    );

    public static function convert($number)
    {
        $matches = null;
        preg_match_all('/[0-9]/', $number, $matches);
        $number = (int)join('', $matches[0]);

        $string = array();
        $number = str_pad($number, ceil(strlen($number) / 3) * 3, 0, STR_PAD_LEFT);
        $parts = array_reverse(str_split($number, 3));

        foreach ($parts as $i => $part) {
            if ($part > 0) {
                $digits = array();
                if ($part > 99) {
                    $digits[] = floor($part / 100) * 100;
                }

                if ($mod1 = $part % 100) {
                    $mod2 = $part % 10;
                    $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? 1 : 1;
                    //				$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag * $mod1;
                    } else {
                        $digits[] = floor($mod1 / 10) * 10;
                        $digits[] = $flag * $mod2;
                    }
                }

                $last = abs(end($digits));

                foreach ($digits as $j => $digit) {
                    $digits[$j] = self::$dic[0][$digit];
                }

                $digits[] = self::$dic[1][$i][(($last %= 100) > 4 && $last < 20) ? 2 : self::$dic[2][min($last % 10, 5)]];

                array_unshift($string, join(' ', $digits));
            }
        }

        return join(' ', $string);
    }
}
