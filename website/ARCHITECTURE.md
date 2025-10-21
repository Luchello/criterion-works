# 아키텍처 설계 문서

호텔 어필리에이트 웹사이트의 상세 아키텍처 설명 및 모듈 책임 분석.

## 1. 전체 시스템 다이어그램

```
┌─────────────────────────────────────────────────────────────────────┐
│                        User Browser                                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌────────────────────────────────────────────────────────────┐   │
│  │                    criterion-wroks.html                    │   │
│  │  ┌──────────────────────────────────────────────────────┐  │   │
│  │  │              <head> 메타정보/CSS 로드               │  │   │
│  │  │  - 메타 태그 (description, OG, Twitter)            │  │   │
│  │  │  - 스타일 로드: assets/css/styles.css              │  │   │
│  │  └──────────────────────────────────────────────────────┘  │   │
│  │                                                              │   │
│  │  ┌──────────────────────────────────────────────────────┐  │   │
│  │  │                   <body> 콘텐츠                     │  │   │
│  │  │  ┌────────────────────────────────────────────────┐ │  │   │
│  │  │  │ <header><nav>...</nav></header>               │ │  │   │
│  │  │  │ ↓ 내비 클릭 → #home, #hotels, #about, ...    │ │  │   │
│  │  │  └────────────────────────────────────────────────┘ │  │   │
│  │  │                                                      │  │   │
│  │  │  ┌────────────────────────────────────────────────┐ │  │   │
│  │  │  │ <main>                                         │ │  │   │
│  │  │  │   <section id="section-home"> (항상 표시)    │ │  │   │
│  │  │  │   <section id="section-hotels">              │ │  │   │
│  │  │  │     ├─ <div class="hotel-item">...          │ │  │   │
│  │  │  │     │  └─ <a data-aff="booking">예약</a>   │ │  │   │
│  │  │  │     ├─ <div class="hotel-item">...          │ │  │   │
│  │  │  │     └─ ...                                   │ │  │   │
│  │  │  │   <section id="section-about">              │ │  │   │
│  │  │  │   <section id="section-contact">            │ │  │   │
│  │  │  │                                              │ │  │   │
│  │  │  │   (router.js가 하나의 section만 보이게 함) │ │  │   │
│  │  │  └────────────────────────────────────────────────┘ │  │   │
│  │  │                                                      │  │   │
│  │  │  ┌────────────────────────────────────────────────┐ │  │   │
│  │  │  │ <footer>...</footer>                           │ │  │   │
│  │  │  └────────────────────────────────────────────────┘ │  │   │
│  │  │                                                      │  │   │
│  │  │  <script type="module" src="assets/js/app.js">  │  │   │
│  │  │  ├─ imports: router.js, affiliate.js            │  │   │
│  │  │  └─ init() 호출 → initRouter() + initAffiliate() │  │   │
│  │  └──────────────────────────────────────────────────────┘  │   │
│  │                                                              │   │
│  └────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
         ↓ (모듈 초기화)
┌─────────────────────────────────────────────────────────────────────┐
│                    JavaScript 모듈 시스템                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌──────────────────────────────┐  ┌──────────────────────────┐   │
│  │     router.js                │  │   affiliate.js           │   │
│  │  ━━━━━━━━━━━━━━━━━━━━━━━━   │  │  ━━━━━━━━━━━━━━━━━━━━   │   │
│  │  export initRouter()         │  │  export initAffiliate()  │   │
│  │  • hashchange 이벤트 리스너  │  │  • 어필리에이트 ID 읽음  │   │
│  │  • 섹션 show/hide            │  │  • a[data-aff] 감지     │   │
│  │  • 포커스 이동               │  │  • 클릭 시 URL 변형    │   │
│  │  • 내비 활성 상태 관리       │  │  • UTM 파라미터 추가    │   │
│  │  (aria-current)              │  │                          │   │
│  └──────────────────────────────┘  └──────────────────────────┘   │
│           ↑                                 ↑                       │
│           └─────────────────┬───────────────┘                       │
│                             │ (imports)                             │
│                     app.js 에서 호출                             │
│                             │                                       │
└─────────────────────────────────────────────────────────────────────┘
         ↓ (이벤트 바인딩)
┌─────────────────────────────────────────────────────────────────────┐
│                      Runtime Events                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────┐   ┌──────────────────────────┐   │
│  │   User Click Nav Link       │   │  User Click Hotel Link   │   │
│  │  (#home, #hotels, ...)      │   │  (예약 버튼)            │   │
│  │   ↓                         │   │   ↓                      │   │
│  │  hashchange 이벤트          │   │  affiliate:click         │   │
│  │  ↓                         │   │  ↓                      │   │
│  │  render() 실행              │   │  URL 파싱 & 변형         │   │
│  │  ↓                         │   │  (aid, utm_*)           │   │
│  │  다른 섹션 숨기기           │   │  ↓                      │   │
│  │  활성 섹션 표시             │   │  외부 사이트로 이동      │   │
│  │  포커스 이동                │   │  (booking.com + params) │   │
│  │  내비 활성 표시             │   │                         │   │
│  └─────────────────────────────┘   └──────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

## 2. 모듈별 책임 및 인터페이스

### 2.1 router.js (라우팅 엔진)

**책임**:
- 해시 기반 라우팅 관리
- 섹션 표시/숨김 제어
- 포커스 및 접근성 관리

**인터페이스**:
```javascript
export function initRouter() {
  // 라우터 초기화 및 이벤트 리스너 등록
}
```

**내부 상태**:
```javascript
const routeToSectionId = {
  '': 'section-home',
  'home': 'section-home',
  'hotels': 'section-hotels',
  'about': 'section-about',
  'contact': 'section-contact',
};
```

**이벤트 흐름**:
```
브라우저 hashchange 이벤트
  ↓
