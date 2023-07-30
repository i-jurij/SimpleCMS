<?php
$title = 'Masters data edit';
$page_meta_description = 'admins page, Masters data editing';
$page_meta_keywords = 'Masters data editing';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>

<?php if(!empty(session('mes'))): ?>
    <?php if(is_array(session('mes'))): ?>
        <p class="content">MESSAGE:<br>
            <?php $__currentLoopData = session('mes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $re): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($re); ?><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    <?php elseif(is_string(session('mes'))): ?>
        <p class="content">MESSAGE:<br> <?php echo e(session('mes')); ?></p>
    <?php endif; ?>
<?php endif; ?>

    <?php if(!empty($masters)): ?>
    <div class="content">
        <?php if(is_array($masters)): ?>
            <table class="table masters_edit">
                <caption>Masters</caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Spec</th>
                        <th>Hired Принят</th>
                        <th>Dismissed Уволен</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text_left">
                    <?php $__currentLoopData = $masters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $master): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' ?>
                        <?php if(!empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master['master_photo']))): ?>
                            <?php $img = $master['master_photo'] ?>
                        <?php elseif(empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg'))): ?>
                            <?php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg' ?>
                        <?php endif; ?>

                    <tr>
                        <td><?php echo e($key + 1); ?></td>
                        <td><img src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$img)); ?>" alt="Photo  <?php echo e($master['master_fam']); ?>" /></td>
                        <td><?php echo e($master['master_name']); ?><br> <?php echo e($master['sec_name']); ?><br> <?php echo e($master['master_fam']); ?></td>
                        <td><?php echo str_replace(' ', '&nbsp;', $master['master_phone_number']); ?></td>
                        <td>
                            <?php if(!empty($services) && !empty($services[$master['id']])): ?>
                            <ul>
                                <?php $__currentLoopData = $services[$master['id']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        list($page_id, $page_title) = explode('#', $key);
                                    ?>
                                    <li class="">
                                    <?php echo e($page_title); ?>:
                                    <br>
                                    <button class="buttons" onclick="showall(this.id);" id="spec<?php echo e($master['id']); ?><?php echo e($page_id); ?>">Show all</button>
                                    <br>
                                        <ul class="display_none" id="sspec<?php echo e($master['id']); ?><?php echo e($page_id); ?>">
                                            <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                ?>
                                                <?php if($cat_name !== 'page_serv'): ?>
                                                <li class="margin_top_1rem"><?php echo e($cat_name); ?>: </li>
                                                <ul>
                                                    <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="margin_rl1"><?php echo e($serv); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                    if ($cat_name === 'page_serv') $cat_name = 'Other';
                                                ?>
                                                <?php if($cat_name === 'Other'): ?>
                                                <li class="margin_top_1rem"><?php echo e($cat_name); ?></li>
                                                <ul>
                                                    <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="margin_rl1"><?php echo e($serv); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($master['data_priema']); ?></td>
                        <td><?php echo e($master['data_uvoln']); ?></td>
                        <td>
                            <form method="post" action="<?php echo e(url()->route('admin.masters.edit.form')); ?>" class="display_inline_block">
                            <?php echo csrf_field(); ?>
                                <button type="submit" class="buttons" value="<?php echo e($master['id']); ?>" name="id">Edit</button>
                            </form>
                            <form method="post" action="<?php echo e(url()->route('admin.masters.remove')); ?>" class="display_inline_block">
                            <?php echo csrf_field(); ?>
                                <button type="submit" class="buttons" value="<?php echo e($master['id']); ?>plusplus<?php echo e($img); ?>" name="id">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php elseif(is_string($masters)): ?>
            <p class="content">MESSAGE:<br> <?php echo e($masters); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if(!empty($masters_dism) && is_array($masters_dism)): ?>
    <div class="content">
        <table class="table masters_edit">
            <caption>Dismissed masters. Уволенные мастера.</caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Spec</th>
                        <th>Hired Принят</th>
                        <th>Dismissed Уволен</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text_left">
                    <?php $__currentLoopData = $masters_dism; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $master_dism): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' ?>
                        <?php if(!empty($master_dism['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master_dism['master_photo']))): ?>
                            <?php $img = $master_dism['master_photo'] ?>
                        <?php elseif(empty($master_dism['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master_dism['master_phone_number']))).'.jpg'))): ?>
                            <?php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master_dism['master_phone_number']))).'.jpg' ?>
                        <?php endif; ?>
                    <tr>
                        <td><?php echo e($key + 1); ?></td>
                        <td><img src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$img)); ?>" alt="Photo  <?php echo e($master['master_fam']); ?>" /></td>
                        <td><?php echo e($master_dism['master_name']); ?><br> <?php echo e($master_dism['sec_name']); ?><br> <?php echo e($master_dism['master_fam']); ?></td>
                        <td><?php echo e($master_dism['master_phone_number']); ?></td>
                        <td>
                        <?php if(!empty($services) && !empty($services[$master_dism['id']])): ?>
                            <ul>
                                <?php $__currentLoopData = $services[$master_dism['id']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        list($page_id, $page_title) = explode('#', $key);
                                    ?>
                                    <li class="">
                                    <?php echo e($page_title); ?>:
                                    <br>
                                    <button class="buttons" onclick="showall(this.id);" id="spec<?php echo e($master_dism['id']); ?><?php echo e($page_id); ?>">Show all</button>
                                    <br>
                                        <ul class="display_none" id="sspec<?php echo e($master_dism['id']); ?><?php echo e($page_id); ?>">
                                            <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                ?>
                                                <?php if($cat_name !== 'page_serv'): ?>
                                                <li class="margin_top_1rem"><?php echo e($cat_name); ?>: </li>
                                                <ul>
                                                    <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="margin_rl1"><?php echo e($serv); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                    if ($cat_name === 'page_serv') $cat_name = 'Other';
                                                ?>
                                                <?php if($cat_name === 'Other'): ?>
                                                <li class="margin_top_1rem"><?php echo e($cat_name); ?></li>
                                                <ul>
                                                    <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="margin_rl1"><?php echo e($serv); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($master_dism['data_priema']); ?></td>
                        <td><?php echo e($master_dism['data_uvoln']); ?></td>
                        <td>
                            <form method="post" action="<?php echo e(url()->route('admin.masters.edit.form')); ?>" class="display_inline_block">
                            <?php echo csrf_field(); ?>
                                <button type="submit" class="buttons" value="<?php echo e($master_dism['id']); ?>plusplus<?php echo e($img); ?>" name="id">Edit</button>
                            </form>
                            <form method="post" action="<?php echo e(url()->route('admin.masters.remove')); ?>" class="display_inline_block">
                            <?php echo csrf_field(); ?>
                                <button type="submit" class="buttons" value="<?php echo e($master_dism['id']); ?>plusplus<?php echo e($img); ?>" name="id">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
    </div>
    <?php endif; ?>

    <script>
        /*
        function showall(id) {
            const EL = document.querySelector('#s'+id);
            if (EL) {
                if (EL.style.display == "") {
                    EL.style.display =  "block";
                    document.querySelector('#'+id).innerHTML = 'Hide';
                } else {
                    EL.style.display =  "";
                    document.querySelector('#'+id).innerHTML = 'Show All';
                }
            }
        }
        */
        function showall(id) {
            const EL = $('#s'+id);
            if (EL) {
                EL.slideToggle('slow');
            }
            if ($('#'+id).html() == 'Show all') {
                $('#'+id).html('Hide');
            } else {
                $('#'+id).html('Show all');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/masters.blade.php ENDPATH**/ ?>