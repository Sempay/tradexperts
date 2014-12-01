<?php
/**
 * @file
 * content_type_slideshow.features.inc
 */

/**
 * Implements hook_node_info().
 */
function content_type_slideshow_node_info() {
  $items = array(
    'slideshow' => array(
      'name' => t('Слайдшоу'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Заголовок'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
