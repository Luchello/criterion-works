/**
 * 애플리케이션 부트스트랩
 * 
 * Intent: 페이지 로드 시 라우터, 어필리에이트 링크 데코레이터 초기화
 * Inputs: DOM 문서
 * Outputs: 라우팅 시스템 활성, 어필리에이트 링크 준비
 * Invariants: DOMContentLoaded 이후 실행
 * Complexity: O(n+m) where n=섹션, m=링크
 */

import { initRouter } from './router.js';
import { initAffiliate } from './affiliate.js';

/**
 * 애플리케이션 초기화
 * 
 * 모든 필수 모듈 초기화
 */
function init() {
  initRouter();
  initAffiliate();
}

// DOM 로드 완료 후 초기화
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  // 스크립트가 이미 로드된 후 실행되는 경우
  init();
}
