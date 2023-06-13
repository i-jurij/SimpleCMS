@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    // title get from $this_show_method_data['name']
    $title = (!empty($this_show_method_data['name'])) ? $this_show_method_data['name'] : $page_data[0]["title"];
    // page_meta_description get from $data['cat']['description']
    $page_meta_description = (!empty($this_show_method_data['description'])) ? $this_show_method_data['description'] : $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = (!empty($data['cat']) && !empty($data['cat']['name'])) ? $data['cat']['name'] : $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}

@endphp


@extends("layouts/index")

@section("content")

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

@stop
