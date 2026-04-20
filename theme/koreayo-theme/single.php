<?php
/**
 * Single Post Template
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();
?>

<main class="site-main">
  <?php while (have_posts()) : the_post(); ?>

  <!-- Article Header -->
  <header class="article-header">
    <div class="container">
      <div class="article-header-content">
        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
        ?>
        <div class="article-categories">
          <?php foreach ($categories as $cat) : ?>
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="article-category">
              <?php echo esc_html($cat->name); ?>
            </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h1 class="article-title"><?php the_title(); ?></h1>

        <?php if (has_excerpt()) : ?>
        <p class="article-excerpt"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>

        <div class="article-meta">
          <div class="article-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Last updated: <?php echo get_the_modified_date(); ?></span>
          </div>
          <div class="article-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span><?php echo koreayo_reading_time(); ?> min read</span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Featured Image -->
  <?php if (has_post_thumbnail()) : ?>
  <div class="article-featured-image">
    <div class="container">
      <?php the_post_thumbnail('koreayo-featured'); ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Article Content -->
  <article class="article-content-wrapper">
    <div class="container">
      <div class="content-with-sidebar">
        <div class="article-content">
          <?php the_content(); ?>

          <!-- Tags -->
          <?php
          $tags = get_the_tags();
          if ($tags) :
          ?>
          <div class="article-tags">
            <span class="article-tags-label">Tags:</span>
            <?php foreach ($tags as $tag) : ?>
              <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="article-tag">
                <?php echo esc_html($tag->name); ?>
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Author Box -->
          <div class="author-box">
            <div class="author-avatar">
              <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
            </div>
            <div class="author-info">
              <span class="author-label">Written by</span>
              <h4 class="author-name"><?php the_author(); ?></h4>
              <p class="author-bio"><?php echo get_the_author_meta('description'); ?></p>
            </div>
          </div>

          <!-- Related Posts -->
          <?php
          $related_args = array(
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'category__in' => wp_get_post_categories(get_the_ID()),
            'orderby' => 'rand'
          );
          $related_query = new WP_Query($related_args);

          if ($related_query->have_posts()) :
          ?>
          <section class="related-posts">
            <h3 class="related-posts-title">Related Guides</h3>
            <div class="related-posts-grid">
              <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
              <article class="related-post-card">
                <?php if (has_post_thumbnail()) : ?>
                <div class="related-post-image">
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('koreayo-card'); ?></a>
                </div>
                <?php endif; ?>
                <div class="related-post-content">
                  <h4 class="related-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h4>
                  <span class="related-post-date"><?php echo get_the_date(); ?></span>
                </div>
              </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </section>
          <?php endif; ?>

        </div>

        <!-- Sidebar -->
        <aside class="sidebar article-sidebar">
          <!-- Table of Contents (if applicable) -->
          <div class="sidebar-widget toc-widget" id="toc-widget">
            <h3 class="sidebar-widget-title">Table of Contents</h3>
            <nav class="toc-nav" id="toc-nav">
              <!-- Populated by JavaScript -->
            </nav>
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

          <!-- Popular Posts -->
          <div class="sidebar-widget">
            <h3 class="sidebar-widget-title">Popular Guides</h3>
            <div class="popular-posts-list">
              <?php
              $popular_args = array(
                'posts_per_page' => 4,
                'post__not_in' => array(get_the_ID()),
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
        </aside>
      </div>
    </div>
  </article>

  <?php endwhile; ?>
</main>

<style>
/* Single Post Specific Styles */
.article-header {
  background: var(--color-gray-50);
  padding: var(--spacing-12) 0;
}

.article-header-content {
  max-width: 800px;
}

.article-categories {
  display: flex;
  gap: var(--spacing-2);
  margin-bottom: var(--spacing-4);
}

.article-category {
  display: inline-block;
  padding: var(--spacing-1) var(--spacing-3);
  background: var(--color-primary);
  color: var(--color-white);
  font-size: var(--text-xs);
  font-weight: 600;
  text-transform: uppercase;
  border-radius: var(--radius-full);
}

.article-title {
  font-family: var(--font-heading);
  font-size: var(--text-4xl);
  font-weight: 700;
  color: var(--color-gray-900);
  line-height: 1.2;
  margin-bottom: var(--spacing-4);
}

.article-excerpt {
  font-size: var(--text-xl);
  color: var(--color-gray-600);
  line-height: 1.6;
  margin-bottom: var(--spacing-6);
}

.article-meta {
  display: flex;
  gap: var(--spacing-6);
  color: var(--color-gray-500);
  font-size: var(--text-sm);
}

.article-meta-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
}

.article-featured-image {
  margin-bottom: var(--spacing-8);
}

.article-featured-image img {
  width: 100%;
  max-width: 900px;
  height: auto;
  border-radius: var(--radius-lg);
}

.article-content-wrapper {
  padding: var(--spacing-8) 0 var(--spacing-16);
}

.article-content {
  font-size: var(--text-lg);
  line-height: 1.8;
  color: var(--color-gray-700);
}

.article-content h2 {
  font-family: var(--font-heading);
  font-size: var(--text-2xl);
  font-weight: 600;
  color: var(--color-gray-900);
  margin: var(--spacing-10) 0 var(--spacing-4);
  padding-left: var(--spacing-4);
  border-left: 4px solid var(--color-primary);
}

.article-content h3 {
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 600;
  color: var(--color-gray-800);
  margin: var(--spacing-8) 0 var(--spacing-3);
}

.article-content p {
  margin-bottom: var(--spacing-5);
}

.article-content ul,
.article-content ol {
  margin: var(--spacing-5) 0 var(--spacing-5) var(--spacing-6);
  padding-left: var(--spacing-4);
}

.article-content li {
  margin-bottom: var(--spacing-2);
}

