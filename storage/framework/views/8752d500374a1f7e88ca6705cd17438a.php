<footer class="foot">
    <div class="foot_div">
        <?php echo $__env->make('layouts.contacts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="foot_div">
        <?php echo "2022 - " . date('Y') . PHP_EOL; ?>
    </div>
</footer>
<?php /**PATH /var/www/html/resources/views/layouts/footer.blade.php ENDPATH**/ ?>