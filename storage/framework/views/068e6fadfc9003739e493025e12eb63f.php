<?php
$title = 'Pages editing';
$page_meta_description = 'admins page, Pages editing';
$page_meta_keywords = 'Pages editing';
$robots = 'NOINDEX, NOFOLLOW';

$sp = false;
if ((bool) mb_strstr(url()->current(), 'service_page')) {
    $sp = true;
    $title = 'Service page editing';
    $page_meta_description = 'admins page, service page editing';
}
?>


<?php $__env->startSection('content'); ?>
    <?php if(!empty($res)): ?> <p class="content">MESSAGE:<br> <?php echo $res; ?></p> <?php endif; ?>

    <?php if(!empty($pages)): ?>
        <?php if(is_array($pages)): ?>
        <div class="content margintb1 ">
        <div class="">
                <table class="table">
                    <thead>
                    <tr>
                        <th>N</th>
                        <th>Alias</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text_left">
                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($sp): ?>
                                <?php if($page['service_page'] === 'yes'): ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($page['alias']); ?></td>
                                        <td><?php echo e($page['title']); ?></td>
                                        <td>
                                        <form method="post" action="<?php echo e(url()->route('admin.pages.edit.form')); ?>" class="display_inline_block">
                                        <?php echo csrf_field(); ?>
                                            <button type="submit" class="buttons" value="<?php echo e($page['id']); ?>" name="id">Edit</button>
                                        </form>
                                        <form method="post" action="<?php echo e(url()->route('admin.pages.remove')); ?>" class="display_inline_block">
                                        <?php echo csrf_field(); ?>
                                            <button type="submit" class="buttons" value="<?php echo e($page['id']); ?>plusplus<?php echo e($page['alias']); ?>plusplus<?php echo e($page['img']); ?>plusplus<?php echo e($page['single_page']); ?>plusplus<?php echo e($page['service_page']); ?>" name="id">Remove</button>
                                        </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($page['alias']); ?></td>
                                    <td><?php echo e($page['title']); ?></td>
                                    <td>
                                    <form method="post" action="<?php echo e(url()->route('admin.pages.edit.form')); ?>" class="display_inline_block">
                                    <?php echo csrf_field(); ?>
                                        <button type="submit" class="buttons" value="<?php echo e($page['id']); ?>" name="id">Edit</button>
                                    </form>
                                    <form method="post" action="<?php echo e(url()->route('admin.pages.remove')); ?>" class="display_inline_block">
                                    <?php echo csrf_field(); ?>
                                        <button type="submit" class="buttons" value="<?php echo e($page['id']); ?>plusplus<?php echo e($page['alias']); ?>plusplus<?php echo e($page['img']); ?>plusplus<?php echo e($page['single_page']); ?>plusplus<?php echo e($page['service_page']); ?>" name="id">Remove</button>
                                    </form>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
            <p class="content">MESSAGE:<br> <?php echo e($pages); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p class="content">MESSAGE:<br> No data</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/pages.blade.php ENDPATH**/ ?>