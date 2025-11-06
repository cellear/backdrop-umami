<?php
/**
 * @file
 * Install content types and fields for Umami demo.
 */

/**
 * Helper to create field if it doesn't exist.
 */
function create_field_if_not_exists($field_name, $field_config) {
  if (!field_info_field($field_name)) {
    field_create_field($field_config);
    echo "Created field: $field_name\n";
    return TRUE;
  }
  return FALSE;
}

/**
 * Helper to create field instance if it doesn't exist.
 */
function create_instance_if_not_exists($entity_type, $bundle, $field_name, $instance_config) {
  if (!field_info_instance($entity_type, $field_name, $bundle)) {
    field_create_instance($instance_config);
    echo "Added $field_name to $bundle\n";
    return TRUE;
  }
  return FALSE;
}

// Create Article content type if it doesn't exist
if (!node_type_load('article')) {
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
  echo "Created Article content type\n";
}

// Page content type already exists in standard install, skip creation

// Create Recipe content type if it doesn't exist
if (!node_type_load('recipe')) {
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
  echo "Created Recipe content type\n";
}

// Add body field to Article
if (!field_info_field('body')) {
  $field = array(
    'field_name' => 'body',
    'type' => 'text_with_summary',
    'entity_types' => array('node'),
  );
  field_create_field($field);
}

if (!field_info_instance('node', 'body', 'article')) {
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
  echo "Added body field to Article\n";
}

// Body field already exists on Page in standard install

// Create field_tags
create_field_if_not_exists('field_tags', array(
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
));

create_instance_if_not_exists('node', 'article', 'field_tags', array(
  'field_name' => 'field_tags',
  'entity_type' => 'node',
  'bundle' => 'article',
  'label' => 'Tags',
  'widget' => array('type' => 'taxonomy_autocomplete'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
    'teaser' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
));

// Create field_media_image (using image field type in Backdrop)
create_field_if_not_exists('field_media_image', array(
  'field_name' => 'field_media_image',
  'type' => 'image',
  'cardinality' => 1,
));

create_instance_if_not_exists('node', 'article', 'field_media_image', array(
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
));

// Add tags field to recipe
create_instance_if_not_exists('node', 'recipe', 'field_tags', array(
  'field_name' => 'field_tags',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Tags',
  'widget' => array('type' => 'taxonomy_autocomplete'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
    'teaser' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
));

// Create field_recipe_category
create_field_if_not_exists('field_recipe_category', array(
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
));

create_instance_if_not_exists('node', 'recipe', 'field_recipe_category', array(
  'field_name' => 'field_recipe_category',
  'entity_type' => 'node',
  'bundle' => 'recipe',
  'label' => 'Recipe category',
  'widget' => array('type' => 'options_select'),
  'display' => array(
    'default' => array('type' => 'taxonomy_term_reference_link', 'weight' => 10),
  ),
));

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
