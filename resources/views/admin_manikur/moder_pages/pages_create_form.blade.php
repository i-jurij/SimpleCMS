<?php
$title = 'Pages creating';
$page_meta_description = 'admins page, Pages creating';
$page_meta_keywords = 'Pages, creating';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

<div class="content">
    <p style="margin:0;" id="p_pro">Показать/скрыть справку</p>
    <p class="margin_rlb1 text_left display_none" id="pro">
        Создание страниц:<br />
        <b>File upload</b> - изображение страницы в меню на главной странице, вес - до 1МБ, формат - jpg, png, webp.<br />
        Данные для шаблона страницы:<br />
        * <b>alias(100)</b> - короткое имя для страницы для URL, латиница, уникальное значение,
        те не может быть страниц с одинаковым alias, состоит только из букв, цифр, дефисов, подчеркиваний количеством до 100, обязателен;<br />
        * <b>title(100)</b> - название страницы;<br />
        <b>description(255)</b> - описание страницы сайта для отображения в результатах поиска и для SEO;<br />
        <b>keywords</b> - набор ключевых фраз для страницы;<br />
        <b>robots</b> - правила для поисковых роботов (https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html);<br />
        <b>content</b> - html|php содержимое страницы;<br />
        <b>single_page</b> - 'yes' or 'no', страница содержит подстраницы или нет (для страниц с подстраницами будут созданы контроллеры, модели, представления);<br />
        <b>publish</b> - 'yes' or 'no', показывать страницу или нет.<br />
        <small>* - обязательно для заполнения.</small><br />
    </p>
</div>

<div class=" ">
    <div class="margin_bottom_1rem" style="max-width:55rem;">
                <form action="{{url()->route('admin.pages.store')}}" method="post" enctype="multipart/form-data" name="create_page" id="create_page" class="form_page_add">
                @csrf
                    <div class="back shad rad pad margin_rlb1">
                        <p class="margin_rlb1">Выберите файлы</p>
                        <label class="display_inline_block margin_bottom_1rem">Файл изображения страницы (jpg, png, webp, < 1MB):<br />
                            <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
                            <input type="file" name="picture" accept="image/jpeg, image/pjpeg, image/png, image/webp" />
                        </label>
                    </div>
                    <div class="margin_bottom_1rem back shad rad pad mar">
                        <p class="margin_rlb1">Введите данные (alias и title обязательны)</p>
<?php
if (!empty($fields)) {
    foreach ($fields as $key => $val) {
        if (!in_array($key, ['id', 'img', 'created_at', 'updated_at'])) {
            $required = ($key === 'alias' || $key === 'title' || $key === 'description') ? 'required' : '';
            $star = ($key === 'alias' || $key === 'title' || $key === 'description') ? '*' : '';
            $value = '';
            if ($key === 'content' || $key === 'description') {
                if ($key === 'content') {
                    $placeholder = 'Pages text or html, php, js content';
                }
                if ($key === 'description') {
                    $placeholder = 'Description of page';
                }
                $br = '<br>';
                $input_start = '<textarea placeholder="'.$placeholder.'."';
                $input_end = '></textarea>';
            } else {
                $br = '';
                $input_start = '<input type="text"';
                $input_end = ' />';
            }

            if ($key === 'robots') {
                $value = 'INDEX,FOLLOW';
            }
            if ($key === 'single_page') {
                $value = 'yes';
            }
            if ($key === 'publish') {
                $value = 'yes';
            }
            echo $br.'<label class="display_inline_block margin_bottom_1rem">'.$key.' '.$star.' ('.$val->getLength().')<br />'
                        .$input_start.' name="'.$key.'" maxlength="'.$val->getLength().'" value="'.$value.'" '.$required.$input_end.
                    '</label>'.$br.PHP_EOL;

            unset($value, $length, $type);
        }
    }
    unset($required, $type, $length, $value, $key, $val);
}
?>
                    <div class="">
                        <button type="submit" form="create_page" class="buttons" id="create_page_sub">Create</button>
                        <button type="reset" form="create_page" class="buttons">Reset</button>
                    </div>
                </div>
            </form>
        </div>
</div>

@stop
