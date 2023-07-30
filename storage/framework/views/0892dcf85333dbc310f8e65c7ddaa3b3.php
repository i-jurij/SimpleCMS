<?php
$title = 'Contacts creating';
$page_meta_description = 'admins page, Contacts creating';
$page_meta_keywords = 'contacts, creating';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>
<div class="content">
    <p>
        Create contacts data:<br />
        type: adres, tlf, email, vk, telegram, watsapp, viber or other:<br />
        data: value of contact.<br />
    </p>
</div>

<div>
<form action="<?php echo e(route('admin.contacts.store')); ?>" method="post" class="content">
    <?php echo csrf_field(); ?>
    <div class="form-recall-main ">

        <div class="mar">
            <div class="mar">
                <input type="text" placeholder="Type of contact eg tlf" name="type" maxlength="100" />
                <input type="text" placeholder="Value eg +7 978 000 11 22" name="data" maxlength="100" />
            <div id="error"><small></small></div>
        </div>

        <div class="mar">
            <button class="buttons form-recall-submit" type="submit">Submit</button>
            <button class="buttons form-recall-reset" type="reset" onclick="Reset()">Reset</button>
        </div>

    </div>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/contacts_create.blade.php ENDPATH**/ ?>