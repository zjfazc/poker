<?php
	function smarty_modifier_hours($time){
		$sub = (time()-$time)/60;
		if( $sub < 3){
			return "刚刚";
		}else if( $sub<60){
			return (int)$sub.'分钟前';
		}else if( $sub/60 < 24){
			return (int)($sub/60) . '小时前';
		}else {
			return date('Y-m-d', $time);
		}
	}