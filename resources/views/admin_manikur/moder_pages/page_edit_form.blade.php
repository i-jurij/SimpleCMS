<?php
$title = 'Page edit form';
$page_meta_description = 'admins page, Page editing form';
$page_meta_keywords = 'Pages, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content">
    @foreach ($fields as $page)
        @if (is_array($page) && !empty($page))
        <div class="price">
            <form method="post" action="{{ url()->route('admin.pages.update') }}" id="page_update"  class="form_page_add">
            @csrf
            <table class="table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody class="text_left">
                <?php
                foreach ($page as $key => $value) {
                    $pattern = '';
                    $length = '100';
                    if ($key === 'alias') {
                        $pattern = 'pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$"';
                    }
                    if ($key === 'content' || $key === 'description') {
                        $input_start = '<textarea  style="width:100%;"';
                        $input_end = '>'.$value.'</textarea>';
                        if ($key === 'description') {
                            $length = '255';
                        }
                        if ($key === 'content') {
                            $length = '65535';
                        }
                    } elseif ($key === 'id' || $key === 'single_page' || $key === 'service_page') {
                        $input_start = '<input type="hidden" style="width:100%;"';
                        $input_end = ' />'.$value;
                    } else {
                        $input_start = '<input type="text" style="width:100%;"';
                        $input_end = ' />';
                    }

                    if ($key === 'keywords') {
                        $length = '500';
                    }

                    if ($key === 'publish') {
                        $length = '10';
                    }

                    if ($key !== 'created_at' && $key !== 'updated_at') {
                        echo '  <tr>
                                    <td>'.$key.'</td>
                                    <td>'.$input_start.' name="'.$key.'" maxlength="'.$length.'" value="'.$value.'" '.$pattern.' '.$input_end.'</td>
                                </tr>';
                    }
                }
?>
                </tbody>
            </table>
            <div class="">
                <button type="submit" form="page_update" class="buttons" id="edit_page_sub">Save</button>
                <button type="reset" form="page_update" class="buttons">Reset</button>
            </div>
            </form>
        </div>
    </div>
        @endif
    @endforeach
@stop
