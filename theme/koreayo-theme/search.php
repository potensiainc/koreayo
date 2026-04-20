<?php
/**
 * Search Results Template
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();
?>

<main class="site-main">
  <!-- Search Header -->
  <header class="search-header">
    <div class="container">
      <div class="search-header-content">
        <span class="search-label">Search Results</span>
        <h1 class="search-title">
          <?php
          printf(
            esc_html__('Results for "%s"', 'koreayo'),
            '<span>' . get_search_query() . '</span>'
          );
          ?>
        </h1>
        <p class="search-count">
          <?php
          global $wp_query;
          printf(
            _n('%s result found', '%s results found', $wp_query->found_posts, 'koreayo'),
            number_format_i18n($wp_query->found_posts)
          );
          ?>
        </p>

        <!-- Search Form -->
        <form role="search" method="get" class="search-form-large" action="<?php echo esc_url(home_url('/')); ?>">
          <input type="search" class="search-input-large" placeholder="<?php esc_attr_e('Search guides...', 'koreayo'); ?>" value="<?php echo get_search_query(); ?>" name="s">
          <button type="submit" class="search-submit-large">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            Search
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Search Results -->
  <section class="section">
    <div class="container">
      <?php if (have_posts()) : ?>
        <div class="search-results-list">
          <?php while (have_posts()) : the_post(); ?>
          <article class="search-result-item">
            <?php if (has_post_thumbnail()) : ?>
            <div class="search-result-image">
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('koreayo-thumbnail'); ?></a>
            </div>
            <?php endif; ?>
            <div class="search-result-content">
              <?php
              $categories = get_the_category();
              if (!empty($categories)) :
              ?>
              <span class="search-result-category"><?php echo esc_html($categories[0]->name); ?></span>
              <?php endif; ?>

              <h2 class="search-result-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>

              <p class="search-result-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
              </p>

              <div class="search-result-meta">
                <span class="search-result-date"><?php echo get_the_date(); ?></span>
                <span class="search-result-reading-time"><?php echo koreayo_reading_time(); ?> min read</span>
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
          <div class="no-results-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </div>
          <h2>No results found</h2>
          <p>Sorry, we couldn't find any articles matching "<strong><?php echo get_search_query(); ?></strong>". Try different keywords or browse our categories.</p>

          <div class="no-results-suggestions">
            <h3>Popular Categories</h3>
            <div class="suggestion-tags">
              <?php
              $popular_cats = get_categories(array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 6
              ));

              foreach ($popular_cats as $cat) :
              ?>
              <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="suggestion-tag">
                <?php echo esc_html($cat->name); ?>
              </a>
              <?php endforeach; ?>
            </div>
          </div>

          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-blue">Back to Home</a>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<style>
/* Search Header */
.search-header {
  background: linear-gradient(135deg, var(--color-gray-800) 0%, var(--color-gray-900) 100%);
  padding: var(--spacing-12) 0;
  color: var(--color-white);
}

.search-header-content {
  max-width: 700px;
  text-align: center;
  margin: 0 auto;
}

.search-label {
  display: inline-block;
  padding: var(--spacing-1) var(--spacing-3);
  background: rgba(255, 255, 255, 0.1);
  border-radius: var(--radius-full);
  font-size: var(--text-sm);
  font-weight: 500;
  margin-bottom: var(--spacing-3);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.search-title {
  font-family: var(--font-heading);
  font-size: var(--text-3xl);
  font-weight: 700;
  margin-bottom: var(--spacing-2);
}

.search-title span {
  color: var(--color-primary-light);
}

.search-count {
  font-size: var(--text-base);
  opacity: 0.8;
  margin-bottom: var(--spacing-6);
}

/* Large Search Form */
.search-form-large {
  display: flex;
  gap: var(--spacing-2);
  max-width: 500px;
  margin: 0 auto;
}

.search-input-large {
  flex: 1;
  padding: var(--spacing-4);
  border: none;
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  background: var(--color-white);
  color: var(--color-gray-800);
}

.search-input-large::placeholder {
  color: var(--color-gray-500);
}

.search-submit-large {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  padding: var(--spacing-4) var(--spacing-6);
  background: var(--color-primary);
  color: var(--color-white);
  border: none;
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  font-weight: 600;
  cursor: pointer;
  transition: background var(--transition-fast);
}

.search-submit-large:hover {
  background: var(--color-primary-dark);
}

/* Search Results List */
.search-results-list {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-6);
}

.search-result-item {
  display: flex;
  gap: var(--spacing-5);
  padding: var(--spacing-5);
  background: var(--color-white);
  border: 1px solid var(--color-gray-200);
  border-radius: var(--radius-lg);
  transition: all var(--transition-base);
}

.search-result-item:hover {
  box-shadow: var(--shadow-md);
  border-color: var(--color-gray-300);
}

.search-result-image {
  flex-shrink: 0;
  width: 180px;
  height: 120px;
  border-radius: var(--radius-md);
  overflow: hidden;
}

.search-result-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.search-result-content {
  flex: 1;
  min-width: 0;
}

.search-result-category {
  display: inline-block;
  padding: var(--spacing-1) var(--spacing-2);
  background: var(--color-primary);
  color: var(--color-white);
  font-size: var(--text-xs);
  font-weight: 600;
  text-transform: uppercase;
  border-radius: var(--radius-sm);
  margin-bottom: var(--spacing-2);
}

.search-result-title {
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 600;
  color: var(--color-gray-900);
  line-height: 1.4;
  margin-bottom: var(--spacing-2);
}

.search-result-title a {
  color: inherit;
  text-decoration: none;
}

.search-result-title a:hover {
  color: var(--color-primary);
}

.search-result-excerpt {
  font-size: var(--text-sm);
  color: var(--color-gray-600);
  line-height: 1.6;
  margin-bottom: var(--spacing-3);
}

.search-result-meta {
  display: flex;
  gap: var(--spacing-4);
  font-size: var(--text-xs);
  color: var(--color-gray-500);
}

/* No Results */
.no-results {
  text-align: center;
  padding: var(--spacing-16) var(--spacing-8);
  max-width: 600px;
  margin: 0 auto;
}

.no-results-icon {
  color: var(--color-gray-300);
  margin-bottom: var(--spacing-6);
}

.no-results h2 {
  font-family: var(--font-heading);
  font-size: var(--text-2xl);
  color: var(--color-gray-800);
  margin-bottom: var(--spacing-3);
}

.no-results p {
  color: var(--color-gray-600);
  margin-bottom: var(--spacing-8);
}

.no-results-suggestions {
  margin-bottom: var(--spacing-8);
}

.no-results-suggestions h3 {
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--color-gray-700);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: var(--spacing-4);
}

.suggestion-tags {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: var(--spacing-2);
}

.suggestion-tag {
  display: inline-block;
  padding: var(--spacing-2) var(--spacing-4);
  background: var(--color-gray-100);
  color: var(--color-gray-700);
  font-size: var(--text-sm);
  border-radius: var(--radius-full);
  transition: all var(--transition-fast);
}

.suggestion-tag:hover {
  background: var(--color-primary);
  color: var(--color-white);
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

/* Responsive */
@media (max-width: 768px) {
  .search-title {
    font-size: var(--text-xl);
  }

  .search-form-large {
    flex-direction: column;
  }

  .search-result-item {
    flex-direction: column;
  }

  .search-result-image {
    width: 100%;
    height: 180px;
  }
}
</style>

<?php get_footer(); ?>
