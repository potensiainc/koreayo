<?php
/**
 * Koreayo Theme Functions
 *
 * @package Koreayo
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function koreayo_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add custom image sizes
    add_image_size('koreayo-featured', 1200, 630, true);
    add_image_size('koreayo-card', 600, 375, true);
    add_image_size('koreayo-thumbnail', 150, 150, true);

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'koreayo'),
        'footer' => __('Footer Menu', 'koreayo'),
        'footer-legal' => __('Footer Legal Menu', 'koreayo')
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height' => 80,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true
    ));

    // Add editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'koreayo_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function koreayo_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'koreayo-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Google Fonts - Pretendard & Poppins
    wp_enqueue_style(
        'koreayo-fonts',
        'https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css',
        array(),
        null
    );

    wp_enqueue_style(
        'koreayo-poppins',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // Main JavaScript
    wp_enqueue_script(
        'koreayo-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Localize script for AJAX
    wp_localize_script('koreayo-main', 'koreayoAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('koreayo_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'koreayo_enqueue_scripts');

/**
 * Register Widget Areas
 */
function koreayo_widgets_init() {
    register_sidebar(array(
        'name' => __('Blog Sidebar', 'koreayo'),
        'id' => 'sidebar-blog',
        'description' => __('Widgets in this area will appear in the blog sidebar.', 'koreayo'),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-widget-title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Footer Column 1', 'koreayo'),
        'id' => 'footer-1',
        'description' => __('First footer column.', 'koreayo'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-column-title">',
        'after_title' => '</h4>'
    ));

    register_sidebar(array(
        'name' => __('Footer Column 2', 'koreayo'),
        'id' => 'footer-2',
        'description' => __('Second footer column.', 'koreayo'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-column-title">',
        'after_title' => '</h4>'
    ));

    register_sidebar(array(
        'name' => __('Footer Column 3', 'koreayo'),
        'id' => 'footer-3',
        'description' => __('Third footer column.', 'koreayo'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-column-title">',
        'after_title' => '</h4>'
    ));
}
add_action('widgets_init', 'koreayo_widgets_init');

/**
 * Calculate Reading Time
 */
function koreayo_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute

    return max(1, $reading_time);
}

/**
 * Custom Excerpt Length
 */
function koreayo_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'koreayo_excerpt_length');

/**
 * Custom Excerpt More
 */
function koreayo_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'koreayo_excerpt_more');

/**
 * Add Featured Post Meta Box
 */
