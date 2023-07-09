@php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
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
                ?>
                <p class="margin_rlb1">
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?prev=<?php echo $prev; ?>" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
                <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:15rem;"><?php echo date('l d M Y', strtotime($date)); ?></span>
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?next=<?php echo $next; ?>" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
                </p>

                <?php
                $res = '';
                foreach ($data['by_date'] as $master => $signup) {
                    $art = '';
                    $res .= '<div class="back shad rad pad margin_rlb1">';
                    $res .= '<p><b>'.$master.'</b></p>';
                    foreach ($signup as $value) {
                        if (\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value['start_dt'])->toDateString() === $date) {
                            $art .= '<article class="main_section_article">
                                          <p>'.\Carbon\Carbon::createFromFormat('Y-m-d H:m:i', $value['start_dt'])->format('d.m.Y H:i').'</p>
                                          <p>'.$value['service'].' </p>
                                          <p>'.$value['client'].'</p>
                                      </article>';
                        }
                    }
                    if (!empty($art)) {
                        $res .= $art;
                    } else {
                        $res .= '<p class="pad">К мастеру нет записей на '.date('d.m.Y', strtotime($date)).'.</p>';
                    }
                    $res .= '</div>';
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
                                        let day = days_of_week[new Date(datas).getDay()];
                                        let year = new Date(datas).getFullYear();
                                        let month = months[new Date(datas).getMonth()];
                                        let date = new Date(datas).getDate();
                                        let date_dt = day+', '+pad(date)+' '+month+' '+year;

                                        string += '<div class="back shad rad pad margin_rlb1">\
                                                    <p class="pad">'+date_dt+'</p>';
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
    }
</script>
@stop
