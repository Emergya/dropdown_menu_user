<?php

/**
 * @file
 * General functionality and helper functions.
 */

/**
 * Callback to get all the image styles available
 *
 * @return array
 *  Image styles.
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
 *
 * @param $block
 *  Dropdown menu.
 */
function dropdown_menu_user_login_block_view(&$block) {
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
    // Get the rendered array.
    $block_body_render = dropdown_menu_user_render_menu_items();
    $block_body = render($block_body_render);
  }
  else {
    // If the user isn't logged show user login block.
    $block_header = '<span>' . l(t('Sign up'), 'user/register') . '</span>' .
                    '<span>' . t('or') . '</span>' .
                    '<span class="user-login">' . t('Login') . '</span>';
    $form = drupal_get_form('user_login_block');
    $block_body = drupal_render($form);
  }

  $content = array(
    '#theme' => 'dropdown_menu_user_login_block',
    '#block_header' => $block_header,
    '#block_body' => $block_body,
    '#attached' => array(
      'js' => array(drupal_get_path('module', 'dropdown_menu_user') . '/js/dropdown_menu_user.js'),
      'css' => array(drupal_get_path('module', 'dropdown_menu_user') . '/css/dropdown_menu_user.css'),
    ),
  );

  if (!variable_get('dropdown_menu_user_disable_user_menu', '')) {
    $block['content'] = render($content);
  }
}

/**
 * Callback to build menu items for current user.
 *
 * @return array
 *  Dropdown menu links.
 */
function dropdown_menu_user_render_menu_items() {
  $items = array();

  $items['profile'] = array(
    'data' => l(t('My profile'), 'user'),
  );
  $items['configuration'] = array(
    'data' => l(t('Configuration'), 'admin/config/people/dropdown_menu_user'),
  );
  $items['logout'] = array(
    'data' => l(t('Logout'), 'user/logout'),
  );

  return array(
    '#theme' => 'item_list',
    '#items' => $items,
  );
}
