<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function tradexperts_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  tradexperts_preprocess_html($variables, $hook);
  tradexperts_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function tradexperts_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function tradexperts_preprocess_page(&$variables, $hook) {
  drupal_add_js(array('tradexpertsWidgetId' => TRADEXPERTS_WIDGET_ID), 'setting');
  if (!empty($variables['node']) && isset($variables['node']->field_category)) {
    $field_category = field_get_items('node', $variables['node'], 'field_category');
    if (!empty($field_category[0]['tid'])) {
      $parents = taxonomy_get_parents_all($field_category[0]['tid']);
      $breadcrumbs = array();
      foreach ($parents as $term) {
        $path_main_category = field_get_items('taxonomy_term', $term, 'field_main_category');
        if (!empty($path_main_category[0]['value'])) {
          $path = $path_main_category[0]['value'];
        }
        else {
          $path = 'taxonomy/term/' . $term->tid;
        }
        if (request_path() !== $path) {
          $breadcrumbs[] = l($term->name, $path);
        }
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      array_unshift($breadcrumbs, l(t('Home'), '<front>'));
      drupal_set_breadcrumb($breadcrumbs);
    }
  }
  if(drupal_is_front_page()) {
    // Уберём default_message с главной
    unset($variables['page']['content']['system_main']['default_message']);
  }
}
//

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function tradexperts_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // tradexperts_preprocess_node_page() or tradexperts_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
  unset($variables['content']['links']['node']['#links']['node-readmore']);

  $variables['content']['google_stars'] = array(
    '#markup' => tradexperts_theme_google_stars($variables['elements']['#node']),
    '#weight' => 1000,
  );
}


/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function tradexperts_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function tradexperts_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function tradexperts_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */


function tradexperts_theme_google_stars($node) {
  if (module_exists('fivestar')) {
    $field_rating = field_get_items('node', $node, 'field_rating');
    if (isset($field_rating[0]['average'])) {
      $votes = array(
        20  => 1,
        40  => 2,
        60  => 3,
        80  => 4,
        100 => 5,
      );
      $rating   = isset($votes[0][ $field_rating[0]['average'] ]) ? $votes[0][ $field_rating[0]['average'] ] : 0;
      $max_rate = 5;
      $count    = isset($field_rating[0]['count']) ? $field_rating[0]['count'] : 0;
      $html = '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
      $html .= '<span itemprop="ratingValue">' . $rating . '</span>';
      $html .= '<span itemprop="bestRating">' . $max_rate . '</span>';
      $html .= '<span itemprop="reviewCount">' . $count . '</span>';
      $html .= '</div>';

      return '<div class="element-hidden">' . $html . '</div>';
    }
  }
  return '';
}