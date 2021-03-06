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

// Load the admin_permissions.php language file
$lang->load('admin_permissions');

if (isset($_POST['form_sent']))
{
	confirm_referrer('admin_permissions.php');

	$form = array_map('intval', $_POST['form']);

	$query = $db->update(array('conf_value' => ':value'), 'config');
	$query->where = 'conf_name = :name';

	foreach ($form as $key => $input)
	{
		// Only update values that have changed
		if (array_key_exists('p_'.$key, $pun_config) && $pun_config['p_'.$key] != $input)
		{
			$params = array(':name' => 'p_'.$key, ':value' => $input);

			$query->run($params);
			unset ($params);
		}
	}

	unset ($query);

	// Regenerate the config cache
	$cache->delete('config');

	redirect('admin_permissions.php', $lang->t('Perms updated redirect'));
}

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang->t('Admin'), $lang->t('Permissions'));
define('PUN_ACTIVE_PAGE', 'admin');
require PUN_ROOT.'header.php';

generate_admin_menu('permissions');

?>
	<div class="blockform">
		<h2><span><?php echo $lang->t('Permissions head') ?></span></h2>
		<div class="box">
			<form method="post" action="admin_permissions.php">
				<p class="submittop"><input type="submit" name="save" value="<?php echo $lang->t('Save changes') ?>" /></p>
				<div class="inform">
					<input type="hidden" name="form_sent" value="1" />
					<fieldset>
						<legend><?php echo $lang->t('Posting subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('BBCode label') ?></th>
									<td>
										<input type="radio" name="form[message_bbcode]" value="1"<?php if ($pun_config['p_message_bbcode'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[message_bbcode]" value="0"<?php if ($pun_config['p_message_bbcode'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('BBCode help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Image tag label') ?></th>
									<td>
										<input type="radio" name="form[message_img_tag]" value="1"<?php if ($pun_config['p_message_img_tag'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[message_img_tag]" value="0"<?php if ($pun_config['p_message_img_tag'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Image tag help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('All caps message label') ?></th>
									<td>
										<input type="radio" name="form[message_all_caps]" value="1"<?php if ($pun_config['p_message_all_caps'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[message_all_caps]" value="0"<?php if ($pun_config['p_message_all_caps'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('All caps message help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('All caps subject label') ?></th>
									<td>
										<input type="radio" name="form[subject_all_caps]" value="1"<?php if ($pun_config['p_subject_all_caps'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[subject_all_caps]" value="0"<?php if ($pun_config['p_subject_all_caps'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('All caps subject help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Require e-mail label') ?></th>
									<td>
										<input type="radio" name="form[force_guest_email]" value="1"<?php if ($pun_config['p_force_guest_email'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[force_guest_email]" value="0"<?php if ($pun_config['p_force_guest_email'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Require e-mail help') ?></span>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang->t('Signatures subhead') ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row"><?php echo $lang->t('BBCode sigs label') ?></th>
									<td>
										<input type="radio" name="form[sig_bbcode]" value="1"<?php if ($pun_config['p_sig_bbcode'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[sig_bbcode]" value="0"<?php if ($pun_config['p_sig_bbcode'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('BBCode sigs help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Image tag sigs label') ?></th>
									<td>
										<input type="radio" name="form[sig_img_tag]" value="1"<?php if ($pun_config['p_sig_img_tag'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[sig_img_tag]" value="0"<?php if ($pun_config['p_sig_img_tag'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Image tag sigs help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('All caps sigs label') ?></th>
									<td>
										<input type="radio" name="form[sig_all_caps]" value="1"<?php if ($pun_config['p_sig_all_caps'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[sig_all_caps]" value="0"<?php if ($pun_config['p_sig_all_caps'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('All caps sigs help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Max sig length label') ?></th>
									<td>
										<input type="text" name="form[sig_length]" size="5" maxlength="5" value="<?php echo $pun_config['p_sig_length'] ?>" />
										<span><?php echo $lang->t('Max sig length help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Max sig lines label') ?></th>
									<td>
										<input type="text" name="form[sig_lines]" size="3" maxlength="3" value="<?php echo $pun_config['p_sig_lines'] ?>" />
										<span><?php echo $lang->t('Max sig lines help') ?></span>
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
									<th scope="row"><?php echo $lang->t('Banned e-mail label') ?></th>
									<td>
										<input type="radio" name="form[allow_banned_email]" value="1"<?php if ($pun_config['p_allow_banned_email'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[allow_banned_email]" value="0"<?php if ($pun_config['p_allow_banned_email'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Banned e-mail help') ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang->t('Duplicate e-mail label') ?></th>
									<td>
										<input type="radio" name="form[allow_dupe_email]" value="1"<?php if ($pun_config['p_allow_dupe_email'] == '1') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('Yes') ?></strong>&#160;&#160;&#160;<input type="radio" name="form[allow_dupe_email]" value="0"<?php if ($pun_config['p_allow_dupe_email'] == '0') echo ' checked="checked"' ?> />&#160;<strong><?php echo $lang->t('No') ?></strong>
										<span><?php echo $lang->t('Duplicate e-mail help') ?></span>
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
