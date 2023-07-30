<?php
$title = "Map edit";
$page_meta_description = "Map edit page";
$page_meta_keywords = "Map, editor";
$robots = "NOINDEX, NOFOLLOW";
?>




<?php $__env->startSection("content"); ?>
    <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>
    <?php if(!empty($res)): ?> <p class="content"><?php echo $res; ?></p>
    <?php else: ?>
        <div class="content" >
            <form action="<?php echo e(url()->route('admin.map.go')); ?>" method="post" name="map_form" id="map_form" class="margin_bottom_1rem" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
                <p class="margintb1">Вставьте ссылку на iframe карты из Google Map, Яндекс карт, OpenStreetMap. </p>
                <p class="margintb1">Javascript API карт здесь НЕ подключится. Это нужно сделать самостоятельно. </p>
                <p class="margintb1">
                    <a class="buttons display_inline_block margin_rl1" href="https://yandex.ru/search/?text=как+вставить+яндекс+карту+на+сайт&clid=2574587&win=536&lr=959"  target="_blank">Как получить ссылку?</a>
                </p>
                <input type="text" name="map_iframe" placeholder="<iframe src=xxx></iframe>" maxlength="5000" />

                <p class="margintb1">Или добавьте изображение карты</p>
                <input type="file" name="map_img" />
            </form>
            <div class="mar " id="form-buttons">
                <button class="buttons" form="map_form" type="reset" >Очистить</button>
                <button class="buttons" type="submit" form="map_form">Готово</button>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/map.blade.php ENDPATH**/ ?>