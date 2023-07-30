<?php
$url = url()->current();
if (str_contains($url, 'admin/contacts/remove')) {
    $res_route = route('admin.contacts.destroy');
    $remove_or_edit = true;
    $buttonname = 'Remove';
    $type = 'checkbox';
} elseif (str_contains($url, 'admin/contacts/edit')) {
    $res_route = route('admin.contacts.post_edit');
    $remove_or_edit = true;
    $buttonname = 'Edit';
    $type = 'radio';
} else {
    $buttonname = 'list';
    $remove_or_edit = false;
}

$title = 'Contacts '.$buttonname;
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit';
$robots = 'NOINDEX, NOFOLLOW';
?>


<?php $__env->startSection('content'); ?>
    <?php if(!empty($res)): ?> <p class="content">MESSAGE: <?php echo e($res); ?></p>

    <?php else: ?>

    <div class="content margintb1 ">
        <div class="price">
            <?php if(Auth::user()['status']==='admin' || Auth::user()['status']==='moder'): ?>
                <?php if(is_string($content)): ?>
                    <?php echo e($content); ?>

                <?php else: ?>

                    <?php if( $remove_or_edit ): ?>
                        <form method="post" action="<?php echo e($res_route); ?>" id="contacts_form" class="pad">
                            <?php echo csrf_field(); ?>

                            <div class="form-element margintb1">
                                <table class="table" id="ctable">
                                    <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Type</th>
                                        <th>Data</th>
                                        <th><?php echo e($buttonname); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td><?php echo e($key+1); ?></td>
                                        <td><?php echo e($contact->type); ?></td>
                                        <td><?php echo e($contact->data); ?></td>
                                        <td class="text_center" style="padding: 0;">
                                            <input type="<?php echo e($type); ?>" name="contacts[]" value="<?php echo e($contact->id.'plusplus'.$contact->type.'plusplus'.$contact->data); ?>">
                                        </td>
                                    </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-element mar">
                                <button type="submit" form="contacts_form" class="buttons" id="contacts_submit"><?php echo e($buttonname); ?></button>
                                <button type="reset" form="contacts_form" class="buttons" id="contacts_reset">Reset</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <table class="table">
                            <tr>
                                <th>N</th>
                                <th>Type</th>
                                <th>Data</th>
                            </tr>
                            <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td><?php echo e($key+1); ?></td>
                                    <td><?php echo e($contact->type); ?></td>
                                    <td><?php echo e($contact->data); ?></td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
            You are not authorized.
            <?php endif; ?>
        </div>

    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let SUBM = document.querySelector('#contacts_submit');
    if (SUBM) {
        SUBM.disabled = true;

        let table = document.querySelector('#ctable');
        let edit = '<?php echo $buttonname; ?>';

        for(let i = 1; i < table.rows.length; i++)
        {
            table.rows[i].onclick = function()
            {
                //rIndex = this.rowIndex;
                /*
                document.getElementById("fname").value = this.cells[0].innerHTML;
                document.getElementById("lname").value = this.cells[1].innerHTML;
                document.getElementById("age").value = this.cells[2].innerHTML;
                */
                if ( edit == 'Edit') {
                    for(let i = 1; i < table.rows.length; i++)
                    {
                        table.rows[i].removeAttribute("style");
                    }
                }

                let input = this.cells[3].children[0];

                if (this.style.color == 'red') {
                    input.removeAttribute('checked');
                    this.removeAttribute("style");
                } else {
                    this.style.color = 'red';
                    input.setAttribute('checked', 'checked');
                }

                SUBM.disabled = false;
            };
        }

        document.querySelector('#contacts_reset').onclick = function () {
            for (let i = 1; i < table.rows.length; i++)
            {
                let input = table.rows[i].cells[3].children[0];
                input.removeAttribute('checked');
                table.rows[i].removeAttribute("style");
            }
        }
    }
});
</script>

<?php echo $__env->make('layouts/index_admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin_manikur/moder_pages/contacts.blade.php ENDPATH**/ ?>