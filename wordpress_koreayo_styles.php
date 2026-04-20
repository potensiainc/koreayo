<?php
/**
 * Koreayo Article Styles
 *
 * REQ-HTML-001: CSS 분리
 *
 * 이 파일을 WordPress 테마의 functions.php에 추가하거나,
 * 별도의 플러그인으로 설치하세요.
 *
 * 설치 방법:
 * 1. 이 코드를 functions.php 파일 끝에 추가
 * 2. 또는 이 파일을 테마 폴더에 복사 후 functions.php에서 require_once
 *
 * @package Koreayo
 * @version 5.0.0
 * @since 2026-04-12
 */

// 직접 접근 방지
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================
 * CSS 의존성 검증 엔드포인트
 * ============================================================
 *
 * n8n 워크플로우 배포 전 CSS 설치 여부를 확인하는 헬스체크
 * 테스트 URL: https://koreayo.com/?koreayo_css_check=1
 *
 * 응답:
 * - 성공: {"status":"ok","version":"5.0.0","classes":["koreayo-article",...]}
 * - 실패: 이 엔드포인트가 없으면 404 또는 빈 응답
 */
add_action('init', 'koreayo_css_health_check');

function koreayo_css_health_check() {
    if (isset($_GET['koreayo_css_check']) && $_GET['koreayo_css_check'] === '1') {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        echo json_encode([
            'status' => 'ok',
            'version' => '5.0.0',
            'installed_at' => '2026-04-12',
            'classes' => [
                'koreayo-article',
                'koreayo-meta',
                'koreayo-meta-date',
                'koreayo-meta-verified',
                'koreayo-h1',
                'koreayo-h2',
                'koreayo-h3',
                'koreayo-paragraph',
                'koreayo-list',
                'koreayo-list-ordered',
                'koreayo-code',
                'koreayo-official-resources',
                'koreayo-author-box',
                'koreayo-author-header',
                'koreayo-author-avatar',
                'koreayo-author-info',
                'koreayo-author-name',
                'koreayo-author-role',
                'koreayo-author-bio',
                'koreayo-author-contact',
                'koreayo-faq-section',
                'koreayo-faq-item',
                'koreayo-faq-question',
                'koreayo-faq-answer',
                'koreayo-faq-icon',
                'koreayo-faq-icon-a',
                'koreayo-related-section',
                'koreayo-related-list',
                'koreayo-related-item',
                'koreayo-related-title',
                'koreayo-related-arrow'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

/**
 * Koreayo 글에 대한 CSS 스타일 등록
 * wp_head 훅에서 실행되어 <head> 태그 내에 스타일 삽입
 */
add_action('wp_head', 'koreayo_register_article_styles', 10);

function koreayo_register_article_styles() {
    // 단일 포스트 페이지에서만 스타일 로드
    if (!is_single()) {
        return;
    }

    // Minified CSS (REQ-HTML-001 요구사항)
    // 줄바꿈 없이 한 줄로 작성하여 WordPress wpautop 필터의 <br/> 삽입 방지
    ?>
    <style id="koreayo-article-styles">
.koreayo-article{font-family:'Pretendard',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;line-height:1.8;color:#333;max-width:100%;counter-reset:main-list}.koreayo-meta{background:#f9fafb;border-bottom:1px solid #e5e7eb;padding:1rem;margin-bottom:1.5rem;font-size:0.9rem;color:#6b7280;display:flex;align-items:center;gap:2rem;flex-wrap:wrap;border-radius:8px 8px 0 0}.koreayo-meta-date::before{content:"📅 "}.koreayo-meta-verified::before{content:"✓ ";color:#10b981}.koreayo-meta-verified{color:#059669}.koreayo-h1{font-size:2rem;font-weight:700;color:#1a1a1a;margin:2rem 0 1rem 0;padding-bottom:0.5rem;border-bottom:3px solid #2563eb}.koreayo-h2{font-size:1.5rem;font-weight:600;color:#1e40af;margin:2rem 0 1rem 0;padding-left:12px;border-left:4px solid #3b82f6}.koreayo-h3{font-size:1.25rem;font-weight:600;color:#374151;margin:1.5rem 0 0.75rem 0}.koreayo-paragraph{font-size:1.05rem;color:#4b5563;margin:1rem 0;text-align:justify}.koreayo-list{margin:1rem 0 1rem 1.5rem;padding:0;list-style-type:disc}.koreayo-list-ordered{margin:1rem 0 1rem 1.5rem;padding:0;list-style:none}.koreayo-list li,.koreayo-list-ordered li{margin:0.5rem 0;padding-left:0.5rem;color:#4b5563}.koreayo-list-ordered li{counter-increment:main-list}.koreayo-list-ordered li::before{content:counter(main-list) ". ";color:#3b82f6;font-weight:600}.koreayo-list li::marker{color:#3b82f6}.koreayo-code{background:#f3f4f6;border-radius:8px;padding:1rem;overflow-x:auto;font-family:'Fira Code',monospace;font-size:0.9rem;border:1px solid #e5e7eb}.koreayo-official-resources{margin-top:3rem;padding:2rem;background:linear-gradient(135deg,#ecfdf5 0%,#d1fae5 100%);border-radius:16px;border:1px solid #6ee7b7}.koreayo-official-resources .koreayo-h2{color:#065f46}.koreayo-official-resources .koreayo-list li{margin:0.75rem 0}.koreayo-author-box{background:linear-gradient(135deg,#f5f7fa 0%,#e8ecf1 100%);border-left:4px solid #2563eb;padding:1.5rem;border-radius:8px;margin:2rem 0;font-size:0.95rem}.koreayo-author-header{display:flex;align-items:center;gap:12px;margin-bottom:1rem}.koreayo-author-avatar{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;background:#2563eb;color:#fff;border-radius:50%;font-weight:700;font-size:1.1rem}.koreayo-author-info{display:flex;flex-direction:column}.koreayo-author-name{font-weight:600;color:#1a1a1a}.koreayo-author-role{font-size:0.85rem;color:#6b7280}.koreayo-author-bio{color:#4b5563;line-height:1.6;margin:0.5rem 0}.koreayo-author-contact{font-size:0.9rem;color:#6b7280;margin-top:1rem}.koreayo-author-contact a{color:#2563eb;text-decoration:none}.koreayo-author-contact a:hover{text-decoration:underline}.koreayo-faq-section{margin-top:3rem;padding:2rem;background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 100%);border-radius:16px}.koreayo-faq-list{margin-top:1.5rem}.koreayo-faq-item{background:#fff;border-radius:12px;padding:1.25rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);border:1px solid #e0e7ff}.koreayo-faq-question{display:flex;align-items:flex-start;gap:12px;font-weight:600;color:#1e40af;margin-bottom:0.75rem}.koreayo-faq-answer{display:flex;align-items:flex-start;gap:12px;color:#4b5563;padding-left:0}.koreayo-faq-icon{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:28px;background:#3b82f6;color:#fff;border-radius:50%;font-weight:700;font-size:0.85rem}.koreayo-faq-icon-a{background:#10b981}.koreayo-related-section{margin-top:3rem;padding:2rem;background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);border-radius:16px}.koreayo-related-list{margin-top:1.5rem;display:flex;flex-direction:column;gap:12px}.koreayo-related-item{display:flex;align-items:center;justify-content:space-between;background:#fff;border-radius:12px;padding:1rem 1.25rem;text-decoration:none;color:#1e40af;font-weight:500;box-shadow:0 2px 8px rgba(0,0,0,0.06);border:1px solid #fcd34d;transition:all 0.2s ease}.koreayo-related-item:hover{background:#fffbeb;transform:translateX(4px);box-shadow:0 4px 12px rgba(0,0,0,0.1)}.koreayo-related-title{flex:1}.koreayo-related-arrow{font-size:1.25rem;color:#f59e0b;font-weight:700}.koreayo-article strong{color:#1e40af;font-weight:600}.koreayo-article em{color:#6b7280;font-style:italic}
    </style>
    <?php
}

/**
 * 개발용: Readable CSS (주석 처리됨)
 * 디버깅이 필요할 때 위의 minified 버전 대신 이 버전을 사용
 */
/*
function koreayo_register_article_styles_readable() {
    if (!is_single()) {
        return;
    }
    ?>
    <style id="koreayo-article-styles">
    /* Koreayo Article Base Styles */
    .koreayo-article {
        font-family: 'Pretendard', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.8;
        color: #333;
        max-width: 100%;
        counter-reset: main-list;
    }

    /* Headings */
    .koreayo-h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 2rem 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #2563eb;
    }

    .koreayo-h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e40af;
        margin: 2rem 0 1rem 0;
        padding-left: 12px;
        border-left: 4px solid #3b82f6;
    }

    .koreayo-h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 1.5rem 0 0.75rem 0;
    }

    /* Paragraphs */
    .koreayo-paragraph {
        font-size: 1.05rem;
        color: #4b5563;
        margin: 1rem 0;
        text-align: justify;
    }

    /* Lists */
    .koreayo-list {
        margin: 1rem 0 1rem 1.5rem;
        padding: 0;
        list-style-type: disc;
    }

    .koreayo-list-ordered {
        margin: 1rem 0 1rem 1.5rem;
        padding: 0;
        list-style: none;
    }

    .koreayo-list li,
    .koreayo-list-ordered li {
        margin: 0.5rem 0;
        padding-left: 0.5rem;
        color: #4b5563;
    }

    .koreayo-list-ordered li {
        counter-increment: main-list;
    }

    .koreayo-list-ordered li::before {
        content: counter(main-list) ". ";
        color: #3b82f6;
        font-weight: 600;
    }

    .koreayo-list li::marker {
        color: #3b82f6;
    }

    /* Code Blocks */
    .koreayo-code {
        background: #f3f4f6;
        border-radius: 8px;
        padding: 1rem;
        overflow-x: auto;
        font-family: 'Fira Code', monospace;
        font-size: 0.9rem;
        border: 1px solid #e5e7eb;
    }

    /* FAQ Section */
    .koreayo-faq-section {
        margin-top: 3rem;
        padding: 2rem;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 16px;
    }

    .koreayo-faq-list {
        margin-top: 1.5rem;
    }

    .koreayo-faq-item {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e0e7ff;
    }

    .koreayo-faq-question {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 0.75rem;
    }

    .koreayo-faq-answer {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: #4b5563;
        padding-left: 0;
    }

    .koreayo-faq-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        background: #3b82f6;
        color: #fff;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .koreayo-faq-icon-a {
        background: #10b981;
    }

    /* Related Articles Section */
    .koreayo-related-section {
        margin-top: 3rem;
        padding: 2rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 16px;
    }

    .koreayo-related-list {
        margin-top: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .koreayo-related-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        text-decoration: none;
        color: #1e40af;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #fcd34d;
        transition: all 0.2s ease;
    }

    .koreayo-related-item:hover {
        background: #fffbeb;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .koreayo-related-title {
        flex: 1;
    }

    .koreayo-related-arrow {
        font-size: 1.25rem;
        color: #f59e0b;
        font-weight: 700;
    }

    /* Typography Enhancements */
    .koreayo-article strong {
        color: #1e40af;
        font-weight: 600;
    }

    .koreayo-article em {
        color: #6b7280;
        font-style: italic;
    }
    </style>
    <?php
}
*/
