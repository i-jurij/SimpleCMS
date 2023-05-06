<?php

if (!empty($content)) {
    $admin_url = url()->route('admin.home');
    $prev_url = url()->previous();
    $current_url = url()->current();
    $prev = '<a href="'.$prev_url.'">'.mb_ucfirst(last(explode('/', $prev_url))).'</a>';
    $current = '<span>'.mb_ucfirst(last(explode('/', $current_url))).'</span>';
    if ($prev_url !== $current_url && $current_url !== $admin_url) {
        echo $prev.' <- '.$current;
    } else {
        echo $current;
    }
} else {
    echo 'No menu';
}
