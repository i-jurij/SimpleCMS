<?php
$title = 'HOME';
$page_meta_description = 'GET FROM DB';
$page_meta_keywords = 'GET FROM DB';
$robots = 'INDEX, FOLLOW';
$data['content'] = 'CONTENT FOR DEL IN FUTURE';
?>

@extends('layouts/index')

@Push('css')
*{
   /* color: black; */
}
@endpush

@section('content')
<div class="content">
@if (!empty($content['pages_menu']))
    @foreach ($content['pages_menu'] as $pages)
        {{$pages['title']}}<br>
    @endforeach
@else
    No routes (pages)
@endif
</div>
@stop

@Push('js')
<script></script>
@endpush
