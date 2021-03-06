<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */
?>

<div id="page">

  <header class="header" id="header" role="banner">
    <div class="full-line"></div>
    <div class="steps-bg"></div>
    <div class="header-content clearfix">
      <div class="header-center clearfix">
        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="header__logo" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="header__logo-image" /></a>
        <?php endif; ?>

        <?php if ($site_name || $site_slogan): ?>
          <div class="header__name-and-slogan" id="name-and-slogan">
            <?php if ($site_name): ?>
              <h1 class="header__site-name" id="site-name">
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" class="header__site-link" rel="home"><span><?php print $site_name; ?></span></a>
              </h1>
            <?php endif; ?>

            <?php if ($site_slogan): ?>
              <div class="header__site-slogan" id="site-slogan"><?php print $site_slogan; ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php print render($page['header']); ?>
      </div>
    </div>
    <div class="steps-bg"></div>
    <div class="full-line"></div>
  </header>
  <div id="page-content-wrapper" class="clearfix">
    <div id="page-content">
      <div id="main" class="clearfix">
        <?php
          $content_top = render($page['content_top']);
          if ($content_top):
        ?>
            <div id="content-top">
              <?php
                print $content_top;
              ?>
            </div>
        <?php
          endif;
        ?>
        <div id="content" class="column" role="main">
          <!-- <?php print render($page['highlighted']); ?> -->
          <?php print $breadcrumb; ?>
          <!-- <a id="main-content"></a> -->
          <?php print render($title_prefix); ?>
          <?php if ($title && !$is_front): ?>
            <h1 class="page__title title" id="page-title"><?php print $title; ?></h1>
          <?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php print $messages; ?>
          <?php print render($tabs); ?>
          <!-- <?php print render($page['help']); ?> -->
          <?php if ($action_links): ?>
            <ul class="action-links"><?php print render($action_links); ?></ul>
          <?php endif; ?>
          <?php print render($page['content']); ?>
          <!-- <?php print $feed_icons; ?> -->
        </div>
        <?php
          // Render the sidebars to see if there's anything in them.
          $sidebar  = render($page['sidebar']);
        ?>
        <?php if ($sidebar): ?>
          <aside class="sidebars" id="sidebar">
            <?php print $sidebar; ?>
          </aside>
        <?php endif; ?>
      </div>
      <?php
        print render($page['content_bottom']);
      ?>
    </div>
  </div>
  <div class="footer">
    <div class="full-line"></div>
    <?php
        print render($page['footer']);
    ?>
    <div class="full-line"></div>
  </div>
</div>
