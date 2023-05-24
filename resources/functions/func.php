<?php

function mb_ucfirst($str)
{
    $fc = mb_strtoupper(mb_substr($str, 0, 1));

    return $fc.mb_substr($str, 1);
}

function sanitize($filename)
{
    // remove HTML tags
    $filename = strip_tags($filename);
    // remove non-breaking spaces
    $filename = preg_replace("#\x{00a0}#siu", ' ', $filename);
    // remove illegal file system characters
    $filename = str_replace(array_map('chr', range(0, 31)), '', $filename);
    // remove dangerous characters for file names
    $chars = ['?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '’', '%20',
                   '+', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', '^', chr(0)];
    $filename = str_replace($chars, '_', $filename);
    // remove break/tabs/return carriage
    $filename = preg_replace('/[\r\n\t -]+/', '_', $filename);
    // convert some special letters
    $convert = ['Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss',
                     'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'];
    $filename = strtr($filename, $convert);
    // remove foreign accents by converting to HTML entities, and then remove the code
    $filename = html_entity_decode($filename, ENT_QUOTES, 'utf-8');
    $filename = htmlentities($filename, ENT_QUOTES, 'utf-8');
    $filename = preg_replace('/(&)([a-z])([a-z]+;)/i', '$2', $filename);
    // clean up, and remove repetitions
    $filename = preg_replace('/_+/', '_', $filename);
    $filename = preg_replace(['/ +/', '/-+/'], '_', $filename);
    $filename = preg_replace(['/-*\.-*/', '/\.{2,}/'], '.', $filename);
    // cut to 255 characters
    // $filename = substr($data, 0, 255);
    // remove bad characters at start and end
    $filename = trim($filename, '.-_');

    return $filename;
}

/**
 * глубина вложенности массива
 * dimension of array.
 */
function array_depth(array $array)
{
    $max_depth = 1;

    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = array_depth($value) + 1;

            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }

    return $max_depth;
}

function menu()
{
    /*
        // rout list from lavarel
        if (!empty($data['page_list'])) {
            $res = array_column($data['page_list'], 'page_alias', 'page_h1'); // get pages array: 'page_h1' => 'page_alias'
        } else {
            $res = [];
        }
        // url path from rout and controller
        if (!empty($data['nav'])) {
            if (is_array($data['nav'])) {
                foreach ($data['nav'] as $value) {
                    $ress[$value] = array_search($value, $res); // get array 'nav = page_alias' => 'page_h1'
                }
            }
        }
        // set empty value for main pages 'home' and 'admin'
        if (!empty($data['page_db_data'][0])) {
            $ress[$data['page_db_data'][0]['page_alias']] = $data['page_db_data'][0]['page_h1'];
            if ($data['page_db_data'][0]['page_alias'] == 'home' or $data['page_db_data'][0]['page_alias'] == 'adm') {
                $nav = '';
            } else {
                $nav = '<a href="'.url('/').'/adm">Главная</a>';
            }
        }
        // get full path for links
        if (!empty($ress)) {
            $prevk = '';
            foreach ($ress as $key => $value) {
                if (empty($value)) {
                    $value = (!empty($data['name'])) ? $data['name'] : $key;
                }
                if (!empty($prevk)) {
                    $nav .= ' / <a href="'.url('/').$prevk.'/'.$key.'/">'.$value.'</a>';
                    $prevk .= DIRECTORY_SEPARATOR.$key;
                } else {
                    if (empty($nav)) {
                        $nav = '<a href="'.url('/').'/'.$key.'/">'.$value.'</a>';
                    } else {
                        $nav .= ' / <a href="'.url('/').'/'.$key.'/">'.$value.'</a>';
                    }
                    $prevk .= DIRECTORY_SEPARATOR.$key;
                }
            }
        }
        return (isset($nav)) ? $nav : '';
        */
}

