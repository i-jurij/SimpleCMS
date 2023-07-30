<?php
$title = 'Users list';
$page_meta_description = 'admins page, deleting of users';
$page_meta_keywords = 'admins, user, delete';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>

    <div class="content margintb1 ">
        <div>
            <?php if(Auth::user()['status']==='admin'): ?>

            <p>
                WARNING!!!<br />
                You need to leave at least one user with the admin status.
            </p>

            <?php
            $labelclass = '';
            $divclass = "";
            if (\Request::getRequestUri() === "/admin/user/remove") {
                $res_route = route('admin.user.remove');
                $labelclass = "checkbox-btn";
                $type = "checkbox";
            } elseif (\Request::getRequestUri() === "/admin/user/change") {
                $res_route = route('admin.user.show');
                $divclass = "form_radio_btn";
                $type = "radio";
            }
            ?>
            <form method="post" action="<?php echo e($res_route); ?>" id="users_shoose" class="pad form_del_ch">
            <?php echo csrf_field(); ?>
                <div class="form-element margintb1 text_left <?php echo e($divclass); ?>">
                    <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="<?php echo e($labelclass); ?>">
                            <input type = "<?php echo e($type); ?>" id ="user_<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>" name="user_id[]" />
                                <span>
                                    <table class="text_left">
                                    <tr>
                                        <td>Name: </td>
                                        <td><?php echo e($user->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email: </td>
                                        <td><?php echo e($user->email); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status: </td>
                                        <td><?php echo e($user->status); ?></td>
                                    </tr>
                                    </table>
                                </span>
                            </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="form-element" id="paginator"><?php echo $content->render(); ?></div>
                <div class="form-element mar">
                    <button type="submit" form="users_shoose" class="buttons" id="del_ch">Submit</button>
                    <button type="reset" form="users_shoose" class="buttons">Reset</button>
                </div>
            </form>
            <?php else: ?>
            You are not authorized.
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/adm_pages/user_list.blade.php ENDPATH**/ ?>