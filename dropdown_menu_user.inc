<?php

/**
 * @file
 * General DROPDOWN MENU USER functionality and helper functions.
 */

/**
 * Callback to get all the image styles available.
 */
function dropdown_menu_user_get_image_style() {
  $styles = array();

  foreach (image_styles() as $style) {
    $styles[$style['name']] = $style['label'];
  }

  return $styles;
}

/**
 * Callback to build different forms depending on whether the user is online or not.
 * @param $block
 */
function dropdown_menu_user_login_block_view(&$block) {
  drupal_add_css(drupal_get_path('module', 'dropdown_menu_user') . '/css/dropdown_menu_user.css');
  drupal_add_js(drupal_get_path('module', 'dropdown_menu_user') . '/js/dropdown_menu_user.js');

  global $user;
  if (!empty($user->uid)) {
    $account = user_load($user->uid);
    $image_style = variable_get('dropdown_menu_user_image_style', 'avatar');
    $image_path = variable_get('user_picture_default', '');
    $image_render = '';
    if ((isset($account->picture->uri)) || !empty($image_path)) {
      $image_render = theme('image_style', array(
          'style_name' => $image_style,
          'path' => isset($account->picture->uri) ? $account->picture->uri : $image_path,
        )
      );
    }

    $block_header_render = array(
      '#theme' => 'dropdown_menu_user_logged_user',
      '#user' => array(
        'name' => $account->name,
        'image' => $image_render,
      ),
    );

    $block_header = render($block_header_render);
    $block_body = theme('item_list', array('items' => dropdown_menu_user_login_block_get_menu_items()));
  }
  else {
    // If the user isn't logged: Show user login block.
    $block_header = '<span>' . t('Access') . '</span>';
    $form = drupal_get_form('user_login_block');
    $block_body = drupal_render($form);
  }

  $content = array(
    '#theme' => 'dropdown_menu_user_login_block',
    '#block_header' => $block_header,
    '#block_body' => $block_body,
  );

  if (!variable_get('dropdown_menu_user_disable_user_menu', '')) {
    $block['content'] = render($content);
  }
}

/**
 * Callback to build menu items for current user.
 */
function dropdown_menu_user_login_block_get_menu_items() {
  $items = array();

  $items['profile'] = array(
    'data' => l(t('My profile'), 'user'),
  );
  $items['profile2'] = array(
    'data' => l(t('Configuration'), 'admin/settings/dropdown_menu_user'),
  );

  return $items;
}