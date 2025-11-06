<?php
/**
 * @file
 * Install content types and fields for Umami demo.
 */

// Create Article content type
$type = new stdClass();
$type->type = 'article';
$type->name = 'Article';
$type->description = 'Use <em>articles</em> for time-sensitive content like news, press releases or blog posts.';
$type->title_label = 'Title';
$type->has_title = 1;
$type->base = 'node_content';
$type->custom = 1;
$type->modified = 1;
$type->locked = 0;
$type->disabled = 0;
$type->is_new = 1;
$type->settings = array(
  'status_default' => 1,
  'promote_enabled' => 1,
  'promote_default' => 1,
  'sticky_enabled' => 0,
  'sticky_default' => 0,
  'revision_enabled' => 1,
  'revision_default' => 0,
  'show_submitted_info' => 1,
  'node_submitted' => 'Submitted by [node:author] on [node:created:long]',
  'node_user_picture' => 1,
);
node_type_save($type);

// Create Page content type
$type = new stdClass();
$type->type = 'page';
$type->name = 'Page';
$type->description = 'Use <em>pages</em> for static content like about pages or contact information.';
$type->title_label = 'Title';
$type->has_title = 1;
$type->base = 'node_content';
$type->custom = 1;
$type->modified = 1;
$type->locked = 0;
$type->disabled = 0;
$type->is_new = 1;
$type->settings = array(
  'status_default' => 1,
  'promote_enabled' => 0,
  'promote_default' => 0,
  'sticky_enabled' => 0,
  'sticky_default' => 0,
  'revision_enabled' => 1,
  'revision_default' => 0,
  'show_submitted_info' => 0,
);
node_type_save($type);

// Create Recipe content type
$type = new stdClass();
$type->type = 'recipe';
$type->name = 'Recipe';
$type->description = 'Add a new recipe to the site.';
$type->title_label = 'Recipe name';
$type->has_title = 1;
$type->base = 'node_content';
$type->custom = 1;
$type->modified = 1;
$type->locked = 0;
$type->disabled = 0;
$type->is_new = 1;
$type->settings = array(
  'status_default' => 1,
  'promote_enabled' => 1,
  'promote_default' => 1,
  'sticky_enabled' => 0,
  'sticky_default' => 0,
  'revision_enabled' => 1,
  'revision_default' => 0,
  'show_submitted_info' => 0,
);
node_type_save($type);

// Add body field to Article
if (!field_info_field('body')) {
  $field = array(
    'field_name' => 'body',
    'type' => 'text_with_summary',
    'entity_types' => array('node'),
  );
  field_create_field($field);
}

$instance = array(
  'field_name' => 'body',
  'entity_type' => 'node',
  'bundle' => 'article',
  'label' => 'Body',
  'widget' => array('type' => 'text_textarea_with_summary'),
  'settings' => array('display_summary' => TRUE),
  'display' => array(
    'default' => array('label' => 'hidden', 'type' => 'text_default'),
    'teaser' => array('label' => 'hidden', 'type' => 'text_summary_or_trimmed'),
  ),
);
field_create_instance($instance);

// Add body field to Page
$instance = array(
  'field_name' => 'body',
  'entity_type' => 'node',
  'bundle' => 'page',
  'label' => 'Body',
  'widget' => array('type' => 'text_textarea_with_summary'),
  'display' => array(
    'default' => array('label' => 'hidden', 'type' => 'text_default'),
    'teaser' => array('label' => 'hidden', 'type' => 'text_summary_or_trimmed'),
  ),
);
field_create_instance($instance);

