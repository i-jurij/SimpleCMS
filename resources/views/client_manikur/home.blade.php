<?php
$title = 'HOME';
$page_meta_description = 'GET FROM DB';
$page_meta_keywords = 'GET FROM DB';
$robots = 'INDEX, FOLLOW';
$data['content'] = 'CONTENT FOR DEL IN FUTURE';
$data['telegram'] = 'tg';
$data['vk'] = 'vk';
$data['tlf'] = '+7999 777 66 55';
$data['adres'] = 'adres';
?>

@extends('layouts/index')

@Push('css')
*{
   /* color: black; */
}
@endpush

@section('content')
{{$data['content']}}
<br />
This area for home page content
@stop

@Push('js') // This is for internal js
<script></script>
@endpush
