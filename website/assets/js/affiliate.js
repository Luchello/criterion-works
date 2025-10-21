/**
 * 어필리에이트 링크 데코레이터 모듈
 * 
 * Intent: 예약 링크에 어필리에이트 ID 및 UTM 파라미터 주입
 * Inputs: body[data-aff-id], a[data-aff]
 * Outputs: 클릭 시 파라미터화된 URL로 리다이렉트
 * Invariants: 기존 쿼리 파라미터 보존, 원본 href는 수정 불가(once 이벤트)
 * Complexity: O(m) where m = 어필리에이트 링크 개수
 */

/**
 * 어필리에이트 링크 초기화
 * 
 * body data-aff-id에서 어필리에이트 ID를 읽고,
 * a[data-aff] 링크 클릭 시 쿼리스트링에 aid, utm_* 파라미터 추가
 * 
 * Args:
 *   없음
 * Returns:
 *   없음
 * Raises:
 *   없음(URL 파싱 실패 시 조용히 무시)
 * Examples:
 *   HTML: <a href="https://booking.com/..." data-aff="booking">예약</a>
 *   결과: 클릭 시 URL에 ?aid=YOUR_AFF_ID&utm_source=site&... 추가
 * Notes:
 *   - once: true로 이벤트 리스너 한 번만 실행(성능)
 *   - nofollow, sponsored, noopener rel 속성 권장(보안/SEO)
 */
export function initAffiliate() {
  const affId = document.body.dataset.affId || '';
  
  document.querySelectorAll('a[data-aff]')
    .forEach(link => {
      link.addEventListener('click', (event) => {
        try {
          const url = new URL(link.href);
          
          // 어필리에이트 ID 추가
          if (affId) {
            url.searchParams.set('aid', affId);
          }
          
          // UTM 파라미터 추가(기존 값 있으면 덮어쓰기)
          url.searchParams.set('utm_source', 'site');
          url.searchParams.set('utm_medium', 'affiliate');
          url.searchParams.set('utm_campaign', 'hotel');
          
          // 호텔명이나 데이터 속성이 있으면 캠페인 세분화 가능
          const hotelName = link.closest('[data-hotel-name]')?.dataset.hotelName;
          if (hotelName) {
            url.searchParams.set('utm_content', hotelName);
          }
          
          // 수정된 URL로 업데이트
          link.href = url.toString();
        } catch (e) {
          // URL 파싱 실패(상대 경로 등) → 무시
          console.warn('affiliate: URL parsing failed for', link.href);
        }
      }, { once: true });
    });
}
