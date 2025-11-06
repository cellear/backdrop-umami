<?php
/**
 * Export media entity to file mapping for Umami content.
 * Run this from the Drupal root directory.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// Bootstrap Drupal
chdir(__DIR__ . '/web');
$autoloader = require_once __DIR__ . '/vendor/autoload.php';

$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();

// Set the container so \Drupal static methods work
\Drupal::setContainer($kernel->getContainer());

$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$media_items = $media_storage->loadMultiple();

$export = [];

foreach ($media_items as $media) {
  $data = [
    'mid' => $media->id(),
    'name' => $media->getName(),
    'bundle' => $media->bundle(),
  ];

  if ($media->hasField('field_media_image')) {
    $image_field = $media->get('field_media_image');
    if (!$image_field->isEmpty()) {
      $file = $image_field->entity;
      if ($file) {
        $data['file'] = [
          'fid' => $file->id(),
          'uri' => $file->getFileUri(),
          'filename' => $file->getFilename(),
          'filemime' => $file->getMimeType(),
          'filesize' => $file->getSize(),
        ];

        // Get alt and title from the image field
        $data['alt'] = $image_field->alt;
        $data['title'] = $image_field->title;
      }
    }
  }

  $export[] = $data;
}

echo json_encode($export, JSON_PRETTY_PRINT);
