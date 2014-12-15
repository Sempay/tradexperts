<?php
/**
 * @file
 * taxonomy_vocabularies.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function taxonomy_vocabularies_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'taxonomy_term-category-field_main_category'
  $field_instances['taxonomy_term-category-field_main_category'] = array(
    'bundle' => 'category',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_main_category',
    'label' => 'Адрес главной страницы категории',
    'required' => FALSE,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Адрес главной страницы категории');

  return $field_instances;
}
