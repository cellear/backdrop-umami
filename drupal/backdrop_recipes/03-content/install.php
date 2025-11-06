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

// Load media file mappings
$media_files = json_decode(file_get_contents(__DIR__ . '/media_files.json'), TRUE);
$media_mapping = array();
foreach ($media_files as $media) {
  $media_mapping[$media['mid']] = $media;
}

// Import nodes from JSON
$nodes_data = json_decode(file_get_contents(__DIR__ . '/nodes.json'), TRUE);

foreach ($nodes_data as $node_data) {
  $node = entity_create('node', array(
    'type' => $node_data['type'],
    'title' => $node_data['title'],
    'language' => $node_data['langcode'],
    'uid' => 1, // Admin user
    'status' => $node_data['status'] ? 1 : 0,
    'promote' => $node_data['promote'] ? 1 : 0,
    'sticky' => $node_data['sticky'] ? 1 : 0,
    'created' => $node_data['created'],
    'changed' => $node_data['changed'],
  ));

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
      // Create file entries for images using real filenames from Drupal
      if (!empty($field_values[0]['target_id'])) {
        $media_id = $field_values[0]['target_id'];

        // Look up the media file info
        if (isset($media_mapping[$media_id])) {
          $media_info = $media_mapping[$media_id];
          $file_info = $media_info['file'];

          // Create a file record with the real filename
          $file = new stdClass();
          $file->uid = 1;
          $file->filename = $file_info['filename'];
          $file->uri = 'public://' . $file_info['filename'];
          $file->filemime = $file_info['filemime'];
          $file->filesize = 0; // Will be updated when actual file is copied
          $file->status = FILE_STATUS_PERMANENT;
          $file->timestamp = REQUEST_TIME;

          // Check if file already exists
          $existing = db_select('file_managed', 'f')
            ->fields('f', array('fid'))
            ->condition('uri', $file->uri)
            ->execute()
            ->fetchField();

          if ($existing) {
            $fid = $existing;
          } else {
            // Save the file record
            $fid = db_insert('file_managed')
              ->fields(array(
                'uid' => $file->uid,
                'filename' => $file->filename,
                'uri' => $file->uri,
                'filemime' => $file->filemime,
                'filesize' => $file->filesize,
                'status' => $file->status,
                'timestamp' => $file->timestamp,
              ))
              ->execute();
          }

          // Attach to node with proper alt text
          $node->{$field_name} = array(
            LANGUAGE_NONE => array(
              array(
                'fid' => $fid,
                'alt' => $media_info['alt'] ?: $node_data['title'],
                'title' => $media_info['title'] ?: '',
              ),
            ),
          );
          echo "Added image {$file_info['filename']} for node {$node_data['title']} (fid: {$fid})\n";
        } else {
          echo "Warning: Media ID {$media_id} not found in media mapping for node {$node_data['title']}\n";
        }
      }
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
