<?php

// Export nodes
$node_storage = \Drupal::entityTypeManager()->getStorage('node');
$nodes = $node_storage->loadMultiple();
$nodes_export = [];

foreach ($nodes as $node) {
  $data = [
    'nid' => $node->id(),
    'type' => $node->getType(),
    'title' => $node->getTitle(),
    'langcode' => $node->language()->getId(),
    'uid' => $node->getOwnerId(),
    'status' => $node->isPublished(),
    'created' => $node->getCreatedTime(),
    'changed' => $node->getChangedTime(),
    'promote' => $node->isPromoted(),
    'sticky' => $node->isSticky(),
    'fields' => [],
  ];

  foreach ($node->getFields() as $field_name => $field) {
    if (in_array($field_name, ['nid', 'type', 'title', 'langcode', 'uid', 'status', 'created', 'changed', 'promote', 'sticky', 'uuid', 'revision_timestamp', 'revision_uid', 'revision_log', 'revision_translation_affected', 'default_langcode', 'revision_default', 'vid'])) {
      continue;
    }
    $val = $field->getValue();
    if (!empty($val)) {
      $data['fields'][$field_name] = $val;
    }
  }
  $nodes_export[] = $data;
}

file_put_contents(__DIR__ . '/exports/nodes.json', json_encode($nodes_export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported " . count($nodes_export) . " nodes\n";

// Export taxonomy terms
$term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
$terms = $term_storage->loadMultiple();
$terms_export = [];

foreach ($terms as $term) {
  $data = [
    'tid' => $term->id(),
    'vid' => $term->bundle(),
    'name' => $term->getName(),
    'description' => $term->getDescription(),
    'weight' => $term->getWeight(),
    'parent' => [],
    'langcode' => $term->language()->getId(),
    'fields' => [],
  ];

  // Get parent terms
  $parents = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($term->id());
  foreach ($parents as $parent) {
    $data['parent'][] = $parent->id();
  }

  // Get other fields
  foreach ($term->getFields() as $field_name => $field) {
    if (in_array($field_name, ['tid', 'vid', 'name', 'description', 'weight', 'parent', 'langcode', 'uuid', 'revision_id', 'revision_created', 'revision_user', 'revision_log_message', 'revision_default', 'revision_translation_affected', 'default_langcode', 'changed', 'status'])) {
      continue;
    }
    $val = $field->getValue();
    if (!empty($val)) {
      $data['fields'][$field_name] = $val;
    }
  }
  $terms_export[] = $data;
}

file_put_contents(__DIR__ . '/exports/taxonomy_terms.json', json_encode($terms_export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported " . count($terms_export) . " taxonomy terms\n";

// Export menu links
$menu_link_storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');
$menu_links = $menu_link_storage->loadMultiple();
$menu_links_export = [];

foreach ($menu_links as $link) {
  $menu_links_export[] = [
    'id' => $link->id(),
    'title' => $link->getTitle(),
    'link' => $link->link->uri,
    'menu_name' => $link->getMenuName(),
    'weight' => $link->getWeight(),
    'expanded' => $link->isExpanded(),
    'enabled' => $link->isEnabled(),
    'parent' => $link->getParentId(),
    'description' => $link->getDescription(),
  ];
}

file_put_contents(__DIR__ . '/exports/menu_links.json', json_encode($menu_links_export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported " . count($menu_links_export) . " menu links\n";

echo "Export complete!\n";
