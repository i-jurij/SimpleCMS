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

    @if (!empty($this_show_method_data))
        @php
            print_r($this_show_method_data)
        @endphp
    @else

    <article class="main_section_article">
            <div class="main_section_article_imgdiv pad" style="background-color: var(--bgcolor-content);">
                <h2>Расценки</h2>
            </div>
            <div class="main_section_article_content"><br />
                <h2>{{$title}}</h2>
                @if (!empty($data['min_price']))
                    @foreach ($data['min_price'] as $k => $v)
                        <span>{{$k}} - от {{$v}} руб.</span><br />
                    @endforeach
                @endif
                <br />
                <a href="{{url('/price#'.$page_data[0]['alias'])}}" style="text-decoration: underline;">Прайс</a>
            </div>
        </article>

        @if (!empty($data['cat']))
            @foreach ($data['cat'] as $key => $cat)
            @php
                $img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['image'];
            @endphp
                <article class="main_section_article ">
                    <div class="main_section_article_imgdiv">
                        <img src="{{asset('storage'.$img_cat)}}" alt="Фото {{$cat['name']}}" class="main_section_article_imgdiv_img" />
                    </div>
                    <div class="main_section_article_content margin_top_1rem">
                        <h3>{{$cat['name']}}</h3>
                            @if (!empty($data['serv']))
                                @foreach ($data['serv'] as $k => $serv)
                                    @if ($serv['category_id'] == $cat['id'])
                                        <span>{{$serv['name']}} от {{$serv['price']}} руб.</span><br />
                                    @endif
                                @endforeach
                            @endif
                     </div>
                </article>
            @endforeach
        @endif

        @if (!empty($data['serv']))
            @foreach ($data['serv'] as $ke => $serv)
                @php
                    $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
                @endphp
                @if (empty($data['serv'][$ke]['category_id']) || $data['serv'][$ke]['category_id'] === '')
                    <article class="main_section_article ">
                        <div class="main_section_article_imgdiv">
                            <img src="{{asset('storage'.$img_serv)}}" alt="Фото {{$serv['name']}}" class="main_section_article_imgdiv_img" />
                        </div>
                        <div class="main_section_article_content  margin_top_1rem">
                            <h3>{{$data['serv'][$ke]['name']}}</h3>
                            <span>{{$data['serv'][$ke]['description']}}</span><br />
                            <span>от {{$data['serv'][$ke]['price']}} руб.</span>
                        </div>
                    </article>
                @endif
            @endforeach
        @endif

    @endif

@stop
