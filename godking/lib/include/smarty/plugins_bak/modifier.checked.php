<?php
function smarty_modifier_checked($string, $value)
{
    return $string==$value?"checked='checked'":'';
}
?>
