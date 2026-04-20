<?php
/**
 * 404 Error Page Template
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();
?>

<main class="site-main">
  <section class="error-404-section">
    <div class="container">
      <div class="error-404-content">
        <!-- Visual Element -->
        <div class="error-404-visual">
          <div class="error-404-number">404</div>
          <div class="error-404-illustration">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
              <!-- Map Pin Lost -->
              <circle cx="100" cy="100" r="80" fill="#EEF2FF" stroke="#C7D2FE" stroke-width="2"/>
              <path d="M100 50C78.5 50 61 67.5 61 89C61 119 100 150 100 150C100 150 139 119 139 89C139 67.5 121.5 50 100 50Z" fill="#3B82F6"/>
              <circle cx="100" cy="89" r="15" fill="white"/>
              <text x="100" y="95" text-anchor="middle" fill="#3B82F6" font-size="16" font-weight="bold">?</text>
              <!-- Dashed Path -->
              <path d="M40 160 Q70 140 100 160 Q130 180 160 160" stroke="#9CA3AF" stroke-width="2" stroke-dasharray="5 5" fill="none"/>
            </svg>
          </div>
        </div>

        <!-- Text Content -->
        <div class="error-404-text">
          <h1 class="error-404-title">Page Not Found</h1>
          <p class="error-404-description">
            Oops! It looks like you've wandered off the path. The page you're looking for doesn't exist or has been moved.
          </p>
        </div>

        <!-- Actions -->
        <div class="error-404-actions">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-blue btn-large">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Back to Home
          </a>
          <a href="javascript:history.back()" class="btn btn-outline-dark">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Go Back
          </a>
        </div>

        <!-- Search -->
        <div class="error-404-search">
          <p class="error-404-search-label">Or try searching for what you need:</p>
          <form role="search" method="get" class="search-form-404" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" class="search-input-404" placeholder="<?php esc_attr_e('Search guides...', 'koreayo'); ?>" name="s" required>
            <button type="submit" class="search-submit-404">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </button>
          </form>
        </div>

        <!-- Popular Pages -->
        <div class="error-404-suggestions">
          <h3>Popular Guides</h3>
          <div class="suggestion-links">
            <?php
            $popular_posts = new WP_Query(array(
              'posts_per_page' => 4,
              'orderby' => 'comment_count',
              'order' => 'DESC'
            ));

            if ($popular_posts->have_posts()) :
              while ($popular_posts->have_posts()) : $popular_posts->the_post();
            ?>
            <a href="<?php the_permalink(); ?>" class="suggestion-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              <?php the_title(); ?>
            </a>
            <?php
              endwhile;
              wp_reset_postdata();
            endif;
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<style>
/* 404 Section */
.error-404-section {
  min-height: 80vh;
  display: flex;
  align-items: center;
  padding: var(--spacing-16) 0;
  background: linear-gradient(180deg, var(--color-gray-50) 0%, var(--color-white) 100%);
}

.error-404-content {
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

/* Visual */
.error-404-visual {
  margin-bottom: var(--spacing-8);
}

.error-404-number {
  font-family: var(--font-heading);
  font-size: 8rem;
  font-weight: 800;
  color: var(--color-primary);
  line-height: 1;
  opacity: 0.1;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  pointer-events: none;
}

.error-404-illustration {
  position: relative;
  z-index: 1;
}

/* Text */
.error-404-text {
  margin-bottom: var(--spacing-8);
}

.error-404-title {
  font-family: var(--font-heading);
  font-size: var(--text-3xl);
  font-weight: 700;
  color: var(--color-gray-900);
  margin-bottom: var(--spacing-4);
}

.error-404-description {
  font-size: var(--text-lg);
  color: var(--color-gray-600);
  line-height: 1.7;
}

/* Actions */
.error-404-actions {
  display: flex;
  justify-content: center;
  gap: var(--spacing-4);
  margin-bottom: var(--spacing-10);
}

.btn-large {
  padding: var(--spacing-4) var(--spacing-8);
  font-size: var(--text-base);
}

.btn-outline-dark {
  background: transparent;
  border: 2px solid var(--color-gray-300);
  color: var(--color-gray-700);
}

.btn-outline-dark:hover {
  background: var(--color-gray-100);
  border-color: var(--color-gray-400);
  color: var(--color-gray-900);
}

/* Search */
.error-404-search {
  margin-bottom: var(--spacing-10);
}

.error-404-search-label {
  font-size: var(--text-sm);
  color: var(--color-gray-500);
  margin-bottom: var(--spacing-3);
}

.search-form-404 {
  display: flex;
  max-width: 400px;
  margin: 0 auto;
  border: 2px solid var(--color-gray-200);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: border-color var(--transition-fast);
}

.search-form-404:focus-within {
  border-color: var(--color-primary);
}

.search-input-404 {
  flex: 1;
  padding: var(--spacing-4);
  border: none;
  font-size: var(--text-base);
  background: var(--color-white);
}

.search-input-404:focus {
  outline: none;
}

.search-submit-404 {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  background: var(--color-primary);
  border: none;
  color: var(--color-white);
  cursor: pointer;
  transition: background var(--transition-fast);
}

.search-submit-404:hover {
  background: var(--color-primary-dark);
}

/* Suggestions */
.error-404-suggestions {
  padding-top: var(--spacing-8);
  border-top: 1px solid var(--color-gray-200);
}

.error-404-suggestions h3 {
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--color-gray-500);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: var(--spacing-4);
}

.suggestion-links {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2);
}

.suggestion-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-2);
  padding: var(--spacing-2) var(--spacing-4);
  color: var(--color-gray-700);
  font-size: var(--text-sm);
  border-radius: var(--radius-md);
  transition: all var(--transition-fast);
}

.suggestion-link:hover {
  background: var(--color-gray-100);
  color: var(--color-primary);
}

/* Responsive */
@media (max-width: 768px) {
  .error-404-number {
    font-size: 5rem;
  }

  .error-404-title {
    font-size: var(--text-2xl);
  }

  .error-404-actions {
    flex-direction: column;
  }

  .error-404-actions .btn {
    width: 100%;
  }
}
</style>

<?php get_footer(); ?>
