# Umami Sample Content

This module provides sample recipes, articles, and images for the Backdrop Umami food magazine site.

## What's Included

- **10 Recipes** including:
  - Deep Mediterranean Quiche
  - Vegan Chocolate and Nut Brownies
  - Thai Green Curry
  - Victoria Sponge Cake
  - And more...

- **8 Articles** about:
  - Growing your own herbs
  - Baking tips and troubleshooting
  - Seasonal ingredients
  - And more...

- **1 Page**: About Umami

- **Taxonomy Terms**: Categories and tags for organizing content

- **Images**: Professional food photography for all recipes and articles

## Installation

### Prerequisites

1. **Backdrop Umami Recipe module must be installed first**
   ```bash
   drush en backdrop_umami_recipe -y
   ```

2. **(Optional but Recommended) Copy image files**

   For the best experience, copy the actual image files from the Drupal Umami demo or your own images to:
   ```
   modules/backdrop_umami_recipe/umami_sample_content/images/
   ```

   The module expects these image files (see `data/media_files.json` for the complete list):
   - `mediterranean-quiche-umami.jpg`
   - `vegan-chocolate-nut-brownies.jpg`
   - `oatmeal-fruit-syrup-topping.jpg`
   - And more...

   If images are not provided, the content will still be imported but images will show as broken links.

### Enable the Module

Enable via the admin interface at `admin/modules` or use Drush:

```bash
drush en umami_sample_content -y
```

The module will automatically:
1. Import all taxonomy terms
2. Copy image files from the module directory to `files/`
3. Create 19 content nodes with all field data

## After Installation

1. **Visit the homepage** at `/` to see the featured recipes and articles

2. **View all recipes** at `/recipes` (if you've created a view)

3. **Mark content as promoted** to feature it on the homepage:
   - Edit any recipe or article
   - Check "Promoted to front page"
   - Save

4. **Customize content**:
   - All imported content is fully editable
   - Add your own recipes and articles
   - Upload your own images

## Data Files

The sample content is stored in JSON format in the `data/` directory:

- `nodes.json` - All recipe, article, and page content
- `media_files.json` - Image file metadata and references
- `taxonomy_terms.json` - Category and tag terms

These files were exported from a Drupal Umami installation and adapted for Backdrop.

## Adding Images

If you want to add the actual image files after installation:

1. Copy images to `sites/default/files/`
2. The filenames must match those in `data/media_files.json`
3. Clear the cache: `drush cc all`

Example image files needed:
```
sites/default/files/mediterranean-quiche-umami.jpg
sites/default/files/vegan-chocolate-nut-brownies.jpg
sites/default/files/thai-green-curry.jpg
...
```

You can obtain these images from:
- A Drupal Umami installation at `web/sites/default/files/`
- Stock photo websites
- Your own photography

## Uninstallation

When you uninstall this module:
- All imported content (recipes, articles, pages) will be deleted
- Taxonomy terms will remain
- Image files will remain in `sites/default/files/`

**Important**: Back up your content before uninstalling!

## Customization

Feel free to:
- Modify the content after import
- Add your own recipes and articles
- Change images
- Adjust taxonomy terms
- Use as a starting point for your food blog or magazine

## Credits

Sample content adapted from the Drupal Umami demonstration profile.

Images are for demonstration purposes only.

## License

GPL v2 or later
