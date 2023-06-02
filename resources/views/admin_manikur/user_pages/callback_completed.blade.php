<?php
$title = 'Completed callbacks';
$page_meta_description = 'admins page, Completed callbacks';
$page_meta_keywords = 'Completed callbacks';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    @if (!empty($res))
        <p class="content">{{$res}}</p>
    @elseif (!empty($callbacks))
    <div class="zapis_usluga pad">
        <form method="post" action="{{ url()->route('admin.callbacks.remove') }}" class="display_inline_block">
        @csrf
            <a href="{{ url()->route('admin.callbacks.remove') }}" class="buttons">Очистить журнал</a>
        </form>
    </div>
    <div class="div_center pad" style="width:100%;max-width:1240px;">

            <table class="table">
                <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="50%">
                </colgroup>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Дата, время</th>
                    <th>Номер</th>
                    <th>Имя</th>
                    <th>Сообщение</th>
                </tr>
                </thead>
                <tbody>
            <?php
            $i = 1;
foreach ($callbacks as $value) {
    $date = new DateTimeImmutable($value->created_at);
    $data = $date->format('d.m.Y');
    $time = $date->format('H:i');
    ?>
                                <tr>
                                <td><?php echo $i; ?></td>
                                <td style="text-align:left"><?php echo $data.' '.$time; ?></td>
                                <td style="text-align:left; white-space: nowrap;"><?php echo $value->client['phone']; ?></td>
                                <td style="text-align:left"><?php echo $value->name; ?></td>
                                <td style="text-align:left"><?php echo $value->send; ?></td>
                                </tr>
                                <?php
    ++$i;
}
?>
            </tbody>
            </table>
    </div>
    @else
        <p class="content">MESSAGE:<br> Empty callbacks.</p>
    @endif
@stop
