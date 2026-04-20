<?php
/**
 * Koreayo Theme (GeneratePress Child)
 *
 * @package Koreayo
 * @version 1.0.0
 * @author Koreayo Team
 * @description Practical guides for foreigners living in Korea
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Version
define('KOREAYO_VERSION', '1.0.0');

// ============================================
// 1. Enqueue Styles & Scripts
// ============================================
add_action('wp_enqueue_scripts', 'koreayo_enqueue_scripts', 15);
function koreayo_enqueue_scripts() {
    // Parent theme style
    wp_enqueue_style(
        'generatepress',
        get_template_directory_uri() . '/style.css',
        [],
        GENERATE_VERSION
    );

    // Child theme style
    wp_enqueue_style(
        'koreayo-style',
        get_stylesheet_uri(),
        ['generatepress'],
        KOREAYO_VERSION
    );

    // Google Fonts - Inter & Noto Sans KR
    wp_enqueue_style(
        'koreayo-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+KR:wght@400;500;600;700&display=swap',
        [],
        null
    );
}

// ============================================
// 2. Theme Setup
// ============================================
add_action('after_setup_theme', 'koreayo_setup', 15);
function koreayo_setup() {
    // Custom Image Sizes
    add_image_size('koreayo-thumbnail', 400, 267, true);  // 3:2 ratio
    add_image_size('koreayo-featured', 800, 450, true);   // 16:9 ratio
    add_image_size('koreayo-hero', 1200, 630, true);      // OG image size

    // Excerpt Length
    add_filter('excerpt_length', function() {
        return 25;
    });

    add_filter('excerpt_more', function() {
        return '...';
    });
}

// ============================================
// 3. External/Affiliate Link Handling
// ============================================
add_filter('the_content', 'koreayo_external_links', 12);
function koreayo_external_links($content) {
    $home_url = home_url();
    $affiliate_domains = [
        'coupang.com',
        'coupa.ng',
        'amzn.to',
        'amazon.com',
        'amazon.co.kr',
        'link.cafe24.com'
    ];

    return preg_replace_callback(
        '#<a\s+([^>]*href=[\'"][^\'"]+[\'"][^>]*)>#i',
        function($matches) use ($home_url, $affiliate_domains) {
            $tag = $matches[0];

            if (!preg_match('#href=[\'"]([^\'"]+)[\'"]#i', $tag, $href_match)) {
                return $tag;
            }
            $href = $href_match[1];

            $is_external = (strpos($href, $home_url) !== 0) && preg_match('#^https?://#', $href);
            if (!$is_external) {
                return $tag;
            }

            $is_affiliate = false;
            foreach ($affiliate_domains as $domain) {
                if (stripos($href, $domain) !== false) {
                    $is_affiliate = true;
                    break;
                }
            }

            $rel_attrs = [];
            if (preg_match('#rel=[\'"]([^\'"]+)[\'"]#i', $tag, $rel_match)) {
                $rel_attrs = array_filter(array_unique(preg_split('/\s+/', strtolower($rel_match[1]))));
            }

            $rel_attrs = array_unique(array_merge($rel_attrs, ['noopener', 'noreferrer']));

            if ($is_affiliate) {
                $rel_attrs = array_unique(array_merge($rel_attrs, ['sponsored', 'nofollow']));
            }

            if (!preg_match('#target=#i', $tag)) {
                $tag = str_replace('<a ', '<a target="_blank" ', $tag);
            }

            $rel_string = implode(' ', $rel_attrs);
            if (preg_match('#rel=[\'"][^\'"]+[\'"]#i', $tag)) {
                $tag = preg_replace('#rel=[\'"][^\'"]+[\'"]#i', 'rel="' . $rel_string . '"', $tag);
            } else {
                $tag = str_replace('<a ', '<a rel="' . $rel_string . '" ', $tag);
            }

            return $tag;
        },
        $content
    );
}

// ============================================
// 4. Reading Time Calculation
// ============================================
function koreayo_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = mb_strlen(strip_tags($content), 'UTF-8');
    $reading_time = max(1, ceil($word_count / 500));

    return $reading_time;
}

// ============================================
// 5. SEO Automation (Yoast / RankMath)
// ============================================
add_action('save_post', 'koreayo_auto_seo', 20);
function koreayo_auto_seo($post_id) {
    if (wp_is_post_revision($post_id)) return;
    if (get_post_status($post_id) !== 'publish') return;

    $post = get_post($post_id);
    if ($post->post_type !== 'post') return;

    $existing_focuskw = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
    $existing_metadesc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);

    if (!empty($existing_focuskw) && !empty($existing_metadesc)) {
        return;
    }

    $content = strip_tags($post->post_content);
    $content = preg_replace('/\s+/', ' ', trim($content));
    $meta_desc = mb_substr($content, 0, 120, 'UTF-8');

    $title = mb_strtolower(strip_tags($post->post_title), 'UTF-8');
    $title = preg_replace('/[^a-z0-9가-힣\s]/u', '', $title);
    $words = preg_split('/\s+/', $title, -1, PREG_SPLIT_NO_EMPTY);
    $focus_keyphrase = implode(' ', array_slice($words, 0, 3));

    if (empty($existing_metadesc)) {
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta_desc);
        update_post_meta($post_id, '_rank_math_description', $meta_desc);
    }

    if (empty($existing_focuskw)) {
        update_post_meta($post_id, '_yoast_wpseo_focuskw', $focus_keyphrase);
        update_post_meta($post_id, '_rank_math_focus_keyword', $focus_keyphrase);
    }
}

// ============================================
// 6. Yoast SEO REST API Support (n8n)
// ============================================
add_action('init', 'koreayo_register_seo_meta');
function koreayo_register_seo_meta() {
    $meta_fields = [
        '_yoast_wpseo_focuskw',
        '_yoast_wpseo_metadesc',
        '_yoast_wpseo_title'
    ];

    foreach ($meta_fields as $field) {
        register_post_meta('post', $field, [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);
    }
}

// ============================================
// 7. Meta Description Fallback
// ============================================
add_action('wp_head', 'koreayo_meta_description', 1);
function koreayo_meta_description() {
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }

    $description = '';

    if (is_singular()) {
        $post = get_queried_object();
        if (!$post) return;

        if (has_excerpt($post->ID)) {
            $description = get_the_excerpt($post);
        } else {
            $raw = wp_strip_all_tags($post->post_content);
            $raw = preg_replace('/\s+/', ' ', $raw);
            $description = mb_substr(trim($raw), 0, 155, 'UTF-8');
        }
    } elseif (is_home() || is_front_page()) {
        $site_desc = get_bloginfo('description', 'display');
        $description = $site_desc ?: get_bloginfo('name') . ' - Practical guides for foreigners living in Korea';
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $description = !empty($term->description)
            ? wp_strip_all_tags($term->description)
            : single_term_title('', false) . ' - Guides and tips';
    }

    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}

// ============================================
// 8. Naver Search Advisor
// ============================================
add_action('wp_head', 'koreayo_naver_verification', 1);
function koreayo_naver_verification() {
    echo '<meta name="naver-site-verification" content="9438adae9830aa165d288cff383543a5d1c3e164">' . "\n";
}

// ============================================
// 9. Performance Optimization
// ============================================

// Disable Emojis
add_action('init', 'koreayo_disable_emojis');
function koreayo_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}

// Clean up WP Head
add_action('init', 'koreayo_cleanup_head');
function koreayo_cleanup_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}

// ============================================
// 10. Custom 404 Title
// ============================================
add_filter('generate_404_title', 'koreayo_404_title');
function koreayo_404_title() {
    return 'Page Not Found';
}

add_filter('generate_404_text', 'koreayo_404_text');
function koreayo_404_text() {
    return 'The page you\'re looking for doesn\'t exist or has been moved. Try searching for what you need below.';
}

// ============================================
// 11. Add Reading Time to Post Meta
// ============================================
add_filter('generate_post_date_output', 'koreayo_add_reading_time', 10, 3);
function koreayo_add_reading_time($output, $time_string, $time_string_updated) {
    if (is_singular('post') || is_archive() || is_home()) {
        $reading_time = koreayo_reading_time();
        $output .= ' <span class="reading-time">' . $reading_time . ' min read</span>';
    }
    return $output;
}

// ============================================
// 12. Customize Archive Title
// ============================================
add_filter('get_the_archive_title', 'koreayo_archive_title');
function koreayo_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
}

// ============================================
// 13. Add Post Class for Styling
// ============================================
add_filter('post_class', 'koreayo_post_class');
function koreayo_post_class($classes) {
    if (has_post_thumbnail()) {
        $classes[] = 'has-thumbnail';
    }
    return $classes;
}

// ============================================
// 14. Breadcrumbs Customization (if GP Premium)
// ============================================
add_filter('generate_breadcrumb_trail_args', 'koreayo_breadcrumb_args');
function koreayo_breadcrumb_args($args) {
    $args['show_on_front'] = false;
    $args['separator'] = ' / ';
    return $args;
}

// ============================================
// 15. GA4 Tracking
// ============================================
add_action('wp_footer', 'koreayo_ga4_tracking');
function koreayo_ga4_tracking() {
    if (!is_singular('post')) return;
    ?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
document.addEventListener('DOMContentLoaded', function() {
    if (typeof gtag === 'function') {
        gtag('event', 'view_post', {
            event_category: 'engagement',
            event_label: document.title
        });
    }
});
</script>
<?php
}

// ============================================
// 16. Remove Duplicate Featured Images
// ============================================
// Remove GeneratePress default post image on archive/index pages
// We handle it ourselves in content.php
add_action('after_setup_theme', 'koreayo_remove_post_image', 20);
function koreayo_remove_post_image() {
    // Remove post image from archive pages (we handle it in content.php)
    remove_action('generate_after_entry_header', 'generate_post_image');

    // Re-add it only for single posts
    add_action('generate_after_entry_header', 'koreayo_conditional_post_image');
}

function koreayo_conditional_post_image() {
    // Only show featured image on single posts, not archives
    if (is_singular('post')) {
        // Check if GeneratePress function exists
        if (function_exists('generate_post_image')) {
            generate_post_image();
        }
    }
}

// ============================================
// 17. Hide Featured Image in Post Content
// ============================================
// Remove first image from post content if it matches featured image
add_filter('the_content', 'koreayo_remove_duplicate_featured_image', 5);
function koreayo_remove_duplicate_featured_image($content) {
    // Only on single posts
    if (!is_singular('post')) {
        return $content;
    }

    $post_id = get_the_ID();

    // Check if post has featured image
    if (!has_post_thumbnail($post_id)) {
        return $content;
    }

    // Get featured image URL
    $featured_image_url = get_the_post_thumbnail_url($post_id, 'full');
    if (!$featured_image_url) {
        return $content;
    }

    // Extract base filename without size suffix
    $featured_base = preg_replace('/-\d+x\d+(?=\.[a-z]+$)/i', '', $featured_image_url);
    $featured_base = basename($featured_base);
    $featured_base = preg_replace('/\.[^.]+$/', '', $featured_base);

    // Find first image in content
    if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $match)) {
        $first_image_url = $match[1];
        $first_base = preg_replace('/-\d+x\d+(?=\.[a-z]+$)/i', '', $first_image_url);
        $first_base = basename($first_base);
        $first_base = preg_replace('/\.[^.]+$/', '', $first_base);

        // If first image matches featured image, remove it
        if ($featured_base === $first_base) {
            // Remove the first image (and surrounding figure/p tags if present)
            $content = preg_replace(
                '/<(figure|p)[^>]*>\s*<img[^>]+src=[\'"]' . preg_quote($first_image_url, '/') . '[\'"][^>]*>\s*(<figcaption[^>]*>.*?<\/figcaption>)?\s*<\/(figure|p)>/is',
                '',
                $content,
                1
            );
            // Also try without wrapper
            $content = preg_replace(
                '/<img[^>]+src=[\'"]' . preg_quote($first_image_url, '/') . '[\'"][^>]*>/i',
                '',
                $content,
                1
            );
        }
    }

    return $content;
}
