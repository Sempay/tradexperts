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
  if (drupal_is_front_page()) {
    unset($variables['page']['content']['system_main']['default_message']);
  }

  if (arg(0) == 'contact' && !arg(1)) {
    $top_text = variable_get('tradexperts_contact_top_text');
    if ($top_text) {
      $top_text_container['top_text'] = array(
        '#markup' => '<div class="contact-form-top-text">' . $top_text . '</div>',
      );
    }
    if (!empty($top_text_container)) {
      $variables['page']['content'] = $top_text_container + $variables['page']['content'];
    }
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
  $variables['content']['links']['hypercomments'] = NULL;
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

/**
 * Return a themed breadcrumb trail.
 *
 * @param $variables
 *   - title: An optional string to be used as a navigational heading to give
 *     context for breadcrumb links to screen-reader users.
 *   - title_attributes_array: Array of HTML attributes for the title. It is
 *     flattened into a string within the theme function.
 *   - breadcrumb: An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
function tradexperts_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $output = '';

  // Determine if we are to display the breadcrumb.
  $show_breadcrumb = theme_get_setting('zen_breadcrumb');
  if ($show_breadcrumb == 'yes' || $show_breadcrumb == 'admin' && arg(0) == 'admin') {

    // Optionally get rid of the homepage link.
    $show_breadcrumb_home = theme_get_setting('zen_breadcrumb_home');
    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }

    // Return the breadcrumb with separators.
    if (!empty($breadcrumb)) {
      $breadcrumb_separator = filter_xss_admin(theme_get_setting('zen_breadcrumb_separator'));
      $trailing_separator = $title = '';
      if (theme_get_setting('zen_breadcrumb_title')) {
        $item = menu_get_item();
        if (!empty($item['tab_parent'])) {
          // If we are on a non-default tab, use the tab's title.
          $breadcrumb[] = check_plain($item['title']);
        }
        else {
          $breadcrumb[] = drupal_get_title();
        }
      }
      elseif (theme_get_setting('zen_breadcrumb_trailing')) {
        $trailing_separator = $breadcrumb_separator;
      }

      // Provide a navigational heading to give context for breadcrumb links to
      // screen-reader users.
      if (empty($variables['title'])) {
        $variables['title'] = t('You are here');
      }
      // Unless overridden by a preprocess function, make the heading invisible.
      if (!isset($variables['title_attributes_array']['class'])) {
        $variables['title_attributes_array']['class'][] = 'element-invisible';
      }

      // Build the breadcrumb trail.
      $output = '<nav class="breadcrumb" role="navigation">';
      $output .= '<ol><li>' . implode($breadcrumb_separator . '</li><li>', $breadcrumb) . $trailing_separator . '</li></ol>';
      $output .= '</nav>';
    }
  }

  return $output;
}