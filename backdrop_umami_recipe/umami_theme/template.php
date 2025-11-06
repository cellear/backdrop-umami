<?php
/**
 * @file
 * Functions to support theming in the Umami theme.
 */

/**
 * Implements hook_preprocess_html().
 *
 * Adds body classes if certain regions have content.
 */
function umami_preprocess_html(&$variables) {
  // Add a sidebar class if the sidebar has content in it.
  if (!empty($variables['page']['sidebar'])) {
    $variables['classes_array'][] = 'two-columns';
  }
  else {
    $variables['classes_array'][] = 'one-column';
  }
}

/**
 * Implements hook_preprocess_page().
 */
function umami_preprocess_page(&$variables) {
  // Add page title to breadcrumb
  if (!empty($variables['title'])) {
    $variables['breadcrumb_title'] = $variables['title'];
  }
}

/**
 * Implements hook_preprocess_node().
 */
function umami_preprocess_node(&$variables) {
  $node = $variables['node'];

  // Add view mode class
  $variables['classes_array'][] = 'node--view-mode-' . $variables['view_mode'];

  // Add content type specific classes
  $variables['classes_array'][] = 'node--type-' . $node->type;
}

/**
 * Implements hook_preprocess_field().
 */
function umami_preprocess_field(&$variables) {
  $element = $variables['element'];

  // Add class to label and items fields to be styled using the meta styles.
  if (isset($element['#field_name'])) {
    $field_name = $element['#field_name'];
    if ($field_name == 'field_recipe_category' ||
        $field_name == 'field_tags' ||
        $field_name == 'field_difficulty') {
      $variables['classes_array'][] = 'label-items';
    }
  }
}

/**
 * Implements hook_preprocess_block().
 */
function umami_preprocess_block(&$variables) {
  // Add a title class
  if (isset($variables['title'])) {
    $variables['title_attributes_array']['class'][] = 'block__title';
  }
}

/**
 * Implements template_preprocess_breadcrumb().
 */
function umami_preprocess_breadcrumb(&$variables) {
  // Add current page to breadcrumb
  if (!empty($variables['breadcrumb']) && $node = menu_get_object()) {
    $variables['breadcrumb'][] = array(
      'text' => $node->title,
      'class' => array('breadcrumb__link', 'breadcrumb__link--current'),
    );
  }
}

/**
 * Implements hook_css_alter().
 */
function umami_css_alter(&$css) {
  // Remove some core CSS that conflicts with our styles
  $remove = array(
    'core/misc/ui/theme.css',
  );

  foreach ($remove as $file) {
    $path = backdrop_get_path('module', 'system') . '/' . $file;
    if (isset($css[$path])) {
      unset($css[$path]);
    }
  }
}
