<!--<a href="<?php echo e(url()->route('client.home')); ?>"  class="backbutton buttons">
    <img src="<?php echo e(Vite::asset('resources/imgs/home.png')); ?>" alt="Home"/>
</a>
-->
<div class="backbutton">
<input
    type="image"
    class=" buttons"
    src="<?php echo e(Vite::asset('resources/imgs/home.png')); ?>"
    onclick = "window.location.assign('<?php echo url()->route('client.home'); ?>')"
/>
</div>
<?php /**PATH /var/www/html/resources/views/components/button_client_home.blade.php ENDPATH**/ ?>