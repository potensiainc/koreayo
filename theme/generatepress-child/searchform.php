<?php
/**
 * Template: Search Form
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$unique_id = wp_unique_id('search-form-');
?>
<form role="search" method="get" class="search-form koreayo-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>" class="screen-reader-text">
        <?php echo esc_html_x('Search for:', 'label', 'koreayo'); ?>
    </label>
    <div class="search-form-inner">
        <input
            type="search"
            id="<?php echo esc_attr($unique_id); ?>"
            class="search-field"
            placeholder="<?php echo esc_attr_x('Search guides...', 'placeholder', 'koreayo'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
            autocomplete="off"
        >
        <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x('Search', 'submit button', 'koreayo'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </div>
</form>

<style>
.koreayo-search-form {
    width: 100%;
}

.koreayo-search-form .search-form-inner {
    display: flex;
    gap: 0;
    border: 2px solid var(--koreayo-gray-200, #e5e7eb);
    border-radius: 0.5rem;
    overflow: hidden;
    transition: border-color 0.15s ease;
}

.koreayo-search-form .search-form-inner:focus-within {
    border-color: var(--koreayo-primary, #2563EB);
}

.koreayo-search-form .search-field {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    font-size: 1rem;
    background: var(--koreayo-white, #fff);
    color: var(--koreayo-gray-800, #1f2937);
}

.koreayo-search-form .search-field::placeholder {
    color: var(--koreayo-gray-400, #9ca3af);
}

.koreayo-search-form .search-field:focus {
    outline: none;
    box-shadow: none;
}

.koreayo-search-form .search-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    background: var(--koreayo-primary, #2563EB);
    color: var(--koreayo-white, #fff);
    border: none;
    cursor: pointer;
    transition: background 0.15s ease;
    padding: 0;
    margin: 0;
    border-radius: 0;
    box-shadow: none;
}

.koreayo-search-form .search-submit:hover {
    background: var(--koreayo-primary-dark, #1e40af);
    transform: none;
}

.koreayo-search-form .search-submit svg {
    flex-shrink: 0;
}

/* Sidebar Search */
.widget-area .koreayo-search-form .search-form-inner {
    border-radius: 0.5rem;
}

/* Mobile */
@media (max-width: 768px) {
    .koreayo-search-form .search-field {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}
</style>
