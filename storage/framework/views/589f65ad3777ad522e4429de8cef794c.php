<?php
$st = (isset($stat)) ? $stat : '';

$title = $st.' callback';
$page_meta_description = 'admins page, Callbacks';
$page_meta_keywords = 'Callbacks';
$robots = 'NOINDEX, NOFOLLOW';

$fmt = new IntlDateFormatter(
    'ru-RU',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Moscow',
    IntlDateFormatter::GREGORIAN,
    'HH:mm, dd MMMM Y, EEEE'
);
// 'HH:mm, dd MMM yy, EEEE'

?>


<?php $__env->startSection('content'); ?>
    <?php if(!empty($res)): ?>
        <p class="content"><?php echo e($res); ?></p>
    <?php elseif(!empty($callbacks)): ?>

    <div class="content">

        <div class="margin_bottom_1rem">
            Выберите номера по которым уже перезвонили, поставив галочку. <br />
            Нажмите кнопку "Удалить", чтобы убрать их из списка,<br />
            или "Сбросить", чтобы снять выбранное.
        </div>

        <form action="" method="post" class="">
            <?php echo csrf_field(); ?>
            <div class="margintb05">
            <input type="submit" class="buttons" name="submit" value="Удалить"/>
            <input type="reset" class="buttons" value="Cбросить"/>
            </div>
            <div class="flex adm_recall_article_container">
                <?php $__currentLoopData = $callbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="adm_recall_article ">
                        <div class=""><?php echo e($fmt->format($cb->created_at)); ?></div>
                            <div class="margin_botom_1rem">
                                <table class="text_left">
                                    <tr>
                                        <td>Имя:</td>
                                        <td>&nbsp;<?php echo e($cb->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Номер:</td>
                                        <td>&nbsp;<?php echo e($cb->client['phone']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Сообщение:</td>
                                        <td>&nbsp;<?php echo e($cb->send); ?></td>
                                    </tr>
                                </table>
                                <p class="margin_top_1rem">
                                    <label ><input type="checkbox" name="id[]" value="<?php echo e($cb->id); ?>" /> Перезвонили</label>
                                </p>
                            </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>
    </div>
    <?php else: ?>
        <p class="content">MESSAGE:<br> Empty callbacks.</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/user_pages/callbacks.blade.php ENDPATH**/ ?>