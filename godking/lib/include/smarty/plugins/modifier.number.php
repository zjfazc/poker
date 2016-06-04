<?php
/**
 * 格式化数字
 * @param $string
 * @return string
 */
function smarty_modifier_number($string)
{
    return number_format($string);
}
?>