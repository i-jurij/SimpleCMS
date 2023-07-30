<?php
    $title = "Ожидайте звонка";
    $page_meta_description = "В скором времени ожидайте обратный вызов.";
    $page_meta_keywords = "Обратный, вызов, звонок, перезвоним";
    $robots = "INDEX, FOLLOW";

?>


<?php $__env->startSection("content"); ?>
<?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>
    <?php if(!empty($res) && is_array($res)): ?>
        <p class="content">
            <?php $__currentLoopData = $res; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $re): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($re); ?><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    <?php elseif(!empty($res) && is_string($res)): ?> <p class="content"><?php echo e($res); ?></p>
    <?php else: ?>
        <p class="content">No data</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/client_manikur/client_pages/callback-store.blade.php ENDPATH**/ ?>