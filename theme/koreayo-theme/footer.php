<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Brand Column -->
      <div class="footer-brand">
        <div class="footer-logo">Korea<span>yo</span></div>
        <p class="footer-description">
          Practical guides and resources for foreigners living in Korea. From visas and banking to housing and healthcare — we help you navigate life in Korea with confidence.
        </p>
        <div class="footer-social">
          <a href="#" aria-label="Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
          </a>
          <a href="#" aria-label="Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
            </svg>
          </a>
          <a href="#" aria-label="YouTube">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
              <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="white"></polygon>
            </svg>
          </a>
        </div>
      </div>

      <!-- Categories Column -->
      <div class="footer-column">
        <h4 class="footer-column-title">Categories</h4>
        <ul class="footer-links">
          <?php
          $footer_categories = array(
            'visa-legal' => 'Visa & Legal',
            'banking-money' => 'Banking & Money',
            'housing' => 'Housing',
            'healthcare' => 'Healthcare',
            'daily-life' => 'Daily Life',
            'travel' => 'Travel'
          );

          foreach ($footer_categories as $slug => $name) {
            $category = get_category_by_slug($slug);
            if ($category) {
              echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($name) . '</a></li>';
            }
          }
          ?>
        </ul>
      </div>

      <!-- Resources Column -->
      <div class="footer-column">
        <h4 class="footer-column-title">Resources</h4>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('start-here'))); ?>">Start Here</a></li>
          <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>">About Us</a></li>
          <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Contact</a></li>
          <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('newsletter'))); ?>">Newsletter</a></li>
          <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('advertise'))); ?>">Advertise</a></li>
        </ul>
      </div>

      <!-- Quick Links Column -->
      <div class="footer-column">
        <h4 class="footer-column-title">Popular Guides</h4>
        <ul class="footer-links">
          <?php
          $popular_posts = new WP_Query(array(
            'posts_per_page' => 5,
            'orderby' => 'comment_count',
            'order' => 'DESC'
          ));

          if ($popular_posts->have_posts()) :
            while ($popular_posts->have_posts()) : $popular_posts->the_post();
          ?>
          <li><a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 5); ?></a></li>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="footer-copyright">
        &copy; <?php echo date('Y'); ?> Koreayo. All rights reserved.
      </p>
      <div class="footer-legal">
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('privacy-policy'))); ?>">Privacy Policy</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('terms'))); ?>">Terms of Use</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('disclaimer'))); ?>">Disclaimer</a>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
