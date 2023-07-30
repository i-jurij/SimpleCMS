<?php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
?>


<?php $__env->startSection("content"); ?>

<div class="content">
    <?php if(!empty($data)): ?>
        <?php if(is_array($data)): ?>
            <?php if(!empty($data['by_date'])): ?>
                <?php if(!empty($dateprevnext)): ?>
                    <?php
                        $date = $dateprevnext;
                        $prev = date('Y-m-d', strtotime($date.'- 1 days')) ?? '';
                        $next = date('Y-m-d', strtotime($date.'+ 1 days')) ?? '';
                    ?>
                    <p class="margin_rlb1">
                    <a href="<?php echo e(url()->route('admin.master_signup.list')); ?>?prev=<?php echo e($prev); ?>" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
                    <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:17rem;"><?php echo e(date('l d M Y', strtotime($date))); ?></span>
                    <a href="<?php echo e(url()->route('admin.master_signup.list')); ?>?next=<?php echo e($next); ?>" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
                    </p>
                <?php endif; ?>
                <?php
                $res = '';
                foreach ($data['by_date'] as $master => $signup) {
                    $art = '';
                    foreach ($signup as $value) {
                        if (\Carbon\Carbon::parse($value['start_dt'])->toDateString() === $date) {
                            $art .= '<article class="main_section_article">
                                          <p>'.\Carbon\Carbon::parse($value['start_dt'])->format('H:i').'</p>
                                          <p>'.$value['service'].' </p>
                                          <p>'.$value['client'].'</p>
                                      </article>';
                        }
                    }
                    if (!empty($art)) {
                        $res .= '<div class="back shad rad pad margin_rlb1">';
                        // $res .= '<p><b>'.$master.'</b></p>';
                        $res .= $art;
                        $res .= '</div>';
                    } else {
                        // $res .= '<p class="pad">К мастеру нет записей на '.date('d.m.Y', strtotime($date)).'.</p>';
                    }
                }
                echo $res;
                ?>
            <?php endif; ?>
        <?php elseif(is_string($data)): ?>
            <p><?php echo e($data); ?></p>
        <?php endif; ?>
    <?php else: ?>
        No data from controller
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/user_pages/signup.blade.php ENDPATH**/ ?>