<?php

/*
Name: PHP Files Cleaner
Author: Destroy666
Version: 1.0
Info: Plugin for MyBB forum software, coded for versions 1.8.x (might work in 1.6.x/1.4.x after small changes).
It helps with finding and cleaning faulty PHP files that have BOM or redundant whitespace and may cause MyBB problems such as broken captcha image.
2 new settings
Released under GNU GPL v3, 29 June 2007. Read the LICENSE.md file for more information.
Support: official MyBB forum - http://community.mybb.com/mods.php?action=profile&uid=58253 (don't PM me, post on forums)
Bug reports: my github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

if(!defined('IN_MYBB'))
{
	die('What are you doing?!');
}

function PHP_files_cleaner_info()
{
    global $db, $lang, $custom_settingsgroup_cache;

	$lang->load('PHP_files_cleaner_acp');

	// Configuration link
	if(empty($custom_settingsgroup_cache))
	{
		$q = $db->simple_select('settinggroups', 'gid, name', 'isdefault = 0');

		while($group = $db->fetch_array($q))
			$custom_settingsgroup_cache[$group['name']] = $group['gid'];
	}

	$gid = isset($custom_settingsgroup_cache['PHP_files_cleaner']) ? $custom_settingsgroup_cache['PHP_files_cleaner'] : 0;
	$PHP_files_cleaner_cfg = '<br />';

	if($gid)
	{
		global $mybb;

		$PHP_files_cleaner_cfg = '<a href="index.php?module=config&amp;action=change&amp;gid='.$gid.'">'.$lang->configuration.'</a>
<br /><a href="index.php?module=tools-system_health&amp;action=clean_PHP_files">'.$lang->PHP_files_cleaner_check.'</a>
<br />
<br />';
	}

	return array(
		'name'			=> $lang->PHP_files_cleaner,
		'description'	=> $lang->PHP_files_cleaner_info.'<br />
'.$PHP_files_cleaner_cfg.'
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ZRC6HPQ46HPVN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" style="border: 0;" name="submit" alt="Donate">
<img alt="" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" style="border: 0; width: 1px; height: 1px;">
</form>',
		'website'		=> 'https://github.com/Destroy666x',
		'author'		=> 'Destroy666',
		'authorsite'	=> 'https://github.com/Destroy666x',
		'version'		=> 1.0,
		'codename'		=> 'PHP_files_cleaner',
		'compatibility'	=> '18*'
    );
}


function PHP_files_cleaner_activate()
{
	global $db, $lang;

	$lang->load('PHP_files_cleaner_acp');

	// Settings
	if(!$db->fetch_field($db->simple_select('settinggroups', 'COUNT(1) AS cnt', "name ='PHP_files_cleaner'"), 'cnt'))
	{
		$PHP_files_cleaner_settinggroup = array(
			'name'			=> 'PHP_files_cleaner',
			'title'			=> $db->escape_string($lang->PHP_files_cleaner),
			'description'	=> $db->escape_string($lang->PHP_files_cleaner_settings),
			'disporder'		=> 666,
			'isdefault'		=> 0
		);

		$db->insert_query('settinggroups', $PHP_files_cleaner_settinggroup);
		$gid = (int)$db->insert_id();

		$d = -1;

		$PHP_files_cleaner_settings[] = array(
			'name'			=> 'PHP_files_cleaner_leading',
			'title'			=> $db->escape_string($lang->PHP_files_cleaner_leading),
			'description'	=> $db->escape_string($lang->PHP_files_cleaner_leading_desc),
			'optionscode'	=> 'yesno',
			'value'			=> 1
		);

		$PHP_files_cleaner_settings[] = array(
			'name'			=> 'PHP_files_cleaner_trailing',
			'title'			=> $db->escape_string($lang->PHP_files_cleaner_trailing),
			'description'	=> $db->escape_string($lang->PHP_files_cleaner_trailing_desc),
			'optionscode'	=> 'yesno',
			'value'			=> 0
		);

		foreach($PHP_files_cleaner_settings as &$current_setting)
		{
			$current_setting['disporder'] = ++$d;
			$current_setting['gid'] = $gid;
		}

		$db->insert_query_multiple('settings', $PHP_files_cleaner_settings);

		rebuild_settings();
	}
}

function PHP_files_cleaner_deactivate()
{
	global $db;

	$db->delete_query('settings', "name LIKE 'PHP\_files\_cleaner\_%'");
	$db->delete_query('settinggroups', "name = 'PHP_files_cleaner'");

	rebuild_settings();
}

$plugins->add_hook('admin_tools_system_health_begin', 'PHP_files_cleaner_actions');

function PHP_files_cleaner_actions()
{
	global $lang, $mybb, $sub_tabs;

	$lang->load('PHP_files_cleaner_acp');

	$sub_tabs['PHP_files_cleaner'] = array(
		'title' => $lang->PHP_files_cleaner,
		'link' => 'index.php?module=tools-system_health&amp;action=clean_PHP_files',
		'description' => $lang->PHP_files_cleaner_info
	);

	if($mybb->input['action'] == 'do_clean_PHP_files' && $mybb->request_method == 'post')
	{
		$errors = array();
		$lead = $mybb->get_input('leading', MyBB::INPUT_ARRAY);
		$trail = $mybb->get_input('trailing', MyBB::INPUT_ARRAY);

		if(!$mybb->settings['PHP_files_cleaner_leading'] && !$mybb->settings['PHP_files_cleaner_trailing'])
			$errors[] = $lang->PHP_files_cleaner_nothing_enabled;

		if(empty($lead) && empty($trail))
			$errors[] = $lang->PHP_files_cleaner_nothing_chosen;

		if(!$errors)
		{
			$leadandtrail = array_unique(array_merge($lead, $trail));
			$nonchangeable = array();

			foreach($leadandtrail as $filename)
			{
				$fullfilename = MYBB_ROOT.$filename;

				if(is_readable($fullfilename) && is_writable($fullfilename))
				{
					$file = file_get_contents($fullfilename);

					// Remove BOM and leading whitespace
					if($mybb->settings['PHP_files_cleaner_leading'] && in_array($filename, $lead))
						$file = preg_replace('/^\x{FEFF}?\s*/u', '', $file);

					// Remove ending tag and trailing whitespace
					if($mybb->settings['PHP_files_cleaner_trailing'] && in_array($filename, $trail))
						$file = preg_replace('/(\s*\?>)?\s*$/', '', $file);

					file_put_contents($fullfilename, $file);
				}
				else
					$nonchangeable[] = htmlspecialchars_uni($filename);
			}

			if($nonchangeable)
				flash_message($lang->sprintf($lang->PHP_files_cleaner_nonchangeable, implode($lang->comma, $nonchangeable)), 'error');
			else
				flash_message($lang->PHP_files_cleaner_success, 'success');

			admin_redirect('index.php?module=tools-system_health&amp;action=clean_PHP_files');
		}
		else
			$mybb->input['action'] = 'clean_PHP_files';
	}

	if($mybb->input['action'] == 'clean_PHP_files')
	{
		global $page;

		$page->output_header($lang->PHP_files_cleaner);
		$page->add_breadcrumb_item($lang->PHP_files_cleaner);
		$page->output_nav_tabs($sub_tabs, 'PHP_files_cleaner');

		if(!empty($errors))
			$page->output_inline_error($errors);

		if(!$mybb->settings['PHP_files_cleaner_leading'] && !$mybb->settings['PHP_files_cleaner_trailing'])
		{
			$table = new Table;
			$table->construct_cell($lang->PHP_files_cleaner_nothing_enabled);
			$table->construct_row();
		}
		else
		{
			$leading = $trailing = array();
			$diriterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(MYBB_ROOT));
			$phpfiles = new RegexIterator($diriterator, '/\.php$/i', RegexIterator::GET_MATCH);

			foreach($phpfiles as $filename => $info)
			{
				if(is_readable($filename))
				{
					$contents = file_get_contents($filename);

					if($mybb->settings['PHP_files_cleaner_leading'] && preg_match('/^\x{FEFF}?\s*/u', $contents, $matches) && $matches[0])
						$leading[] = $filename;

					if($mybb->settings['PHP_files_cleaner_trailing'] && preg_match('/(\s*\?>)?\s*$/', $contents, $matches) && $matches[0])
						$trailing[] = $filename;
				}
			}

			// Filenames sorted by the number of issues
			$cnt = array_count_values(array_merge($leading, $trailing));
			arsort($cnt);
			$allfiles = array_keys($cnt);

			if(empty($allfiles))
			{
				$table = new Table;
				$table->construct_cell($lang->PHP_files_cleaner_nothing_found);
				$table->construct_row();
			}
			else
			{
				$form = new Form('index.php?module=tools-system_health&amp;action=do_clean_PHP_files', 'post');

				$table = new Table;
				$table->construct_header($lang->PHP_files_cleaner_filename);
				$table->construct_header($lang->PHP_files_cleaner_leading, array('class' => 'align_center'));
				$table->construct_header($lang->PHP_files_cleaner_trailing, array('class' => 'align_center'));
				$table->construct_header($form->generate_check_box('allbox', 1, '', array('class' => 'checkall', 'checked' => 1)), array('style' => 'text-align: right;'));

				foreach($allfiles as $filename)
				{
					$clean_filename = htmlspecialchars_uni(str_replace(MYBB_ROOT, '', $filename));
					$leading_cbox = in_array($filename, $leading) ? $form->generate_check_box('leading[]', $clean_filename, '', array('checked' => 1)) : '';
					$trailing_cbox = in_array($filename, $trailing) ? $form->generate_check_box('trailing[]', $clean_filename, '', array('checked' => 1)) : '';

					$table->construct_cell($clean_filename);
					$table->construct_cell($leading_cbox, array('class' => 'align_center'));
					$table->construct_cell($trailing_cbox, array('class' => 'align_center'));
					$table->construct_cell('');
					$table->construct_row();
				}
			}
		}

		$table->output($lang->PHP_files_cleaner);

		if(!empty($allfiles))
		{
			$buttons[] = $form->generate_submit_button($lang->PHP_files_cleaner_clean, array('name' => 'clean'));
			$form->output_submit_wrapper($buttons);
			$form->end();
		}

		$page->output_footer();
	}
}