// Create field_tags
$field = array(
  'field_name' => 'field_tags',
  'type' => 'taxonomy_term_reference',
  'cardinality' => FIELD_CARDINALITY_UNLIMITED,
  'settings' => array(
    'allowed_values' => array(
      array(
        'vocabulary' => 'tags',
        'parent' => 0,
      ),
    ),
  ),
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_tags',
  'entity_type' => 'node',
  'bundle' => 'article',
  'label' => 'Tags',
  'widget' => array('type' => 'taxonomy_autocomplete'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
    'teaser' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
);
field_create_instance($instance);

// Create field_media_image (using image field type in Backdrop)
$field = array(
  'field_name' => 'field_media_image',
  'type' => 'image',
  'cardinality' => 1,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_media_image',
  'entity_type' => 'node',
  'bundle' => 'article',
  'label' => 'Image',
  'required' => FALSE,
  'widget' => array(
    'type' => 'image_image',
    'weight' => 4,
  ),
  'display' => array(
    'default' => array(
      'label' => 'hidden',
      'type' => 'image',
      'weight' => 1,
    ),
    'teaser' => array(
      'label' => 'hidden',
      'type' => 'image',
      'weight' => 1,
    ),
  ),
);
field_create_instance($instance);

// Add tags field to recipe
$instance = array(
  'field_name' => 'field_tags',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Tags',
  'widget' => array('type' => 'taxonomy_autocomplete'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
    'teaser' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
);
field_create_instance($instance);

// Create field_recipe_category
$field = array(
  'field_name' => 'field_recipe_category',
  'type' => 'taxonomy_term_reference',
  'cardinality' => FIELD_CARDINALITY_UNLIMITED,
  'settings' => array(
    'allowed_values' => array(
      array(
        'vocabulary' => 'recipe_category',
        'parent' => 0,
      ),
    ),
  ),
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_recipe_category',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Recipe category',
  'widget' => array('type' => 'options_select'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
);
field_create_instance($instance);

// Create field_summary
$field = array(
  'field_name' => 'field_summary',
  'type' => 'text_long',
  'cardinality' => 1,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_summary',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Summary',
  'widget' => array('type' => 'text_textarea'),
  'display' => array(
    'default' => array('label' => 'above', 'type' => 'text_default'),
  ),
);
field_create_instance($instance);

// Add image field to recipe
$instance = array(
  'field_name' => 'field_media_image',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Image',
  'required' => FALSE,
  'widget' => array(
    'type' => 'image_image',
    'weight' => 2,
  ),
  'display' => array(
    'default' => array(
      'label' => 'hidden',
      'type' => 'image',
      'weight' => 1,
    ),
  ),
);
field_create_instance($instance);

// Create field_difficulty
$field = array(
  'field_name' => 'field_difficulty',
  'type' => 'list_text',
  'cardinality' => 1,
  'settings' => array(
    'allowed_values' => array(
      'easy' => 'Easy',
      'medium' => 'Medium',
      'hard' => 'Hard',
    ),
  ),
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_difficulty',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Difficulty',
  'widget' => array('type' => 'options_select'),
  'display' => array(
    'default' => array('type' => 'list_default', 'label' => 'inline'),
  ),
);
field_create_instance($instance);

// Create field_preparation_time
$field = array(
  'field_name' => 'field_preparation_time',
  'type' => 'number_integer',
  'cardinality' => 1,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_preparation_time',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Preparation time',
  'description' => 'How long does this take to prepare, in minutes?',
  'widget' => array('type' => 'number'),
  'settings' => array('suffix' => ' minutes'),
  'display' => array(
    'default' => array('type' => 'number_integer', 'label' => 'inline'),
  ),
);
field_create_instance($instance);

// Create field_cooking_time
$field = array(
  'field_name' => 'field_cooking_time',
  'type' => 'number_integer',
  'cardinality' => 1,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_cooking_time',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Cooking time',
  'description' => 'How long does this take to cook, in minutes?',
  'widget' => array('type' => 'number'),
  'settings' => array('suffix' => ' minutes'),
  'display' => array(
    'default' => array('type' => 'number_integer', 'label' => 'inline'),
  ),
);
field_create_instance($instance);

// Create field_number_of_servings
$field = array(
  'field_name' => 'field_number_of_servings',
  'type' => 'number_integer',
  'cardinality' => 1,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_number_of_servings',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Number of servings',
  'widget' => array('type' => 'number'),
  'display' => array(
    'default' => array('type' => 'number_integer', 'label' => 'inline'),
  ),
);
field_create_instance($instance);

// Create field_ingredients
$field = array(
  'field_name' => 'field_ingredients',
  'type' => 'text',
  'cardinality' => FIELD_CARDINALITY_UNLIMITED,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_ingredients',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Ingredients',
  'widget' => array('type' => 'text_textfield'),
  'display' => array(
    'default' => array('type' => 'text_default', 'label' => 'above'),
  ),
);
field_create_instance($instance);

// Create field_recipe_instruction
$field = array(
  'field_name' => 'field_recipe_instruction',
  'type' => 'text_long',
  'cardinality' => FIELD_CARDINALITY_UNLIMITED,
);
field_create_field($field);

$instance = array(
  'field_name' => 'field_recipe_instruction',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Recipe instruction',
  'widget' => array('type' => 'text_textarea'),
  'display' => array(
    'default' => array('type' => 'text_default', 'label' => 'above'),
  ),
);
field_create_instance($instance);

echo "Content types and fields created successfully!\n";
