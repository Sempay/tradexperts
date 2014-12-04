<?php
/**
 * @file
 * feature_image_styles.features.inc
 */

/**
 * Implements hook_image_default_styles().
 */
function feature_image_styles_image_default_styles() {
  $styles = array();

  // Exported image style: 100x52sc.
  $styles['100x52sc'] = array(
    'name' => '100x52sc',
    'label' => '100x52sc',
    'effects' => array(
      1 => array(
        'label' => 'Scale and crop',
        'help' => 'Scale and crop will maintain the aspect-ratio of the original image, then crop the larger dimension. This is most useful for creating perfectly square thumbnails without stretching the image.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 100,
          'height' => 52,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: 31x32sc.
  $styles['31x32sc'] = array(
    'name' => '31x32sc',
    'label' => '31x32sc',
    'effects' => array(
      1 => array(
        'label' => 'Scale and crop',
        'help' => 'Scale and crop will maintain the aspect-ratio of the original image, then crop the larger dimension. This is most useful for creating perfectly square thumbnails without stretching the image.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 31,
          'height' => 32,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}
