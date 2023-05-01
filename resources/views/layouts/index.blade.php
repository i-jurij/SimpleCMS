<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <!--
  <meta name="referrer" content="origin-when-cross-origin">
-->
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title>{{ $title }}</title>
  <meta name="description" content="{{$page_meta_description}}">
  <META NAME="keywords" CONTENT="{{$page_meta_keywords}}">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="{{ $robots }}">
  <meta name="author" content="I-Jurij">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="wrapper">
        @include('layouts/header')

        <div class="main ">
            <section class="main_section">
                <div class="flex flex_top">
                    <div class="content title">
                        <h2>{{ $title }}</h2>
                    </div>
                    @yield('content')
                </div>
            </section>
        </div>

        @include('layouts/footer')
    </div>

    @stack('js')
</body>

</html>
