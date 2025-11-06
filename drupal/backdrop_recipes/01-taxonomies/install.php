<?php
/**
 * @file
 * Install taxonomies for Umami demo.
 */

// Create Tags vocabulary
$vocabulary = new stdClass();
$vocabulary->name = 'Tags';
$vocabulary->machine_name = 'tags';
$vocabulary->description = 'Use tags to group content on similar topics into categories.';
$vocabulary->module = 'taxonomy';
taxonomy_vocabulary_save($vocabulary);

// Create Recipe Category vocabulary
$vocabulary = new stdClass();
$vocabulary->name = 'Recipe Category';
$vocabulary->machine_name = 'recipe_category';
$vocabulary->description = 'Categorize recipes by type.';
$vocabulary->module = 'taxonomy';
taxonomy_vocabulary_save($vocabulary);

// Import taxonomy terms from JSON
$terms_data = json_decode(file_get_contents(__DIR__ . '/taxonomy_terms.json'), TRUE);

foreach ($terms_data as $term_data) {
  $term = new stdClass();
  $term->vid = $term_data['vid'];
  $term->name = $term_data['name'];
  $term->description = $term_data['description'];
  $term->weight = $term_data['weight'];
  $term->language = $term_data['langcode'];

  // Save term
  taxonomy_term_save($term);

  // Store mapping of old tid to new tid for parent relationships
  if (!isset($term_mapping)) {
    $term_mapping = [];
  }
  $term_mapping[$term_data['tid']] = $term->tid;
}

// Update parent relationships
foreach ($terms_data as $term_data) {
  if (!empty($term_data['parent'])) {
    $new_tid = $term_mapping[$term_data['tid']];
    $term = taxonomy_term_load($new_tid);
    $term->parent = [];
    foreach ($term_data['parent'] as $old_parent_tid) {
      if (isset($term_mapping[$old_parent_tid])) {
        $term->parent[] = $term_mapping[$old_parent_tid];
      }
    }
    taxonomy_term_save($term);
  }
}

echo "Taxonomies created successfully!\n";
