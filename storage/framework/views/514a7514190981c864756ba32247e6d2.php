<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
  <meta charset="utf-8">
  <!--
  <meta name="referrer" content="origin-when-cross-origin">
-->
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title><?php echo e($title); ?></title>
  <meta name="description" content="<?php echo e($page_meta_description); ?>">
  <META NAME="keywords" CONTENT="<?php echo e($page_meta_keywords); ?>">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="<?php echo e($robots); ?>">
  <meta name="author" content="I-Jurij">
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div class="wrapper">
        <?php echo $__env->make('layouts/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="main ">
            <section class="main_section">
                <div class="flex flex_top">
                    <div class="content title">
                        <h1><?php echo e($title); ?></h1>
                    </div>
                    <?php if($errors->any()): ?>
                        <div class="zapis_usluga back shad pad margin_rlb1 alert alert-danger error">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(is_array($error)): ?>
                                        <?php $__currentLoopData = $error; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($mes); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php if($error === "The client password field confirmation does not match."): ?>
                                            <li><?php echo e("Неверный пароль :("); ?></li>
                                        <?php else: ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>

                    <?php if(url()->current() !== url()->route('client.home')): ?>
                        <?php $pieces = explode('/', Request::path()); ?>
                        <?php if(count($pieces) > 3): ?>
                            <?php echo $__env->make('components/back_button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make('components/button_client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <?php echo $__env->make('layouts/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <script src="<?php echo e(asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'form-recall-mask.js')); ?>"></script>
</body>

</html>
<?php /**PATH /var/www/html/resources/views/layouts/index.blade.php ENDPATH**/ ?>