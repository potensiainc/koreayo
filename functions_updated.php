<?php
/**
 * GeneratePress Child - functions.php
 * Version: 2025.10.25
 * Author: 대표님
 * Purpose: 수익형 워드프레스 블로그 (AI 콘텐츠 + 쿠팡 파트너스 + GA4 자동 트래킹 + SEO 자동화)
 */

// ------------------------------------------------------------
// 1️⃣ 부모 테마 스타일 불러오기
// ------------------------------------------------------------
add_action('wp_enqueue_scripts', function() {
  wp_enqueue_style(
    'parent-style',
    get_template_directory_uri() . '/style.css',
    [],
    null,
    'all'
  );
});

// ------------------------------------------------------------
// 2️⃣ 외부/제휴 링크 자동 속성 추가
// ------------------------------------------------------------
add_filter('the_content', function($content){
  $home = home_url();
  $aff_domains = ['coupang.com','coupa.ng','amzn.to','amazon.co','link.cafe24.com'];
  return preg_replace_callback('#<a\s+([^>]*href=[\'"][^\'"]+[\'"][^>]*)>#i', function($m) use($home,$aff_domains){
    $tag = $m[0];
    if(!preg_match('#href=[\'"]([^\'"]+)[\'"]#i', $tag, $h)) return $tag;
    $href = $h[1];
    $is_external = (strpos($href, $home) !== 0) && preg_match('#^https?://#', $href);
    $is_aff = false;
    foreach($aff_domains as $d){ if(stripos($href, $d)!==false){ $is_aff=true; break; } }

    $rel = '';
    if(preg_match('#rel=[\'"]([^\'"]+)[\'"]#i', $tag, $r)) $rel = strtolower($r[1]);
    $rels = array_filter(array_unique(preg_split('/\s+/', $rel)));

    if($is_external){
      $rels = array_unique(array_merge($rels, ['noopener','noreferrer']));
      if($is_aff){ $rels = array_unique(array_merge($rels, ['sponsored','nofollow'])); }
      if(!preg_match('#target=#i',$tag)) $tag = str_replace('<a ', '<a target="_blank" ', $tag);
    }

    $rel_str = implode(' ', $rels);
    if(preg_match('#rel=[\'"][^\'"]+[\'"]#i',$tag)){
      $tag = preg_replace('#rel=[\'"][^\'"]+[\'"]#i', 'rel="'.$rel_str.'"', $tag);
    } elseif($rel_str){
      $tag = str_replace('<a ', '<a rel="'.$rel_str.'" ', $tag);
    }

    return $tag;
  }, $content);
}, 12);

// ------------------------------------------------------------
// 3️⃣ GA4 자동 트래킹
// ------------------------------------------------------------
add_action('wp_footer', function(){ ?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
window.addEventListener('load', function() {
  if (document.body.classList.contains('single-post')) {
    gtag('event', 'view_post', {event_category: 'engagement', event_label: document.title});
  }
});
</script>
<?php });

// ------------------------------------------------------------
// 4️⃣ SEO 자동화 (Yoast / RankMath 호환) - save_post 시 자동 설정
// ------------------------------------------------------------
add_action('save_post', function($post_id){
  if (wp_is_post_revision($post_id)) return;
  if (get_post_status($post_id) !== 'publish') return;

  $post = get_post($post_id);
  if ($post->post_type !== 'post') return;

  // REST API에서 이미 설정된 경우 덮어쓰지 않음
  $existing_focuskw = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
  $existing_metadesc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);

  // 이미 값이 있으면 스킵 (n8n에서 설정한 값 보존)
  if (!empty($existing_focuskw) && !empty($existing_metadesc)) {
    return;
  }

  $content = strip_tags($post->post_content);
  $meta_desc = mb_substr(preg_replace('/\s+/', ' ', $content), 0, 120, 'UTF-8');
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
}, 20);

// ------------------------------------------------------------
// 5️⃣ Yoast SEO REST API 지원 (n8n 워크플로우용)
// ------------------------------------------------------------
add_action('init', function() {
  // 초점 키프레이즈 - REST API에서 수정 가능하게 등록
  register_post_meta('post', '_yoast_wpseo_focuskw', [
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'auth_callback' => function() {
      return current_user_can('edit_posts');
    }
  ]);

  // 메타 설명 - REST API에서 수정 가능하게 등록
  register_post_meta('post', '_yoast_wpseo_metadesc', [
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'auth_callback' => function() {
      return current_user_can('edit_posts');
    }
  ]);

  // SEO 제목 (옵션) - REST API에서 수정 가능하게 등록
  register_post_meta('post', '_yoast_wpseo_title', [
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'auth_callback' => function() {
      return current_user_can('edit_posts');
    }
  ]);
});


// ------------------------------------------------------------
// 🚫 광고 배너 관련 전체 주석 처리 (쿠팡 / RemitBuddy / 청약가점 등)
// ------------------------------------------------------------
/*

// 5️⃣ 본문 상단 + 중간 쿠팡 배너 자동 삽입
add_filter('the_content', function($content){ ... });

// 6️⃣ 사이드바 배너 삽입 + 검색창 비활성화
add_action('generate_before_right_sidebar_content', function() { ... });
add_action('widgets_init', function(){ unregister_widget('WP_Widget_Search'); });
add_action('generate_after_right_sidebar_content', function() { ... });

// 8️⃣ 포스트 하단 쿠팡 / RemitBuddy / 청약가점 배너 자동 삽입
add_filter('the_content', function($content) { ... }, 25);

// 9️⃣ 왼쪽 고정형 RemitBuddy + 청약가점 배너 (PC 전용)
add_action('wp_footer', function() { ... });

*/


// ------------------------------------------------------------
// 🔍 네이버 서치어드바이저 소유확인 메타태그
// ------------------------------------------------------------
add_action('wp_head', function() {
  echo '<meta name="naver-site-verification" content="9438adae9830aa165d288cff383543a5d1c3e164">';
});

// ------------------------------------------------------------
//  🔎 <meta name="description"> 자동 생성 (Yoast/RankMath 미사용 대비)
// ------------------------------------------------------------
add_action('wp_head', function () {
  if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) return;
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
    $desc = $site_desc ? $site_desc : get_bloginfo('name') . '의 최신 글과 실용 정보 모음';
  } elseif (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    $desc = isset($term->description) && $term->description ? wp_strip_all_tags($term->description) : single_term_title('', false) . ' 관련 콘텐츠 모음';
  }

  if ($desc) {
    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
  }
});