hash 추출 (location.hash)
  ↓
라우트 맵에서 섹션 ID 조회
  ↓
모든 섹션 반복: 
  - 활성? → hidden=false, focus()
  - 비활성? → hidden=true
  ↓
내비 활성 상태 업데이트 (aria-current)
```

### 2.2 affiliate.js (어필리에이트 링크 관리)

**책임**:
- 어필리에이트 ID 주입
- 링크 클릭 시 UTM 파라미터 추가
- 외부 사이트로의 추적 링크 생성

**인터페이스**:
```javascript
export function initAffiliate() {
  // 어필리에이트 링크 초기화 및 이벤트 리스너 등록
}
```

**데이터 소스**:
- `body[data-aff-id]` → 어필리에이트 ID
- `a[data-aff]` → 어필리에이트 링크
- `data-hotel-name` → 호텔명(옵션)

**쿼리 파라미터 주입 규칙**:
```
원본 URL: https://booking.com/hotel/xyz?lang=ko
  ↓
클릭 시 변환:
https://booking.com/hotel/xyz?lang=ko
  &aid=YOUR_AFFILIATE_ID         (어필리에이트 ID)
  &utm_source=site               (출처)
  &utm_medium=affiliate          (매체)
  &utm_campaign=hotel            (캠페인)
  &utm_content=호텔명             (선택: 세분화)
```

**이벤트 흐름**:
```
페이지 로드
  ↓
body[data-aff-id] 읽음
  ↓
모든 a[data-aff] 찾음
  ↓
각 링크에 click 이벤트 리스너 등록 (once: true)
  ↓
사용자 클릭
  ↓
URL 객체 생성
  ↓
searchParams에 파라미터 추가
  ↓
href 업데이트
  ↓
