<?php
$title = 'Pages editing';
$page_meta_description = 'admins page, Pages editing';
$page_meta_keywords = 'Pages, editing';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

<div class="content">
    <p style="margin:0;" id="p_pro">Показать/скрыть справку</p>
    <p class="margin_rlb1 text_left display_none" id="pro">
        Создание страниц:<br />
        Данные для шаблона страницы:<br />
        * <b>alias(100)</b> - короткое имя для страницы для URL, латиница, уникальное значение,
        те не может быть страниц с одинаковым alias, состоит только из букв, цифр, дефисов, подчеркиваний количеством до 100, обязателен;<br />
        * <b>title(100)</b> - название страницы;<br />
        <b>description(255)</b> - описание страницы сайта для отображения в результатах поиска и для SEO;<br />
        <b>keywords</b> - набор ключевых фраз для страницы;<br />
        <b>robots</b> - правила для поисковых роботов (https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html);<br />
        <b>content</b> - html|php содержимое страницы;<br />
        <b>single_page</b> - 'yes' or 'no', страница содержит подстраницы или нет (для страниц с подстраницами будут созданы контроллеры, модели, представления);<br />
        <b>img</b> - изображение страницы в меню на главной странице, вес - до 3МБ, формат - jpg, png, webp;<br />
        <b>publish</b> - 'yes' or 'no', показывать страницу или нет.<br />
        <small>* - обязательно для заполнения.</small><br />
    </p>
</div>

<div class=" ">
    @if (!empty($fields)) {
        <div class="margin_bottom_1rem" style="max-width:55rem;">
                <form action="'.url()->route('admin.pages.store').'" method="post" enctype="multipart/form-data" name="edit_page" id="edit_page" >
                @csrf
                <div class="back shad rad pad margin_rlb1">
                        <p class="margin_rlb1">Выберите файлы</p>
                        <label class="display_inline_block margin_bottom_1rem">Файл изображения страницы (jpg, png, webp, < 3MB):<br />
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                            <input type="file" name="picture" accept="image/jpeg, image/pjpeg, image/png, image/webp" />
                        </label>
                </div>
                    <div class="margin_bottom_1rem back shad rad pad mar">
                        <p class="margin_rlb1">Введите данные (alias и title обязательны)</p>
    @foreach ($fields as $key => $val)
        @if (!in_array($key, ['id', 'img', 'created_at', 'updated_at']))
            @php
            $required = ($key === 'alias' || $key === 'title') ? 'required' : '';
            $star = ($key === 'alias' || $key === 'title') ? '*' : '';
            $value = '';

            switch ($val) {
                case str_contains($val->getType()->getName(), 'string'):
                    if ($val->getLength() > 10) {
                        $type_txt = 'VARCHAR';
                    } else {
                        $type_txt = 'CHAR';
                    }
                    $type = 'text';
                    break;
                case str_contains($val->getType()->getName(), 'text'):
                    $type_txt = 'TEXT';
                    $type = 'text';
                    break;
            }
                    <label class="display_inline_block margin_bottom_1rem">{{$key}} {{$star}} ({{type_txt}}: {{$val->getLength()}})<br />
                        <input type="{{$type}}" name="{{$key}}" maxlength="{{$val->getLength()}}" value="{{$value}}" {{$required}} />
                    </label>
            @endphp
        @endif
    @endforeach
                     <div class="">
                        <button type="submit" form="edit_page" class="buttons" id="edit_page_sub">Update</button>
                        <button type="reset" form="edit_page" class="buttons">Reset</button>
                    </div>
                </div>
            </form>
        </div>
@endif
</div>

@stop
