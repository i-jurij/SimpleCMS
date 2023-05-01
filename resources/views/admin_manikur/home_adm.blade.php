<?php
$title = 'Административная страница';
$page_meta_description = 'admins page';
$page_meta_keywords = 'admins page';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')

@Push('css')
*{
   /* color: black; */
}
@endpush

@section('menu')

@endsection

@section('content')
   <div class="adm_content">
      <p class="margin_bottom_1rem">
         <?php
            function name(array $array)
            {
                reset($array);
                $n = '';
                $max = sizeof($array);
                for ($i = 1; $i < $max; ++$i) {
                    if (!empty($array[$i])) {
                        $n .= $array[$i].' ';
                    }
                }

                return $n;
            }

            foreach ($content as $uri) {
                $name_array = explode('/', $uri);
                $nn = name($name_array);
                $linkname = str_replace('_', ' ', mb_ucfirst(trim($nn)));
                if (!empty($linkname) && $linkname !== 'Admin') {
                    echo '<a href="'.$uri.'" class="buttons">'.$linkname.'</a>';
                }
            }
?>
      </p>
   </div>
@stop

@Push('js')
<script></script>
@endpush
