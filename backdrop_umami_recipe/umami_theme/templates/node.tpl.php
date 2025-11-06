<?php
/**
 * @file
 * Default theme implementation to display a node.
 */
?>
<article<?php print backdrop_attributes($attributes); ?>>

  <?php print $user_picture; ?>

  <?php if (!$page): ?>
    <h2>
      <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
    </h2>
  <?php endif; ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content">
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <?php print render($content['links']); ?>
  <?php print render($content['comments']); ?>

</article>
