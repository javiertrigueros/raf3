<td class="a-admin-text username"><?php echo link_to($sf_guard_user->getUsername(), 'a_user_admin_edit', $sf_guard_user) ?></td>
<td class="a-admin-text first_name"><?php echo $sf_guard_user->getFirstName() ?></td>
<td class="a-admin-text last_name"><?php echo $sf_guard_user->getLastName() ?></td>
<td class="a-admin-text is_active"><?php echo $sf_guard_user->getIsActive()?__('enable',null,'apostrophe'):__('disable',null,'apostrophe')  ?></td>
<td class="a-admin-date created_at"><?php echo false !== strtotime($sf_guard_user->getCreatedAt()) ? format_date($sf_guard_user->getCreatedAt(), "g") : '&nbsp;' ?></td>
<td class="a-admin-date last_login"><?php echo false !== strtotime($sf_guard_user->getLastLogin()) ? format_date($sf_guard_user->getLastLogin(), "g") : '&nbsp;' ?></td>
