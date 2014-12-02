<?php
/**
 * @file
 * content_type_brokers.features.inc
 */

/**
 * Implements hook_node_info().
 */
function content_type_brokers_node_info() {
  $items = array(
    'brokers' => array(
      'name' => t('Брокеры'),
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
