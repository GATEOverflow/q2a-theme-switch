<?php

function qa_get_site_theme() {

	$userid = qa_get_logged_in_userid();
	if(!$userid)
		return qa_get_site_theme_base();
	if( qa_is_mobile_probably()){
		if(qa_opt('theme_switch_enable_mobile')) {
			$mobile_theme = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT value FROM ^usermetas WHERE userid=# AND title=$',
						$userid, 'custom_theme_mobile'
						),true
					);
			if($mobile_theme) return  $mobile_theme;
		}
		return qa_get_site_theme_base();

	}
	if(qa_opt('theme_switch_enable')) {
		$theme = qa_db_read_one_value(
				qa_db_query_sub(
					'SELECT value FROM ^usermetas WHERE userid=# AND title=$',
					$userid, 'custom_theme'
					),true
				);
		if($theme) return $theme;
	}
	return qa_get_site_theme_base();

}

/*							  
							  Omit PHP closing tag to help avoid accidental output
 */							  


