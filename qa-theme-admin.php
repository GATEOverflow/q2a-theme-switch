<?php
class qa_theme_admin {

	function option_default($option) {

		switch($option) {
			case 'theme_switch_title':
				return 'Theme';
			case 'theme_switch_text':
				return 'Choose theme:';
			case 'theme_switch_mobile_title':
				return 'Mobile Theme';
			case 'theme_switch_mobile_text':
				return 'Choose mobile theme:';
			default:
				return null;				
		}

	}

	function allow_template($template)
	{
		return ($template!='admin');
	}	   

	function admin_form(&$qa_content)
	{					   

		// Process form input

		$ok = null;

		if (qa_clicked('theme_switch_save')) {
			foreach($_POST as $i => $v) {

				qa_opt($i,$v);
			}

			$ok = qa_lang('admin/options_saved');


		}
		else if (qa_clicked('theme_switch_reset')) {
			foreach($_POST as $i => $v) {
				$def = $this->option_default($i);
				if($def !== null) qa_opt($i,$def);
			}
			$ok = qa_lang('admin/options_reset');
		}
		else if(qa_clicked('theme_switch_migrate')) {
			$rows = qa_db_read_all_values(qa_db_query_sub('select user_id from ^usermeta where meta_key like $ and user_id in (select userid from ^users) and user_id not in (select userid from ^usermetas where title like $)', '%theme%', '%theme%'));

			if(count($rows) > 0)
			{	

				$query = 'INSERT INTO ^usermetas (userid,title,content)  SELECT user_id, meta_key, meta_value from ^usermeta where user_id in (select userid from ^users) and meta_key like \'%theme%\';';
				qa_db_query_sub($query);
				qa_opt('theme_switch_migrated', "1");
				$ok = "Successfully Migrated";
			}
			else 
			{
				$ok = "Already Migrated";
			}
		}



		// Create the form for display

		$themes = qa_admin_theme_options();

		$fields = array();

		$fields[] = array(
				'label' => 'Enable theme switching',
				'tags' => 'NAME="theme_switch_enable"',
				'value' => qa_opt('theme_switch_enable'),
				'type' => 'checkbox',
				);



		$fields[] = array(
				'label' => 'Theme switch title',
				'type' => 'text',
				'value' => qa_html(qa_opt('theme_switch_title')),
				'tags' => 'NAME="theme_switch_title"',
				);		   


		$fields[] = array(
				'label' => 'Theme switch text',
				'type' => 'text',
				'value' => qa_html(qa_opt('theme_switch_text')),
				'tags' => 'NAME="theme_switch_text"',
				);		   


		$fields[] = array(
				'label' => 'Enable mobile theme',
				'tags' => 'NAME="theme_switch_enable_mobile"',
				'value' => qa_opt('theme_switch_enable_mobile'),
				'type' => 'checkbox',
				);
		$fields[] = array(
				'label' => 'Mobile Theme switch title',
				'type' => 'text',
				'value' => qa_html(qa_opt('theme_switch_mobile_title')),
				'tags' => 'NAME="theme_switch_mobile_title"',
				);		   
		$fields[] = array(
				'label' => 'Mobile Theme switch text',
				'type' => 'text',
				'value' => qa_html(qa_opt('theme_switch_mobile_text')),
				'tags' => 'NAME="theme_switch_mobile_text"',
				);		   
		foreach ($themes as $theme)
		{
		$fields[] = array(
				'label' => $theme.' Theme Include CSS',
				'type' => 'textarea',
				'value' => qa_html(qa_opt('theme_'.$theme.'_i_css')),
				'tags' => 'NAME="theme_'.$theme.'_i_css"',
			);
		$fields[] = array(
				'label' => $theme.' Theme Exclude CSS',
				'type' => 'textarea',
				'value' => qa_html(qa_opt('theme_'.$theme.'_e_css')),
				'tags' => 'NAME="theme_'.$theme.'_e_css"',
			);

		}

		return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,

				'fields' => $fields,
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'NAME="theme_switch_save"',
					     ),
					array(
						'label' => qa_lang_html('admin/reset_options_button'),
						'tags' => 'NAME="theme_switch_reset"',
					     ),
					array(
						'label' => 'Migrate from old',
						'tags' => 'NAME="theme_switch_migrate"',
					     ),
					)
			    );
	}


}

