<?php
function panel(array $variable)
{
    $res = '';
    foreach ($variable as $key => $value) {
        if (is_array($value) && !empty($value)) {
            $class = 'back shad rad pad mar display_inline_block';
            $p = '<p class="pad"><b>'.mb_ucfirst($key).'</b></p>';
            if ($key == 'admin') {
                $key = '';
                $p = '';
                $class = 'back shad rad pad margin_rlb1 justify';
            }
            $res .= '<div class="'.$class.'">'.$p;
            $res .= panel($value);
            $res .= '</div>';
        }
        if (is_string($value) && !empty($value) && ($key !== 'admin' && $value !== 'admin.home')) {
            // $res .= '<a href="'.url()->route($value, ['id' => $pages->id]).'" class="buttons">'.mb_ucfirst($key).'</a>';
            $res .= '<a href="'.url()->route($value).'" class="buttons">'.mb_ucfirst($key).'</a>';
        }
    }

    return $res;
}
?>

<div class="adm_content">
            <?php
            if (!empty($content)) {
                echo panel($content);
            } else {
                echo 'No routes (pages)';
            } ?>
</div>
