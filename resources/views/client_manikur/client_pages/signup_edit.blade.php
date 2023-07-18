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
    <div class="content" id="signup_edit_div">
        @if (is_array($signup))
            {!!inhtml($signup)!!}
            @include('components.back_button_js')
        @elseif (is_string($signup))
            <p>{{$signup}}</p>
        @endif
    </div>
    @endif

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
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
            let div_for_paste = document.querySelector('#signup_edit_div') ;
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
                input_client_id.value = '{{$client_id}}';

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
}, false);
</script>

@stop
