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


<?php
$master_id = '8';
$time = DateTime::createFromFormat('d-m-Y H:i', '16-06-2023 14:30');

$order = \App\Models\Order::where('master_id', $master_id)
    ->where('start_dt', '<=', $time)
    ->where('end_dt', '>', $time)
    ->get()
    ->toArray();

print_r($order);
?>


<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    @if (!empty($data['res']))
        <div class="content"><p>{{$data['res']}}</p></div>
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
                                                    value="{{$duration}}plus{{$serv_id}}"
                                                    id="{{$id}}"
                                                />
                                                <span>&emsp;{{$cat_name}}: {{$serv_name}},<br>&emsp;{{$price}} руб.</span>
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
                                                value="{{$duration}}plus{{$serv_id}}"
                                                id="{{$id}}" />
                                                <span>&emsp;{{$serv_name}},<br>&emsp;{{$price}} руб.</span>
                                            </label>
                                        @endforeach
                                    @endif

                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="choice display_none" id="master_choice">
                    <h3 class="back shad rad pad margin_rlb1">Выберите специалиста</h3>
                    <div class="radio-group flex">
                        @foreach ($data['masters'] as $key => $master)
                            <article class="main_section_article radio" data-value="{{$master['id']}}">
                                <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                                    <img src="{{asset('storage'.DIRECTORY_SEPARATOR.$master['master_photo'])}}" alt="Фото {{$master['master_fam']}}" class="main_section_article_imgdiv_img" />
                                </div>

                                <div class="main_section_article_content margin_top_1rem">
                                    <h3 id="'{{$master['id']}}">{{$master['master_name']}} {{$master['master_fam']}}</h3>
                                    <span>{{$master['spec']}}</span>
                                </div>
                            </article>
                        @endforeach
                        <input type="hidden" id="master" name="master" />
                    </div>
                </div>

                <h3 class="back shad rad pad margin_rlb1 display_none" id="timeh3">Выберите время</h3>
                <div class="choice display_none margin_bottom_1rem" id="time_choice"></div>

                <div class="choice display_none" id="give_a_phone"></div>

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
      $('#services_choice').hide();
      $('#master_choice').show();
      $('html, body').animate({
          scrollTop: $(".title").offset().top
      }, 500);

      $(this).val('time_next');
      $('#button_back').val('services_choice').prop('disabled', false);
    }
    else if ( $('#master_choice #master').val() && $(this).val() == 'time_next' )
    {
      $('#master_choice').hide();
      $('#timeh3').show();
      $('#time_choice').show();
      $(this).val('phone_next');
      $('#button_back').val('master_choice');
      //console.log($('#master_choice #master').val());
      let master = $('#master_choice #master').val();
      appointment('short');
      $('html, body').animate({
          scrollTop: $(".title").offset().top
      }, 500);
      scrolltobuttonnext('input[name="time"]');
    }
    else if ( $('#time_choice input[name="time"]:checked').length && $(this).val() == 'phone_next' )
    {
        //alert($('.master_datetime input[name="time"]:checked').val());
        $('#timeh3').hide();
        $('#time_choice').hide();
        $('#give_a_phone').show();
        $(this).val('end_next');
        $('#button_back').val('time_choice');
        let master = $('#master_choice #master').val();
        let serv = $('#services_choice input[type="radio"]:checked').val();
        let ttime = Number($('#time_choice input[type="radio"][name="time"]:checked').val());
        const jsDateTime = new Date(ttime);
        const day = capitalizeFirstLetter(getDayName(jsDateTime, locale, long));
        const day_of_month = jsDateTime.getDate();
        const month = jsDateTime.getMonth();
        const year = jsDateTime.getFullYear();
        const hours = jsDateTime.getHours();
        const minutes = jsDateTime.getMinutes();

      $.ajax({
        url: '<?php echo url('/'); ?>/signup/appoint_phone',
    		method: 'post',
    		dataType: 'json',

    		data: {
                "_token": "{{ csrf_token() }}",
                'master': master,
                'serv': serv,
                'time': ttime
            },
    		success: function(data){
                if (data.res && data.res != 'empty') {
                    var tlf_form =
                        '<div class="form-recall-main">\
                            <h3 class="back shad rad pad margin_bottom_1rem">Введите свое имя и номер телефона для связи</h3>\
                            <div class="form-recall-main-section">\
                                <div class="flex">\
                                    <input type="text" placeholder="Ваше имя" name="zapis_name" id="zapis_name" maxlength="50"></input>\
                                    <input type="tel" name="zapis_phone_number"  id="number" class="number"\
                                        title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"\
                                        minlength="6" maxlength="17"\
                                        pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"\
                                        required />\
                                    <div id="error"><small></small></div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="form-recall-main">\
                            <p class="pers">\
                                Отправляя данную форму, вы даете согласие на\
                                <br>\
                                <a href="{{url('/')}}/persinfo">\
                                обработку персональных данных\
                                </a>\
                            </p>\
                        </div>';
                } else if (data.res && data.res == 'empty') {
                    var tlf_form = '<p class="pad">Извините, где-то возникла ошибка :(</p>';
                } else {
                    var tlf_form = "<p class=\"pad\">"+day+', '+day_of_month+' '+month+' '+year+', '+pad(hours)+':'+pad(minutes)+"\
                                        <br /> недавно были заняты другим клиентом.<br />Выберите, пожалуйста другое время.\
                                    </p>";
                }
    			$('#give_a_phone').html(tlf_form);
                /*
                $('body').find('.number').each(function(){
                        $(this).mask("+7 999 999 99 99",{autoclear: false});
                });
                */
    		},
            error: function(data) {
                $('#give_a_phone').html('<p class="pad">Извините, где-то возникла ошибка :(</p>');
            }
    	});
    }
    else if ( $('#give_a_phone input[name="zapis_phone_number"]').val() && $(this).val() == 'end_next' )
    {
      $('#give_a_phone').hide();
      $('#zapis_end').show();
      $('#button_back').val('give_a_phone');
      $(this).val('zapis_sql').html('Записаться!');
      //$('form#zapis_usluga_form').hide();

      let client_name = $('form#zapis_usluga_form input[name="zapis_name"]').val();
      $('#zapis_end').show().addClass('back shad rad pad margin_rlb1').html('<h3>'+client_name+' </h3>\
                                  <p id="zap_na">Вы записываетесь на:</p>\
                                  <div class="table_body text_left" >\
                                  ');
//////////////////////
      let serv = $('#services_choice input:radio:checked').val().split('plus');
      if (serv[1] != 'page_serv') {
        var cn = serv[1]+': ';
      }else {
        cn = '';
      }
      let price = serv[3].split('-');
      $('#zapis_end').append('  <div class="table_row">\
                                    <div class="table_cell text_right">'+serv[0]+', '+cn.toLowerCase()+' '+serv[2].toLowerCase()+'</div>\
                                    <div class="table_cell text_left">'+price[0]+' руб.</div>\
                                </div>');
/////////////////
      //let master_data = $('#master_choice #master').val().split('#');
      let master = $('#master_choice #master').val();
      let master_data = $('#'+master).html();
      $('#zapis_end').append('  <div class="table_row">\
                                    <div class="table_cell text_right">Мастер: </div>\
                                    <div class="table_cell text_left">'+master_data+'</div>\
                                </div>');

      let dayweek_date = $('#time_choice input[type="radio"][name="date"]:checked').val();
      let day_date_arr = dayweek_date.split(/\s{1}/);
      let date_0 = day_date_arr[1].split('-');
      let date = date_0[2]+'.'+date_0[1]+'.'+date_0[0]+', '+day_date_arr[0];
      let time = $('#time_choice input[type="radio"][name="time"]:checked').val();
      $('#zapis_end').append('<div class="table_row">\
                                <div class="table_cell text_right">Дата,<br /> время:</div>\
                                <div class="table_cell text_left">'+date+'<br />'+time+'</div>\
                              </div>');
      let phone = $('#give_a_phone input[type="tel"]').val().replace(/ /g, '\u00a0');
      $('#zapis_end').append('<div class="table_row">\
                                <div class="table_cell text_right">Ваш номер:</div>\
                                <div class="table_cell text_left">'+phone+' </div>\
                              </div>');

    }
    else if ( $(this).val() == 'zapis_sql' )
    {
      $.ajax({
    		url: '<?php echo url('/'); ?>/app/models/appoint_end.php',
    		method: 'post',
    		dataType: 'html',
    		data: $('form#zapis_usluga_form').serialize(),
    		success: function(data){
                $('#zapis_end').html(data);
                $('#button_back, #button_next').hide();
                //console.dir(data);
                }
            });
    }
    else
    {
      alert('Сделайте выбор или введите данные, пожалуйста.');
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
        } else if ($(this).prop('id') == 'master_choice') {
          $('#button_next').val('time_next');
          $('#button_back').val('services_choice');
          $('#timeh3').hide();
        }else if ($(this).prop('id') == 'time_choice') {
          $('#button_next').val('phone_next');
          $('#button_back').val('master_choice');
          let master = $('#master_choice #master').val();
          $('#timeh3').show();
          appointment('short');
        }else if ($(this).prop('id') == 'give_a_phone') {
          $('#button_back').val('time_choice');
          $('#button_next').val('end_next').html('Далее');
        } /* else if ($(this).prop('id') == 'zapis_end') {
          $('#button_back').val('give_a_phone');
          //$('#button_next').val('zapis_sql');
        } */
      } else {
        $(this).hide();
      }
    });
  });

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
});
}, false);
</script>

@stop
