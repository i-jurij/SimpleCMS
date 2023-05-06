<?php
$title = 'Contacts';
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

    <div class="content margintb1 ">
        <div>
            @if (Auth::user()['status']==='admin' || Auth::user()['status']==='moder')
                @foreach ($content as $contact)
                    <table class="text_left">
                        <tr>
                            <td>Name: </td>
                            <td>{{$user->name}}</td>
                        </tr>
                        <tr>
                            <td>Email: </td>
                            <td>{{$user->email}}</td>
                        </tr>
                        <tr>
                            <td>Status: </td>
                            <td>{{$user->status}}</td>
                        </tr>
                    </table>
                @endforeach
            @else
            You are not authorized.
            @endif
        </div>
    </div>

@stop
