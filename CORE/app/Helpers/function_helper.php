<?php

if (!function_exists('rupiah')) {

    /**
     * Function for formatting number to currency (Rupiah)
     * 
     * How to use:
     * rupiah(1000) => 1.000
     * 
     * @param int $int
     * @return string
     */
    function rupiah(int $int): string
    {

        return (string) number_format($int, 0, ',', '.');
    }
}

if (!function_exists('decode_stdClass')) {

    /**
     * Function to decode stdClass
     * 
     * @param stdClass $stdClass
     * @return array
     */
    function decode_stdClass(stdClass $stdClass): array
    {

        return (array) json_decode(json_encode($stdClass), true);
    }
}

if (!function_exists('is_string_base64')) {

    /**
     * Function to check if string is base64 or not
     * 
     * How to use: 
     * is_string_base64('owhih129eh') => False
     * 
     * @param string $string
     * @return boolean
     */
    function is_string_base64(string $string = ''): bool
    {

        return (bool) base64_encode(base64_decode($string, true)) === $string;
    }
}

if (!function_exists('appendDate')) {

    /**
     * Function to Add and Subtract Date
     * 
     * How to use:
     * appendDate('2000-12-5', 10) => '2000-12-15'
     * 
     * @param string $date Format: yyyy-mm-dd
     * @param int $append The amount of addition and subtraction of date
     * @return string
     */
    function appendDate(string $date, int $append)
    {

        // Convert date to TTL
        $dtTimestamp = strtotime($date);

        // Times $append with TTL (1 Day)
        $appendTTL = 86400 * $append;

        // Add First TTL with append TTL
        $finalDtTimestamp = $dtTimestamp + $appendTTL;

        // Return string in y-m-d format
        return date('Y-m-d', $finalDtTimestamp);
    }
}

if (!function_exists('censorText')) {

    /**
     * Function to replace characters in string with "*"
     * 
     * How to use:
     * censorText('owaieofawdokjda', 6) => 'owa***jda'
     * 
     * @param string $string        Input string
     * @param int $showTextLength   Length of chars that want to display
     * @return string
     */
    function censorText(string $string, int $showTextLength)
    {

        $showTextLength = abs($showTextLength);

        if (
            $showTextLength < strlen($string)
            || $showTextLength >= 2
        ) {

            $show = [
                'start' => ceil(($showTextLength) / 2),
                'end' => floor(($showTextLength) / 2)
            ];

            $returnText = '';

            for ($i = 0; $i < strlen($string); $i++) {

                if ($i + 1 <= $show['start']) {

                    $returnText .= $string[$i];
                } else if ($i + 1 > strlen($string) - $show['end']) {

                    $returnText .= $string[$i];
                } else {

                    $returnText .= '*';
                }
            }

            return $returnText;
        } else if ($showTextLength == 1) {

            return $string[0] . substr($string, 1, strlen($string) - 1);
        } else {

            return $string;
        }
    }
}

if (!function_exists('removeNullValues')) {

    /**
     * Fungsi untuk menghapus key dari array asosiatif yang memiliki value null.
     *
     * @param array $array Array asosiatif yang akan dihapus key dengan value null.
     * @return array Array baru tanpa key yang memiliki value null.
     */
    function removeNullValues(array $array)
    {
        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }
}

if (!function_exists('round_number')) {

    /**
     * Function to round number
     * 
     * @param int $number
     * @return int
     */
    function round_number(int $number)
    {

        $number = number_format($number, 1, '.', '');

        $expNumber = explode('.', $number);
        $expNumber[1] >= 5 ? $expNumber[1] = '5' : $expNumber[1] = '0';

        $number = $expNumber[0] . '.' . $expNumber[1];

        return $number;
    }
}

if (!function_exists('round_unit')) {

    /**
     * Function to round a number with its unit
     * 
     * @param int $number   Number input
     * @param string $unit  WEIGHT|LENGTH|QUANTITY
     * @return string
     */
    function round_unit(int $number, string $unit)
    {

        $unit = strtoupper($unit);

        $unitCode = [
            'WEIGHT' => ['gr', 'kg', 'kg', 'kg'],
            'LENGTH' => ['m', 'km', 'km', 'km'],
            'QUANTITY' => ['', 'rb', 'jt', 'mly']
        ];

        if ($number >= 1000000000) {

            // More than 1 billion
            $number = substr(strval($number), 0, -9);
            $unit = $unitCode[$unit][3];
        } else if ($number >= 1000000) {

            // More than 1 million
            $number = substr(strval($number), 0, -6);
            $unit = $unitCode[$unit][2];
        } else if ($number >= 1000) {

            // More than 1 kilo
            $number = substr(strval($number), 0, -3);
            $unit = $unitCode[$unit][1];
        } else {

            // Less than 1 kilo
            $number = strval($number);
            $unit = $unitCode[$unit][0];
        }

        return $number . $unit;
    }
}

if (!function_exists('var_isset')) {

    /**
     * Function to check if variable available or not and print default string if variable not available
     * 
     * @param variable &$var
     * @param mixed $alter_val  Alternative value when variable not available
     * @return mixed
     */
    function var_isset(&$var, $alter_val = '')
    {

        if (!isset($var)) return $alter_val;
        return $var;
    }
}

