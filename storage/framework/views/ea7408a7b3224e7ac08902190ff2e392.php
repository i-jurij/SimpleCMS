<?php
$title = "Price edit";
$page_meta_description = "Price edit page";
$page_meta_keywords = "Price edit";
$robots = "NOINDEX, NOFOLLOW";
?>


<?php $__env->startSection("content"); ?>

<div class="content">

<?php if(!empty($data['res'])): ?>
    <?php if(is_array($data['res'])): ?>
        <?php
            print_r($data['res'])
        ?>
    <?php elseif(is_string($data['res'])): ?>
        <p><?php echo e($data['res']); ?></p>
    <?php endif; ?>
<?php elseif(!empty($data['serv'])): ?>
    <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
        <p class="pad margin_bottom_1rem">В строке нужной услуги кликните по ячейке в колонке с ценой, введите данные, нажмите кнопку Сохранить.</p>
        <div class="price">
            <form action="<?php echo e(url()->route('admin.price.update')); ?>" method="post" name="price_form" id="price_form" >
            <?php echo csrf_field(); ?>
                <table class="table price_form_table">
                    <caption class=""><b><?php echo e($data['title']); ?></b></caption>
                    <colgroup>
                        <col width="10%">
                        <col width="65%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Услуга</th>
                            <th>Цена</th>
                        </tr>
                    </thead>
                    <tbody>

    <?php
        $i = 1;
    ?>
    <?php $__currentLoopData = $data['serv']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_name => $serv_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($cat_name != 'page_serv'): ?>
            <tr><td colspan="3"><?php echo e($cat_name); ?></td></tr>
            <?php $__currentLoopData = $serv_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serv_name => $cat_serv_price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $ar = explode('#', $cat_serv_price);
                    $price = $ar[1];
                    $id = $ar[0];
                ?>
                <tr>
                    <td><?php echo e($i); ?></td>
                        <td style="text-align:left"><?php echo e($serv_name); ?></td>
                        <td class="td" id="serv_id[<?php echo e($id); ?>]"><?php echo e($price); ?></td>
                </tr>
                <?php
                    ++$i;
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <td colspan="3">Услуги вне категорий</td>
            <?php $__currentLoopData = $serv_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servv_name => $serv_price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $sar = explode('#', $serv_price);
                    $sprice = $sar[1];
                    $sid = $sar[0];
                ?>
                <tr>
                    <td><?php echo e($i); ?></td>
                        <td style="text-align:left"><?php echo e($servv_name); ?></td>
                        <td class="td" id="serv_id[<?php echo e($sid); ?>]"><?php echo e($sprice); ?></td>
                </tr>
                <?php
                    ++$i;
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                </table>
                <div class="margintb1" id="form_buttons" >
                    <button type="submit" name="submit" class="buttons" form="price_form" />Сохранить</button>
                    <input type="reset" class="buttons" form="price_form" value="Сбросить" />
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <?php if(!empty($data['service_page'])): ?>
        <form action="<?php echo e(url()->route('admin.price.post_edit')); ?>" method="post" id="form_price_edit" >
        <?php echo csrf_field(); ?>
                <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <p class="pad margin_bottom_1rem">Выберите страницу для редактирования расценок:</p>
                    <?php $__currentLoopData = $data['service_page']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label>
                            <input type="radio" name="id" value="<?php echo e($value['id']); ?>" required />
                            <span><?php echo e($value['title']); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="margintb1" id="form_price_edit_buttons" >
                    <button type="submit" name="submit" class="buttons" form="form_price_edit" />Далее</button>
                    <input type="reset" class="buttons" form="form_price_edit" value="Сбросить" />
                </div>
            </form>
    <?php else: ?>
        'No data'
    <?php endif; ?>
<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $ (function () {// эквивалентна вкладке тела на странице плюс событие onload
                // Найти все узлы TD
        var tds=$(".price_form_table .td");
                // Добавить событие щелчка для всех TD
        tds.click(function(){
                        // Получить объект текущего клика
            var td=$(this);
                        // Удалите текущий текстовый контент TD
           var oldText=td.text();
           var idstr=td.attr('id');
                      // Создать текстовое поле, установите значение текстового поля сохранено значение
           var input=$('<input type="number" name="'+idstr+'" min="0" step="10" style="width:100%;" value="'+oldText+'" />');
                      // Установите содержимое текущего объекта TD для ввода
           td.html(input);
                      // Установите флажок Click события текстового поля
           input.click(function(){
               return false;
           });
                      // Установите стиль текстового поля
           input.css("border-width","0");
           //input.css("font-size","1rem");
           input.css("text-align","center");
                      // Установите ширину текстового поля, равная ширине TD
           input.width(td.width());
                      // Запустите полное событие выбора, когда текстовое поле получает фокус
           input.trigger("focus").trigger("select");
                      // вернуться к тексту, когда текстовое поле потеряло фокус
           input.blur(function(){
               var input_blur=$(this);
           });
        });

        // удаление полей ввода при нажатии кнопки сброса
        $("#price_form").on('reset', function(){
            let td = $('.td');
            td.each( function() {
                let inp = $(this).find('input');
                if ( inp.val() !== '' ) {
                    let price = inp.val();
                    inp.remove();
                    $(this).html(price);
                    //console.log(price+'\n');
                }
            });
        });

   });
}, false);
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts/index_admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/price.blade.php ENDPATH**/ ?>