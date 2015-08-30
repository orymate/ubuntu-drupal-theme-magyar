<?php
// $Id$

/**
 * @file
 * Core layout for every page
 */

 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <!--[if gte IE 8]><! -->
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--<![endif]-->
    <!--<meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" />-->
  </head>
  <body <?php print phptemplate_body_attributes($is_front, $layout, $logged_in); ?>>
    <!-- Header -->
    <div id="header">
      <div class="container" style="width: <?php print pagewidth(); ?>;">
        <?php print $menu; ?>
        <?php print $logo; ?>
        <?php print $header; ?>
        <?php if ($search_box) { ?>
          <div class="block block-theme">
            <?php print $search_box ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <!-- Sub Header -->
    <?php if ($secondary_links or $subheader) { ?>
      <div id="subheader">
        <div class="container" style="width: <?php print pagewidth(); ?>;">
          <div class="container-inside">
            <?php if (isset($secondary_links)) { ?>
              <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
              <?php print $subheader; ?>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
    <!-- Content -->
    <div id="content">
      <div class="container" style="width: <?php print pagewidth(); ?>;">
        <?php print $above_content  ?>
        <div id="above_content_end"></div>
        <?php if ($show_messages) { print $messages; } ?>
        <?php print $help_text ?>
        <?php if ($left) { ?>
          <div id="sidebar-left" class="sidebar">
          <?php print $left ?>
          </div>
        <?php } ?>
       <?php if ($right) { ?>
          <div id="sidebar-right" class="sidebar">
            <?php print $right ?>
          </div>
        <?php } ?>
        <div id="center" <?php print phptemplate_body_class($left, $right); ?>>
          <?php print $top_content ?>
          <?php if ($title): ?>
            <h1><?php print $title ?></h1>
          <?php endif; ?>
          <?php if ($tabs) { print $tabs; } ?>
          <?php print $content; ?>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <!-- Footer -->
    <?php if (theme_get_setting('ubuntu_footer', TRUE)) { ?>
      <div id="footer">
        <div class="container" style="width: <?php print pagewidth(); ?>;">
          <?php print $footer; ?>
          <?php print $closure ?>
        </div>
      </div>
    <?php } ?>
  </body>
</html>