if (!function_exists('full_current_url')) {

    function full_current_url()
    {

        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if (!function_exists('last_item')) {

    function last_item(array $array)
    {

        return $array[count($array) - 1];
    }
}

if (!function_exists('build_url')) {

    function build_url(array $urlComponent)
    {

        $buildUrl = '';

        if (isset($urlComponent['scheme'])) $buildUrl .= $urlComponent['scheme'] . '://';
        if (isset(
            $urlComponent['user'],
            $urlComponent['pass']
        )) $buildUrl .= $urlComponent['user'] . ':' . $urlComponent['pass'] . '@';
        if (isset($urlComponent['host'])) $buildUrl .= $urlComponent['host'];
        if (isset($urlComponent['port'])) $buildUrl .= ':' . $urlComponent['port'];
        if (isset($urlComponent['path'])) $buildUrl .= $urlComponent['path'];
        if (isset($urlComponent['query'])) $buildUrl .= '?' . $urlComponent['query'];
        if (isset($urlComponent['fragment'])) $buildUrl .= '#' . $urlComponent['fragment'];

        return $buildUrl;
    }
}

if (!function_exists('convert_month')) {

    function convert_month(
        $month,
        String $format = 'FULL',
        String $lang = 'EN'
    ) {

        $month_name = [];

        switch ($lang) {

            case 'ID':

                $month_name = [
                    'januari', 'februari', 'maret',
                    'april', 'mei', 'juni',
                    'juli', 'agustus', 'september',
                    'oktober', 'november', 'desember'
                ];
                break;

            case 'EN':
            default:

                $month_name = [
                    'january', 'february', 'march',
                    'april', 'may', 'june',
                    'july', 'august', 'september',
                    'october', 'november', 'desember'
                ];
                break;
        }

        if ($format == 'FULL') {

            return $month_name[(intval($month) - 1)];
        } else if ($format == 'SHORT') {

            return substr($month_name[(intval($month) - 1)], 0, 3);
        }
    }
}

if (!function_exists('convert_ymdhi')) {

    // Hours to TTL
    function convert_ymdhi(
        $date = null,
        String $format = 'FULL',
        String $lang = 'EN'
    ) {

        if ($date == null) return null;

        $splitDate = explode(' ', $date);
        $exp = explode('-', $splitDate[0]);

        return $exp[2] . ' ' . ucfirst(convert_month($exp[1], $format, $lang)) . ' ' . $exp[0] . ', ' . substr($splitDate[1], 0, 5);
    }
}

if (!function_exists('convert_ymd')) {

    // Hours to TTL
    function convert_ymd(
        String $date = null,
        String $format = 'FULL',
        String $lang = 'EN'
    ) {

        if ($date == null) return null;

        $exp = explode('-', $date);

        return $exp[2] . ' ' . ucfirst(convert_month($exp[1], $format, $lang)) . ' ' . $exp[0];
    }
}

if (!function_exists('remove_special_chars')) {

    function remove_special_chars(string $string)
    {

        $string = str_replace(' ', '-', $string);

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}


if (!function_exists('appendMin')) {

    function appendMin(
        string $dateNow,
        int $append
    ) {

        $date = explode(' ', $dateNow)[0];
        $time = explode(':', explode(' ', $dateNow)[1]);
        $finalTime = null;

        while (1) {

            if ($append > 0) {

                // If append value is positive
                if (intval($time[1]) + $append >= 60) {

                    $append -= (60 - intval($time[1]));

                    $time[1] = '00';

                    if (intval($time[0]) + 1 >= 24) {

                        $time[0] = '00';

                        // Append date
                        $date = appendDate($date, 1);
                    } else {

                        $time[0] = intval($time[0]) + 1;

                        $time[0] = str_pad($time[0], 2, '0', STR_PAD_LEFT);
                    }
                } else {

                    $time[1] = intval($time[1]) + $append;

                    $time[1] = str_pad($time[1], 2, '0', STR_PAD_LEFT);

                    $append = 0;
                }
            } else if ($append < 0) {

                // If append value is negative
                if ($append + intval($time[1]) <= 0) {

                    $append += intval($time[1]);

                    $time[1] = '59';

                    if (intval($time[0]) - 1 < 0) {

                        $time[0] = '23';

                        $date = appendDate($date, -1);
                    } else {

                        $time[0] = intval($time[0]) - 1;

                        $time[0] = str_pad($time[0], 2, '0', STR_PAD_LEFT);
                    }
                } else {

                    $time[1] = intval($time[1]) + $append;

                    $time[1] = str_pad($time[1], 2, '0', STR_PAD_LEFT);

                    $append = 0;
                }
            } else {

                $finalTime = $date . ' ' . $time[0] . ':' . $time[1] . ':' . $time[2];

                break;
            }
        }

        // Return
        return $finalTime;
    }
}




if (!function_exists('strpos2')) {

    // Round number
    function strpos2(string $string, $find = null)
    {

        if ($find == null) return false;

        if (!is_array($find)) $find[] = $find;

        foreach ($find as $value) {

            if (strpos($string, $value)) return true;
        }

        return false;
    }
}

if (!function_exists('cookie_expires')) {

    // Cookie expired in hour
    function cookie_expires(int $int)
    {

        return time() + 3600 * $int;
    }
}

if (!function_exists('getcookie2')) {

    // Hours to TTL
    function getcookie2($cookie_name = null)
    {

        if ($cookie_name == null) return null;

        return isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : null;
    }
}
