<?php
$title = 'Contacts edit form';
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content margintb1 ">

        <div class="price">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Data</th>
                </tr>
                <tr>
                    <td>{{$contact_data['id']}}</td>
                    <td>{{$contact_data['type']}}</td>
                    <td>{{$contact_data['data']}}</td>
                </tr>
            </table>
        </div>

    </div>
@stop
