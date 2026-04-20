<?php
/**
 * Template: Comments
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}

do_action('generate_before_comments');
?>
<div id="comments" class="koreayo-comments">

    <?php do_action('generate_inside_comments'); ?>

    <?php if (have_comments()) : ?>
        <?php
        $comments_number = get_comments_number();
        $comments_title = sprintf(
            _n(
                '%s Comment',
                '%s Comments',
                $comments_number,
                'koreayo'
            ),
            number_format_i18n($comments_number)
        );
        ?>

        <h2 class="comments-title"><?php echo esc_html($comments_title); ?></h2>

        <?php do_action('generate_below_comments_title'); ?>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav id="comment-nav-above" class="comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'koreayo'); ?></h2>
            <div class="nav-links">
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'koreayo')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'koreayo')); ?></div>
            </div>
        </nav>
        <?php endif; ?>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 50,
                'callback' => 'koreayo_comment_callback'
            ]);
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav id="comment-nav-below" class="comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'koreayo'); ?></h2>
            <div class="nav-links">
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'koreayo')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'koreayo')); ?></div>
            </div>
        </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'koreayo'); ?></p>
    <?php endif; ?>

    <?php
    comment_form([
        'title_reply' => __('Leave a Comment', 'koreayo'),
        'title_reply_to' => __('Reply to %s', 'koreayo'),
        'cancel_reply_link' => __('Cancel reply', 'koreayo'),
        'label_submit' => __('Post Comment', 'koreayo'),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun', 'koreayo') . '</label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" placeholder="' . esc_attr__('Write your comment here...', 'koreayo') . '"></textarea></p>',
    ]);
    ?>

</div>

<?php
/**
 * Custom comment callback function
 */
function koreayo_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author-avatar">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
            </div>
            <div class="comment-content-wrapper">
                <header class="comment-meta">
                    <span class="comment-author-name">
                        <?php echo get_comment_author_link(); ?>
                    </span>
                    <span class="comment-date">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                esc_html__('%1$s at %2$s', 'koreayo'),
                                get_comment_date(),
                                get_comment_time()
                            );
                            ?>
                        </time>
                    </span>
                </header>

                <?php if ($comment->comment_approved == '0') : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'koreayo'); ?></p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-actions">
                    <?php
                    comment_reply_link(array_merge($args, [
                        'add_below' => 'div-comment',
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'],
                        'before' => '<span class="reply-link">',
                        'after' => '</span>'
                    ]));

                    edit_comment_link(__('Edit', 'koreayo'), '<span class="edit-link">', '</span>');
                    ?>
                </div>
            </div>
        </article>
    <?php
}
?>

<style>
/* Comments Section */
.koreayo-comments {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.comments-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
    margin-bottom: 1.5rem;
}

/* Comment List */
.comment-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.comment-list .comment {
    margin-bottom: 1.5rem;
}

.comment-list .children {
    list-style: none;
    padding-left: 2rem;
    margin-top: 1rem;
    border-left: 2px solid var(--koreayo-gray-200, #e5e7eb);
}

/* Comment Body */
.comment-body {
    display: flex;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--koreayo-gray-50, #f9fafb);
    border-radius: 0.75rem;
}

.comment-author-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content-wrapper {
    flex: 1;
    min-width: 0;
}

/* Comment Meta */
.comment-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.comment-author-name {
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
}

.comment-author-name a {
    color: inherit;
    text-decoration: none;
}

.comment-author-name a:hover {
    color: var(--koreayo-primary, #2563EB);
}

.comment-date {
    font-size: 0.8125rem;
    color: var(--koreayo-gray-500, #6b7280);
}

/* Comment Content */
.comment-content {
    font-size: 0.9375rem;
    color: var(--koreayo-gray-700, #374151);
    line-height: 1.6;
}

.comment-content p {
    margin: 0 0 0.75rem;
}

.comment-content p:last-child {
    margin-bottom: 0;
}

/* Comment Actions */
.comment-actions {
    display: flex;
    gap: 1rem;
    margin-top: 0.75rem;
    font-size: 0.8125rem;
}

.comment-actions a {
    color: var(--koreayo-gray-500, #6b7280);
    text-decoration: none;
    transition: color 0.15s ease;
}

.comment-actions a:hover {
    color: var(--koreayo-primary, #2563EB);
}

/* Awaiting Moderation */
.comment-awaiting-moderation {
    padding: 0.5rem 0.75rem;
    background: #fef3c7;
    border-radius: 0.375rem;
    font-size: 0.8125rem;
    color: #92400e;
    margin-bottom: 0.75rem;
}

/* No Comments */
.no-comments {
    text-align: center;
    padding: 2rem;
    color: var(--koreayo-gray-500, #6b7280);
    font-style: italic;
}

/* Comment Form */
.comment-respond {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.comment-reply-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
    margin-bottom: 1rem;
}

.comment-reply-title small {
    font-size: 0.875rem;
    font-weight: 400;
    margin-left: 0.5rem;
}

.comment-reply-title small a {
    color: var(--koreayo-primary, #2563EB);
}

.comment-form label {
    display: block;
    font-weight: 500;
    color: var(--koreayo-gray-700, #374151);
    margin-bottom: 0.375rem;
    font-size: 0.875rem;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--koreayo-gray-300, #d1d5db);
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.comment-form input:focus,
.comment-form textarea:focus {
    outline: none;
    border-color: var(--koreayo-primary, #2563EB);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.comment-form textarea {
    min-height: 150px;
    resize: vertical;
}

.comment-form p {
    margin-bottom: 1rem;
}

.comment-form .form-submit {
    margin-top: 1rem;
}

.comment-form .submit {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

/* Comment Navigation */
.comment-navigation {
    display: flex;
    justify-content: space-between;
    margin: 1.5rem 0;
    padding: 1rem 0;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
    border-bottom: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.comment-navigation a {
    color: var(--koreayo-primary, #2563EB);
    text-decoration: none;
    font-weight: 500;
}

.comment-navigation a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .comment-body {
        flex-direction: column;
    }

    .comment-list .children {
        padding-left: 1rem;
    }
}
</style>
