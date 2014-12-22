<?php
/**
 * @file
 * content_type_page.features.inc
 */

/**
 * Implements hook_node_info().
 */
function content_type_page_node_info() {
  $items = array(
    'page' => array(
      'name' => t('Страница'),
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
