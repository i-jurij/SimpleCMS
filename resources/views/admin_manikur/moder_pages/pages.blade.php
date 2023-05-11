<?php
$title = 'Pages editing';
$page_meta_description = 'admins page, Pages editing';
$page_meta_keywords = 'Pages editing';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    @if (!empty($res)) <p class="content">MESSAGE: {{$res}}</p> @endif

    @if (!empty($pages))
    <form method="post" action="{{ url()->route('admin.pages.remove') }}" id="pages_remove_form" class="pad">
        @csrf

        @foreach ($pages as $key => $page)
            {{$key+1}}<br>
            <input type="submit" value="{{$page['id']}}" name="id" /><br>
            {{$page['alias']}}<br>
            {{$page['title']}}<br>
            {{$page['description']}}<br>
            {{$page['keywords']}}<br>
            {{$page['robots']}}<br>
            {{$page['content']}}<br>
            {{$page['single_page']}}<br>
            {{$page['img']}}<br>
            {{$page['publish']}}<br>
            {{$page['created_at']}}<br>
            {{$page['updated_at']}}<br>

        @endforeach

        <div class="form-element mar">
            <!-- <button type="submit" form="contacts_form" class="buttons" id="contacts_submit">Remove</button> -->
            <button type="reset" form="contacts_form" class="buttons" id="contacts_reset">Reset</button>
        </div>
    </form>
    @endif
@stop
