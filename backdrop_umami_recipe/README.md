# Backdrop Umami Recipe

A comprehensive recipe module that provides everything needed to create a food magazine website in Backdrop CMS, inspired by Drupal's Umami demo profile.

## What This Module Provides

### Content Types
- **Recipe** - For sharing cooking instructions with ingredients, preparation time, difficulty, etc.
- **Article** - For food magazine articles and blog posts
- **Page** - For static pages like "About"

### Taxonomies
- **Tags** - For categorizing content by topics
- **Recipe Category** - For organizing recipes by type

### Theme
- **Umami Theme** - A custom food magazine theme with:
  - Beautiful typography and color scheme
  - Responsive design
  - Recipe-specific styling
  - Card-based layouts

### Layouts
- **Umami Home Layout** - Custom homepage layout featuring:
  - Hero banner with featured recipe
  - Featured content cards
  - Recipe grid
  - Recipe collections footer

## Installation

1. **Download/Clone this module** to your Backdrop `modules/` directory:
   ```bash
   cd modules/
   git clone [repository-url] backdrop_umami_recipe
   ```

2. **Enable the module** at `admin/modules` or via Drush:
   ```bash
   drush en backdrop_umami_recipe -y
   ```

3. **Enable the Umami theme** at `admin/appearance`:
   - The theme will be copied to `themes/umami/`
   - Set it as the default theme

4. **Configure the homepage layout**:
   - Go to `admin/structure/layouts`
   - Edit the "Default Layout" or "Home" layout
   - Change the layout template to "Umami Home"
   - Save

5. **(Optional) Install sample content**:
   - Enable the "Umami Sample Content" sub-module
   - This will import 10 recipes, 8 articles, and all images

## Sample Content

The **Umami Sample Content** sub-module (`umami_sample_content/`) provides:
- 10 sample recipes with images
- 8 food magazine articles
- 1 About page
- Taxonomy terms for categories and tags
- Professional food photography

**Note:** To include images in the sample content, you need to copy image files to `modules/backdrop_umami_recipe/umami_sample_content/images/` before enabling the module. See the sample content README for details.

## Requirements

- Backdrop CMS 1.20.0 or higher
- PHP 7.2 or higher
- The following Backdrop core modules:
  - Field
  - Image
  - Taxonomy
  - Node
  - Layout

## Configuration

### Content Types

After installation, you can customize the content types at:
- **Recipes**: `admin/structure/types/manage/recipe`
- **Articles**: `admin/structure/types/manage/article`
- **Pages**: `admin/structure/types/manage/page`

### Vocabularies

Manage taxonomy terms at:
- **Tags**: `admin/structure/taxonomy/tags`
- **Recipe Categories**: `admin/structure/taxonomy/recipe_category`

### Homepage

The homepage uses promoted content to display:
1. **Hero Banner**: First promoted recipe with image
2. **Featured Section**: Next 3 promoted items
3. **Recipe Grid**: Remaining promoted recipes
4. **Collections**: Static links to recipe categories

To feature content on the homepage, edit any recipe or article and check "Promoted to front page".

## Uninstallation

When you uninstall this module:
- Content types and their configurations will be removed
- The theme and layout will remain (delete manually if needed)
- Existing content will be deleted (backup first!)

## Credits

This recipe is inspired by the Umami demo profile from Drupal core, adapted for Backdrop CMS.

Content and images are sample data for demonstration purposes.

## License

GPL v2 or later

## Support

For issues, feature requests, or contributions, please visit:
[Repository URL]
