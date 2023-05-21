@php

            if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {

                $title = $page_data[0]["title"];

                $page_meta_description = $page_data[0]["description"];

                $page_meta_keywords = $page_data[0]["keywords"];

                $robots = $page_data[0]["robots"];

                $content["page_content"] = $page_data[0]["content"];

            } else {

                $title = "Title";

                $page_meta_description = "description";

                $page_meta_keywords = "keywords";

                $robots = "INDEX, FOLLOW";

                $content = "CONTENT FOR DEL IN FUTURE";

            }

            @endphp

            @extends("layouts/index")


            @section("content")

                @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif


                <div class="content">
                    @if (!empty($abouts) && is_array($abouts))
                        @foreach ($abouts as $about)
                            @php
                                $img = imageFor($about['image']);
                            @endphp
                            <article class="main_section_article ">
                                <div class="main_section_article_imgdiv margin_bottom_1rem">
                                    <img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="{{$about['title']}}" class="main_section_article_imgdiv_img" />
                                </div>
                                <div class="main_section_article_content">
                                    <h3>{{$about['title']}}</h3>
                                    <span>{{$about['content']}}</span>
                                </div>
                            </article>
                        @endforeach
                    @endif

                    @if (!empty($masters) && is_array($masters))
                        @foreach ($masters as $master)
                            @php
                                $img = imageFor('images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.'master_photo_'.$master['id']);
                            @endphp

                            <article class="main_section_article ">
                                <div class="main_section_article_imgdiv margin_bottom_1rem" style="background-color: var(--bgcolor-content);">
                                    <img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="{{$master['master_fam']}}" class="main_section_article_imgdiv_img" />
                                </div>

                                <div class="main_section_article_content">
                                    <h3>{{$master['master_name']}} {{$master['master_fam']}}</h3>
                                    <span>
                                        {{$master['spec']}}
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    @endif

                </div>
            @stop
