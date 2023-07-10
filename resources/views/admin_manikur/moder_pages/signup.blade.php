@php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
$uv = '';
@endphp
@extends("layouts/index_admin")

@section("content")

<div class="content">
    @if (!empty($data))
        @if (is_array($data))
            @if (!empty($data['by_date']))
                <?php
                if (!empty($dateprevnext)) {
                    $date = $dateprevnext;
                }
                $prev = date('Y-m-d', strtotime($date.'- 1 days'));
                $next = date('Y-m-d', strtotime($date.'+ 1 days'));
                // $fulldate = date('l d M Y', strtotime($date));
                $dt = \Carbon\Carbon::parse($date)->locale('ru_RU');
                $weekday = $dt->getTranslatedShortDayName();
                $dat = $dt->isoFormat('LL');
                $fulldate = $dat.', '.$weekday;
                ?>
                <p class="margin_rlb1">
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?prev=<?php echo $prev; ?>" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
                <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:15rem;"><?php echo $fulldate; ?></span>
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?next=<?php echo $next; ?>" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
                </p>

                <?php
                $res = '';
                foreach ($data['by_date']['work'] as $master => $signup) {
                    $art = '';
                    foreach ($signup as $value) {
                        if (\Carbon\Carbon::parse($value['start_dt'])->toDateString() === $date) {
                            $carbon_dt = \Carbon\Carbon::parse($value['start_dt']);
                            $time = $carbon_dt->format('H:i');
                            $datet = $carbon_dt->format('d.m.Y');
                            $art .= '<article class="main_section_article" id="'.$value['order_id'].'">
                                          <p>'.$time.'<br>'.$datet.'</p>
                                          <p>'.$value['service'].' </p>
                                          <p>'.$value['client'].'</p>
                                      </article>';
                        }
                    }
                    if (!empty($art)) {
                        $res .= '<div class="back shad rad pad margin_rlb1">';
                        $res .= '<p><b>'.$master.'</b></p>';
                        $res .= $art;
                        $res .= '</div>';
                    }
                }
                echo $res;
                ?>
            @elseif (!empty($data['by_master']))
                <div id="app">
                    <p class="pad">Выберите мастера:</p>
                @foreach ($data['by_master'] as $master)
                    @php
                        $master_name = $master['master_name'].' '
                            .$master['sec_name'].' '
                            .$master['master_fam'].'<br>'
                            .$master['master_phone_number'];
                    @endphp
                    <button
                        type="button"
                        class="buttons masters"
                        id="{{$master['id']}}">{!!$master_name!!}</button>
                @endforeach
                </div>
            @elseif (!empty($data['list']))
                <div id="js_table"></div>
                {{ $data['list']->links() }}
            @endif
        @elseif (is_string($data))
            <p>{{$data}}</p>
        @endif
    @else
        No data from controller
    @endif
</div>

