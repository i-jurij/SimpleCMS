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
                        class="buttons"
                        id="{{$master['id']}}" onclick="showZ(this.id);">{!!$master_name!!}</button>
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
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    function showZ(id){
        document.querySelector('#app').innerHTML = '';
        //document.querySelector('#div_'+id).toggle();
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

        data_from_db("{{url()->route('admin.signup.post_by_master')}}", "master_id=" + id)
            .then(promise => promise)
            .then(master_data => {

                document.querySelector('#app').innerHTML = master_data;
            })
            .catch(function (err) {
            console.log("Fetch Error :-S", err);
            });
    }
</script>
@stop
