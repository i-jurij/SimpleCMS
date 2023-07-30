<?php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    // title get from $this_show_method_data['name']
    $title = $page_data[0]["title"];
    // page_meta_description get from $data['cat']['description']
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}

?>




<?php $__env->startSection("content"); ?>

    <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>

    <?php if(!empty($this_show_method_data)): ?>
        <?php echo $__env->make('components.back_button_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php
            $title = (!empty($this_show_method_data['cat']['name'])) ? $this_show_method_data['cat']['name'] : $this_show_method_data['serv']['name'];
            $page_meta_description = (!empty($this_show_method_data['cat']['description'])) ? $this_show_method_data['cat']['description'] : ((!empty($this_show_method_data['serv']['description'])) ? $this_show_method_data['serv']['description'] : '');
            $page_meta_keywords =str_replace(' ', ', ', $page_meta_description);
            $robots = "INDEX, FOLLOW";
        ?>

        <?php if(!empty($this_show_method_data['cat'])): ?>
            <?php
                $cat = $this_show_method_data['cat'];
                $img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['image'];
            ?>
            <article class="main_section_article ">
                <div class="main_section_article_imgdiv">
                    <img src="<?php echo e(asset('storage'.$img_cat)); ?>" alt="Фото <?php echo e($cat['name']); ?>" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content margin_top_1rem">
                    <h3><?php echo e($cat['name']); ?></h3>
                    <span><?php echo e($cat['description']); ?></span>
                </div>
            </article>

            <?php if(!empty($this_show_method_data['serv'])): ?>
                <?php $__currentLoopData = $this_show_method_data['serv']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
                    ?>
                    <?php if(empty($data['serv'][$ke]['category_id']) || $data['serv'][$ke]['category_id'] === ''): ?>
                        <article class="main_section_article ">
                            <div class="main_section_article_imgdiv">
                                <img src="<?php echo e(asset('storage'.$img_serv)); ?>" alt="Фото <?php echo e($serv['name']); ?>" class="main_section_article_imgdiv_img" />
                            </div>
                            <div class="main_section_article_content  margin_top_1rem">
                                <h3><?php echo e($serv['name']); ?></h3>
                                <span><?php echo e($serv['description']); ?></span><br />
                                <span>от <?php echo e($serv['price']); ?> руб.</span>
                            </div>
                        </article>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endif; ?>

        <?php if(empty($this_show_method_data['cat']) && !empty($this_show_method_data['serv'])): ?>
            <?php
                $serv = $this_show_method_data['serv'];
                $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
            ?>
            <article class="back shad rad pad margin_rlb1">
                <div class="persinfo ">
                    <img
                        src="<?php echo e(asset('storage'.$img_serv)); ?>"
                        alt="Фото <?php echo e($serv['name']); ?>"
                        style="width:60%;display:block;margin:auto;"
                    />
                </div>
                <div class="  margin_top_1rem">
                    <h3><?php echo e($serv['name']); ?></h3>
                    <span><?php echo e($serv['description']); ?></span><br />
                     <span>от <?php echo e($serv['price']); ?> руб.</span>
                </div>
            </article>
        <?php endif; ?>

    <?php else: ?>
        <article class="main_section_article">
            <div class="main_section_article_imgdiv pad" style="background-color: var(--bgcolor-content);">
                <h2>Расценки</h2>
            </div>
            <div class="main_section_article_content"><br />
                <h2><?php echo e($title); ?></h2>
                <?php if(!empty($data['min_price'])): ?>
                    <?php $__currentLoopData = $data['min_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span><?php echo e($k); ?> - от <?php echo e($v); ?> руб.</span><br />
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <br />
                <a href="<?php echo e(url('/price#'.$page_data[0]['alias'])); ?>" style="text-decoration: underline;">Прайс</a>
            </div>
        </article>

        <?php if(!empty($data['cat'])): ?>
            <?php $__currentLoopData = $data['cat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['image'];
            ?>
                <article class="main_section_article ">
                <a href="<?php echo url('/'.$page_data[0]['alias'].'/category/'.$cat['id']); ?>">
                    <div class="main_section_article_imgdiv">
                        <img src="<?php echo e(asset('storage'.$img_cat)); ?>" alt="Фото <?php echo e($cat['name']); ?>" class="main_section_article_imgdiv_img" />
                    </div>
                    <div class="main_section_article_content margin_top_1rem">
                        <h3><?php echo e($cat['name']); ?></h3>
                            <?php if(!empty($data['serv'])): ?>
                                <?php $__currentLoopData = $data['serv']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($serv['category_id'] == $cat['id']): ?>
                                        <span><?php echo e($serv['name']); ?> от <?php echo e($serv['price']); ?> руб.</span><br />
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                     </div>
                </a>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if(!empty($data['serv'])): ?>
            <?php $__currentLoopData = $data['serv']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
                ?>
                <?php if(empty($data['serv'][$ke]['category_id']) || $data['serv'][$ke]['category_id'] === ''): ?>
                    <article class="main_section_article ">
                    <a href="<?php echo url('/'.$page_data[0]['alias'].'/service/'.$data['serv'][$ke]['id']); ?>">
                        <div class="main_section_article_imgdiv">
                            <img src="<?php echo e(asset('storage'.$img_serv)); ?>" alt="Фото <?php echo e($serv['name']); ?>" class="main_section_article_imgdiv_img" />
                        </div>
                        <div class="main_section_article_content  margin_top_1rem">
                            <h3><?php echo e($data['serv'][$ke]['name']); ?></h3>
                            <!-- <span><?php echo e($data['serv'][$ke]['description']); ?></span><br /> -->
                            <span>от <?php echo e($data['serv'][$ke]['price']); ?> руб.</span>
                        </div>
                    </a>
                    </article>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/client_manikur/client_pages/service_page.blade.php ENDPATH**/ ?>