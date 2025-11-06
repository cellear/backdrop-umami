<?php
/**
 * @file
 * Default theme implementation to display a recipe node.
 */
?>
<article<?php print backdrop_attributes($attributes); ?>>

  <?php if (empty($page)): ?>
    <h2>
      <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
    </h2>
  <?php endif; ?>

  <?php if (!empty($display_submitted)): ?>
    <div class="node__meta">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="node__content">
    <?php
      // Hide the links now so we can render them later.
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <?php if (!empty($content['links'])): ?>
    <div class="node__links">
      <?php print render($content['links']); ?>
    </div>
  <?php endif; ?>

</article>
