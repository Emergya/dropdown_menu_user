<?php

namespace Drupal\dropdown_menu_user\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\user\Entity\User;

/**
 * Provides a 'AdSquare' block.
 *
 * @Block(
 *   id = "LoginBlock",
 *   admin_label = @Translation("Login Block"),
 *   category = @Translation("Menus")
 * )
 */
class LoginBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // Get current path.
    $current_path = \Drupal::service('path.current')->getPath();
    $current_path_alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    
    $global_config = \Drupal::config('dropdown_menu_user.settings');
    $user = \Drupal::currentUser();
    
    if ($user->isAuthenticated()) {
      
      $account = User::load($user->id());
      
      $image_style = $global_config->get('dropdown_menu_user_image_style');
      
      $field = FieldConfig::loadByName('user', 'user', 'user_picture');
      $default_image = $field->getSetting('default_image');
      
      $image_render = '';
      
      if (!empty($default_image['uuid'])) {
        
        $file = \Drupal::entityManager()
          ->loadEntityByUuid('file', $default_image['uuid']);
        $image_path = $file->getFileUri();
        
        if (($user->user_picture->isEmpty()) || !empty($image_path)) {
          $image_render = $user->user_picture->view('image_style');
        }
      }
      
      $block_header_render = array(
        '#theme' => 'dropdown_menu_user_logged_user',
        '#user' => array(
          'name' => $account->getUsername(),
          'image' => $image_render,
        ),
      );
      
      $block_header = $block_header_render;
      
      $items = array();
      
      $userUrl = URL::fromRoute('user.page');
      $items['profile'] = array(
        '#markup' => \Drupal::l(t('My profile'), $userUrl),
      );
      
      $settingsUrl = URL::fromRoute('dropdown_menu.user_settings', array('destination' => $current_path_alias));
      $items['configuration'] = array(
        '#markup' => \Drupal::l(t('Configuration'), $settingsUrl),
      );
      
      $logoutUrl = URL::fromRoute('user.logout', array('destination' => $current_path_alias));
      $items['logout'] = array(
        '#markup' => \Drupal::l(t('Logout'), $logoutUrl),
      );
      
      $block_body_render = array(
        '#theme' => 'item_list',
        '#items' => $items,
      );
      $block_body = $block_body_render;
    }
    else {
      // If the user isn't logged show user login block.
      
      // Create alias to user register.
      $url = Url::fromRoute('user.register', array('destination' => $current_path_alias));
      $block_header = '<span>' . \Drupal::l(t('Sign up'), $url) . '</span>' .
        '<span>' . t('or') . '</span>' .
        '<span class="user-login">' . t('Login') . '</span>';
      $block_body = \Drupal::formBuilder()->getForm('Drupal\user\Form\UserLoginForm');
    }
    
    $content = array(
      '#theme' => 'dropdown_menu_user_login_block',
      '#block_header' => $block_header,
      '#block_body' => $block_body,
    );
    
    if (!$global_config->get('dropdown_menu_user_disable_user_menu')) {
      $block = $content;
    }
  
    $block['#attached']['library'][] = 'dropdown_menu_user/dropdown_menu_user';
    return $block;
  }
}