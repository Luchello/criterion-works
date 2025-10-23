<?php
// Place this file in your WordPress root (same dir as wp-load.php)
require_once __DIR__ . '/wp-load.php';

$sample = '<main>
        <!-- 호텔 섹션: 리스트 및 예약 -->
        <section id="section-hotels">
            <div class="container">
                <h1>홍콩의 가성비 좋은 고급호텔 TOP 5</h1>
                
                <!-- 호텔 1 -->
                <div class="hotel-item" data-hotel-name="호텔명1">
                    <div class="hotel-images">
                        <img src="" alt="호텔 이미지 1" loading="lazy">
                        <img src="" alt="호텔 이미지 2" loading="lazy">
                    </div>
                    <div class="hotel-info">
                        <h3>호텔명 1</h3>
                        <p>호텔설명 좋은 호텔입니다. 호텔설명 좋은 호텔입니다</p>
                        
                        <h5>주위 가볼만한 곳</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <h5>호텔 시설, 특징, 교통</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <a href="https://booking.com/hotel/..." 
                           data-aff="booking" 
                           rel="nofollow sponsored noopener" 
                           target="_blank"
                           class="btn btn-primary mt-3">지금 예약하기</a>
                    </div>
                </div>

                <!-- 호텔 2 -->
                <div class="hotel-item" data-hotel-name="호텔명2">
                    <div class="hotel-images">
                        <img src="" alt="호텔 이미지 1" loading="lazy">
                        <img src="" alt="호텔 이미지 2" loading="lazy">
                    </div>
                    <div class="hotel-info">
                        <h3>호텔명 2</h3>
                        <p>호텔설명 좋은 호텔입니다. 호텔설명 좋은 호텔입니다</p>
                        
                        <h5>주위 가볼만한 곳</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <h5>호텔 시설, 특징, 교통</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <a href="https://booking.com/hotel/..." 
                           data-aff="booking" 
                           rel="nofollow sponsored noopener" 
                           target="_blank"
                           class="btn btn-primary mt-3">지금 예약하기</a>
                    </div>
                </div>

                <!-- 호텔 3 -->
                <div class="hotel-item" data-hotel-name="호텔명3">
                    <div class="hotel-images">
                        <img src="" alt="호텔 이미지 1" loading="lazy">
                        <img src="" alt="호텔 이미지 2" loading="lazy">
                    </div>
                    <div class="hotel-info">
                        <h3>호텔명 3</h3>
                        <p>호텔설명 좋은 호텔입니다. 호텔설명 좋은 호텔입니다</p>
                        
                        <h5>주위 가볼만한 곳</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <h5>호텔 시설, 특징, 교통</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <a href="https://booking.com/hotel/..." 
                           data-aff="booking" 
                           rel="nofollow sponsored noopener" 
                           target="_blank"
                           class="btn btn-primary mt-3">지금 예약하기</a>
                    </div>
                </div>

                <!-- 호텔 4 -->
                <div class="hotel-item" data-hotel-name="호텔명4">
                    <div class="hotel-images">
                        <img src="" alt="호텔 이미지 1" loading="lazy">
                        <img src="" alt="호텔 이미지 2" loading="lazy">
                    </div>
                    <div class="hotel-info">
                        <h3>호텔명 4</h3>
                        <p>호텔설명 좋은 호텔입니다. 호텔설명 좋은 호텔입니다</p>
                        
                        <h5>주위 가볼만한 곳</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <h5>호텔 시설, 특징, 교통</h5>
                        <ul>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                            <li>항목 - 장소</li>
                        </ul>
                        
                        <a href="https://booking.com/hotel/..." 
                           data-aff="booking" 
                           rel="nofollow sponsored noopener" 
                           target="_blank"
                           class="btn btn-primary mt-3">지금 예약하기</a>
                    </div>
                </div>

                <!-- 호텔 5 -->
                <div class="hotel-item" data-hotel-name="호텔명5">
                    <div class="hotel-images">
                        <img src="" alt="호텔 이미지 1" loading="lazy">
                        <img src="" alt="호텔 이미지 2" loading="lazy">
                    </div>
                    <div class="hotel-info">
                        <h3>호텔명 5</h3>
        <p>호텔설명 좋은 호텔입니다. 호텔설명 좋은 호텔입니다</p>
        
                        <h5>주위 가볼만한 곳</h5>
        <ul>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
        </ul>
        
                        <h5>호텔 시설, 특징, 교통</h5>
        <ul>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
            <li>항목 - 장소</li>
        </ul>
                        
                        <a href="https://booking.com/hotel/..." 
                           data-aff="booking" 
                           rel="nofollow sponsored noopener" 
                           target="_blank"
                           class="btn btn-primary mt-3">지금 예약하기</a>
                    </div>
                </div>
            </div>
        </section>';

if (!function_exists('wp_insert_post')) {
    http_response_code(500);
    exit('WordPress failed to load');
}

$post_id = wp_insert_post([
    'post_title'   => '이것은 제목입니다',
    'post_content' => $sample,
    'post_status'  => 'publish',
    'post_author'  => 1,
]);

if (is_wp_error($post_id)) {
    http_response_code(500);
    exit($post_id->get_error_message());
}

echo 'OK ' . $post_id . ' ' . get_permalink($post_id);
