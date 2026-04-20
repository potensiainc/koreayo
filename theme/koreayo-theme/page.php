<?php
/**
 * Page Template
 *
 * @package Koreayo
 * @version 1.0.0
 */

get_header();
?>

<main class="site-main">
  <?php while (have_posts()) : the_post(); ?>

  <!-- Page Header -->
  <header class="page-header">
    <div class="container">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </header>

  <!-- Page Content -->
  <article class="page-content-wrapper">
    <div class="container">
      <div class="page-content">
        <?php the_content(); ?>
      </div>
    </div>
  </article>

  <?php endwhile; ?>
</main>

<style>
/* Page Header */
.page-header {
  background: var(--color-gray-50);
  padding: var(--spacing-10) 0;
  border-bottom: 1px solid var(--color-gray-200);
}

.page-title {
  font-family: var(--font-heading);
  font-size: var(--text-4xl);
  font-weight: 700;
  color: var(--color-gray-900);
  margin: 0;
}

/* Page Content */
.page-content-wrapper {
  padding: var(--spacing-12) 0;
}

.page-content {
  max-width: 800px;
  font-size: var(--text-lg);
  line-height: 1.8;
  color: var(--color-gray-700);
}

.page-content h2 {
  font-family: var(--font-heading);
  font-size: var(--text-2xl);
  font-weight: 600;
  color: var(--color-gray-900);
  margin: var(--spacing-10) 0 var(--spacing-4);
  padding-left: var(--spacing-4);
  border-left: 4px solid var(--color-primary);
}

.page-content h3 {
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 600;
  color: var(--color-gray-800);
  margin: var(--spacing-8) 0 var(--spacing-3);
}

.page-content p {
  margin-bottom: var(--spacing-5);
}

.page-content ul,
.page-content ol {
  margin: var(--spacing-5) 0 var(--spacing-5) var(--spacing-6);
  padding-left: var(--spacing-4);
}

.page-content li {
  margin-bottom: var(--spacing-2);
}

.page-content a {
  color: var(--color-primary);
  text-decoration: underline;
  text-underline-offset: 2px;
}

.page-content a:hover {
  color: var(--color-primary-dark);
}

.page-content img {
  border-radius: var(--radius-md);
  margin: var(--spacing-6) 0;
}

.page-content blockquote {
  background: var(--color-gray-50);
  border-left: 4px solid var(--color-primary);
  padding: var(--spacing-5) var(--spacing-6);
  margin: var(--spacing-6) 0;
  border-radius: 0 var(--radius-md) var(--radius-md) 0;
  font-style: italic;
  color: var(--color-gray-600);
}

/* Full Width Page Template */
.page-template-full-width .page-content {
  max-width: 100%;
}

/* Contact Form Styling */
.page-content .wpcf7-form {
  margin-top: var(--spacing-6);
}

.page-content .wpcf7-form label {
  display: block;
  font-weight: 500;
  color: var(--color-gray-700);
  margin-bottom: var(--spacing-2);
}

.page-content .wpcf7-form input[type="text"],
.page-content .wpcf7-form input[type="email"],
.page-content .wpcf7-form textarea {
  width: 100%;
  padding: var(--spacing-3) var(--spacing-4);
  border: 1px solid var(--color-gray-300);
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  transition: border-color var(--transition-fast);
  margin-bottom: var(--spacing-4);
}

.page-content .wpcf7-form input:focus,
.page-content .wpcf7-form textarea:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.page-content .wpcf7-form input[type="submit"] {
  background: var(--color-primary);
  color: var(--color-white);
  border: none;
  padding: var(--spacing-3) var(--spacing-8);
  font-size: var(--text-base);
  font-weight: 600;
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: background var(--transition-fast);
}

.page-content .wpcf7-form input[type="submit"]:hover {
  background: var(--color-primary-dark);
}

/* Responsive */
@media (max-width: 768px) {
  .page-title {
    font-size: var(--text-2xl);
  }

  .page-content {
    font-size: var(--text-base);
  }
}
</style>

<?php get_footer(); ?>