<script>
    window.onload = function() {
        function js_date(datas){
            let day = days_of_week[new Date(datas).getDay()];
            let year = new Date(datas).getFullYear();
            let month = months[new Date(datas).getMonth()];
            let date = new Date(datas).getDate();
            let date_dt = day+', '+pad(date)+' '+month+' '+year;
            return date_dt;
        }
        function pad(n) {
            return n < 10 ? '0' + n : n;
        }
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        var days_of_week = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        var months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        window.Laravel = { csrfToken: '{{ csrf_token() }}' };

        async function data_from_db(url, enter_data = '') { // or data = {}
            const myHeaders = {
                //'Content-Type': 'application/json'
                'Content-Type': 'application/x-www-form-urlencoded',
                "X-CSRF-TOKEN": Laravel.csrfToken
            };

            const myInit = {
                method: 'POST',
                mode: 'same-origin', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: myHeaders,
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *client
                body: enter_data
                // JSON.stringify(data) // body data type must match "Content-Type" header
            };
            const myRequest = new Request(url, myInit);
            const response = await fetch(myRequest);
            const contentType = response.headers.get('content-type');
            //const mytoken = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Data from server is not JSON!");
            }

            return await response.json();
        }

        let elem = document.querySelectorAll('.masters');
        for (var i = 0; i < elem.length; i++) {
            elem[i].onclick = function (e) {
                let id = e.target.id;


                document.querySelector('#app').innerHTML = '';
                //document.querySelector('#div_'+id).toggle();
                data_from_db("{{url()->route('admin.signup.post_by_master')}}", "master_id=" + id)
                    .then(promise => promise)
                    .then(master_data => {
                        let string = '';
                        for (const key in master_data.post_by_master) {
                            if (Object.hasOwnProperty.call(master_data.post_by_master, key)) {
                                master = key;
                                string += '<div class="back shad rad pad margin_rlb1" >'+'<span><b>'+master+'</b></span></div>';

                                for (const datas in master_data.post_by_master[key]) {
                                    if (Object.hasOwnProperty.call(master_data.post_by_master[key], datas)) {

                                        string += '<div class="back shad rad pad margin_rlb1">\
                                                    <p class="pad">'+js_date(datas)+'</p>';
                                        let serv_and_client = master_data.post_by_master[key][datas];

                                        for (var i = 0; i < serv_and_client.length; i++){
                                            let start_dt = serv_and_client[i].start_dt;
                                            let hour = new Date(start_dt).getHours();
                                            let minutes = new Date(start_dt).getMinutes();
                                            let fulldate = pad(hour)+':'+pad(minutes);

                                            let service = serv_and_client[i].service;
                                            let client = serv_and_client[i].client;

                                            string += '<div class="back shad rad pad mar display_inline_block"'
                                                        +'<span class="display_inline_block zapis_usluga"><b>'+capitalizeFirstLetter(fulldate)+'</b></span><br>'
                                                        +service+'<br>'
                                                        +client+'<br>\
                                                        </div>';
                                        }
                                        string += '</div>';
                                    }
                                }
                            }
                        }
                        document.querySelector('#app').innerHTML = string;
                    })
                    .catch(function (err) {
                        console.log("Fetch Error :-S", err);
                    });
            };
        }

        let js_table = document.querySelector('#js_table');
        if (!!js_table) {
            let page_object = <?php if (!empty($data['list'])) {
                echo json_encode($data['list']);
            } else {
                echo 'list';
            }?>;

            //console.log(page_object['data'])
            var res_obj = {};
            page_object['data'].forEach(element => {
                let options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                //let date = capitalizeFirstLetter(new Date(element.start_dt).toLocaleDateString("ru-RU", options));
                const dt = new Date(element.start_dt);
                const year = dt.getFullYear();
                const month = dt.getMonth();
                const day = dt.getDate();
                let date = pad(day)+'.'+pad(month)+'.'+year;
                let order_id = element.id;
                let start_dt = pad(new Date(element.start_dt).getHours())
                    +':'+pad(new Date(element.start_dt).getMinutes())
                    +'<br>'+capitalizeFirstLetter(new Date(element.start_dt).toLocaleDateString("ru-RU", options));
                let uvolen = (element.master.data_uvoln == null || element.master.data_uvoln == "") ? '' : '<p style="color:red;">УВОЛЕН!</p>';
                let master = element.master.master_name+' '+element.master.sec_name+' '+element.master.master_fam+',<br>'+element.master.master_phone_number+uvolen;
                let category = (element.category != null || element.category != 'undefined') ? element.category+', ' : '';
                let service = element.page+', '+category+element.service.name+', '+element.service.duration+' мин., '+element.service.price+' руб.';
                let client = element.client.name+', '+element.client.phone;

                function arr_push() {
                    res_obj[date][master].push({start_dt: start_dt, order_id: order_id, service: service, client: client});
                }
                function obj_add() {
                    if (!!res_obj[date][master]) {
                        arr_push();
                    } else {
                        res_obj[date][master] = [];
                        arr_push();
                    }
                }

                if (!!res_obj[date]) {
                    obj_add();
                } else {
                    res_obj[date] = {};
                    obj_add();
                }
            });

            //console.log(res_obj)

            inhtml = '<table class="table price_form_table" id="signup_all_list">\
                                    <colgroup>\
                                        <col width="15%">\
                                        <col width="30%">\
                                        <col width="30%">\
                                        <col width="25%">\
                                    </colgroup>\
                                    <tbody>';
            for (const date in res_obj) {
                if (Object.hasOwnProperty.call(res_obj, date)) {
                    const element = res_obj[date];
                    date_p = '<tr><td colspan="4">'+date+'</td></tr>';
                    //js_table.parentNode.insertBefore(newDiv, js_table);
                    //js_table.insertAdjacentHTML( 'afterbegin', date_p );
                    inhtml += date_p;
                    for (const master in element) {
                        if (Object.hasOwnProperty.call(element, master)) {
                            const array = element[master];
                            inhtml += '<tr><td colspan="4">'+master+'</td></tr>';

                            for (let index = 0; index < array.length; index++) {
                                const data = array[index];
                                inhtml += ' <tr>\
                                                            <td>'
                                                                +data.start_dt+
                                                            '</td>\
                                                            <td>'
                                                                +data.service+
                                                            '</td>\
                                                            <td>'
                                                                +data.client+
                                                            '</td>\
                                                            <td>\
                                                                <button type="button" value="change" class="buttons" id="'+data.order_id+'">Изменить</button>\
                                                                <button type="button" value="delete" class="buttons" id="'+data.order_id+'" >Удалить</button>\
                                                            </td>\
                                                        </tr>';

                            }
                        }
                    }
                    inhtml += '<tr><td colspan="4"></td></tr>';
                }
            }
            inhtml += '</tbody></table>';
            js_table.innerHTML = inhtml;

            let table = document.querySelector('#signup_all_list');
            if(!!table) {
                table.addEventListener('click', function(element){
                    if (element.target.nodeName == 'BUTTON') {
                        let action = element.target.value;
                        let id = element.target.id;

                        if (action == 'delete' && id != 'undefined') {
                            data_from_db("{{url()->route('admin.signup.remove')}}", "order_id=" + id)
                            .then(promise => promise)
                            .then(master_data => {
                                location.reload();
                            })
                            .catch(function (err) {
                                console.log("Fetch Error :-S", err);
                            });
                        }
                    }
                });
            }
        }
    }
</script>
@stop
