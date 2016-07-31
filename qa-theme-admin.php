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
				'value' => qa_html(qa_opt('theme_mobile_switch_text')),
				'tags' => 'NAME="theme_switch_mobile_text"',
				);		   


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
                                        )
			    );
	}
}

