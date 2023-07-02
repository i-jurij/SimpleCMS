@php
$title = "Shedule of masters";
$page_meta_description = "Shedule of masters";
$page_meta_keywords = "Shedule of masters";
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
    <form action="{{url()->route('admin.masters.shedule.edit')}}" method="post" name="grafiki-master" id="grafiki-master" class="pad form_radio_btn">
    @csrf
        @foreach ($data['masters'] as $master)
            <label class="">
                <input
                    type="radio"
                    name="master"
                    id="{{$master['id']}}"
                    value="{{$master['id']}}"
                />
                <span>
                    <img
                        class="display_inline_block margint0b0rlauto"
                        src="{{asset('storage'.DIRECTORY_SEPARATOR.$master['master_photo'])}}"
                        alt="Photo of {{$master['master_name']}} {{$master['sec_name']}} {{$master['master_fam']}}"
                        style="max-width:120px;"
                    />
                    <p>
                        {{$master['master_name']}} {{$master['sec_name']}} {{$master['master_fam']}}<br />{{$master['master_phone_number']}}
                    </p>
                </span>
            </label>
        @endforeach
    </form>
    </div>
    @endif
</div>
<script >
    document.addEventListener('DOMContentLoaded', function () {
        //submit master form
        document.querySelector('#grafiki-master').addEventListener('click',function(){
            document.querySelector('form#grafiki-master').submit();
        });
    }, false);
</script>
@stop
