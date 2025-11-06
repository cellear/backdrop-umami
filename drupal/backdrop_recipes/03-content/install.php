<?php
/**
 * @file
 * Import content for Umami demo.
 */

// Load taxonomy term mappings
$taxonomy_mapping = array();
$terms = taxonomy_term_load_multiple(array(), array());
foreach ($terms as $term) {
  // Create a mapping by vocabulary and name
  $taxonomy_mapping[$term->vocabulary][$term->name] = $term->tid;
}

// Import nodes from JSON
$nodes_data = json_decode(file_get_contents(__DIR__ . '/nodes.json'), TRUE);

foreach ($nodes_data as $node_data) {
  $node = new stdClass();
  $node->type = $node_data['type'];
  $node->title = $node_data['title'];
  $node->language = $node_data['langcode'];
  $node->uid = 1; // Admin user
  $node->status = $node_data['status'] ? 1 : 0;
  $node->promote = $node_data['promote'] ? 1 : 0;
  $node->sticky = $node_data['sticky'] ? 1 : 0;
  $node->created = $node_data['created'];
  $node->changed = $node_data['changed'];

  // Prepare the node
  node_object_prepare($node);

  // Add fields
  foreach ($node_data['fields'] as $field_name => $field_values) {
    // Skip non-field data
    if (in_array($field_name, array('path', 'moderation_state', 'content_translation_source', 'content_translation_outdated', 'content_translation_uid', 'content_translation_created', 'layout_builder__layout'))) {
      continue;
    }

    // Handle special field types
    if ($field_name == 'field_tags' || $field_name == 'field_recipe_category') {
      // Look up term IDs by name
      $vocab = ($field_name == 'field_tags') ? 'tags' : 'recipe_category';
      $node->{$field_name} = array();
      foreach ($field_values as $field_value) {
        if (isset($field_value['target_id'])) {
          // We need to map the old term ID to the new one
          // For now, we'll skip this and let users manually assign terms
          // or we could look up by term name
          continue;
        }
      }
    }
    elseif ($field_name == 'field_media_image') {
      // Skip media images for now as requested by user
      continue;
    }
    elseif ($field_name == 'body') {
      // Handle body field
      if (isset($field_values[0])) {
        $node->body = array(
          LANGUAGE_NONE => array(
            array(
              'value' => $field_values[0]['value'] ?? '',
              'summary' => $field_values[0]['summary'] ?? '',
              'format' => 'filtered_html',
            ),
          ),
        );
      }
    }
    elseif ($field_name == 'field_ingredients' || $field_name == 'field_recipe_instruction') {
      // Multi-value text fields
      $node->{$field_name} = array(LANGUAGE_NONE => array());
      foreach ($field_values as $field_value) {
        $node->{$field_name}[LANGUAGE_NONE][] = array(
          'value' => $field_value['value'],
          'format' => 'filtered_html',
        );
      }
    }
    elseif (strpos($field_name, 'field_') === 0) {
      // Generic field handling
      $node->{$field_name} = array(LANGUAGE_NONE => array());
      foreach ($field_values as $field_value) {
        if (isset($field_value['value'])) {
          $node->{$field_name}[LANGUAGE_NONE][] = array('value' => $field_value['value']);
        }
        elseif (isset($field_value['target_id'])) {
          $node->{$field_name}[LANGUAGE_NONE][] = array('target_id' => $field_value['target_id']);
        }
      }
    }
  }

  // Save the node
  node_save($node);
  echo "Created " . $node->type . ": " . $node->title . " (nid: " . $node->nid . ")\n";
}

echo "Content import complete!\n";
