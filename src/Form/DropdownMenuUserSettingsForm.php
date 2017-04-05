<?php

namespace Drupal\dropdown_menu_user\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\quickedit\Annotation\InPlaceEditor;

class DropdownMenuUserSettingsForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array(
      'dropdown_menu_user.settings',
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropdown_menu_user_settings_form';
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dropdown_menu_user.settings');
    $dropdown_menu_user_image_style = $config->get('dropdown_menu_user_image_style');
    $dropdown_menu_user_disable_user_menu = $config->get('dropdown_menu_user_disable_user_menu');
    
    $form['settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Settings user profile'),
    );
  
    $form['settings']['dropdown_menu_user_image_style'] = array(
      '#type' => 'select',
      '#title' => t('Image style'),
      '#options' => $this->getImageStyles(),
      '#default_value' => $dropdown_menu_user_image_style,
      '#description' => t('Select the image style for the user avatar.'),
    );
  
    $form['settings']['dropdown_menu_user_disable_user_menu'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this box if you want to disable the dropdown menu when the user logs'),
      '#default_value' => $dropdown_menu_user_disable_user_menu,
      '#prefix' => '<div><label>' . t('Disable dropdown menu user') . '</label>',
      '#suffix' => '</div>',
    );
    
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('sevilla_fc_custom.settings');
    
    $config->set('dropdown_menu_user_image_style', $form_state->getValue('dropdown_menu_user_image_style'))
      ->save();
    $config->set('dropdown_menu_user_disable_user_menu', $form_state->getValue('dropdown_menu_user_disable_user_menu'))
      ->save();
    parent::submitForm($form, $form_state);
  }
  
  
  /**
   * @return array with all system image styles with option format prepared for a form
   */
  protected function getImageStyles(){
    $styles = ImageStyle::loadMultiple();
    $styleOptions = array();
    foreach ($styles as $style){
      $styleOptions[$style->getName()] = $style->label();
    }
    
    return $styleOptions;
  }
  
}