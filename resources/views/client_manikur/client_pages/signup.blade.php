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

<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    @if (!empty(session('res')))
        @if (is_array(session('res')))
            @php
                print_r(session('res'));
            @endphp
        @elseif (is_string(session('res')))
            <div class="content"><p>{!!session('res')!!}</p></div>
        @endif
    @else
        <div class="content">
        @if (!empty($data['serv']))
            <form method="post" action="" id="zapis_usluga_form" class="form_zapis_usluga">
                @csrf
                <div class="choice" id="services_choice">
                    <h3 class="back shad rad pad margin_rlb1">Выберите услугу</h3>
                    <div class="zapis_usluga page_buttons">
                        @foreach ($data['serv'] as $page => $cat_arr)
                            <button type="button" class="buttons zapis_usluga_buttons masters_edit vertaligntop" id="{{translit_to_lat(sanitize($page))}}">
                                <img class="" src="{{asset('storage'.DIRECTORY_SEPARATOR.$data[$page])}}" alt="{{$page}} image" />
                                {{$page}}
                            </button>
                        @endforeach
                    </div>
                    <div class="zapis_usluga zapis_usluga_spisok">
                        @foreach ($data['serv'] as $page => $cat_arr)
                            <div class="uslugi display_none" id="div{{translit_to_lat(sanitize($page))}}" >
                                @foreach ($cat_arr as $cat_name => $serv_arr)
                                    <div class="">
                                    @if ($cat_name !== 'page_serv')
                                        @foreach ($serv_arr as $serv_name => $serv_duration)
                                            @php
                                                $id = translit_to_lat(sanitize($serv_name)).'plus'.$serv_duration;
                                                list($price, $duration, $serv_id) = explode('-', $serv_duration);
                                            @endphp
                                            <label class="custom-checkbox back text_left" for="{{$id}}">
                                                <input
                                                    type="radio"
                                                    name="usluga"
                                                    value="{{$serv_id}}"
                                                    id="{{$id}}"
                                                />
                                                <span>{{$cat_name}}: {{$serv_name}},<br>{{$price}} руб.</span>
                                            </label>
                                        @endforeach
                                    @elseif ($cat_name == 'page_serv')
                                        @foreach ($serv_arr as $serv_name => $serv_duration)
                                            @php
                                                $id = translit_to_lat(sanitize($serv_name)).'plus'.(int) $serv_duration;
                                                list($price, $duration, $serv_id) = explode('-', $serv_duration);
                                                $cat_name = 'page_serv';
                                            @endphp
                                            <label class="custom-checkbox back text_left" for="{{$id}}">
                                                <input
                                                    type="radio"
                                                    name="usluga"
                                                    value="{{$serv_id}}"
                                                    id="{{$id}}"
                                                />
                                                <span>{{$serv_name}},<br>{{$price}} руб.</span>
                                            </label>
                                        @endforeach
                                    @endif

                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="choice display_none" id="master_choice"></div>

                <h3 class="back shad rad pad margin_rlb1 display_none" id="timeh3">Выберите время</h3>
                <div class="choice display_none margin_bottom_1rem" id="time_choice"></div>

                <div class="choice display_none" id="give_a_phone">
                    <div class=" display_none" id="form_phone">
                        <h3 class="back shad rad pad">Введите свое имя и номер телефона для связи</h3>
                        <div class="form-group pad margin_bottom_1rem">
                            <div class="">
                                <div id="error"><small></small></div>
                                <label class="zapis_usluga">
                                    <p class="pad">Ваше имя:</p>
                                    <input type="text" placeholder="Ваше имя (одно слово, только буквы)" pattern="^([а-яА-ЯёЁa-zA-Z]+)?$" name="zapis_name" id="zapis_name" maxlength="255" value="{{ old('name') }}" />
                                </label>
                                <br>
                                <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
                                <p class="error" id="tel_mes"></p>
                                <label class="zapis_usluga">
                                    <p class="pad">Номер мобильной связи:</p>
                                    <input type="tel" name="zapis_phone_number"  id="number" class="number"
                                    title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"
                                    minlength="6" maxlength="17"
                                    pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                                    value="{{ old('phone_number') }}"
                                    required />
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="display_none" id="occupied"></div>
                </div>

            </form>

            <div class="choice display_none" id="zapis_end"></div>
            <div class="zapis_usluga margin_rlb1" id="buttons_div">
                <button type="button" class="buttons" id="button_back" value="" disabled >Назад</button>
                <button type="button" class="buttons" id="button_next" value="" disabled >Далее</button>
            </div>
        @else
            <div class="content"><p>Список услуг пуст.</p></div>
        @endif
        </div>
    @endif

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {

    function scrolltobuttonnext(radioinputselector) {
        $(radioinputselector).on('change', function(){
            if ( $(radioinputselector+':checked').length > 0 ) {
                $('html, body').animate({
                scrollTop: $("#buttons_div").offset().top
                }, 500);
                $('#button_next').focus();
            }
        });
    }

    function my_date_string(timestamp) {
        let cyrnameofmonth = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        const jsDateTime = new Date(timestamp);
        const day = capitalizeFirstLetter(getDayName(jsDateTime, locale, long));
        const day_of_month = jsDateTime.getDate();
        const month = cyrnameofmonth[jsDateTime.getMonth()];
        const year = jsDateTime.getFullYear();
        const hours = jsDateTime.getHours();
        const minutes = jsDateTime.getMinutes();

        return day+', '+day_of_month+' '+month+' '+year+', '+pad(hours)+':'+pad(minutes);
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

$(function() {
    //for page choice
    // Найти все узлы TD
    var page_buttons=$("#services_choice > .page_buttons > .zapis_usluga_buttons");
    // Добавить событие щелчка для всех TD
    page_buttons.click(function() {
        var button_id = $(this).prop('id');
        $('.uslugi').each(function (index, value){
        let page_id = $(this).prop('id');
        if (page_id == 'div'+button_id) {
        $("#div"+button_id).toggle();
        //$('#services_choice label').show();
        } else {
        $(this).hide();
        }
        });
    });

    $('#services_choice input[type="radio"]').on('change', function(){
        if ( $('#services_choice input[type="radio"]:checked').length > 0 ) {
            $('#button_next').val('master_next').prop('disabled', false);
            $('html, body').animate({
            scrollTop: $("#buttons_div").offset().top
            }, 500);
            $('#button_next').focus();
        }
    });
////////////////////////
$('#button_next').click(function(){
    //if ( $('#services_choice input:checkbox:checked').length > 0 && $(this).val() == 'master_next')
    if ( $('#services_choice input:radio:checked').length > 0 && $(this).val() == 'master_next')
    {
        let service = $('#services_choice input[type="radio"][name="usluga"]:checked').val();
        $('#services_choice').hide();
        $('#master_choice').show();
        $(this).val('time_next');
        $('#button_back').val('services_choice').prop('disabled', false);
        $.ajax({
            url: '<?php echo url('/'); ?>/signup/masters',
    		method: 'post',
    		dataType: 'json',
    		data: {
                "_token": "{{ csrf_token() }}",
                'serv_id': service
            },
    		success: function(result){
                if (result.masters.length > 0) {
                    let mst = '<h3 class="back shad rad pad margin_rlb1">Выберите специалиста</h3>\
                                <div class="radio-group flex">';

                        result.masters.forEach(element => {
                        mst += '<article class="main_section_article radio" data-value="'+element.id+'">\
                                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">\
                                        <img src="{{asset("storage")}}/'+element.master_photo+'" alt="Фото '+element.master_fam+'" class="main_section_article_imgdiv_img" />\
                                    </div>\
                                    <div class="main_section_article_content margin_top_1rem">\
                                        <h3 id="'+element.id+'">'+element.master_name+' '+element.master_fam+'</h3>\
                                    </div>\
                                </article>';
                    });
                    mst += '<input type="hidden" id="master" name="master" />\
                            </div>';
                    $('#master_choice').html(mst);

                    //for master_choice
                    $('.radio-group .radio').click(function(){
                        $(this).parent().find('.radio').removeClass('selected');
                        $(this).addClass('selected');
                        var val = $(this).attr('data-value');
                        $(this).parent().find('#master').val(val);

                        $('html, body').animate({
                            scrollTop: $("#buttons_div").offset().top
                        }, 500);
                        $('#button_next').focus();
                    });
                } else {
                    $('#master_choice').html('<p class="pad">No masters for this service available.</p>');
                    //click event on button next
                    setTimeout(function(){
                        $("#button_next").click();
                    }, 10);
                }

    		},
            error: function(data) {
                $('#master_choice').html('<p class="pad">Извините, где-то возникла ошибка :(</p>');
            },
            cache: false
    	});
    }
    else if ( ( $('#master_choice #master').val() || $('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') && $(this).val() == 'time_next' )
    {
      $('#master_choice').hide();
      $('#timeh3').show();
      $('#time_choice').show();
      $(this).val('phone_next');
      $('#button_back').val('master_choice');
      let master = $('#master_choice #master').val();
      // CALL TO APPOINTMENT SCRIPT
      appointment('short');
      $('html, body').animate({
          scrollTop: $(".title").offset().top
      }, 500);
      scrolltobuttonnext('input[name="time"]');
    }
    else if ( $('#time_choice input[name="time"]:checked').length && $(this).val() == 'phone_next' )
    {
        let master = $('#master_choice #master').val();
        let ttime = Number($('#time_choice input[type="radio"][name="time"]:checked').val());
        $.ajax({
            url: '<?php echo url('/'); ?>/signup/phone',
    		method: 'post',
    		dataType: 'json',

    		data: {
                "_token": "{{ csrf_token() }}",
                'master': master,
                'time': ttime
            },
    		success: function(data){
                if (!data.res) {
                    let mes = "<p class=\"pad\">"+my_date_string(ttime)+"\
                                        <br /> недавно были заняты другим клиентом.<br />Выберите, пожалуйста другое время.\
                                    </p>";
                    $('#occupied').html(mes).show();
                    $('#form_phone').hide();
                } else if (data.res && data.res == 'empty') {
                    let mes = '<p class="pad">Сервер получил запрос без необходимых данных :(</p>';
                    $('#occupied').html(mes).show();
                    $('#form_phone').hide();
                } else if (data.res && data.res != 'empty') {
                    $('#occupied').hide();
                    $('#form_phone').show();
                }
    		},
            error: function(data) {
                let tlf_form = '<p class="pad">Извините, где-то возникла ошибка :(</p>)';
                $('#occupied').html(tlf_form).show();
                $('#form_phone').hide();
            },
            cache: false
    	});
        //alert($('.master_datetime input[name="time"]:checked').val());

        $('#timeh3').hide();
        $('#time_choice').hide();
        $('#give_a_phone').show();
        $(this).val('end_next');
        $('#button_back').val('time_choice');
    }
    else if ( $('#give_a_phone input[name="zapis_phone_number"]').val() && $(this).val() == 'end_next' )
    {
        $('#give_a_phone').hide();
        $('#zapis_end').show();
        $('#button_back').val('give_a_phone');
        $(this).val('zapis_sql').html('Записаться!');

        let master = $('#master_choice #master').val();
        let master_data = $('#'+master).html();
        let time = Number($('#time_choice input[type="radio"][name="time"]:checked').val());
        let phone = $('#give_a_phone input[type="tel"]').val().replace(/ /g, '\u00a0');

        //validation inputs
        let res = {};
        let name_regex = new RegExp('\^\(\[а\-яА\-ЯёЁa\-zA\-Z\]\+\)\?\$');
        let client_name = escapeHtml($('form#zapis_usluga_form input[name="zapis_name"]').val());

        let phone_regex = new RegExp("\^\(\\\+\?\(7\|8\|38\)\)\[ \]\{0,1\}s\?\[\\\(\]\{0,1\}\?\\d\{3\}\[\\\)\]\{0,1\}s\?\[\\\- \]\{0,1\}s\?\\d\{1\}\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?");
        let client_phone = escapeHtml($('form#zapis_usluga_form input[name="zapis_phone_number"]').val());

        if (name_regex.test(client_name)) {
            res.client_name = true;
        } else {
            res.error = '<p class="error pad" >Имя должно состоять только из букв.</p>';
        }
        if (phone_regex.test(client_phone)) {
            res.client_phone = true;
        } else {
            res.error += '<p class="error pad" >Неверно введен номер телефона.</p>';
        }
        if (res.client_name && res.client_phone) {
            $('#zapis_end').show().addClass('margin_rlb1').html('<h3>'+client_name+' </h3>\
                                    <p id="zap_na">Вы записываетесь на:</p>\
                                    <div class="table_body text_left div_center" >\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Услуга:</div>\
                                            <div class="table_cell text_left">'
                                                +$("input:radio[name=\"usluga\"]:checked").next('span').html().replace('<br>', ' ').split('&emsp;').join(' ')+
                                            '</div>\
                                        </div>\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Мастер: </div>\
                                            <div class="table_cell text_left">'+master_data+'</div>\
                                        </div>\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Дата,<br /> время:</div>\
                                            <div class="table_cell text_left">'+my_date_string(time)+'</div>\
                                        </div>\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Ваш номер:</div>\
                                            <div class="table_cell text_left">'+phone+' </div>\
                                        </div>\
                                    </div>');
        } else {
            $('#zapis_end').html(res.error);
            $('#button_next').prop('disabled', true);
        }
    }
    else if ( $(this).val() == 'zapis_sql' )
    {
        // set button next type = submit and action for form = url()->route("client.signup.end")
        $(this).val('zapis_sql').attr("type", "submit").attr("form", "zapis_usluga_form");
        $('form#zapis_usluga_form').attr('action', '{{url()->route("client.signup.end")}}');
        /*
        $.ajax({
            url: '<?php // echo url('/');?>/signup/end',
            method: 'post',
            dataType: 'html',
            data: $('form#zapis_usluga_form').serialize(),
            success: function(data){
                $('#zapis_end').html(data);
                $('#button_back, #button_next').hide();
                //console.dir(data);
            },
            error: function(data) {
                let res = '<p class="error pad">Ошибка передачи данных. Повторите ввод данных, пожалуйста. :(</p>)';
                $('#zapis_end').html(res);
            },
            cache: false
        });
        */
    }
    else
    {
      //alert('Сделайте выбор или введите данные, пожалуйста.');
      modal_alert('Сделайте выбор или введите данные, пожалуйста.');
    }
  });

  $('#button_back').click(function() {
    let choice_div_id = $(this).val();
    $('.choice').each(function(){
      if ( $(this).prop('id') == choice_div_id )
      {
        $('#'+choice_div_id).show();
        if ( $(this).prop('id') == 'services_choice') {
            $('#button_next').val('master_next');
            $('#button_back').prop('disabled', true);
            if ($('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') {
                $('#timeh3').hide();
            }
        } else if ($(this).prop('id') == 'master_choice') {
          $('#button_next').val('time_next');
          $('#button_back').val('services_choice');
          $('#timeh3').hide();
        }else if ($(this).prop('id') == 'time_choice') {
            $('#button_next').val('phone_next');
            if ($('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') {
                $('#button_back').val('services_choice');
            } else {
                $('#button_back').val('master_choice');
            }
            $('#timeh3').show();
            //appointment('short');
        }else if ($(this).prop('id') == 'give_a_phone') {
          $('#button_back').val('time_choice');
          $('#button_next').val('end_next').html('Далее');
          $('#button_next').prop('disabled', false);
        } else if ($(this).prop('id') == 'zapis_end') {
          $('#button_back').val('give_a_phone');
          //$('#button_next').val('zapis_sql');
        }
      } else {
        $(this).hide();
      }
    });
  });
});
}, false);
</script>

@stop
