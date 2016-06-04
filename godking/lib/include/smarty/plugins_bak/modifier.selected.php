<?php
function smarty_modifier_selected($string, $value)
{
    return $string==$value?" selected='selected'":'';
}
?>