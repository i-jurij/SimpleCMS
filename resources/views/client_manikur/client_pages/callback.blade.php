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
    // action for form (if isset imgs in storage for captcha or not)
    // $action = (!empty($captcha_imgs)) ? url()->route('client.callback.send_mail') : 'javascript:void(0)';
    $action = 'javascript:void(0)';
@endphp
@extends("layouts/index")
@section("content")
@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
    @if (!empty($res) && is_array($res))
        <p class="content">
            @foreach ($res as $re)
                {{$re}}<br>
            @endforeach
        </p>
    @elseif (!empty($res) && is_string($res)) {{$res}}
    @else
        <p class="back shad pad margin_rlb1 zapis_usluga">
            Не обещаем перезвонить вам сразу же. У нас нет колл-центра.<br />
            Перезвоним как только сможем.
        </p>
        <div class="content form_recall_div">
            <form action="{{$action}}" method="post" class="form-recall-main" id="recall_one">
                @csrf
                <div class="">
                    <div class="form-group ">
                        <div class="">
                            <div id="error"><small></small></div>
                            <label class="zapis_usluga">Ваше имя:
                                <br>
                                <input type="text" placeholder="Ваше имя" name="name" id="name" maxlength="50" />
                            </label>
                            <br>
                            <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
                            <label class="zapis_usluga">Номер мобильной связи:
                                <br>
                                <input type="tel" name="phone_number"  id="number" class="number"
                                title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"
                                minlength="6" maxlength="17"
                                pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                                required />
                            </label>
                            <br>
                            <label class="zapis_usluga">Вопрос:
                                <br>
                                <textarea placeholder="Что вас интересует?" name="send"  id="send" maxlength="300"></textarea>
                            </label>
                        </div>
                    </div>

                    <div class="margin_bottom_1rem capcha" id="captcha_div"></div>

                    <div class="form-group" id="sr_but">
                        <button type="submit" class="buttons form-recall-submit" >Отправить</button>
                        <button class="buttons form-recall-reset" type="reset">Очистить</button>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="margin_top_1rem">
                    <p class="pers">
                    Отправляя данную форму, вы даете согласие на
                    <br>
                    <a href="{{url('/persinfo')}}">
                        обработку персональных данных
                    </a>
                    </p>
                </div>
            </form>


        <script type="module">
        function guidGenerator() {
            var S4 = function() {
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
            };
            return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
        }

        document.addEventListener('DOMContentLoaded', function () {
            $(function() {
            var strings = [];
            let cimgs = <?php echo json_encode($captcha_imgs); ?>;
            var imgs = (cimgs) ? cimgs : [];

            if (imgs.length > 0) {
                var uniqids = [];
                //for (var i = 0; i < 6; i++)
                for (var i = 0; i < imgs.length; i++)
                {
                    //random id generated
                    uniqids[i] = guidGenerator();
                }
                //choice random id from ids array
                var truee = uniqids[Math.floor(Math.random()*uniqids.length)];

                for (var i = 0; i < uniqids.length; i++)
                {
                    /*
                    let ii = i+1;
                    let imgpath = '<?php echo asset('storage'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'captcha_imgs'.DIRECTORY_SEPARATOR); ?>';
                    imgs[uniqids[i]] = '<img src="'+imgpath+'<?php echo DIRECTORY_SEPARATOR; ?>'+ii+'.jpg" style="width:5rem;" />';
                    //console.log(imgs[uniqids[i]]);
                    */


                    strings[i] = '<input id="captcha_'+uniqids[i]+'" class="captcha" name="dada" value="'+ii+'" type="radio" />\
                    <label class="captcha_img  display_inline_block" for="captcha_'+uniqids[i]+'">\
                        <img src="'+imgpath+'<?php echo DIRECTORY_SEPARATOR; ?>'+ii+'.jpg" id="img_'+uniqids[i]+'"/>\
                    </label>';
                }

                $('#captcha_div').before('<div><p>Выберите, пожалуйста, среди других этот рисунок:</p>\
                                    <p class="div_center pad">'+imgs[truee]+'</p></div>');

                $('#captcha_div').addClass('pad');
                $('#captcha_div').append('<div class="imgs div_center" style="width:21rem;"></div>');
                for (var i = 0; i < strings.length; i++)
                {
                    $('#captcha_div .imgs').append(strings[i]);
                }

                $("#img_"+truee).addClass('access');

                $('#captcha_div').after('<p><small>После выбора рисунка нажмите Отправить.</small></p>');

                $('button.form-recall-submit').click(function(){
                    event.preventDefault();
                    let check = $("#captcha_"+truee).is(':checked');
                    if ($('#number').val()) {
                    if ( check == true ) {
                        $('form#recall_one').submit();
                    } else {
                        alert('Выберите, пожалуйста, соответствующий рисунок :)');
                    }
                    } else {
                    alert('Вы забыли ввести номер телефона :)');
                    }
                });
            } else {
                // laravel captcha
                $('#sr_but').before('\
                    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">\
                        <label class="col-md-4 control-label" id="mes"></label>\
                        <div class="">\
                            <div class="capcha2">\
                                <span class="display_inline_block div_center" style="vertical-align: middle;">{!! captcha_img() !!}</span>\
                                <button type="button" class="buttons" id="reload">&#x21bb;</button>\
                            </div>\
                            <input id="captcha" type="text" class="form-control" placeholder="Введите текст" name="captcha">\
                        </div>\
                    </div>\
                ');


                $('#reload').click(function () {
                    $.ajax({
                        type: 'GET',
                        url: "{{url()->route('captcha.reload')}}",
                        success: function (data) {
                            $(".capcha2 span").html(data.captcha);
                        }
                    });
                });

                $('button.form-recall-submit').click(function(){
                    event.preventDefault();
                    if ($('#captcha').val()) {
                        $('form#recall_one').submit();
                    } else {
                        //alert('Заполните поле для ввода текста с картинки :)');
                        $('#mes').html('Заполните поле для ввода текста с картинки :)');
                        $('html, body').animate({
                                scrollTop: $("#mes").offset().top
                        }, 2000);
                    }
                });
            }

            $("form#recall_one").on("submit", function(event){
                //event.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                var dataar = $("form#recall_one").serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{url()->route('client.callback.send_mail')}}",
                    method: 'post',
                    dataType: 'json',
                    data: dataar,
                    success: function(data){
                        //$(".flex_top").append(data);
                        $(".form_recall_div").html(data);
                    }
                });
            });

            });

        }, false);
        </script>
        </div>
    @endif
@stop
