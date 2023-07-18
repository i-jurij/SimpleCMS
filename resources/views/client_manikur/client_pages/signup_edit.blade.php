<?php
    $title = 'Editing an appointments';
    $page_meta_description = 'Editing an appointments';
    $page_meta_keywords = 'appointment, signup, edit, remove';
    $robots = 'INDEX, FOLLOW';
    $content['content'] = 'CONTENT OR NOT CONTENT';

    function inhtml($res_obj)
    {
        $inhtml = '<table class="table price_form_table" id="signup_all_list">
                                    <colgroup>
                                        <col width="20%">
                                        <col width="30%"
                                        <col width="25%">
                                        <col width="25%">
                                    </colgroup>
                                    <tbody>';
        foreach ($res_obj as $key => $date) {
            $inhtml .= '<tr><td colspan="4">'.$key.'</td></tr>';
            foreach ($date as $data) {
                $inhtml .= ' <tr>
                                    <td id="time_order_id'.$data['order_id'].'">'
                                        .$data['start_dt'].
                                    '</td>
                                    <td>'
                                        .$data['service'].
                                    '</td>
                                    <td>'
                                        .$data['master'].
                                    '</td>
                                    <td>
                                        <button type="button" value="change" class="buttons" id="'.$data['order_id'].'">Изменить</button>
                                        <button type="button" value="delete" class="buttons" id="'.$data['order_id'].'" >Удалить</button>
                                    </td>
                                </tr>';
            }
            $inhtml .= '<tr><td colspan="4"></td></tr>';
        }
        $inhtml .= '</tbody></table>';

        return $inhtml;
    }
    ?>


@extends("layouts/index")

@section("content")
<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

    @if (!empty($res))
        <div class="content" id="res_div">
            @if (is_array($res))
                @php
                    print_r($res)
                @endphp
            @elseif (is_string($res))
                <p>{{$res}}</p>
            @endif
        </div>
    @endif

    @if (!empty($signup))
        <div class="content" id="signup_list_div">
            @if (is_array($signup))
                {!!inhtml($signup)!!}
                @include('components.back_button_js')
            @elseif (is_string($signup))
                <p>{{$signup}}</p>
            @endif
        </div>
    @endif

    @if (!empty($data))
        <div class="content" id="signup_edit_div"></div>
    @endif

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    function pad(n) {
            return n < 10 ? '0' + n : n;
    }
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    var days_of_week = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
    var months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

    let res_div = document.querySelector('#res_div');
    if (res_div) {
        const yOffset = -100;
        const y = res_div.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({top: y, behavior: 'smooth'});
    }

    let table = document.querySelector('#signup_all_list');
    if(table) {
        table.addEventListener('click', function(element){
            let form_data = '<form action="" method="post" id="signup_edit_form" name="theForm">@csrf\
                            <input type="hidden" name="order_id" value="" id="order_id_input" />\
                            <input type="hidden" name="client_id" value="" id="order_client_id" />\
                        </form>';
            let div_for_paste = document.querySelector('#signup_list_div') ;
            if (div_for_paste) {
                div_for_paste.innerHTML += form_data;
            }

            if (element.target.nodeName == 'BUTTON') {
                let signup_action = element.target.value;
                let id = element.target.id;
                let input = document.querySelector('#order_id_input');
                if (input) {
                    input.value = id;
                }
                let input_client_id = document.querySelector('#order_client_id');
                input_client_id.value = '<?php if (!empty($client_id)) {
                    echo $client_id;
                } else {
                    echo '';
                } ?>';

                if (signup_action == 'delete' && id) {
                    if (document.theForm) {
                        document.theForm.action = '{{url()->route("client.signup.remove")}}';
                        document.theForm.submit();
                    }
                }
                if (signup_action == 'change' && id) {
                    if (document.theForm) {
                        document.theForm.action = '{{url()->route("client.signup.edit")}}';
                        document.theForm.submit();
                    }
                }
            }
        });
    }

    let edit_div = document.querySelector('#signup_edit_div');
    if (edit_div) {
        master_data = <?php if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo '';
        }?>;
        console.log(master_data)
        if (master_data) {
            arr = master_data.edit;
            order_id = arr.id;
            service_id = arr.service_id;
            serv_dur = (new Date(arr.end_dt).getTime() - new Date(arr.start_dt).getTime()) / 1000 / 60;

            edit_div.innerHTML = '<div class="form-recall-main">\
                                        <table class="table price_form_table" id="signup_change">\
                                            <tbody class="text_left">\
                                                <tr>\
                                                    <td>\
                                                        Время: \
                                                    </td>\
                                                    <td id="order_time">'
                                                        +pad(new Date(arr.start_dt).getHours())
                                                        +':'+pad(new Date(arr.start_dt).getMinutes())
                                                        +'<br>'+capitalizeFirstLetter(new Date(arr.start_dt).toLocaleDateString("ru-RU", { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }))+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="time_ch">Перенести</button>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Мастер: \
                                                    </td>\
                                                    <td id="order_master">'
                                                        +arr.master.master_name+' '+arr.master.sec_name+' '+arr.master.master_fam+', '+arr.master.master_phone_number+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="master_ch">Выбрать</button>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Услуга: \
                                                    </td>\
                                                    <td>'
                                                        +arr.service+
                                                    '</td>\
                                                    <td>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Клиент: \
                                                    </td>\
                                                    <td>'
                                                        +arr.client.name+', '+arr.client.phone+
                                                    '</td>\
                                                    <td>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Статус: \
                                                    </td>\
                                                    <td>'
                                                        +arr.status+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="status_ch">Change status</button>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                        </table>\
                                    </div>';
        }

        /*
        let table_ch = document.querySelector('#signup_change');
        if (!!table_ch) {
            table_ch.addEventListener('click', function(element){
                if (element.target.id == 'time_ch') {
                    if (!!document.querySelector('#order_master').value) {
                        master_id = document.querySelector('#order_master').value;
                        //console.log(master_id+'new')
                    } else {
                        master_id = arr.master_id;
                        //console.log(master_id)
                    }
                    time_change(order_id, serv_dur, master_id);
                }
                if (element.target.id == 'master_ch') {
                    if (!!document.querySelector('#order_time').value) {
                        d = new Date(document.querySelector('#order_time').value);
                        start_dt = (d.getFullYear()
                        +"-"+("0"+(d.getMonth()+1)).slice(-2)
                        +"-"+("0"+d.getDate()).slice(-2)
                        +" "+("0"+d.getHours()).slice(-2)
                        + ":" + ("0" + d.getMinutes()).slice(-2));
                    } else {
                        start_dt = arr.start_dt;
                    }
                    master_change(order_id, service_id, start_dt);
                }
            });
        }
        */
    }
}, false);
</script>

@stop
