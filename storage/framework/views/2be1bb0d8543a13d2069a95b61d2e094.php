<?php
if (!empty($status)) {
    $stat = ' '.$status;
    if (trim($status) === 'remove') {
        $action_form = url()->route('admin.about_editor.remove');
    } elseif (trim($status) === 'edit') {
        $action_form = url()->route('admin.about_editor.post_edit');
    } elseif (trim($status) === 'create') {
        $action_form = url()->route('admin.about_editor.store');
    } else {
        $action_form = '';
    }
} else {
    $stat = '';
}

$title = 'Abouts editor'.$stat;
$page_meta_description = 'admins page, Abouts editor'.$stat;
$page_meta_keywords = 'about, edit';
$robots = 'NOINDEX, NOFOLLOW';
?>

            


            <?php $__env->startSection("content"); ?>

                <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>

                <div class="content">

                <?php if(!empty($action_form)): ?>
                    <form action="<?php echo e($action_form); ?>" method="post" name="about_edit" id="about_edit" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                        <?php if(!empty($abouts) && is_array($abouts) && (trim($status) === 'remove' || trim($status) === 'edit')): ?>
                            <p class="pad margin_bottom_1rem">Shoose article, then click "Submit"</p>
                            <div id="inputt"></div>
                            <?php $__currentLoopData = $abouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $about): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $img = imageFor($about['image']);
                                ?>
                                <article class="main_section_article"  id="<?php echo e($about['id']); ?>plusplus<?php echo e(sanitize($about['image'])); ?>">
                                    <div class="main_section_article_imgdiv margin_bottom_1rem">
                                        <img src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$img)); ?>" alt="<?php echo e($about['title']); ?>" class="main_section_article_imgdiv_img" />
                                    </div>
                                    <div class="main_section_article_content">
                                        <h3><?php echo e($about['title']); ?></h3>
                                        <span><?php echo e($about['content']); ?></span>
                                    </div>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php elseif(trim($status) === 'create'): ?>
                            <div class="zapis_usluga" >
                                <div id="inputs">
                                <p class="back shad rad pad mar">Выберите изображение, название и текст для новой карточки страницы</p>
                                <div class="about_form back shad rad pad mar display_inline_block" id="inp0">
                                    <label class="input-file">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
                                        <input type="file" id="f0" name="image_file[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" required />
                                        <span >Выберите фото весом до 1Мб</span>
                                        <p id="fileSizef0" ></p>
                                    </label>
                                    <label ><p>Введите название (до 50 символов)</p>
                                        <p>
                                        <input type="text" name="title[]" placeholder="Название" maxlength="50" required />
                                        </p>
                                    </label>
                                    <label ><p>Введите текст (до 500 символов)</p>
                                        <p>
                                        <textarea name="content[]" placeholder="Текст" maxlength="500" required ></textarea>
                                        </p>
                                    </label>
                                </div>
                                </div>
                                <div class="mar" id="aaf">
                                <button class="buttons" type="button" >Добавить еще</button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mar">
                            <input class="buttons" for="about_edit" type="submit" value="Submit">
                            <input class="buttons" for="about_edit" type="reset" value="Reset">
                        </div>

                    </form>
                <?php else: ?>
                    <?php if(!empty($res) && is_array($res)): ?>
                        <?php $__currentLoopData = $res; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($value['img'])): ?> <?php echo e($value['img']); ?><br> <?php endif; ?>
                            <?php echo e($value['db']); ?><br>
                            <?php if($key > 0 ): ?> <br> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif(!empty($res) && is_string($res)): ?>
                        <?php echo $res; ?>

                    <?php endif; ?>
                <?php endif; ?>
                </div>
            <?php $__env->stopSection(); ?>

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    $('#about_edit').on('reset', function(e) {
        setTimeout(function() {
            // for remove and edit form
            $("div#inputt").empty();
            $(".main_section_article").removeClass('selected');
            // for create form
            $('.about_form').each(function (i) {
                $('.about_form').slice(1).remove();
            });
            $("[type='file']").each(function(){
                let file = 'Выберите фото весом до 3Мб';
                $(this).next().html(file);
                $('#fileSize'+this.id).html('');
            });
        },200);
    });

    $(".main_section_article").on('click', function() {
        let id = this.id;
        //console.log(id);
        if ( $('#ch'+id).val()) {
            $('#ch'+id).remove();
            $(this).removeClass('selected');
        } else {
            let ar = id.split('plusplus');
            //console.log(id)
            $("div#inputt").append('<input type="hidden" name="id[]" value="'+ar[0]+'" id="ch'+id+'" />');
            $("div#inputt").append('<input type="hidden" name="image[]" value="'+ar[1]+'" id="ch'+id+'" />');
            $(this).addClass('selected');
        }
    });


  $('div#aaf > button').on('click', function(){
    var id = parseInt($("div#inputs").find(".about_form:last").attr("id").slice(3))+1;
    $("div#inputs").append('<div class="about_form back shad rad pad mar display_inline_block display_none" id="inp'+id+'">\
            <label class="input-file">\
              <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />\
              <input type="file" id="f'+id+'" name="image_file[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" required />\
              <span>Выберите фото весом до 1Мб</span>\
              <p id="fileSizef'+id+'" ></p>\
            </label>\
            <label ><p>Введите название (до 50 символов)</p>\
              <p>\
              <input type="text" name="title[]" placeholder="Название карточки" maxlength="50" required />\
              </p>\
            </label>\
            <label ><p>Введите текст (до 500 символов)</p>\
              <p>\
              <textarea name="content[]" placeholder="Текст карточки" maxlength="500" required ></textarea>\
              </p>\
            </label>\
        </div>');
  });

  $('form#about_edit').on('change', function(){
    let f = $("[type='file']");
    if (f.length > 0) {
        f.each(function(){
            let file = this.files[0];
            let size = 3*1024*1024; //3MB
            $(this).next().html(file.name);
            if (file.size > size) {
                $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > 3MB');
            } else {
                //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
            }
        });
    }
  });

}, false);
</script>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/about.blade.php ENDPATH**/ ?>