<?php

function mb_ucfirst($str)
{
    $fc = mb_strtoupper(mb_substr($str, 0, 1));

    return $fc.mb_substr($str, 1);
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
