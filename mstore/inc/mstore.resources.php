<?php

/**
 * List link
 */

$R['mstore_submitnewitem'] = '<a href="{$sub_url}" rel="nofollow">'.$L['mstore_addtitle'].'</a>';

$R['list_link_title'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_title'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Title'];
$R['list_link_key'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_key'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Key'];
$R['list_link_date'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_date'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Date'];
$R['list_link_author'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_author'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Author'];
$R['list_link_owner'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_owner'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Owner'];
$R['list_link_count'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_count'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Hits'];
$R['list_link_filecount'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_filecount'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a> '.$L['Hits'];
$R['list_link_field_name'] = '<a href="{$list_link_url_down}" rel="nofollow">{$cot_img_down}</a>';
$R['list_link_field_name'].= '<a href="{$list_link_url_up}" rel="nofollow">{$cot_img_up}</a>&nbsp;{$extratitle}';

$R['list_row_admin'] = '<a href="{$unvalidate_url}">'.$L['Putinvalidationqueue'].'</a> <a href="{$edit_url}">'.$L['Edit'].'</a>';

$R['list_more'] =' <span class="readmore"><a href="{$page_url}" title="'.$L['ReadMore'].'">'.$L['ReadMore'].'</a></span>';

/**
 * mstore Icons
 */

$R['mstore_code_redir'] = '<script type="text/javascript">location.href="{$redir}"</script>Redirecting...';
$R['mstore_icon_file'] = '<img class="icon" src="{$icon}" alt="' . $L['File'] . '" />';
$R['mstore_icon_file_default'] = Cot::$cfg['icons_dir'] . '/' . Cot::$cfg['defaulticons'] . '/24/mstore.png';
$R['mstore_icon_file_path'] = 'images/filetypes/' . Cot::$cfg['defaulticons'] . '/{$type}.png';
