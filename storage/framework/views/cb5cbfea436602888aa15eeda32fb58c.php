<?php
$title = "Form for search client";
$page_meta_description = "Form for search client";
$page_meta_keywords = "Form, search, client";
$robots = "NOINDEX, NOFOLLOW";
$uv = '';
?>


<?php $__env->startSection("content"); ?>

<div class="content" id="by_client">
    <p class="margin+tp+1rem">Search client by phone number:</p>
    <form action="<?php echo e(url()->route('admin.signup.by_client.post')); ?>" method="post" class="form-recall-main" id="find_client">
    <?php echo csrf_field(); ?>
        <div class="">
            <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
            <div class="form-group padt1">
                <input list="phone_numbers" type="tel" name="phone_number" id="client_phone_numbers"
                        title="Формат: +7 999 999 99 99" placeholder="+7 999 999 99 99"
                        minlength="6" maxlength="17"
                        pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                        value="<?php echo e(old('phone_number')); ?>"
                        required />
                <datalist id="phone_numbers">
                    <?php $__currentLoopData = $data['by_client']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client['phone']); ?>" id="<?php echo e($client['id']); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </datalist>
            </div>

            <div class="form-group pad" id="sr_but">
                <button class="buttons" id="submit_by_client">Submit</button>
                <button class="buttons" type="reset">Reset</button>
                <input type="hidden" name="client_id" id="by_client_client_id" />
            </div>
        </div>
    </form>
</div>
<script>
window.onload = function() {
    let by_client = document.querySelector('#by_client');
    if (!!by_client) {
        let by_client_submit = document.querySelector('#submit_by_client');
        if (!!by_client_submit) {
            by_client_submit.addEventListener('click', function () {
                event.preventDefault();
                let input = document.querySelector("#client_phone_numbers");
                let client = document.querySelector('option[value="'+input.value+'"]');
                let last_name = document.querySelector('#last_name').value ?? '';
                if (!!input && !!client && client.id && last_name == '') {
                    document.querySelector('#by_client_client_id').value = client.id;
                    document.querySelector('#client_phone_numbers').remove();
                    document.querySelector('#find_client').submit();
                }

            }, false);
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/signup_by_client_form.blade.php ENDPATH**/ ?>