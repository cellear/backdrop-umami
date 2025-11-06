<?php
/**
 * @file
 * Front page template for Umami theme.
 */

// Query for promoted content to display
$query = new EntityFieldQuery();
$query->entityCondition('entity_type', 'node')
  ->propertyCondition('status', 1)
  ->propertyCondition('promote', 1)
  ->fieldOrderBy('field_media_image', 'fid', 'DESC')
  ->range(0, 10);
$result = $query->execute();

$promoted_nodes = array();
if (isset($result['node'])) {
  $promoted_nodes = node_load_multiple(array_keys($result['node']));
}

// Separate into hero and featured content
$hero_node = NULL;
$featured_nodes = array();
$recipe_nodes = array();

foreach ($promoted_nodes as $node) {
  if (!$hero_node && $node->type == 'recipe') {
    $hero_node = $node;
  } elseif (count($featured_nodes) < 3) {
    $featured_nodes[] = $node;
  } else {
    $recipe_nodes[] = $node;
  }
}
?>

<div class="layout-container">
  <header role="banner">
    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="site-logo">
        <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
      </a>
    <?php endif; ?>

    <?php if ($site_name): ?>
      <h1 class="site-name">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
          <?php print $site_name; ?>
        </a>
      </h1>
    <?php endif; ?>

    <?php print render($page['header']); ?>
  </header>

  <?php if ($hero_node): ?>
  <div class="hero-banner">
    <?php
      $hero_view = node_view($hero_node, 'teaser');
      $image_field = field_view_field('node', $hero_node, 'field_media_image', array(
        'label' => 'hidden',
        'type' => 'image',
        'settings' => array('image_style' => 'large'),
      ));
      $summary_field = field_view_field('node', $hero_node, 'field_summary', array('label' => 'hidden'));
    ?>
    <div class="hero-image">
      <?php if (!empty($image_field)): ?>
        <?php print render($image_field); ?>
      <?php endif; ?>
    </div>
    <div class="hero-content">
      <h2><?php print $hero_node->title; ?></h2>
      <?php if (!empty($summary_field)): ?>
        <div class="hero-summary"><?php print render($summary_field); ?></div>
      <?php endif; ?>
      <a href="<?php print url('node/' . $hero_node->nid); ?>" class="button">View recipe</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($featured_nodes)): ?>
  <section class="featured-content">
    <h2>Featured</h2>
    <div class="featured-cards">
      <?php foreach ($featured_nodes as $featured_node): ?>
        <article class="featured-card">
          <?php
            $card_image = field_view_field('node', $featured_node, 'field_media_image', array(
              'label' => 'hidden',
              'type' => 'image',
              'settings' => array('image_style' => 'medium'),
            ));
          ?>
          <?php if (!empty($card_image)): ?>
            <div class="card-image">
              <a href="<?php print url('node/' . $featured_node->nid); ?>">
                <?php print render($card_image); ?>
              </a>
            </div>
          <?php endif; ?>
          <div class="card-content">
            <h3>
              <a href="<?php print url('node/' . $featured_node->nid); ?>">
                <?php print $featured_node->title; ?>
              </a>
            </h3>
            <a href="<?php print url('node/' . $featured_node->nid); ?>" class="view-link">
              <?php print $featured_node->type == 'recipe' ? 'View recipe' : 'View article'; ?>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($recipe_nodes)): ?>
  <section class="recipe-grid">
    <h2>Explore recipes across every type of occasion, ingredient, and skill level</h2>
    <div class="recipe-cards">
      <?php foreach ($recipe_nodes as $recipe_node): ?>
        <article class="recipe-card">
          <?php
            $recipe_image = field_view_field('node', $recipe_node, 'field_media_image', array(
              'label' => 'hidden',
              'type' => 'image',
              'settings' => array('image_style' => 'medium'),
            ));
          ?>
          <?php if (!empty($recipe_image)): ?>
            <div class="card-image">
              <a href="<?php print url('node/' . $recipe_node->nid); ?>">
                <?php print render($recipe_image); ?>
              </a>
            </div>
          <?php endif; ?>
          <div class="card-content">
            <h3>
              <a href="<?php print url('node/' . $recipe_node->nid); ?>">
                <?php print $recipe_node->title; ?>
              </a>
            </h3>
            <a href="<?php print url('node/' . $recipe_node->nid); ?>" class="view-link">View recipe</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <section class="recipe-collections">
    <h2>Recipe collections</h2>
    <div class="collections-grid">
      <a href="/recipes?category=alcohol-free">Alcohol free</a>
      <a href="/recipes?category=cake">Cake</a>
      <a href="/recipes?category=dairy-free">Dairy-free</a>
      <a href="/recipes?category=egg">Egg</a>
      <a href="/recipes?category=baked">Baked</a>
      <a href="/recipes?category=carrots">Carrots</a>
      <a href="/recipes?category=dessert">Dessert</a>
      <a href="/recipes?category=grow-your-own">Grow your own</a>
      <a href="/recipes?category=baking">Baking</a>
      <a href="/recipes?category=chocolate">Chocolate</a>
      <a href="/recipes?category=dinner-party">Dinner party</a>
      <a href="/recipes?category=healthy">Healthy</a>
      <a href="/recipes?category=breakfast">Breakfast</a>
      <a href="/recipes?category=comfort-food">Comfort food</a>
      <a href="/recipes?category=drinks">Drinks</a>
      <a href="/recipes?category=herbs">Herbs</a>
    </div>
  </section>

  <footer role="contentinfo">
    <?php print render($page['footer']); ?>
  </footer>
</div>
