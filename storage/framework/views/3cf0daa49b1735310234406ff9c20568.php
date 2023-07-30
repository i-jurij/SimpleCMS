<?php
$title = 'Admins page';
$page_meta_description = 'admins page';
$page_meta_keywords = 'admins page';
$robots = 'NOINDEX, NOFOLLOW';
?>



<?php $__env->startPush('css'); ?>
*{
   /* color: black; */
}
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components/admin-pages-panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/home_adm.blade.php ENDPATH**/ ?>