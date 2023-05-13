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
        @if (is_array($pages) && !empty($pages))
            <article class="main_section_article ">
                <a class="main_section_article_content_a" href="" >
                    <div class="main_section_article_imgdiv">
                    <img src="{{asset('storage/'.$pages['img'])}}" alt="{{$pages['title']}}" class="main_section_article_imgdiv_img" />
                    </div>

                    <div class="main_section_article_content margin_top_1rem">
                        <h3>{{$pages['title']}}</h3>
                        <span>
                            {{$pages['description']}}
                        </span>
                    </div>
                </a>
			</article>
        @else
            There are no pages in the database
        @endif
    @endforeach
@else
    No routes (pages)
@endif
</div>
@stop

@Push('js')
<script></script>
@endpush