브라우저 기본 동작 (새 탭/창에서 열기)
```

### 2.3 app.js (부트스트랩)

**책임**:
- 모듈 임포트
- 초기화 함수 호출 (종료 전 모든 시스템 활성화)
- DOMContentLoaded 타이밍 제어

**호출 순서**:
```javascript
1. app.js 파싱
2. router.js, affiliate.js import
3. DOMContentLoaded 대기 (또는 이미 완료)
4. init() 실행:
   - initRouter() → 해시 라우팅 활성화
   - initAffiliate() → 어필리에이트 링크 활성화
5. 사용자 상호작용 대기
```

## 3. 데이터 흐름 및 경계

### 3.1 입력 데이터 소스

1. **HTML 구조** (criterion-wroks.html)
   - `<section id="section-*">` → 라우트 타겟
   - `<a data-aff>` → 어필리에이트 링크
   - `body[data-aff-id]` → 어필리에이트 ID
   - `data-hotel-name` → UTM 세분화

2. **브라우저 이벤트**
   - `hashchange` → router.js에서 처리
   - `click` (a[data-aff]) → affiliate.js에서 처리

3. **환경 변수/설정**
   - 어필리에이트 ID: `body[data-aff-id]` (하드코딩)

### 3.2 출력 및 부작용

1. **라우터 출력**
   - DOM 조작: `section.hidden` 업데이트
   - 포커스 이동: `section.focus()`
   - ARIA: `nav a[aria-current]` 업데이트

2. **어필리에이트 링크 출력**
   - URL 변형: `a.href` 업데이트
   - 외부 네비게이션: `window.open()` (암묵적)

3. **스타일 및 시각**
   - CSS (styles.css)는 HTML 구조에 따라 렌더링

## 4. 확장 가능성 포인트

### 4.1 데이터 외부화 (Phase 2)

**현재**: HTML에 호텔 정보 하드코딩

**향후**: JSON 외부화
```json
// hotels.json
{
  "hotels": [
    {
      "id": 1,
      "name": "호텔명",
      "description": "...",
      "images": ["img1.jpg", "img2.jpg"],
      "nearbyPlaces": ["장소1", "장소2"],
      "facilities": ["시설1", "시설2"],
      "bookingUrl": "https://booking.com/..."
    }
  ]
}
```

**JS로 DOM 렌더링**:
```javascript
fetch('hotels.json')
  .then(res => res.json())
  .then(data => {
    const hotelsContainer = document.getElementById('section-hotels');
    data.hotels.forEach(hotel => {
      const el = createHotelElement(hotel);
      hotelsContainer.appendChild(el);
    });
    // 기존 라우터 및 어필리에이트 다시 초기화
    initRouter();
    initAffiliate();
  });
```

### 4.2 분석 추가 (Phase 3)

**클라이언트 로컬 추적**:
```javascript
// affiliate.js 내에 통계 기록
const stats = JSON.parse(localStorage.getItem('hotelStats') || '{}');
stats[hotelName] = (stats[hotelName] || 0) + 1;
localStorage.setItem('hotelStats', JSON.stringify(stats));
```

**서버 비콘 (배포 후)**:
```javascript
// 클릭 이벤트 전송
navigator.sendBeacon('/api/track', {
  hotelName,
  timestamp: Date.now(),
  utm_params: {...}
});
```

### 4.3 다중 언어 지원 (Phase 5)

**현재**: 한국어 고정

**향후**: `i18n` 패턴
```javascript
// i18n.js
const translations = {
  ko: { home: '홈', hotels: '호텔', ... },
  en: { home: 'Home', hotels: 'Hotels', ... },
};

function t(key) {
  const lang = document.documentElement.lang || 'ko';
  return translations[lang][key];
}
```

### 4.4 호텔별 상세 페이지 (Phase 5)

**현재**: 단일 호텔 리스트

**향후**: 동적 상세 라우트
```javascript
// 라우트 예: /#hotels/1, /#hotels/2 등
const routeToSectionId = {
  'hotels': 'section-hotels',
  'hotels/:id': 'section-hotel-detail',
};

