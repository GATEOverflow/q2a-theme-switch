<?php
		

		if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
						header('Location: ../../');
						exit;   
		}			   
		
		
		
		qa_register_plugin_module('module', 'qa-theme-admin.php', 'qa_theme_admin', 'Theme Admin');

		
		qa_register_plugin_layer('qa-theme-layer.php', 'Theme Layer');
		
		
		qa_register_plugin_overrides('qa-theme-overrides.php');
			
						  

