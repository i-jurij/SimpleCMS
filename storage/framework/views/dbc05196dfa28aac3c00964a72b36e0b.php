<?php
$title = 'Completed callbacks';
$page_meta_description = 'admins page, Completed callbacks';
$page_meta_keywords = 'Completed callbacks';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>

    <?php if(Session::has('res')): ?>
        <p class="content"><?php echo e(Session::get('res')); ?></p>
        <?php
            Session::put('res', '');
        ?>
    <?php endif; ?>

    <?php if(!empty($callbacks)): ?>
    <div class="content">
        <?php if( (Auth::user()->status === 'Admin' || Auth::user()->status === 'admin') || (Auth::user()->status === 'Moder' || Auth::user()->status === 'moder') ): ?>
            <form method="post" action="<?php echo e(url()->route('admin.callbacks.remove')); ?>" class="zapis_usluga">
            <?php echo csrf_field(); ?>
                <button type="submit" class="buttons" name="submit" value="clear">Очистить журнал</button>
            </form>
        <?php endif; ?>

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
    </div>
    <?php else: ?>
        <p class="content">MESSAGE:<br> Empty callbacks.</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/user_pages/callback_completed.blade.php ENDPATH**/ ?>