@php
$title = "Edit shedule of masters";
$page_meta_description = "Edit shedule of masters";
$page_meta_keywords = "Edit, shedule, masters";
$robots = "NOINDEX, NOFOLLOW";
@endphp
@extends("layouts/index_admin")

@section("content")
    @if (!empty($data['res']))
        <div class="content">
            @if (is_array($data['res']))
                @php
                    print_r($data['res'])
                @endphp
            @elseif (is_string($data['res']))
                <p>{{$data['res']}}</p>
            @endif
        </div>
    @else
    <div class="content">
    <p class="" id="p_pro">Показать / скрыть справку</p>
    <div class="display_none text_left margintb1" style="max-width:60rem;" id="pro">
        <p>Запланированные выходные дни или часы в графике отмечены цветом.</p>
        <p>Чтобы добавить <b>выходной день</b>:</p>
        <ul>
            <li>нажмите на дату.</li>
        </ul>
        <p>Чтобы добавить <b>отдельное время отдыха или перерыва:</b></p>
        <ul>
            <li>нажмите на ячейку на пересечении нужного дня и времени.</li>
        </ul>
        <p>Выходные отмечать не нужно, по умолчанию они отключены для записи клиентов.</p>
        <p>Нажмите кнопку Готово, чтобы сохранить изменения.</p>
    </div>
    </div>

    <div class="content">
    <form action="{{url('/masters/shedule/store')}}" method="post" name="grafiki-master" id="grafiki-master" class="pad form_radio_btn">
    @csrf
        <pre>
            @php
                print_r($data);
            @endphp
        </pre>
        <script>
            appointment('shedule', '/signup/time', service_id, master_id, "{{csrf_token()}}");
        </script>

    </form>
    </div>
    @endif
</div>

<script >

</script>
@stop
