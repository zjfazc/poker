<?php
/**
 * 根据时间判断时间奇偶
 */
function smarty_modifier_houreven( $time)
{
	return date('YmdH', $time)%2;
}
?>