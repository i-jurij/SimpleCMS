<?php
$title = 'Contacts edit form';
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>
    <div class="content ">

        <div class="price">
        <form method="post" action="<?php echo e(url()->route('admin.contacts.update')); ?>" id="contacts_edit_form" class="margin_top_1rem">
            <?php echo csrf_field(); ?>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Data</th>
                </tr>
                <?php $__currentLoopData = $contact_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($data['id']); ?></td>
                    <td><input type="text" name="type" value="<?php echo e($data['type']); ?>" /></td>
                    <td><input type="text" name="data" value="<?php echo e($data['data']); ?>" required /></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
            <input type="hidden" name="id" value="<?php echo e($data['id']); ?>" required />
            <div class="form-element mar">
                <button type="submit" form="contacts_edit_form" class="buttons" id="contacts_submit">Submit</button>
                <button type="reset" form="contacts_edit_form" class="buttons" id="contacts_reset">Reset</button>
            </div>
        </form>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/contacts_edit_form.blade.php ENDPATH**/ ?>