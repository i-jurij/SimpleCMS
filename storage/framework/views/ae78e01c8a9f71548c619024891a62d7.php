<?php
$title = "Shedule of masters";
$page_meta_description = "Shedule of masters";
$page_meta_keywords = "Shedule of masters";
$robots = "NOINDEX, NOFOLLOW";
?>


<?php $__env->startSection("content"); ?>
<link rel="stylesheet" href="<?php echo e(url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css')); ?>" />

<div class="content">
    <?php if(!empty(session('data'))): ?>
        <?php if(is_array(session('data'))): ?>
            <?php $__currentLoopData = session('data'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($mes); ?><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif(is_string(session('data'))): ?>
            <p class="pad"><?php echo e(session('data')); ?></p>
        <?php elseif(session('data') === false): ?>
            <p class="error pad">
                Warning!<br>
                Data of schedule have been NOT stored!
            </p>
        <?php endif; ?>
    <?php endif; ?>
    <p class="" id="p_pro">Показать / скрыть справку</p>
    <div class="display_none text_left margintb1" style="max-width:60rem;" id="pro">
        <p>Запланированные выходные дни или часы в графике отмечены цветом.</p>
        <p>Чтобы добавить <b>выходной день</b>:</p>
        <ul>
            <li>нажмите на дату.</li>
        </ul>
        <p>Чтобы добавить <b>отдельное время отдыха или перерыва:</b></p>
        <ul>
            <li>нажмите на ячейку на пересечении нужного дня и времени.</li>
            <li>Чтобы снять отметку - кликните по ячейке еще раз.</li>
            <li>Чтобы снять все отметки - нажмите кнопку "Сбросить".</li>
        </ul>
        <p>Выходные дни и обеденные часы отмечать не нужно, по умолчанию они уже отключены для записи клиентов.</p>
        <p>Нажмите кнопку Готово, чтобы сохранить изменения.</p>
    </div>
    </div>
    <form action="<?php echo e(url()->route('admin.masters.shedule.store')); ?>" method="post" id="zapis_usluga_form" class="content pad form_radio_btn">
    <?php echo csrf_field(); ?>
        <div id="master_choice">
            <?php $__currentLoopData = $data['masters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $master): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="">
                    <input
                        type="radio"
                        name="master"
                        id="<?php echo e($master['id']); ?>"
                        value="<?php echo e($master['id']); ?>"
                    />
                    <span>
                        <img
                            class="display_inline_block margint0b0rlauto"
                            src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$master['master_photo'])); ?>"
                            alt="Photo of <?php echo e($master['master_name']); ?> <?php echo e($master['sec_name']); ?> <?php echo e($master['master_fam']); ?>"
                            style="max-width:120px;"
                        />
                        <p id="mnsf<?php echo e($master['id']); ?>">
                            <?php echo e($master['master_name']); ?> <?php echo e($master['sec_name']); ?> <?php echo e($master['master_fam']); ?><br /><?php echo e($master['master_phone_number']); ?>

                        </p>
                    </span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div id="time_choice"></div>
        <div class="hor_center buts display_none" id="buttons_div">
            <button type="button" class="but" id="zapis_usluga_form_res" disabled />Сбросить</button>
            <button type="button" class="but" id="zapis_usluga_form_sub" disabled />Готово</button>
        </div>
    </form>
</div>
<script src="<?php echo e(url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')); ?>"></script>

<script >
    document.addEventListener('DOMContentLoaded', function () {
        //submit master form
        let mc = document.querySelector('#master_choice');
        if (!!mc) {
            mc.addEventListener('click',function(element){
                //document.querySelector('form#grafiki-master').submit();
                let input = document.querySelector('input[type="radio"][name="master"]:checked');
                if(!!input) {
                    var master_id = input.value;
                    var service_id = '';
                    document.querySelector('#master_choice').style.display = 'none';

                    let master_name = document.querySelector('#mnsf'+master_id).innerHTML;
                    document.getElementById("page_title").innerHTML += "<br>"+master_name;

                    if (master_id !== 'undefined' || master_id !== '' || master_id !== null) {
                        document.querySelector('#buttons_div').style.display = 'block';
                        appointment('schedule', "<?php echo e(url()->route('admin.masters.shedule.edit')); ?>", service_id, master_id, "<?php echo e(csrf_token()); ?>");
                        //window.scrollTo(0, 0);
                    }
                }
            });
        }
    }, false);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/shedule_masters.blade.php ENDPATH**/ ?>