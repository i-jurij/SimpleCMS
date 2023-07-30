<!--
<a href="<?php echo e(url()->route('admin.home')); ?>"  class="backbutton buttons" id="back_button_admin">
    <img src="<?php echo e(Vite::asset('resources/imgs/home.png')); ?>" alt="Admin Home"/>
</a>
-->
<div class="backbutton">
<input
    type="image"
    class=" buttons"
    src="<?php echo e(Vite::asset('resources/imgs/home.png')); ?>"
    onclick = "window.location.assign('<?php echo url()->route('admin.home'); ?>')"
/>
</div>
<?php /**PATH /var/www/html/resources/views/components/button_go_to_admin_home.blade.php ENDPATH**/ ?>