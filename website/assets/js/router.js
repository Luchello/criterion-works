/**
 * 해시 라우터 모듈
 * 
 * Intent: 해시 기반 단일 페이지 라우팅(home/hotels/about/contact 섹션 관리)
 * Inputs: 브라우저 location.hash
 * Outputs: 활성 섹션 표시, 비활성 섹션 숨김, 포커스 이동
 * Invariants: main > section 구조 유지, 항상 정확히 하나의 섹션 활성
 * Complexity: O(n) where n = 섹션 개수(상수)
 */

const routeToSectionId = {
  '': 'section-home',
  'home': 'section-home',
  'hotels': 'section-hotels',
  'about': 'section-about',
  'contact': 'section-contact',
};

/**
 * 해시 변경 시 섹션 렌더링 및 포커스 이동
 * 
 * Args:
 *   없음
 * Returns:
 *   없음
 * Raises:
 *   없음(안전 가드: 타겟 섹션 없으면 홈으로 기본값)
 * Notes:
 *   - aria-current 속성 동시 처리(내비 활성 표시)
 */
function render() {
  const hash = location.hash.replace('#', '');
  const routeKey = hash.split('/')[0];
  const targetSectionId = routeToSectionId[routeKey] ?? 'section-home';

  // 모든 섹션 숨기기 및 내비 활성 상태 제거
  document.querySelectorAll('main > section').forEach(section => {
    const isActive = section.id === targetSectionId;
    section.hidden = !isActive;
    if (isActive) section.focus();
  });

  // 내비 활성 상태 업데이트
  document.querySelectorAll('nav a[href^="#"]').forEach(link => {
    const linkRoute = link.hash.replace('#', '').split('/')[0];
    const linkTarget = routeToSectionId[linkRoute] ?? routeToSectionId[''];
    const isCurrentPage = linkTarget === targetSectionId;
    link.setAttribute('aria-current', isCurrentPage ? 'page' : 'false');
  });
}

/**
 * 라우터 초기화
 * 
 * 해시 변경 이벤트 리스너 등록 및 초기 렌더링
 */
export function initRouter() {
  window.addEventListener('hashchange', render);
  // 초기 로드 시 렌더링
  render();
}
