<?php
/**
 * Template: Left Sidebar
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div <?php generate_do_attr('left-sidebar'); ?>>
    <div class="inside-left-sidebar koreayo-sidebar">
        <?php
        /**
         * generate_before_left_sidebar_content hook.
         */
        do_action('generate_before_left_sidebar_content');

        if (!dynamic_sidebar('sidebar-2')) {
            generate_do_default_sidebar_widgets('left-sidebar');
        }

        /**
         * generate_after_left_sidebar_content hook.
         */
        do_action('generate_after_left_sidebar_content');
        ?>
    </div>
</div>