.article-content a {
  color: var(--color-primary);
  text-decoration: underline;
  text-underline-offset: 2px;
}

.article-content a:hover {
  color: var(--color-primary-dark);
}

.article-content blockquote {
  background: var(--color-gray-50);
  border-left: 4px solid var(--color-primary);
  padding: var(--spacing-5) var(--spacing-6);
  margin: var(--spacing-6) 0;
  border-radius: 0 var(--radius-md) var(--radius-md) 0;
  font-style: italic;
  color: var(--color-gray-600);
}

.article-content img {
  border-radius: var(--radius-md);
  margin: var(--spacing-6) 0;
}

/* Tags */
.article-tags {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: var(--spacing-2);
  margin-top: var(--spacing-10);
  padding-top: var(--spacing-6);
  border-top: 1px solid var(--color-gray-200);
}

.article-tags-label {
  font-weight: 600;
  color: var(--color-gray-700);
  margin-right: var(--spacing-2);
}

.article-tag {
  display: inline-block;
  padding: var(--spacing-1) var(--spacing-3);
  background: var(--color-gray-100);
  color: var(--color-gray-600);
  font-size: var(--text-sm);
  border-radius: var(--radius-full);
  transition: all var(--transition-fast);
}

.article-tag:hover {
  background: var(--color-primary);
  color: var(--color-white);
}

/* Author Box */
.author-box {
  display: flex;
  gap: var(--spacing-5);
  padding: var(--spacing-6);
  background: var(--color-gray-50);
  border-radius: var(--radius-lg);
  margin-top: var(--spacing-10);
}

.author-avatar img {
  width: 80px;
  height: 80px;
  border-radius: var(--radius-full);
}

.author-label {
  font-size: var(--text-sm);
  color: var(--color-gray-500);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.author-name {
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 600;
  color: var(--color-gray-900);
  margin: var(--spacing-1) 0 var(--spacing-2);
}

.author-bio {
  font-size: var(--text-sm);
  color: var(--color-gray-600);
  line-height: 1.6;
  margin: 0;
}

/* Related Posts */
.related-posts {
  margin-top: var(--spacing-12);
  padding-top: var(--spacing-8);
  border-top: 1px solid var(--color-gray-200);
}

.related-posts-title {
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 600;
  color: var(--color-gray-900);
  margin-bottom: var(--spacing-6);
}

.related-posts-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--spacing-5);
}

.related-post-card {
  background: var(--color-white);
  border: 1px solid var(--color-gray-200);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all var(--transition-base);
}

.related-post-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.related-post-image {
  aspect-ratio: 16/10;
  overflow: hidden;
}

.related-post-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  margin: 0;
  border-radius: 0;
}

.related-post-content {
  padding: var(--spacing-4);
}

.related-post-title {
  font-size: var(--text-base);
  font-weight: 600;
  color: var(--color-gray-800);
  line-height: 1.4;
  margin-bottom: var(--spacing-2);
}

.related-post-title a {
  color: inherit;
  text-decoration: none;
}

.related-post-title a:hover {
  color: var(--color-primary);
}

.related-post-date {
  font-size: var(--text-xs);
  color: var(--color-gray-500);
}

/* Article Sidebar */
.article-sidebar {
  position: sticky;
  top: 90px;
  height: fit-content;
}

/* Table of Contents */
.toc-nav {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2);
}

.toc-nav a {
  display: block;
  padding: var(--spacing-2) var(--spacing-3);
  font-size: var(--text-sm);
  color: var(--color-gray-600);
  border-left: 2px solid var(--color-gray-200);
  transition: all var(--transition-fast);
}

.toc-nav a:hover,
.toc-nav a.active {
  color: var(--color-primary);
  border-left-color: var(--color-primary);
  background: var(--color-gray-50);
}

.toc-nav a.toc-h3 {
  padding-left: var(--spacing-6);
  font-size: var(--text-xs);
}

/* Responsive */
@media (max-width: 1024px) {
  .article-sidebar {
    display: none;
  }

  .article-content-wrapper .content-with-sidebar {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .article-title {
    font-size: var(--text-2xl);
  }

  .article-excerpt {
    font-size: var(--text-base);
  }

  .related-posts-grid {
    grid-template-columns: 1fr;
  }

  .author-box {
    flex-direction: column;
    text-align: center;
  }
}
</style>

<script>
// Generate Table of Contents
document.addEventListener('DOMContentLoaded', function() {
  const articleContent = document.querySelector('.article-content');
  const tocNav = document.getElementById('toc-nav');
  const tocWidget = document.getElementById('toc-widget');

  if (!articleContent || !tocNav) return;

  const headings = articleContent.querySelectorAll('h2, h3');

  if (headings.length < 3) {
    // Hide TOC if less than 3 headings
    if (tocWidget) tocWidget.style.display = 'none';
    return;
  }

  headings.forEach(function(heading, index) {
    // Add ID to heading
    const id = 'heading-' + index;
    heading.id = id;

    // Create TOC link
    const link = document.createElement('a');
    link.href = '#' + id;
    link.textContent = heading.textContent;
    link.className = 'toc-' + heading.tagName.toLowerCase();

    tocNav.appendChild(link);
  });

  // Highlight active TOC item on scroll
  const tocLinks = tocNav.querySelectorAll('a');

  function updateActiveTOC() {
    let activeIndex = 0;

    headings.forEach(function(heading, index) {
      const rect = heading.getBoundingClientRect();
      if (rect.top <= 120) {
        activeIndex = index;
      }
    });

    tocLinks.forEach(function(link, index) {
      if (index === activeIndex) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
  }

  window.addEventListener('scroll', updateActiveTOC);
  updateActiveTOC();
});
</script>

<?php get_footer(); ?>
