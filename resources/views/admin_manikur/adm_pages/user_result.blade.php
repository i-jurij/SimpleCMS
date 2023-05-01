<?php
$title = 'Users action result';
$page_meta_description = 'admins page, users, result of action';
$page_meta_keywords = 'admins, user, delete, change';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content margintb1 ">
            <p>{!! $res !!}</p>

            @if ($errors->any())
                <div class="alert alert-danger error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    </div>

@stop
