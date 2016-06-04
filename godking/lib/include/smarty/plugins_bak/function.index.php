<?php
function smarty_function_index($params, &$smarty)
{
	if(!is_array($params['list']) || empty($params['list'])) return '';
	else {
		$_isset = isset($params['list'][$params['index']]);
		if(isset($params['type'])) {
			switch($params['type']) {
				case 'checked':
					return $_isset ? 'checked="checked"' : '';
				break;
				case 'selected':
                    return $_isset ? 'selected="selected"' : '';
				break;
			}
		} else {
			return $_isset ? $params['list'][$params['index']] : '';
		}
	}
}