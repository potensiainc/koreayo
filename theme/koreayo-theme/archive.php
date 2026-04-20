<?php
/**
 * Archive Template (Category, Tag, Date, Author)
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();

$archive_title = '';
$archive_description = '';

if (is_category()) {
    $archive_title = single_cat_title('', false);
    $archive_description = category_description();
} elseif (is_tag()) {
    $archive_title = single_tag_title('', false);
    $archive_description = tag_description();
} elseif (is_author()) {
    $archive_title = get_the_author();
    $archive_description = get_the_author_meta('description');
} elseif (is_date()) {
    if (is_day()) {
        $archive_title = get_the_date();
    } elseif (is_month()) {
        $archive_title = get_the_date('F Y');
    } elseif (is_year()) {
        $archive_title = get_the_date('Y');
    }
}
?>

<main class="site-main">
  <!-- Archive Header -->
  <header class="archive-header">
    <div class="container">
      <div class="archive-header-content">
        <?php if (is_category()) : ?>
          <span class="archive-label">Category</span>
        <?php elseif (is_tag()) : ?>
          <span class="archive-label">Tag</span>
        <?php elseif (is_author()) : ?>
          <span class="archive-label">Author</span>
        <?php endif; ?>

        <h1 class="archive-title"><?php echo esc_html($archive_title); ?></h1>

        <?php if ($archive_description) : ?>
          <p class="archive-description"><?php echo wp_kses_post($archive_description); ?></p>
        <?php endif; ?>

        <div class="archive-meta">
          <span class="archive-count">
            <?php
            global $wp_query;
            printf(
              _n('%s article', '%s articles', $wp_query->found_posts, 'koreayo'),
              number_format_i18n($wp_query->found_posts)
            );
            ?>
          </span>
        </div>
      </div>
    </div>
  </header>

  <!-- Archive Content -->
  <section class="section">
    <div class="container">
      <div class="content-with-sidebar">
        <div class="main-content">
          <?php if (have_posts()) : ?>
            <div class="articles-grid">
              <?php
              $post_index = 0;
              while (have_posts()) : the_post();
                $post_index++;
                $categories = get_the_category();
                $category_name = !empty($categories) ? $categories[0]->name : 'Guide';
                $is_featured = ($post_index === 1 && is_category());
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
                  <?php if (!is_category()) : ?>
                    <span class="article-card-category"><?php echo esc_html($category_name); ?></span>
                  <?php endif; ?>
                </div>
                <div class="article-card-content">
                  <h2 class="article-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h2>
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
              <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="pagination">
              <?php
              the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> Previous',
                'next_text' => 'Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>'
              ));
              ?>
            </nav>

          <?php else : ?>
            <div class="no-results">
              <h2>No articles found</h2>
              <p>Sorry, there are no articles in this category yet. Check back soon!</p>
              <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-blue">Back to Home</a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
          <?php if (is_active_sidebar('sidebar-blog')) : ?>
            <?php dynamic_sidebar('sidebar-blog'); ?>
          <?php else : ?>
            <!-- Default Categories Widget -->
            <div class="sidebar-widget">
              <h3 class="sidebar-widget-title">Categories</h3>
              <ul class="footer-links">
                <?php
                $sidebar_categories = get_categories(array(
                  'orderby' => 'count',
                  'order' => 'DESC',
                  'number' => 10
                ));

                foreach ($sidebar_categories as $cat) :
                  $is_current = (is_category() && get_queried_object_id() === $cat->term_id);
                ?>
                <li>
                  <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" <?php echo $is_current ? 'class="current"' : ''; ?>>
                    <?php echo esc_html($cat->name); ?> (<?php echo $cat->count; ?>)
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Newsletter Widget -->
            <div class="sidebar-widget newsletter-widget">
              <h3 class="sidebar-widget-title">Stay Updated</h3>
              <p class="newsletter-description">
                Get the latest Korea guides delivered to your inbox.
              </p>
              <form class="newsletter-form" action="#" method="post">
                <input type="email" class="newsletter-input" placeholder="Your email" required>
                <button type="submit" class="newsletter-button">Subscribe</button>
              </form>
            </div>
          <?php endif; ?>
        </aside>
      </div>
    </div>
  </section>
</main>

<style>
/* Archive Header */
.archive-header {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  padding: var(--spacing-12) 0;
  color: var(--color-white);
}

.archive-header-content {
  max-width: 700px;
}

.archive-label {
  display: inline-block;
  padding: var(--spacing-1) var(--spacing-3);
  background: rgba(255, 255, 255, 0.2);
  border-radius: var(--radius-full);
  font-size: var(--text-sm);
  font-weight: 500;
  margin-bottom: var(--spacing-3);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.archive-title {
  font-family: var(--font-heading);
  font-size: var(--text-4xl);
  font-weight: 700;
  margin-bottom: var(--spacing-4);
}

.archive-description {
  font-size: var(--text-lg);
  opacity: 0.9;
  line-height: 1.7;
  margin-bottom: var(--spacing-4);
}

.archive-meta {
  font-size: var(--text-sm);
  opacity: 0.8;
}

/* Pagination */
.pagination {
  margin-top: var(--spacing-10);
  display: flex;
  justify-content: center;
}

.pagination .nav-links {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
}

.pagination a,
.pagination span {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
  padding: 0 var(--spacing-3);
  border-radius: var(--radius-md);
  font-size: var(--text-sm);
  font-weight: 500;
  transition: all var(--transition-fast);
}

.pagination a {
  background: var(--color-white);
  border: 1px solid var(--color-gray-200);
  color: var(--color-gray-700);
}

.pagination a:hover {
  background: var(--color-primary);
  border-color: var(--color-primary);
  color: var(--color-white);
}

.pagination .current {
  background: var(--color-primary);
  color: var(--color-white);
}

.pagination .prev,
.pagination .next {
  gap: var(--spacing-2);
}

/* No Results */
.no-results {
  text-align: center;
  padding: var(--spacing-16) var(--spacing-8);
  background: var(--color-gray-50);
  border-radius: var(--radius-lg);
}

.no-results h2 {
  font-family: var(--font-heading);
  font-size: var(--text-2xl);
  color: var(--color-gray-800);
  margin-bottom: var(--spacing-3);
}

.no-results p {
  color: var(--color-gray-600);
  margin-bottom: var(--spacing-6);
}

/* Sidebar Current Link */
.sidebar .current {
  color: var(--color-primary);
  font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
  .archive-title {
    font-size: var(--text-2xl);
  }
}
</style>

<?php get_footer(); ?>
