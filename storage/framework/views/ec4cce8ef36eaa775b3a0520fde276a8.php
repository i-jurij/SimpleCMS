<?php

            if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {

                $title = $page_data[0]["title"];

                $page_meta_description = $page_data[0]["description"];

                $page_meta_keywords = $page_data[0]["keywords"];

                $robots = $page_data[0]["robots"];

                $content["page_content"] = $page_data[0]["content"];

            } else {

                $title = "Title";

                $page_meta_description = "description";

                $page_meta_keywords = "keywords";

                $robots = "INDEX, FOLLOW";

                $content = "CONTENT FOR DEL IN FUTURE";

            }

            ?>

            


            <?php $__env->startSection("content"); ?>

                <?php if(!empty($menu)): ?> <p class="content"><?php echo e($menu); ?></p> <?php endif; ?>


                <div class="">
                    <?php if(!empty($abouts) && is_array($abouts)): ?>
                        <?php $__currentLoopData = $abouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $about): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $img = imageFor($about['image']);
                            ?>
                            <article class="main_section_article ">
                                <div class="main_section_article_imgdiv margin_bottom_1rem">
                                    <img src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$img)); ?>" alt="<?php echo e($about['title']); ?>" class="main_section_article_imgdiv_img" />
                                </div>
                                <div class="main_section_article_content">
                                    <h3><?php echo e($about['title']); ?></h3>
                                    <span><?php echo e($about['content']); ?></span>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php if(!empty($masters) && is_array($masters)): ?>
                        <?php $__currentLoopData = $masters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $master): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' ?>
                            <?php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' ?>
                            <?php if(!empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master['master_photo']))): ?>
                                <?php $img = $master['master_photo'] ?>
                            <?php elseif(empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg'))): ?>
                                <?php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg' ?>
                            <?php endif; ?>

                            <article class="main_section_article ">
                                <div class="main_section_article_imgdiv margin_bottom_1rem" style="background-color: var(--bgcolor-content);">
                                    <img src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.$img)); ?>" alt="<?php echo e($master['master_fam']); ?>" class="main_section_article_imgdiv_img" />
                                </div>

                                <div class="main_section_article_content">
                                    <h3><?php echo e($master['master_name']); ?> <?php echo e($master['master_fam']); ?></h3>
                                    <span>
                                        <?php echo e($master['spec']); ?>

                                    </span>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </div>
            <?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/client_manikur/client_pages/about.blade.php ENDPATH**/ ?>