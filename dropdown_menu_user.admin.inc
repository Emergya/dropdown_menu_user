<?php

/**
 * @file
 * General DROPDOWN MENU USER administration pages
 */

/**
 * Displays the form available in the administration interface of module.
 */
function dropdown_menu_user_admin() {
  module_load_include('inc', 'dropdown_menu_user');

  $form['settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Settings User Profile'),
  );

  $form['settings']['dropdown_menu_user_image_style'] = array(
    '#type' => 'select',
    '#title' => t('Image style'),
    '#options' => dropdown_menu_user_get_image_style(),
    '#default_value' => variable_get('dropdown_menu_user_image_style', ''),
    '#description' => t('Select the image style for the user avatar.'),
  );

  $form['settings']['dropdown_menu_user_disable_user_menu'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check this box if you want to disable the dropdown menu when the user logs'),
    '#default_value' => variable_get('dropdown_menu_user_disable_user_menu', ''),
    '#prefix' => '<div><label>' . t('Disable dropdown menu user') . '</label>',
    '#suffix' => '</div>',
  );

  return system_settings_form($form);
}
