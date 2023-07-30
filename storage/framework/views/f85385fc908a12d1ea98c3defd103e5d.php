<?php
$title = 'Abouts edit form';
$page_meta_description = 'admins page, Abouts edit form';
$page_meta_keywords = 'about, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
$filesize = 1;
?>

            


            <?php $__env->startSection("content"); ?>

                <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>

                <div class="content">
                <?php if(is_array($abouts)): ?>
                    <form method="post" action="<?php echo e(url()->route('admin.about_editor.update')); ?>"  enctype="multipart/form-data" id="about_update" class="form_page_add">
                    <?php echo csrf_field(); ?>
                        <?php $__currentLoopData = $abouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $about): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="zapis_usluga" >
                                <p class="">Измените изображение, название и текст карточки страницы</p>
                                <div class="about_form back shad rad pad mar display_inline_block" id="inp0">
                                    <input type="hidden" name="id[]" value="<?php echo e($about['id']); ?>" />
                                    <label class="input-file">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo e($filesize*1024000); ?>" />
                                        <input type="file" id="f0" name="image_file[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                                        <span >Изображение весом до <?php echo e($filesize); ?>Мб</span>
                                        <p id="fileSizef0" ></p>
                                    </label>
                                    <label ><p>Название (до 50 символов)</p>
                                        <p>
                                        <input type="text" name="title[]"  value="<?php echo e($about['title']); ?>" maxlength="50" />
                                        </p>
                                    </label>
                                    <label ><p>Текст (до 500 символов)</p>
                                        <p>
                                        <textarea name="content[]" maxlength="500" ><?php echo e($about['content']); ?></textarea>
                                        </p>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="mar">
                            <input class="buttons" form="about_update" type="submit" value="Submit">
                            <input class="buttons" form="about_update" type="reset" value="Reset">
                        </div>

                    </form>
                <?php else: ?> <?php echo e($abouts); ?>

                <?php endif; ?>
                </div>
            <?php $__env->stopSection(); ?>

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    $('form#about_update').on('change', function(){
    let f = $("[type='file']");
    if (f.length > 0) {
        f.each(function(){
            let file = this.files[0];
            let size = <?php echo $filesize; ?>*1024*1024; //1MB
            $(this).next().html(file.name);
            $('#fileSize'+this.id).html('');
            if (file.size > size) {
                $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > <?php echo $filesize; ?>MB');
            } else {
                //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
            }
        });
    }
  });
}, false);
</script>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/about_edit_form.blade.php ENDPATH**/ ?>