// URL에서 id 추출
const [route, id] = hash.split('/');
if (route === 'hotels' && id) {
  // 상세 페이지 로드
}
```

## 5. 성능 고려사항

### 5.1 현재 최적화

| 항목 | 기법 | 효과 |
|------|------|------|
| 이미지 | `loading="lazy"` | 초기 로드 시간 단축 |
| 폰트 | 시스템 폰트(다운로드 X) | 폰트 요청 제거 |
| 스크립트 | `defer`, 모듈 분할 | 렌더링 블로킹 방지 |
| CSS | 하나의 파일 | 요청 1회, 큐 제거 |
| 레이아웃 | BEM/유틸 | 중복 스타일 최소화 |

### 5.2 향후 최적화

- CSS/JS 최소화 (Vite/esbuild)
- 이미지 WebP 변환
- 정적 사이트 생성 (Eleventy 등)
- CDN 캐싱
- 서버 압축 (gzip/brotli)

## 6. 보안 고려사항

### 6.1 현재 적용

| 항목 | 방법 | 효과 |
|------|------|------|
| 외부 링크 | `rel="nofollow sponsored noopener"` | XSS 방지, SEO 명시 |
| 새 탭 | `target="_blank"` | 원본 페이지 보호 |
| URL 파싱 | try-catch 처리 | 잘못된 URL 처리 |

### 6.2 배포 후 권장사항

- HTTPS 필수
- CSP 헤더 설정
- SRI (Subresource Integrity) 검증
- 정기적 보안 감사

## 7. 접근성(a11y) 아키텍처

### 7.1 구조적 접근성

```html
<!-- 스킵 링크 -->
<a href="#section-home" class="skip-to-main">메인 콘텐츠로 이동</a>

<!-- 시맨틱 구조 -->
<header><nav></nav></header>
<main>
  <section tabindex="-1"></section>
</main>
<footer></footer>

<!-- 내비 활성 표시 -->
<a href="#hotels" aria-current="page">호텔</a>
```

### 7.2 키보드 네비게이션

```
Tab     → 다음 포커스 가능 요소
Shift+Tab → 이전 포커스 가능 요소
Enter   → 링크 클릭
```

### 7.3 스크린리더 지원

- 시맨틱 태그 (`<header>`, `<nav>`, `<main>`)
- `aria-current="page"` (현재 페이지 표시)
- `tabindex="-1"` (섹션, 포커스 받을 준비)
- 이미지 `alt` 속성
- 링크 텍스트 명확

## 8. 테스트 전략

### 8.1 수동 테스트

```
✓ 내비 클릭 → 섹션 변경 확인
✓ 브라우저 뒤로가기 → 해시 변경 확인
✓ 예약 버튼 클릭 → URL 파라미터 추가 확인
✓ 키보드 Tab 네비게이션 → 포커스 순서 확인
✓ 스크린리더 (NVDA, JAWS) → 내용 읽음 확인
```

### 8.2 자동화 테스트 (향후)

```javascript
// router.test.js
test('hashchange on #hotels shows section-hotels', () => {
  location.hash = '#hotels';
  const section = document.getElementById('section-hotels');
  expect(section.hidden).toBe(false);
});

// affiliate.test.js
test('click on a[data-aff] adds aid parameter', () => {
  document.body.dataset.affId = 'test-id';
  initAffiliate();
  const link = document.querySelector('a[data-aff]');
  link.click();
  expect(link.href).toContain('aid=test-id');
});
```

## 결론

이 아키텍처는 다음을 지향합니다:
- **단순성**: 프레임워크 없이 바닐라 JS 사용
- **확장성**: 데이터 외부화, 분석, 다중 언어 준비
- **접근성**: WCAG AA 준수
- **성능**: 최소 리소스, 레이지 로딩
- **유지보수성**: 모듈 분리, 명확한 책임
