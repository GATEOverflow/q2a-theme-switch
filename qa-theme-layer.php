<?php

class qa_html_theme_layer extends qa_html_theme_base {


	// theme replacement functions

	function doctype()
	{


		if (qa_opt('theme_switch_enable')) {
			if($this->template == 'account') { 
				// add theme switcher
				$theme_form = $this->theme_switch_form();
				if($theme_form) {

					$this->content['form'] = $theme_form; 
				}

			}
		}
		qa_html_theme_base::doctype();
	}



	function theme_switch_form() {
		require_once QA_INCLUDE_DIR . 'app/admin.php';
		require_once QA_INCLUDE_DIR . 'db/selects.php';

		if($handle = qa_get_logged_in_handle()) {
			$userid = qa_get_logged_in_userid();
			if (qa_clicked('theme_switch_save')) {
				qa_db_query_sub(
						'INSERT INTO ^usermetas (userid,title,content) VALUES (#,$,$) ON DUPLICATE KEY UPDATE content=$',
						$userid,'custom_theme',qa_post_text('theme_choice'),qa_post_text('theme_choice')
					       );
				if (qa_opt('theme_switch_enable_mobile')) {
					qa_db_query_sub(
							'INSERT INTO ^usermetas (userid,title,content) VALUES (#,$,$) ON DUPLICATE KEY UPDATE content=$',
							$userid,'custom_theme_mobile',qa_post_text('theme_mobile_choice'),qa_post_text('theme_mobile_choice')
						       ); 
				}
				qa_redirect($this->request,array('ok'=>qa_lang_html('admin/options_saved')));
			}
			else if (qa_clicked('theme_switch_user_reset')) {
				qa_db_query_sub(
						'DELETE FROM ^usermetas WHERE userid=# AND title=$',
						$userid,'custom_theme'
					       );
				if (qa_opt('theme_switch_enable_mobile')) {

					qa_db_query_sub(
							'DELETE FROM ^usermetas WHERE userid=# AND title=$',
							$userid,'custom_theme_mobile'
						       );
				}
				qa_redirect($this->request,array('ok'=>qa_lang_html('admin/options_reset')));
			}

			$ok = qa_get('ok')?qa_get('ok'):null;

			$theme_choice = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT content FROM ^usermetas WHERE userid=# AND title=$',
						$userid, 'custom_theme'
						),true
					);				

			$themes = qa_admin_theme_options();
			$fields = array();
			$fields['themes'] = array(
					'label' => qa_opt('theme_switch_text'),
					'tags' => 'NAME="theme_choice"',
					'type' => 'select',
					'options' => qa_admin_theme_options(),
					'value' => @$themes[$theme_choice],					
					);
			if (qa_opt('theme_switch_enable_mobile')) 
			{
				$theme_mobile_choice = qa_db_read_one_value(
						qa_db_query_sub(
							'SELECT content FROM ^usermetas WHERE userid=# AND title=$',
							$userid, 'custom_theme_mobile'
							),true
						);				
				$fields['themes_mobile'] = array(
						'label' => qa_opt('theme_switch_mobile_text'),
						'tags' => 'NAME="theme_mobile_choice"',
						'type' => 'select',
						'options' => qa_admin_theme_options(),
						'value' => @$themes[$theme_mobile_choice],					
						);

			}
			$form=array(

					'ok' => ($ok && !isset($error)) ? $ok : null,

					'style' => 'tall',

					'title' => '<a name="theme_text"></a>'.qa_opt('theme_switch_title'),

					'tags' =>  'action="'.qa_self_html().'#theme_text" method="POST"',

					'fields' => $fields,

					'buttons' => array(
						array(
							'label' => qa_lang_html('admin/reset_options_button'),
							'tags' => 'NAME="theme_switch_user_reset"',
						     ),
						array(
							'label' => qa_lang_html('main/save_button'),
							'tags' => 'NAME="theme_switch_save"',
						     ),
						),
					);
			return $form;
		}			
	}


}

