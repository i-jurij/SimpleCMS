@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]["title"];
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content = "CONTENT FOR DEL IN FUTURE";
}

$img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['category_img'];
$img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['service_img'];

@endphp


@extends("layouts/index")

@section("content")

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    @if (!empty($data['res']))
        {{$data['res']}}
    @else

        <article class="main_section_article">
            <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                <h3>Расценки</h3>
            </div>
            <div class="main_section_article_content"><br />
                <h3>{{$data['page_db_data'][0]['page_title']}}</h3>
                @if (!empty($data['min_price']))
                    @foreach ($data['min_price'] as $k => $v)
                        <span>{{$k}} - от {{$v}} руб.</span><br />
                    @endforeach
                @endif
                <br />
                <a href="{{url('/price#'.$data['page_db_data'][0]['page_alias'])}}" style="text-decoration: underline;">Прайс</a>
            </div>
        </article>

        @if (!empty($data['cat']))
            @foreach ($data['cat'] as $cat)
                <article class="main_section_article ">
                    <div class="main_section_article_imgdiv">
                        <img src="{{asset('storage'.$img_cat)}}" alt="Фото {{$cat['category_name']}}" class="main_section_article_imgdiv_img" />
                    </div>
                    <div class="main_section_article_content">
                        <h3>{{$cat['category_name']}}</h3>';
                            @if (!empty($data['serv']))
                                @foreach ($data['serv'] as $serv)
                                    @if ($serv['category_id'] == $cat['id'])
                                        <span>{{$serv['service_name']}} от {{$serv['price']}} руб.</span><br />
                                    @endif
                                @endforeach
                            @endif
                     </div>
                </article>
            @endforeach
        @endif

        @if (!empty($data['serv']))
            @foreach ($data['serv'] as $serv)
                @if (empty($serv['category_id']) || $serv['category_id'] === '')
                    <article class="main_section_article ">
                        <div class="main_section_article_imgdiv">
                            <img src="{{asset('storage'.$img_serv)}}" alt="Фото {{$serv['service_name']}}" class="main_section_article_imgdiv_img" />
                        </div>
                        <div class="main_section_article_content">
                            <h3>{{$serv['service_name']}}</h3>
                            <span>{{$serv['service_descr']}}</span><br />
                            <span>от {{$serv['price']}} руб.</span>
                        </div>
                    </article>
                @endif
            @endforeach
        @endif

    @endif

@stop
