@php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
@endphp
@extends("layouts/index_admin")

@section("content")

<div class="content">
    @if (!empty($data['res']))
        @if (is_array($data['res']))
            @php
                print_r($data['res'])
            @endphp
        @elseif (is_string($data['res']))
            <p>{{$data['res']}}</p>
        @endif
    @else
        signup
    @endif
</div>
@stop
