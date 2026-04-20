<?php
/**
 * Main Template - Homepage
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();
?>

<main class="site-main">

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-content">
        <div class="hero-text">
          <span class="hero-tagline">🇰🇷 Your Guide to Life in Korea</span>
          <h1 class="hero-title">Practical guides for foreigners living in Korea</h1>
          <p class="hero-description">
            From visas and banking to housing and healthcare — everything you need to navigate life in Korea with confidence.
          </p>
          <div class="hero-cta">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('start-here'))); ?>" class="btn btn-primary">Get Started</a>
            <a href="<?php echo esc_url(get_category_link(get_cat_ID('Visa & Legal'))); ?>" class="btn btn-outline">Explore Guides</a>
          </div>
        </div>

        <?php
        // Featured Post
        $featured_args = array(
          'posts_per_page' => 1,
          'meta_key' => '_is_featured',
          'meta_value' => '1',
          'post_status' => 'publish'
        );
        $featured_query = new WP_Query($featured_args);

        if (!$featured_query->have_posts()) {
          // Fallback to latest post
          $featured_args = array(
            'posts_per_page' => 1,
            'post_status' => 'publish'
          );
          $featured_query = new WP_Query($featured_args);
        }

        if ($featured_query->have_posts()) :
          while ($featured_query->have_posts()) : $featured_query->the_post();
            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : 'Guide';
        ?>
        <article class="hero-featured">
          <div class="hero-featured-image">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>
            <span class="hero-featured-category"><?php echo esc_html($category_name); ?></span>
          </div>
          <div class="hero-featured-content">
            <h2 class="hero-featured-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="hero-featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <div class="hero-featured-meta">
              <span><?php echo get_the_date(); ?></span>
              <span><?php echo koreayo_reading_time(); ?> min read</span>
            </div>
          </div>
        </article>
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
  </section>

  <!-- Category Navigation -->
  <nav class="category-nav">
    <div class="container">
      <div class="category-nav-inner">
        <?php
        $categories = array(
          'visa-legal' => array('icon' => '📋', 'name' => 'Visa & Legal'),
          'banking-money' => array('icon' => '💰', 'name' => 'Banking & Money'),
          'housing' => array('icon' => '🏠', 'name' => 'Housing'),
          'healthcare' => array('icon' => '🏥', 'name' => 'Healthcare'),
          'daily-life' => array('icon' => '🛒', 'name' => 'Daily Life'),
          'travel' => array('icon' => '✈️', 'name' => 'Travel'),
          'work' => array('icon' => '💼', 'name' => 'Work'),
          'culture' => array('icon' => '🎭', 'name' => 'Culture')
        );

        foreach ($categories as $slug => $cat) :
          $category = get_category_by_slug($slug);
          if ($category) :
        ?>
          <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
            <span class="category-nav-icon"><?php echo $cat['icon']; ?></span>
            <?php echo esc_html($cat['name']); ?>
          </a>
        <?php
          endif;
        endforeach;
        ?>
      </div>
    </div>
  </nav>

  <!-- Latest Articles Section -->
  <section class="section">
    <div class="container">
      <div class="content-with-sidebar">
        <div class="main-content">
          <header class="section-header">
            <h2 class="section-title">Latest Guides</h2>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('blog'))); ?>" class="section-link">
              View All <span>→</span>
            </a>
          </header>

          <div class="articles-grid">
            <?php
            $latest_args = array(
              'posts_per_page' => 6,
              'post_status' => 'publish',
              'offset' => 1 // Skip the featured post
            );
            $latest_query = new WP_Query($latest_args);
            $post_index = 0;

            if ($latest_query->have_posts()) :
              while ($latest_query->have_posts()) : $latest_query->the_post();
                $post_index++;
                $categories = get_the_category();
                $category_name = !empty($categories) ? $categories[0]->name : 'Guide';
                $is_featured = ($post_index === 1);
            ?>
            <article class="article-card <?php echo $is_featured ? 'article-card-featured' : ''; ?>">
              <div class="article-card-image">
                <?php if (has_post_thumbnail()) : ?>
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                <?php else : ?>
                  <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                  </a>
                <?php endif; ?>
                <span class="article-card-category"><?php echo esc_html($category_name); ?></span>
              </div>
              <div class="article-card-content">
                <h3 class="article-card-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <p class="article-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), $is_featured ? 30 : 15); ?></p>
                <div class="article-card-meta">
                  <span class="article-card-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="16" y1="2" x2="16" y2="6"></line>
                      <line x1="8" y1="2" x2="8" y2="6"></line>
                      <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo get_the_date(); ?>
                  </span>
                  <span class="article-card-reading-time">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo koreayo_reading_time(); ?> min
                  </span>
                </div>
              </div>
            </article>
            <?php
              endwhile;
              wp_reset_postdata();
            endif;
            ?>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
          <!-- Popular Posts Widget -->
          <div class="sidebar-widget">
            <h3 class="sidebar-widget-title">Popular Guides</h3>
            <div class="popular-posts-list">
              <?php
              $popular_args = array(
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'orderby' => 'comment_count',
                'order' => 'DESC'
              );
              $popular_query = new WP_Query($popular_args);

              if ($popular_query->have_posts()) :
                while ($popular_query->have_posts()) : $popular_query->the_post();
              ?>
              <div class="popular-post-item">
                <div class="popular-post-image">
                  <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                  <?php endif; ?>
                </div>
                <div class="popular-post-content">
                  <h4 class="popular-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h4>
                  <span class="popular-post-date"><?php echo get_the_date(); ?></span>
                </div>
              </div>
              <?php
                endwhile;
                wp_reset_postdata();
              endif;
              ?>
            </div>
          </div>

          <!-- Newsletter Widget -->
          <div class="sidebar-widget newsletter-widget">
            <h3 class="sidebar-widget-title">Stay Updated</h3>
            <p class="newsletter-description">
              Get the latest Korea guides and tips delivered to your inbox.
            </p>
            <form class="newsletter-form" action="#" method="post">
              <input type="email" class="newsletter-input" placeholder="Your email address" required>
              <button type="submit" class="newsletter-button">Subscribe</button>
            </form>
          </div>

          <!-- Categories Widget -->
          <div class="sidebar-widget">
            <h3 class="sidebar-widget-title">Topics</h3>
            <ul class="footer-links">
              <?php
              $sidebar_categories = get_categories(array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 8
              ));

              foreach ($sidebar_categories as $cat) :
              ?>
              <li>
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                  <?php echo esc_html($cat->name); ?> (<?php echo $cat->count; ?>)
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <!-- Category Sections -->
  <?php
  $featured_categories = array(
    array('slug' => 'visa-legal', 'title' => 'Visa & Legal', 'icon' => '📋'),
    array('slug' => 'banking-money', 'title' => 'Banking & Money', 'icon' => '💰'),
    array('slug' => 'housing', 'title' => 'Housing', 'icon' => '🏠')
  );

  foreach ($featured_categories as $feat_cat) :
    $category = get_category_by_slug($feat_cat['slug']);
    if ($category) :
      $cat_args = array(
        'posts_per_page' => 3,
        'cat' => $category->term_id,
        'post_status' => 'publish'
      );
      $cat_query = new WP_Query($cat_args);

      if ($cat_query->have_posts()) :
  ?>
  <section class="section" style="background: var(--color-gray-50);">
    <div class="container">
      <header class="section-header">
        <h2 class="section-title"><?php echo esc_html($feat_cat['title']); ?></h2>
        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="section-link">
          View All <span>→</span>
        </a>
      </header>

      <div class="articles-grid">
        <?php
        while ($cat_query->have_posts()) : $cat_query->the_post();
        ?>
        <article class="article-card">
          <div class="article-card-image">
            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
            <?php else : ?>
              <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
              </a>
            <?php endif; ?>
          </div>
          <div class="article-card-content">
            <h3 class="article-card-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="article-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            <div class="article-card-meta">
              <span class="article-card-date"><?php echo get_the_date(); ?></span>
              <span class="article-card-reading-time"><?php echo koreayo_reading_time(); ?> min read</span>
            </div>
          </div>
        </article>
        <?php
        endwhile;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>
  <?php
      endif;
    endif;
  endforeach;
  ?>

</main>

<?php get_footer(); ?>