function imageFor($path_after_public_path_with_basename): string
{
    if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$path_after_public_path_with_basename))) {
        return $path_after_public_path_with_basename;
    } else {
        return 'images'.DIRECTORY_SEPARATOR.'ddd.jpg';
    }
}

function delete_file(string $path2file): string
{
    $mes = '';
    if (is_string($path2file)) {
        // $path2file = realpath($path2file);
        if (file_exists($path2file)) {
            if (is_writable($path2file)) {
                if (unlink($path2file)) {
                    $mes .= 'true';

                    return $mes;
                } else {
                    $mes .= 'ERROR! Not unlink "'.$path2file.'".';

                    return $mes;
                }
            } else {
                $mes .= 'ERROR! File "'.$path2file.'" is not writable.';

                return $mes;
            }
        } else {
            $mes .= 'WARNING! File "'.$path2file.'" is not exists.';

            return $mes;
        }
    } else {
        $mes .= 'ERROR! Input for delete_file(string $path2file) must be sring.';

        return $mes;
    }
}

/**
 * replaces all Cyrillic letters with Latin.
 *
 * @param string $var
 *
 * @return string
 */
function translit_ostslav_to_lat($textcyr)
{
    $cyr = ['Ц', 'ц', 'а', 'б', 'в', 'ў', 'г', 'ґ', 'д', 'е', 'є', 'ё', 'ж', 'з', 'и', 'ï', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Ў', 'Г', 'Ґ', 'Д', 'Е', 'Є', 'Ё', 'Ж', 'З', 'И', 'Ї', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
    ];
    $lat = ['C', 'c', 'a', 'b', 'v', 'w', 'g', 'g', 'd', 'e', 'ye', 'io', 'zh', 'z', 'i', 'yi', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya', 'A', 'B', 'V', 'W', 'G', 'G', 'D', 'E', 'Ye', 'Io', 'Zh', 'Z', 'I', 'Yi', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya',
    ];
    $textlat = str_replace($cyr, $lat, $textcyr);

    return $textlat;
}
/**
 * replaces all letters with Latin ASCII.
 *
 * @param string $var
 *
 * @return string
 */
function translit_to_lat($text)
{
    $res = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', transliterator_transliterate('Any-Latin; Latin-ASCII', $text));

    return $res;
}

/**
 * @param string $file       - - path to txt file
 * @param string $new_string
 * @param int    $num_string - number of string for replace
 */
function replace_string($file, $new_string, int $num_string = 0)
{
    $array = file($file);
    if ($array) {
        $array[$num_string] = $new_string."\n";
    }
    if (!is_writable($file)) {
        return false;
    }
    if (file_put_contents($file, $array, LOCK_EX) === false) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param string $path - dir for scan
 * @param string $ext  - extension of files eg 'png' or 'png, webp, jpg'
 *
 * @return array path to files
 */
function files_in_dir($path, $ext = '')
{
    $files = [];
    if (file_exists($path)) {
        $f = scandir($path);
        foreach ($f as $file) {
            if (is_dir($file)) {
                continue;
            }
            if (empty($ext)) {
                $files[] = $file;
            } else {
                $arr = explode(',', $ext);
                foreach ($arr as $value) {
                    $extt = mb_strtolower(trim($value));
                    // $extt = mb_strtolower(ltrim(trim($value), '.'));
                    /*
                    if(preg_match("/\.($extt)/", $file)) {
                      $files[] = $file;
                    }
                    */
                    if ($extt === mb_strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
                        $files[] = $file;
                    }
                }
            }
        }
    }

    return $files;
}

/**
 * function for url validation.
 *
 * @param string $url
 *
 * @return bool
 */
function getResponseCode($url)
{
    $header = '';
    $options = [
        CURLOPT_URL => trim($url),
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    if (!curl_errno($ch)) {
        $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
    curl_close($ch);

    if ($header > 0 && $header < 400) {
        return true;
    } else {
        return false;
    }
}
