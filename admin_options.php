<?php

/**
 * Copyright (C) 2008-2011 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Tell header.php to use the admin template
define('PUN_ADMIN_CONSOLE', 1);

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'include/common_admin.php';


if ($pun_user['g_id'] != PUN_ADMIN)
	message($lang->t('No permission'));

// Load the admin_options.php language file
$lang->load('admin_options');

if (isset($_POST['form_sent']))
{
	confirm_referrer('admin_options.php', $lang->t('Bad HTTP Referer message'));

	$form = array(
		'board_title'			=> pun_trim($_POST['form']['board_title']),
		'board_desc'			=> pun_trim($_POST['form']['board_desc']),
		'base_url'				=> pun_trim($_POST['form']['base_url']),
		'default_timezone'		=> floatval($_POST['form']['default_timezone']),
		'default_dst'			=> $_POST['form']['default_dst'] != '1' ? '0' : '1',
		'default_lang'			=> pun_trim($_POST['form']['default_lang']),
		'default_style'			=> pun_trim($_POST['form']['default_style']),
		'time_format'			=> pun_trim($_POST['form']['time_format']),
		'date_format'			=> pun_trim($_POST['form']['date_format']),
		'timeout_visit'			=> intval($_POST['form']['timeout_visit']),
		'timeout_online'		=> intval($_POST['form']['timeout_online']),
		'redirect_delay'		=> intval($_POST['form']['redirect_delay']),
		'show_version'			=> $_POST['form']['show_version'] != '1' ? '0' : '1',
		'show_user_info'		=> $_POST['form']['show_user_info'] != '1' ? '0' : '1',
		'show_post_count'		=> $_POST['form']['show_post_count'] != '1' ? '0' : '1',
		'smilies'				=> $_POST['form']['smilies'] != '1' ? '0' : '1',
		'smilies_sig'			=> $_POST['form']['smilies_sig'] != '1' ? '0' : '1',
		'make_links'			=> $_POST['form']['make_links'] != '1' ? '0' : '1',
		'topic_review'			=> intval($_POST['form']['topic_review']),
		'disp_topics_default'	=> intval($_POST['form']['disp_topics_default']),
		'disp_posts_default'	=> intval($_POST['form']['disp_posts_default']),
		'indent_num_spaces'		=> intval($_POST['form']['indent_num_spaces']),
		'quote_depth'			=> intval($_POST['form']['quote_depth']),
		'quickpost'				=> $_POST['form']['quickpost'] != '1' ? '0' : '1',
		'users_online'			=> $_POST['form']['users_online'] != '1' ? '0' : '1',
		'censoring'				=> $_POST['form']['censoring'] != '1' ? '0' : '1',
		'signatures'			=> $_POST['form']['signatures'] != '1' ? '0' : '1',
		'ranks'					=> $_POST['form']['ranks'] != '1' ? '0' : '1',
		'show_dot'				=> $_POST['form']['show_dot'] != '1' ? '0' : '1',
		'topic_views'			=> $_POST['form']['topic_views'] != '1' ? '0' : '1',
		'quickjump'				=> $_POST['form']['quickjump'] != '1' ? '0' : '1',
		'gzip'					=> $_POST['form']['gzip'] != '1' ? '0' : '1',
		'search_all_forums'		=> $_POST['form']['search_all_forums'] != '1' ? '0' : '1',
		'additional_navlinks'	=> pun_trim($_POST['form']['additional_navlinks']),
		'feed_type'				=> intval($_POST['form']['feed_type']),
		'feed_ttl'				=> intval($_POST['form']['feed_ttl']),
		'report_method'			=> intval($_POST['form']['report_method']),
		'mailing_list'			=> pun_trim($_POST['form']['mailing_list']),
		'avatars'				=> $_POST['form']['avatars'] != '1' ? '0' : '1',
		'avatars_dir'			=> pun_trim($_POST['form']['avatars_dir']),
		'avatars_width'			=> intval($_POST['form']['avatars_width']),
		'avatars_height'		=> intval($_POST['form']['avatars_height']),
		'avatars_size'			=> intval($_POST['form']['avatars_size']),
		'admin_email'			=> strtolower(pun_trim($_POST['form']['admin_email'])),
		'webmaster_email'		=> strtolower(pun_trim($_POST['form']['webmaster_email'])),
		'forum_subscriptions'	=> $_POST['form']['forum_subscriptions'] != '1' ? '0' : '1',
		'topic_subscriptions'	=> $_POST['form']['topic_subscriptions'] != '1' ? '0' : '1',
		'smtp_host'				=> pun_trim($_POST['form']['smtp_host']),
		'smtp_user'				=> pun_trim($_POST['form']['smtp_user']),
		'smtp_ssl'				=> $_POST['form']['smtp_ssl'] != '1' ? '0' : '1',
		'regs_allow'			=> $_POST['form']['regs_allow'] != '1' ? '0' : '1',
		'regs_verify'			=> $_POST['form']['regs_verify'] != '1' ? '0' : '1',
		'regs_report'			=> $_POST['form']['regs_report'] != '1' ? '0' : '1',
		'rules'					=> $_POST['form']['rules'] != '1' ? '0' : '1',
		'rules_message'			=> pun_trim($_POST['form']['rules_message']),
		'default_email_setting'	=> intval($_POST['form']['default_email_setting']),
		'announcement'			=> $_POST['form']['announcement'] != '1' ? '0' : '1',
		'announcement_message'	=> pun_trim($_POST['form']['announcement_message']),
		'maintenance'			=> $_POST['form']['maintenance'] != '1' ? '0' : '1',
		'maintenance_message'	=> pun_trim($_POST['form']['maintenance_message']),
	);

	if ($form['board_title'] == '')
		message($lang->t('Must enter title message'));

	// Make sure base_url doesn't end with a slash
	if (substr($form['base_url'], -1) == '/')
		$form['base_url'] = substr($form['base_url'], 0, -1);

	if (!Flux_Lang::languageExists($form['default_lang']))
		message($lang->t('Bad request'));

	$styles = forum_list_styles();
	if (!in_array($form['default_style'], $styles))
		message($lang->t('Bad request'));

	if ($form['time_format'] == '')
		$form['time_format'] = 'H:i:s';

	if ($form['date_format'] == '')
		$form['date_format'] = 'Y-m-d';


	require PUN_ROOT.'include/email.php';

	if (!is_valid_email($form['admin_email']))
		message($lang->t('Invalid e-mail message'));

	if (!is_valid_email($form['webmaster_email']))
		message($lang->t('Invalid webmaster e-mail message'));

	if ($form['mailing_list'] != '')
		$form['mailing_list'] = strtolower(preg_replace('%\s%S', '', $form['mailing_list']));

	// Make sure avatars_dir doesn't end with a slash
	if (substr($form['avatars_dir'], -1) == '/')
		$form['avatars_dir'] = substr($form['avatars_dir'], 0, -1);

	if ($form['additional_navlinks'] != '')
		$form['additional_navlinks'] = pun_trim(pun_linebreaks($form['additional_navlinks']));

	// Change or enter a SMTP password
	if (isset($_POST['form']['smtp_change_pass']))
	{
		$smtp_pass1 = isset($_POST['form']['smtp_pass1']) ? pun_trim($_POST['form']['smtp_pass1']) : '';
		$smtp_pass2 = isset($_POST['form']['smtp_pass2']) ? pun_trim($_POST['form']['smtp_pass2']) : '';

		if ($smtp_pass1 == $smtp_pass2)
			$form['smtp_pass'] = $smtp_pass1;
		else
			message($lang->t('SMTP passwords did not match'));
	}

	if ($form['announcement_message'] != '')
		$form['announcement_message'] = pun_linebreaks($form['announcement_message']);
	else
	{
		$form['announcement_message'] = $lang->t('Enter announcement here');
		$form['announcement'] = '0';
	}

	if ($form['rules_message'] != '')
		$form['rules_message'] = pun_linebreaks($form['rules_message']);
	else
	{
		$form['rules_message'] = $lang->t('Enter rules here');
		$form['rules'] = '0';
	}

	if ($form['maintenance_message'] != '')
		$form['maintenance_message'] = pun_linebreaks($form['maintenance_message']);
	else
	{
		$form['maintenance_message'] = $lang->t('Default maintenance message');
		$form['maintenance'] = '0';
	}

	// Make sure the number of displayed topics and posts is between 3 and 75
	if ($form['disp_topics_default'] < 3)
		$form['disp_topics_default'] = 3;
	else if ($form['disp_topics_default'] > 75)
		$form['disp_topics_default'] = 75;

	if ($form['disp_posts_default'] < 3)
		$form['disp_posts_default'] = 3;
	else if ($form['disp_posts_default'] > 75)
		$form['disp_posts_default'] = 75;

	if ($form['feed_type'] < 0 || $form['feed_type'] > 2)
		message($lang->t('Bad request'));

	if ($form['feed_ttl'] < 0)
		message($lang->t('Bad request'));

	if ($form['report_method'] < 0 || $form['report_method'] > 2)
		message($lang->t('Bad request'));

	if ($form['default_email_setting'] < 0 || $form['default_email_setting'] > 2)
		message($lang->t('Bad request'));

	if ($form['timeout_online'] >= $form['timeout_visit'])
		message($lang->t('Timeout error message'));

	$query = $db->update(array('conf_value' => ':value'), 'config');
	$query->where = 'conf_name = :name';

	foreach ($form as $key => $input)
	{
		// Only update values that have changed
		if (array_key_exists('o_'.$key, $pun_config) && $pun_config['o_'.$key] != $input)
		{
			$params = array(':name' => 'o_'.$key, ':value' => $input != '' || is_int($input) ? $input : 'NULL');

			$query->run($params);
			unset ($params);
		}
	}

	unset ($query);

	// Regenerate the config cache
	$cache->delete('config');

	redirect('admin_options.php', $lang->t('Options updated redirect'));
}

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang->t('Admin'), $lang->t('Options'));
define('PUN_ACTIVE_PAGE', 'admin');
require PUN_ROOT.'header.php';

generate_admin_menu('options');

?>
	<div class="blockform">
		<h2><span><?php echo $lang->t('Options head') ?></span></h2>
		<div class="box">
			<form method="post" action="admin_options.php">
				<p class="submittop"><input type="submit" name="save" value="<?php echo $lang->t('Save changes') ?>" /></p>
				<div class="inform">
					<input type="hidden" name="form_sent" value="1" />
					<fieldset>
						<legend><?php echo $lang->t('Essentials subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Board title label') ?></th>
									<td>
										<input type="text" name="form[board_title]" size="50" maxlength="255" value="<?php echo pun_htmlspecialchars($pun_config['o_board_title']) ?>" />
										<span><?php echo $lang->t('Board title help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Board desc label') ?></th>
									<td>
										<input type="text" name="form[board_desc]" size="50" maxlength="255" value="<?php echo pun_htmlspecialchars($pun_config['o_board_desc']) ?>" />
										<span><?php echo $lang->t('Board desc help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Base URL label') ?></th>
									<td>
										<input type="text" name="form[base_url]" size="50" maxlength="100" value="<?php echo pun_htmlspecialchars($pun_config['o_base_url']) ?>" />
										<span><?php echo $lang->t('Base URL help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Timezone label') ?></th>
									<td>
										<select name="form[default_timezone]">
											<option value="-12"<?php if ($pun_config['o_default_timezone'] == -12) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-12:00') ?></option>
											<option value="-11"<?php if ($pun_config['o_default_timezone'] == -11) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-11:00') ?></option>
											<option value="-10"<?php if ($pun_config['o_default_timezone'] == -10) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-10:00') ?></option>
											<option value="-9.5"<?php if ($pun_config['o_default_timezone'] == -9.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-09:30') ?></option>
											<option value="-9"<?php if ($pun_config['o_default_timezone'] == -9) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-09:00') ?></option>
											<option value="-8.5"<?php if ($pun_config['o_default_timezone'] == -8.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-08:30') ?></option>
											<option value="-8"<?php if ($pun_config['o_default_timezone'] == -8) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-08:00') ?></option>
											<option value="-7"<?php if ($pun_config['o_default_timezone'] == -7) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-07:00') ?></option>
											<option value="-6"<?php if ($pun_config['o_default_timezone'] == -6) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-06:00') ?></option>
											<option value="-5"<?php if ($pun_config['o_default_timezone'] == -5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-05:00') ?></option>
											<option value="-4"<?php if ($pun_config['o_default_timezone'] == -4) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-04:00') ?></option>
											<option value="-3.5"<?php if ($pun_config['o_default_timezone'] == -3.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-03:30') ?></option>
											<option value="-3"<?php if ($pun_config['o_default_timezone'] == -3) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-03:00') ?></option>
											<option value="-2"<?php if ($pun_config['o_default_timezone'] == -2) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-02:00') ?></option>
											<option value="-1"<?php if ($pun_config['o_default_timezone'] == -1) echo ' selected="selected"' ?>><?php echo $lang->t('UTC-01:00') ?></option>
											<option value="0"<?php if ($pun_config['o_default_timezone'] == 0) echo ' selected="selected"' ?>><?php echo $lang->t('UTC') ?></option>
											<option value="1"<?php if ($pun_config['o_default_timezone'] == 1) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+01:00') ?></option>
											<option value="2"<?php if ($pun_config['o_default_timezone'] == 2) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+02:00') ?></option>
											<option value="3"<?php if ($pun_config['o_default_timezone'] == 3) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+03:00') ?></option>
											<option value="3.5"<?php if ($pun_config['o_default_timezone'] == 3.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+03:30') ?></option>
											<option value="4"<?php if ($pun_config['o_default_timezone'] == 4) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+04:00') ?></option>
											<option value="4.5"<?php if ($pun_config['o_default_timezone'] == 4.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+04:30') ?></option>
											<option value="5"<?php if ($pun_config['o_default_timezone'] == 5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+05:00') ?></option>
											<option value="5.5"<?php if ($pun_config['o_default_timezone'] == 5.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+05:30') ?></option>
											<option value="5.75"<?php if ($pun_config['o_default_timezone'] == 5.75) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+05:45') ?></option>
											<option value="6"<?php if ($pun_config['o_default_timezone'] == 6) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+06:00') ?></option>
											<option value="6.5"<?php if ($pun_config['o_default_timezone'] == 6.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+06:30') ?></option>
											<option value="7"<?php if ($pun_config['o_default_timezone'] == 7) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+07:00') ?></option>
											<option value="8"<?php if ($pun_config['o_default_timezone'] == 8) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+08:00') ?></option>
											<option value="8.75"<?php if ($pun_config['o_default_timezone'] == 8.75) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+08:45') ?></option>
											<option value="9"<?php if ($pun_config['o_default_timezone'] == 9) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+09:00') ?></option>
											<option value="9.5"<?php if ($pun_config['o_default_timezone'] == 9.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+09:30') ?></option>
											<option value="10"<?php if ($pun_config['o_default_timezone'] == 10) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+10:00') ?></option>
											<option value="10.5"<?php if ($pun_config['o_default_timezone'] == 10.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+10:30') ?></option>
											<option value="11"<?php if ($pun_config['o_default_timezone'] == 11) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+11:00') ?></option>
											<option value="11.5"<?php if ($pun_config['o_default_timezone'] == 11.5) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+11:30') ?></option>
											<option value="12"<?php if ($pun_config['o_default_timezone'] == 12) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+12:00') ?></option>
											<option value="12.75"<?php if ($pun_config['o_default_timezone'] == 12.75) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+12:45') ?></option>
											<option value="13"<?php if ($pun_config['o_default_timezone'] == 13) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+13:00') ?></option>
											<option value="14"<?php if ($pun_config['o_default_timezone'] == 14) echo ' selected="selected"' ?>><?php echo $lang->t('UTC+14:00') ?></option>
										</select>
										<span><?php echo $lang->t('Timezone help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('DST label') ?></th>
									<td>
										<input type="radio" name="form[default_dst]" value="1"<?php if ($pun_config['o_default_dst'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[default_dst]" value="0"<?php if ($pun_config['o_default_dst'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('DST help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Language label') ?></th>
									<td>
										<select name="form[default_lang]">
<?php

		$languages = Flux_Lang::getLanguageList();

		foreach ($languages as $temp)
		{
			if ($pun_config['o_default_lang'] == $temp)
				echo "\t\t\t\t\t\t\t\t\t\t\t".'<option value="'.$temp.'" selected="selected">'.$temp.'</option>'."\n";
			else
				echo "\t\t\t\t\t\t\t\t\t\t\t".'<option value="'.$temp.'">'.$temp.'</option>'."\n";
		}

?>
										</select>
										<span><?php echo $lang->t('Language help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Default style label') ?></th>
									<td>
										<select name="form[default_style]">
<?php

		$styles = forum_list_styles();

		foreach ($styles as $temp)
		{
			if ($pun_config['o_default_style'] == $temp)
				echo "\t\t\t\t\t\t\t\t\t\t\t".'<option value="'.$temp.'" selected="selected">'.str_replace('_', ' ', $temp).'</option>'."\n";
			else
				echo "\t\t\t\t\t\t\t\t\t\t\t".'<option value="'.$temp.'">'.str_replace('_', ' ', $temp).'</option>'."\n";
		}

?>
										</select>
										<span><?php echo $lang->t('Default style help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
<?php

	$diff = ($pun_user['timezone'] + $pun_user['dst']) * 3600;
	$timestamp = time() + $diff;

?>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Timeouts subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Time format label') ?></th>
									<td>
										<input type="text" name="form[time_format]" size="25" maxlength="25" value="<?php echo pun_htmlspecialchars($pun_config['o_time_format']) ?>" />
										<span><?php echo $lang->t('Time format help', gmdate($pun_config['o_time_format'], $timestamp), '<a href="http://www.php.net/manual/en/function.date.php">'.$lang->t('PHP manual').'</a>') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Date format label') ?></th>
									<td>
										<input type="text" name="form[date_format]" size="25" maxlength="25" value="<?php echo pun_htmlspecialchars($pun_config['o_date_format']) ?>" />
										<span><?php echo $lang->t('Date format help', gmdate($pun_config['o_date_format'], $timestamp), '<a href="http://www.php.net/manual/en/function.date.php">'.$lang->t('PHP manual').'</a>') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Visit timeout label') ?></th>
									<td>
										<input type="text" name="form[timeout_visit]" size="5" maxlength="5" value="<?php echo $pun_config['o_timeout_visit'] ?>" />
										<span><?php echo $lang->t('Visit timeout help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Online timeout label') ?></th>
									<td>
										<input type="text" name="form[timeout_online]" size="5" maxlength="5" value="<?php echo $pun_config['o_timeout_online'] ?>" />
										<span><?php echo $lang->t('Online timeout help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Redirect time label') ?></th>
									<td>
										<input type="text" name="form[redirect_delay]" size="3" maxlength="3" value="<?php echo $pun_config['o_redirect_delay'] ?>" />
										<span><?php echo $lang->t('Redirect time help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Display subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Version number label') ?></th>
									<td>
										<input type="radio" name="form[show_version]" value="1"<?php if ($pun_config['o_show_version'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[show_version]" value="0"<?php if ($pun_config['o_show_version'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Version number help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Info in posts label') ?></th>
									<td>
										<input type="radio" name="form[show_user_info]" value="1"<?php if ($pun_config['o_show_user_info'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[show_user_info]" value="0"<?php if ($pun_config['o_show_user_info'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Info in posts help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Post count label') ?></th>
									<td>
										<input type="radio" name="form[show_post_count]" value="1"<?php if ($pun_config['o_show_post_count'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[show_post_count]" value="0"<?php if ($pun_config['o_show_post_count'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Post count help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Smilies label') ?></th>
									<td>
										<input type="radio" name="form[smilies]" value="1"<?php if ($pun_config['o_smilies'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[smilies]" value="0"<?php if ($pun_config['o_smilies'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Smilies help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Smilies sigs label') ?></th>
									<td>
										<input type="radio" name="form[smilies_sig]" value="1"<?php if ($pun_config['o_smilies_sig'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[smilies_sig]" value="0"<?php if ($pun_config['o_smilies_sig'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Smilies sigs help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Clickable links label') ?></th>
									<td>
										<input type="radio" name="form[make_links]" value="1"<?php if ($pun_config['o_make_links'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[make_links]" value="0"<?php if ($pun_config['o_make_links'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Clickable links help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Topic review label') ?></th>
									<td>
										<input type="text" name="form[topic_review]" size="3" maxlength="3" value="<?php echo $pun_config['o_topic_review'] ?>" />
										<span><?php echo $lang->t('Topic review help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Topics per page label') ?></th>
									<td>
										<input type="text" name="form[disp_topics_default]" size="3" maxlength="3" value="<?php echo $pun_config['o_disp_topics_default'] ?>" />
										<span><?php echo $lang->t('Topics per page help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Posts per page label') ?></th>
									<td>
										<input type="text" name="form[disp_posts_default]" size="3" maxlength="3" value="<?php echo $pun_config['o_disp_posts_default'] ?>" />
										<span><?php echo $lang->t('Posts per page help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Indent label') ?></th>
									<td>
										<input type="text" name="form[indent_num_spaces]" size="3" maxlength="3" value="<?php echo $pun_config['o_indent_num_spaces'] ?>" />
										<span><?php echo $lang->t('Indent help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Quote depth label') ?></th>
									<td>
										<input type="text" name="form[quote_depth]" size="3" maxlength="3" value="<?php echo $pun_config['o_quote_depth'] ?>" />
										<span><?php echo $lang->t('Quote depth help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Features subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Quick post label') ?></th>
									<td>
										<input type="radio" name="form[quickpost]" value="1"<?php if ($pun_config['o_quickpost'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[quickpost]" value="0"<?php if ($pun_config['o_quickpost'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Quick post help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Users online label') ?></th>
									<td>
										<input type="radio" name="form[users_online]" value="1"<?php if ($pun_config['o_users_online'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[users_online]" value="0"<?php if ($pun_config['o_users_online'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Users online help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><a name="censoring"><?php echo $lang->t('Censor words label') ?></a></th>
									<td>
										<input type="radio" name="form[censoring]" value="1"<?php if ($pun_config['o_censoring'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[censoring]" value="0"<?php if ($pun_config['o_censoring'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Censor words help', '<a href="admin_censoring.php">'.$lang->t('Censoring').'</a>') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><a name="signatures"><?php echo $lang->t('Signatures label') ?></a></th>
									<td>
										<input type="radio" name="form[signatures]" value="1"<?php if ($pun_config['o_signatures'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[signatures]" value="0"<?php if ($pun_config['o_signatures'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Signatures help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><a name="ranks"><?php echo $lang->t('User ranks label') ?></a></th>
									<td>
										<input type="radio" name="form[ranks]" value="1"<?php if ($pun_config['o_ranks'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[ranks]" value="0"<?php if ($pun_config['o_ranks'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('User ranks help', '<a href="admin_ranks.php">'.$lang->t('Ranks').'</a>') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('User has posted label') ?></th>
									<td>
										<input type="radio" name="form[show_dot]" value="1"<?php if ($pun_config['o_show_dot'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[show_dot]" value="0"<?php if ($pun_config['o_show_dot'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('User has posted help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Topic views label') ?></th>
									<td>
										<input type="radio" name="form[topic_views]" value="1"<?php if ($pun_config['o_topic_views'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[topic_views]" value="0"<?php if ($pun_config['o_topic_views'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Topic views help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Quick jump label') ?></th>
									<td>
										<input type="radio" name="form[quickjump]" value="1"<?php if ($pun_config['o_quickjump'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[quickjump]" value="0"<?php if ($pun_config['o_quickjump'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Quick jump help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('GZip label') ?></th>
									<td>
										<input type="radio" name="form[gzip]" value="1"<?php if ($pun_config['o_gzip'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[gzip]" value="0"<?php if ($pun_config['o_gzip'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('GZip help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Search all label') ?></th>
									<td>
										<input type="radio" name="form[search_all_forums]" value="1"<?php if ($pun_config['o_search_all_forums'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[search_all_forums]" value="0"<?php if ($pun_config['o_search_all_forums'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Search all help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Menu items label') ?></th>
									<td>
										<textarea name="form[additional_navlinks]" rows="3" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_additional_navlinks']) ?></textarea>
										<span><?php echo $lang->t('Menu items help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Feed subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Default feed label') ?></th>
									<td>
										<input type="radio" name="form[feed_type]" value="0"<?php if ($pun_config['o_feed_type'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('None') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[feed_type]" value="1"<?php if ($pun_config['o_feed_type'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('RSS') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[feed_type]" value="2"<?php if ($pun_config['o_feed_type'] == '2') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Atom') ?></strong>
										<span><?php echo $lang->t('Default feed help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Feed TTL label') ?></th>
									<td>
										<select name="form[feed_ttl]">
											<option value="0"<?php if ($pun_config['o_feed_ttl'] == '0') echo ' selected="selected"'; ?>><?php echo $lang->t('No cache') ?></option>
<?php

		$times = array(5, 15, 30, 60);

		foreach ($times as $time)
			echo "\t\t\t\t\t\t\t\t\t\t\t".'<option value="'.$time.'"'.($pun_config['o_feed_ttl'] == $time ? ' selected="selected"' : '').'>'.$lang->t('Minutes', $time).'</option>'."\n";

?>
										</select>
										<span><?php echo $lang->t('Feed TTL help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Reports subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Reporting method label') ?></th>
									<td>
										<input type="radio" name="form[report_method]" value="0"<?php if ($pun_config['o_report_method'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Internal') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[report_method]" value="1"<?php if ($pun_config['o_report_method'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('By e-mail') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[report_method]" value="2"<?php if ($pun_config['o_report_method'] == '2') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Both') ?></strong>
										<span><?php echo $lang->t('Reporting method help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Mailing list label') ?></th>
									<td>
										<textarea name="form[mailing_list]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_mailing_list']) ?></textarea>
										<span><?php echo $lang->t('Mailing list help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Avatars subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Use avatars label') ?></th>
									<td>
										<input type="radio" name="form[avatars]" value="1"<?php if ($pun_config['o_avatars'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[avatars]" value="0"<?php if ($pun_config['o_avatars'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Use avatars help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Upload directory label') ?></th>
									<td>
										<input type="text" name="form[avatars_dir]" size="35" maxlength="50" value="<?php echo pun_htmlspecialchars($pun_config['o_avatars_dir']) ?>" />
										<span><?php echo $lang->t('Upload directory help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Max width label') ?></th>
									<td>
										<input type="text" name="form[avatars_width]" size="5" maxlength="5" value="<?php echo $pun_config['o_avatars_width'] ?>" />
										<span><?php echo $lang->t('Max width help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Max height label') ?></th>
									<td>
										<input type="text" name="form[avatars_height]" size="5" maxlength="5" value="<?php echo $pun_config['o_avatars_height'] ?>" />
										<span><?php echo $lang->t('Max height help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Max size label') ?></th>
									<td>
										<input type="text" name="form[avatars_size]" size="6" maxlength="6" value="<?php echo $pun_config['o_avatars_size'] ?>" />
										<span><?php echo $lang->t('Max size help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('E-mail subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Admin e-mail label') ?></th>
									<td>
										<input type="text" name="form[admin_email]" size="50" maxlength="80" value="<?php echo $pun_config['o_admin_email'] ?>" />
										<span><?php echo $lang->t('Admin e-mail help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Webmaster e-mail label') ?></th>
									<td>
										<input type="text" name="form[webmaster_email]" size="50" maxlength="80" value="<?php echo $pun_config['o_webmaster_email'] ?>" />
										<span><?php echo $lang->t('Webmaster e-mail help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Forum subscriptions label') ?></th>
									<td>
										<input type="radio" name="form[forum_subscriptions]" value="1"<?php if ($pun_config['o_forum_subscriptions'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[forum_subscriptions]" value="0"<?php if ($pun_config['o_forum_subscriptions'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Forum subscriptions help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Topic subscriptions label') ?></th>
									<td>
										<input type="radio" name="form[topic_subscriptions]" value="1"<?php if ($pun_config['o_topic_subscriptions'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[topic_subscriptions]" value="0"<?php if ($pun_config['o_topic_subscriptions'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Topic subscriptions help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('SMTP address label') ?></th>
									<td>
										<input type="text" name="form[smtp_host]" size="30" maxlength="100" value="<?php echo pun_htmlspecialchars($pun_config['o_smtp_host']) ?>" />
										<span><?php echo $lang->t('SMTP address help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('SMTP username label') ?></th>
									<td>
										<input type="text" name="form[smtp_user]" size="25" maxlength="50" value="<?php echo pun_htmlspecialchars($pun_config['o_smtp_user']) ?>" />
										<span><?php echo $lang->t('SMTP username help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('SMTP password label') ?></th>
									<td>
										<span><input type="checkbox" name="form[smtp_change_pass]" value="1" />&#160;&#160;<?php echo $lang->t('SMTP change password help') ?></span>
<?php $smtp_pass = !empty($pun_config['o_smtp_pass']) ? random_key(pun_strlen($pun_config['o_smtp_pass']), true) : ''; ?>
										<input type="password" name="form[smtp_pass1]" size="25" maxlength="50" value="<?php echo $smtp_pass ?>" />
										<input type="password" name="form[smtp_pass2]" size="25" maxlength="50" value="<?php echo $smtp_pass ?>" />
										<span><?php echo $lang->t('SMTP password help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('SMTP SSL label') ?></th>
									<td>
										<input type="radio" name="form[smtp_ssl]" value="1"<?php if ($pun_config['o_smtp_ssl'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[smtp_ssl]" value="0"<?php if ($pun_config['o_smtp_ssl'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('SMTP SSL help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Registration subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Allow new label') ?></th>
									<td>
										<input type="radio" name="form[regs_allow]" value="1"<?php if ($pun_config['o_regs_allow'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[regs_allow]" value="0"<?php if ($pun_config['o_regs_allow'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Allow new help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Verify label') ?></th>
									<td>
										<input type="radio" name="form[regs_verify]" value="1"<?php if ($pun_config['o_regs_verify'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[regs_verify]" value="0"<?php if ($pun_config['o_regs_verify'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Verify help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Report new label') ?></th>
									<td>
										<input type="radio" name="form[regs_report]" value="1"<?php if ($pun_config['o_regs_report'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[regs_report]" value="0"<?php if ($pun_config['o_regs_report'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Report new help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Use rules label') ?></th>
									<td>
										<input type="radio" name="form[rules]" value="1"<?php if ($pun_config['o_rules'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[rules]" value="0"<?php if ($pun_config['o_rules'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Use rules help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Rules label') ?></th>
									<td>
										<textarea name="form[rules_message]" rows="10" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_rules_message']) ?></textarea>
										<span><?php echo $lang->t('Rules help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('E-mail default label') ?></th>
									<td>
										<span><?php echo $lang->t('E-mail default help') ?></span>
										<input type="radio" name="form[default_email_setting]" value="0"<?php if ($pun_config['o_default_email_setting'] == '0') echo ' checked="checked"' ?> />&#160;<?php echo $lang->t('Display e-mail label') ?><br />
										<input type="radio" name="form[default_email_setting]" value="1"<?php if ($pun_config['o_default_email_setting'] == '1') echo ' checked="checked"' ?> />&#160;<?php echo $lang->t('Hide allow form label') ?><br />
										<input type="radio" name="form[default_email_setting]" value="2"<?php if ($pun_config['o_default_email_setting'] == '2') echo ' checked="checked"' ?> />&#160;<?php echo $lang->t('Hide both label') ?><br />
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Announcement subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('Display announcement label') ?></th>
									<td>
										<input type="radio" name="form[announcement]" value="1"<?php if ($pun_config['o_announcement'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[announcement]" value="0"<?php if ($pun_config['o_announcement'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Display announcement help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Announcement message label') ?></th>
									<td>
										<textarea name="form[announcement_message]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_announcement_message']) ?></textarea>
										<span><?php echo $lang->t('Announcement message help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Maintenance subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><a name="maintenance"><?php echo $lang->t('Maintenance mode label') ?></a></th>
									<td>
										<input type="radio" name="form[maintenance]" value="1"<?php if ($pun_config['o_maintenance'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[maintenance]" value="0"<?php if ($pun_config['o_maintenance'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Maintenance mode help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Maintenance message label') ?></th>
									<td>
										<textarea name="form[maintenance_message]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_maintenance_message']) ?></textarea>
										<span><?php echo $lang->t('Maintenance message help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<p class="submitend"><input type="submit" name="save" value="<?php echo $lang->t('Save changes') ?>" /></p>
			</form>
		</div>
	</div>
	<div class="clearer"></div>
</div>
<?php

require PUN_ROOT.'footer.php';
