<?php
$title = 'HOME';
$page_meta_description = 'GET FROM DB';
$page_meta_keywords = 'GET FROM DB';
$robots = 'INDEX, FOLLOW';
$data['content'] = 'CONTENT FOR DEL IN FUTURE';
?>



<?php $__env->startPush('css'); ?>
*{
   /* color: black; */
}
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="">
<?php if(!empty($content['pages_menu'])): ?>
    <?php
    $sort_pages = [];
foreach ($content['pages_menu'] as $pages) {
    if (is_array($pages) && !empty($pages)) {
        if ($pages['alias'] === 'callback') {
            $sort_pages[0] = $pages;
        } elseif ($pages['alias'] === 'signup') {
            $sort_pages[1] = $pages;
        } elseif ($pages['alias'] === 'manikur') {
            $sort_pages[2] = $pages;
        } elseif ($pages['alias'] === 'second') {
            $sort_pages[3] = $pages;
        } elseif ($pages['alias'] === 'gallery') {
            $sort_pages[4] = $pages;
        } elseif ($pages['alias'] === 'about') {
            $sort_pages[5] = $pages;
        } elseif ($pages['alias'] === 'map') {
            $sort_pages[6] = $pages;
        } elseif ($pages['alias'] === 'price') {
            $sort_pages[7] = $pages;
        } elseif ($pages['alias'] === 'persinfo') {
            $sort_pages[8] = $pages;
        }
    }
}
foreach ($content['pages_menu'] as $pages) {
    if (!in_array_recursive($pages['alias'], $sort_pages, true)) {
        array_push($sort_pages, $pages);
    }
}
ksort($sort_pages);
unset($content['pages_menu'], $pages);
?>
    <?php $__currentLoopData = $sort_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="main_section_article ">
            <a class="main_section_article_content_a" href="<?php echo e(url('/'.$pages['alias'])); ?>" >
                <div class="main_section_article_imgdiv">
                <img src="<?php echo e(asset('storage/'.$pages['img'])); ?>" alt="<?php echo e($pages['title']); ?>" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content margin_top_1rem">
                    <h2><?php echo e(mb_ucfirst($pages['title'])); ?></h2>
                    <span>
                        <?php echo e(mb_ucfirst($pages['description'])); ?>

                    </span>
                </div>
            </a>
		</article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    No routes (pages)
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts/index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/client_manikur/home.blade.php ENDPATH**/ ?>