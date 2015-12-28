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

$l['PHP_files_cleaner'] = 'PHP Files Cleaner';
$l['PHP_files_cleaner_info'] = 'Helps with finding and cleaning faulty PHP files that have BOM or redundant whitespace and may cause MyBB problems such as broken captcha image.';
$l['PHP_files_cleaner_check'] = 'Check files';

$l['PHP_files_cleaner_settings'] = 'Settings for the PHP Files Cleaner plugin.';
$l['PHP_files_cleaner_leading'] = 'Leading Whitespace and BOM';
$l['PHP_files_cleaner_leading_desc'] = 'Allow clean-up of leading whitespace and Byte Order Mark (BOM)?';
$l['PHP_files_cleaner_trailing'] = 'Trailing Whitespace and ?>';
$l['PHP_files_cleaner_trailing_desc'] = 'Allow clean-up of trailing whitespace and PHP ending tag (?>)?';

$l['PHP_files_cleaner_nothing_enabled'] = "No functionality is enabled in plugin's settings at this moment.";
$l['PHP_files_cleaner_nothing_chosen'] = "You didn't choose any PHP file to modify.";
$l['PHP_files_cleaner_nothing_found'] = 'No files that require modifications were found.';
$l['PHP_files_cleaner_nonchangeable'] = 'The following files could not be modified due to the lack of read/write permissions: {1}';
$l['PHP_files_cleaner_success'] = 'All chosen files have been modified successfully.';
$l['PHP_files_cleaner_filename'] = 'File Path and Name';
$l['PHP_files_cleaner_clean'] = 'Clean Files';