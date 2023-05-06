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
   @if (!empty($content)) {{$content}}
   @else {{$data['content']}}
   @endif
@stop

@Push('js')
<script></script>
@endpush
