<?php
/**
 * WordPress Auto-Post Endpoint
 * 
 * 목적: wp_insert_post를 사용해 표준 post를 즉시 publish로 생성.
 * 입력: POST JSON 또는 form-encoded (title, content 필수; slug, author_id, categories, tags, featured_image_url 선택)
 * 출력: JSON { id, link, status } 또는 에러 메시지
 * 불변식: 워드프레스 루트에 배치, wp-load.php 포함 필수
 * 복잡도: O(1) 메인 로직 + O(n) 미디어 사이드로드(n=1 이미지)
 */

// ─────────────────────────────────────────────────────────────
// 1. WordPress Bootstrap
// ─────────────────────────────────────────────────────────────
require_once __DIR__ . '/wp-load.php';

// ─────────────────────────────────────────────────────────────
// 2. 유틸리티 함수 정의
// ─────────────────────────────────────────────────────────────

/**
 * 에러 응답을 JSON으로 전송 및 종료
 * 
 * Args:
 *   $status_code (int): HTTP 상태 코드
 *   $error (string): 에러 타입
 *   $message (string): 에러 메시지
 * Returns: 없음 (exit 호출)
 */
function sendErrorResponse($status_code, $error, $message) {
    http_response_code($status_code);
    echo json_encode(array(
        'error' => $error,
        'message' => $message
    ));
    exit;
}

/**
 * 성공 응답을 JSON으로 전송 및 종료
 * 
 * Args:
 *   $post_id (int): 생성된 포스트 ID
 * Returns: 없음 (exit 호출)
 */
function sendSuccessResponse($post_id) {
    http_response_code(200);
    echo json_encode(array(
        'id'       => $post_id,
        'link'     => get_permalink($post_id),
        'status'   => 'publish'
    ));
    exit;
}

/**
 * 포스트 생성 (제목, 내용 필수)
 * 
 * Args:
 *   $title (string): 포스트 제목
 *   $content (string): 포스트 내용
 *   $author_id (int): 작성자 ID
 *   $slug (string): URL 슬러그 (선택)
 * Returns:
 *   int: 생성된 포스트 ID
 * Raises:
 *   sendErrorResponse() on wp_insert_post failure
 */
function createPost($title, $content, $author_id, $slug = '') {
    $post_args = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_author'  => $author_id,
    );
    
    if (!empty($slug)) {
        $post_args['post_name'] = $slug;
    }
    
    $post_id = wp_insert_post($post_args, true);
    
    if (is_wp_error($post_id)) {
        sendErrorResponse(400, 'Failed to create post', $post_id->get_error_message());
    }
    
    return $post_id;
}

/**
 * 카테고리 및 태그를 포스트에 연결
 * 
 * Args:
 *   $post_id (int): 포스트 ID
 *   $categories (array): 카테고리 ID 배열
 *   $tags (array): 태그 이름 배열
 * Returns: void
 */
function attachMetadata($post_id, $categories, $tags) {
    if (!empty($categories)) {
        wp_set_post_categories($post_id, $categories);
    }
    
    if (!empty($tags)) {
        wp_set_post_tags($post_id, $tags);
    }
}

/**
 * 원격 이미지를 다운로드하여 대표 이미지로 설정
 * 
 * Args:
 *   $featured_image_url (string): 이미지 URL
 *   $post_id (int): 포스트 ID
 * Returns: void (실패 시 무시)
 */
function attachFeaturedImage($featured_image_url, $post_id) {
    if (empty($featured_image_url)) {
        return;
    }
    
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    
    $attachment_id = media_sideload_image($featured_image_url, $post_id, null, 'id');
    
    if (!is_wp_error($attachment_id)) {
        set_post_thumbnail($post_id, $attachment_id);
    }
}

// ─────────────────────────────────────────────────────────────
// 3. HTTP 메서드 분기
// ─────────────────────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    // ─────────────────────────────────────────────────────────────
    // POST: JSON API (기존 로직 유지)
    // ─────────────────────────────────────────────────────────────
    header('Content-Type: application/json; charset=utf-8');
    
    // 입력 파싱
    $input = array();
    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    
    if (strpos($content_type, 'application/json') !== false) {
        $raw_input = file_get_contents('php://input');
        $input = json_decode($raw_input, true) ?? array();
    } else {
        $input = $_POST;
    }
    
    // 글의 제목 추출 및 검증
    $title = isset($input['title']) ? sanitize_text_field($input['title']) : '';
    
    if (empty($title)) {
        sendErrorResponse(400, 'Missing required fields', 'Title is required.');
    }
    
    // 글의 내용 추출 및 검증
    $content = isset($input['content']) ? wp_kses_post($input['content']) : '';
    
    if (empty($content)) {
        sendErrorResponse(400, 'Missing required fields', 'Content is required.');
    }
    
    // 선택 필드 파싱
    $slug = isset($input['slug']) ? sanitize_title($input['slug']) : '';
    $author_id = isset($input['author_id']) ? absint($input['author_id']) : 1;
    $categories = isset($input['categories']) ? array_map('absint', (array) $input['categories']) : array();
    $tags = isset($input['tags']) ? (is_array($input['tags']) ? $input['tags'] : array($input['tags'])) : array();
    $featured_image_url = isset($input['featured_image_url']) ? esc_url_raw($input['featured_image_url']) : '';
    
    // 포스트 생성
    $post_id = createPost($title, $content, $author_id, $slug);
    
    // 메타데이터 설정 (카테고리, 태그)
    attachMetadata($post_id, $categories, $tags);
    
    // 대표 이미지 첨부
    attachFeaturedImage($featured_image_url, $post_id);
    
    // 성공 응답
    sendSuccessResponse($post_id);

} elseif ($method === 'GET') {
    // ─────────────────────────────────────────────────────────────
    // GET: 더미 글 생성 (브라우저 접속 시)
    // ─────────────────────────────────────────────────────────────
    
    // 기본 값(더미)
    $title = 'Auto Post ' . date('Y-m-d H:i:s');
    $content = '자동 생성된 테스트 글입니다.';
    $author_id = 1;  // 관리자 ID
    
    // 포스트 생성
    $post_id = createPost($title, $content, $author_id);
    
    // 메타데이터 설정
    attachMetadata($post_id, array(), array());
    
    // 응답 방식 선택 (리다이렉트 또는 HTML)
    if (isset($_GET['redirect']) && $_GET['redirect'] === '1') {
        // 리다이렉트 모드
        header('Location: ' . get_permalink($post_id), true, 302);
        exit;
    } else {
        // HTML 응답 모드
        header('Content-Type: text/html; charset=utf-8');
        $post_link = esc_url(get_permalink($post_id));
        $post_title = esc_html($title);
        echo <<<HTML
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 생성됨</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 600px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #f5f5f5;
        }
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h2>✓ 글이 생성되었습니다</h2>
        <p><strong>제목:</strong> {$post_title}</p>
        <p><strong>ID:</strong> {$post_id}</p>
        <p><a href="{$post_link}" target="_blank">생성된 글 보기 →</a></p>
    </div>
</body>
</html>
HTML;
        exit;
    }

} else {
    // 지원하지 않는 메서드
    header('Content-Type: application/json; charset=utf-8');
    sendErrorResponse(405, 'Method not allowed', 'POST 또는 GET만 지원합니다.');
}
?>