function koreayo_add_featured_meta_box() {
    add_meta_box(
        'koreayo_featured_post',
        __('Featured Post', 'koreayo'),
        'koreayo_featured_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'koreayo_add_featured_meta_box');

function koreayo_featured_meta_box_callback($post) {
    wp_nonce_field('koreayo_featured_nonce', 'koreayo_featured_nonce');
    $is_featured = get_post_meta($post->ID, '_is_featured', true);
    ?>
    <label>
        <input type="checkbox" name="koreayo_is_featured" value="1" <?php checked($is_featured, '1'); ?>>
        <?php _e('Display as featured post on homepage', 'koreayo'); ?>
    </label>
    <?php
}

function koreayo_save_featured_meta($post_id) {
    if (!isset($_POST['koreayo_featured_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['koreayo_featured_nonce'], 'koreayo_featured_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // If this post is being set as featured, unset all other featured posts
    if (isset($_POST['koreayo_is_featured'])) {
        // Clear all other featured posts
        $featured_posts = get_posts(array(
            'meta_key' => '_is_featured',
            'meta_value' => '1',
            'posts_per_page' => -1
        ));

        foreach ($featured_posts as $fp) {
            delete_post_meta($fp->ID, '_is_featured');
        }

        update_post_meta($post_id, '_is_featured', '1');
    } else {
        delete_post_meta($post_id, '_is_featured');
    }
}
add_action('save_post', 'koreayo_save_featured_meta');

/**
 * External/Affiliate Link Handler
 */
add_filter('the_content', function($content) {
    $home = home_url();
    $aff_domains = array('coupang.com', 'coupa.ng', 'amzn.to', 'amazon.co', 'link.cafe24.com');

    return preg_replace_callback('#<a\s+([^>]*href=[\'"][^\'"]+[\'"][^>]*)>#i', function($m) use ($home, $aff_domains) {
        $tag = $m[0];

        if (!preg_match('#href=[\'"]([^\'"]+)[\'"]#i', $tag, $h)) {
            return $tag;
        }

        $href = $h[1];
        $is_external = (strpos($href, $home) !== 0) && preg_match('#^https?://#', $href);
        $is_aff = false;

        foreach ($aff_domains as $d) {
            if (stripos($href, $d) !== false) {
                $is_aff = true;
                break;
            }
        }

        $rel = '';
        if (preg_match('#rel=[\'"]([^\'"]+)[\'"]#i', $tag, $r)) {
            $rel = strtolower($r[1]);
        }
        $rels = array_filter(array_unique(preg_split('/\s+/', $rel)));

        if ($is_external) {
            $rels = array_unique(array_merge($rels, array('noopener', 'noreferrer')));
            if ($is_aff) {
                $rels = array_unique(array_merge($rels, array('sponsored', 'nofollow')));
            }
            if (!preg_match('#target=#i', $tag)) {
                $tag = str_replace('<a ', '<a target="_blank" ', $tag);
            }
        }

        $rel_str = implode(' ', $rels);
        if (preg_match('#rel=[\'"][^\'"]+[\'"]#i', $tag)) {
            $tag = preg_replace('#rel=[\'"][^\'"]+[\'"]#i', 'rel="' . $rel_str . '"', $tag);
        } elseif ($rel_str) {
            $tag = str_replace('<a ', '<a rel="' . $rel_str . '" ', $tag);
        }

        return $tag;
    }, $content);
}, 12);

/**
 * Yoast SEO REST API Support
 */
add_action('init', function() {
    $meta_fields = array(
        '_yoast_wpseo_focuskw',
        '_yoast_wpseo_metadesc',
        '_yoast_wpseo_title'
    );

    foreach ($meta_fields as $meta_key) {
        register_post_meta('post', $meta_key, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }
});

/**
 * Add Naver Site Verification
 */
add_action('wp_head', function() {
    echo '<meta name="naver-site-verification" content="9438adae9830aa165d288cff383543a5d1c3e164">' . "\n";
}, 1);

/**
 * Auto Meta Description (Fallback)
 */
add_action('wp_head', function() {
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }

    $desc = '';

    if (is_singular()) {
        $post = get_queried_object();
        if (!$post) return;

        if (has_excerpt($post->ID)) {
            $desc = get_the_excerpt($post);
        } else {
            $raw = wp_strip_all_tags($post->post_content);
            $raw = preg_replace('/\s+/', ' ', $raw);
            $desc = mb_substr(trim($raw), 0, 155, 'UTF-8');
        }
    } elseif (is_home() || is_front_page()) {
        $site_desc = get_bloginfo('description', 'display');
        $desc = $site_desc ? $site_desc : get_bloginfo('name') . ' - Practical guides for foreigners living in Korea';
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $desc = isset($term->description) && $term->description
            ? wp_strip_all_tags($term->description)
            : single_term_title('', false) . ' guides and information';
    }

    if ($desc) {
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    }
}, 5);

/**
 * CSS Health Check Endpoint for n8n
 */
add_action('init', function() {
    if (isset($_GET['koreayo_css_check']) && $_GET['koreayo_css_check'] === '1') {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        echo json_encode(array(
            'status' => 'ok',
            'theme' => 'koreayo',
            'version' => wp_get_theme()->get('Version'),
            'installed_at' => date('Y-m-d')
        ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
});

/**
 * Disable WordPress Admin Bar for Non-Admins (Optional)
 */
// add_filter('show_admin_bar', '__return_false');

/**
 * Add Custom Body Classes
 */
function koreayo_body_classes($classes) {
    // Add class for single posts
    if (is_single()) {
        $classes[] = 'single-post';
    }

    // Add class for pages with sidebar
    if (is_active_sidebar('sidebar-blog') && (is_home() || is_archive() || is_single())) {
        $classes[] = 'has-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'koreayo_body_classes');

/**
 * Customize Login Page (Optional)
 */
function koreayo_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/assets/images/logo.png);
            background-size: contain;
            width: 200px;
            height: 60px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'koreayo_login_logo');

function koreayo_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'koreayo_login_url');
