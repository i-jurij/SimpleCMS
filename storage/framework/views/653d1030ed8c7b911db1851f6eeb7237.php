<?php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]['title'];
    $page_meta_description = $page_data[0]['description'];
    $page_meta_keywords = $page_data[0]['keywords'];
    $robots = $page_data[0]['robots'];
    $content['page_content'] = $page_data[0]['content'];
} else {
    $title = 'Title';
    $page_meta_description = 'description';
    $page_meta_keywords = 'keywords';
    $robots = 'INDEX, FOLLOW';
    $content = 'CONTENT FOR DEL IN FUTURE';
}
?>



<?php $__env->startSection('content'); ?>
    <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>

    <div class="back shad rad pad margin_rlb1">
    <?php if(!empty($content['page_content'])): ?>
        <?php echo $content['page_content']; ?>

    <?php else: ?>
        No content
    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/client_manikur/page_template.blade.php ENDPATH**/ ?>