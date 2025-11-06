# Backdrop Umami Demo Recipes

This directory contains recipes to recreate the Drupal Umami demo content in a Backdrop CMS site.

## Overview

The recipes are organized in the order they should be run:

1. **01-taxonomies** - Creates taxonomy vocabularies and imports terms
2. **02-content-types** - Creates content types (Article, Page, Recipe) and all their fields
3. **03-content** - Imports actual content (nodes)

## Usage

Each recipe directory contains:
- `install.php` - PHP script to execute the recipe
- Data files (JSON) - Contains the exported data from Drupal

### Running the Recipes

From your Backdrop site root, run each recipe in order using Drush or by including the script in a custom module:

```bash
# Method 1: Using Drush
drush php-script ../drupal/backdrop_recipes/01-taxonomies/install.php
drush php-script ../drupal/backdrop_recipes/02-content-types/install.php
drush php-script ../drupal/backdrop_recipes/03-content/install.php

# Method 2: Via PHP (from Backdrop root)
cd /path/to/backdrop
php -d memory_limit=512M scripts/run-recipe.php ../drupal/backdrop_recipes/01-taxonomies/install.php
```

### Prerequisites

- Backdrop CMS installed and configured
- Database set up
- Basic modules enabled (taxonomy, field, node, image, number, list, text)

## Content Structure

### Taxonomies
- **Tags** (56 terms) - General tagging vocabulary
- **Recipe Category** (10 terms) - Recipe-specific categories

### Content Types

#### Article
- Body (text with summary)
- Tags (taxonomy reference)
- Image (image field)

#### Page
- Body (text with summary)

#### Recipe
- Summary (long text)
- Image (image field)
- Tags (taxonomy reference)
- Recipe Category (taxonomy reference)
- Difficulty (list: easy/medium/hard)
- Preparation Time (integer, minutes)
- Cooking Time (integer, minutes)
- Number of Servings (integer)
- Ingredients (multi-value text)
- Recipe Instructions (multi-value long text)

### Content
- 19 nodes total:
  - Recipe nodes
  - Article nodes
  - Page nodes

## Notes

- Media/images are not included in the import. You'll need to manually add images or modify the scripts to handle media migration.
- User IDs are mapped to user 1 (admin) by default
- Language is set to English (en)
- Some Drupal-specific features (like layout_builder, moderation_state) are skipped as they don't have direct Backdrop equivalents

## Troubleshooting

If you encounter errors:

1. Make sure all required modules are enabled
2. Clear cache: `drush cc all`
3. Check file permissions on the recipe directories
4. Increase PHP memory limit if needed: `php -d memory_limit=512M ...`

## Customization

You can modify the JSON data files to:
- Change content
- Add/remove fields
- Adjust taxonomy terms
- Modify field settings in the install.php scripts
