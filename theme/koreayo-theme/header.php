<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="container">
    <div class="header-inner">
      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
        <?php if (has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <span class="site-logo-text">Korea<span>yo</span></span>
        <?php endif; ?>
      </a>

      <!-- Main Navigation -->
      <nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'koreayo'); ?>">
        <?php
        if (has_nav_menu('primary')) {
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => '',
            'items_wrap' => '%3$s',
            'depth' => 1,
            'link_before' => '',
            'link_after' => ''
          ));
        } else {
          // Default menu items
          $default_categories = array(
            'visa-legal' => 'Visa & Legal',
            'banking-money' => 'Banking',
            'housing' => 'Housing',
            'healthcare' => 'Healthcare',
            'daily-life' => 'Daily Life'
          );

          foreach ($default_categories as $slug => $name) {
            $category = get_category_by_slug($slug);
            if ($category) {
              echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($name) . '</a>';
            }
          }
        }
        ?>
      </nav>

      <!-- Header Actions -->
      <div class="header-actions">
        <button class="search-toggle" aria-label="<?php esc_attr_e('Search', 'koreayo'); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </button>

        <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Menu', 'koreayo'); ?>" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu (Hidden by default) -->
  <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="container">
      <nav class="mobile-nav">
        <?php
        if (has_nav_menu('primary')) {
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'mobile-nav-list',
            'depth' => 2
          ));
        }
        ?>
      </nav>
    </div>
  </div>

  <!-- Search Modal (Hidden by default) -->
  <div class="search-modal" id="search-modal" aria-hidden="true">
    <div class="search-modal-inner">
      <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-input" placeholder="<?php esc_attr_e('Search guides...', 'koreayo'); ?>" value="<?php echo get_search_query(); ?>" name="s" autofocus>
        <button type="submit" class="search-submit">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </button>
      </form>
      <button class="search-close" aria-label="<?php esc_attr_e('Close search', 'koreayo'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
  </div>
</header>
