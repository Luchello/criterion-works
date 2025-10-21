# 호텔 어필리에이트 웹사이트

단순하고 확장 가능한 호텔 소개 및 예약 어필리에이트 단일 페이지 웹사이트.

## 특징

- **초단순 구조**: 단일 페이지에 호텔 리스트만 표시 (네비게이션 없음)
- **어필리에이트 링크 자동화**: 예약 버튼 클릭 시 자동으로 ID 및 UTM 파라미터 주입
- **모바일 퍼스트 디자인**: 반응형 레이아웃, 시스템 폰트, 명시적 색 대비
- **접근성 준수**: 시맨틱 HTML, 스크린리더 지원
- **순수 JS**: HTML, CSS, Vanilla JavaScript만 사용(프레임워크 없음)

## 파일 구조

```
website/
├── criterion-wroks.html          # 메인 HTML 엔트리 (호텔 리스트만)
├── assets/
│   ├── css/
│   │   └── styles.css            # 전체 스타일(모바일 퍼스트)
│   ├── js/
│   │   └── affiliate.js          # 어필리에이트 링크 데코레이터
│   └── img/
│       └── (호텔 이미지 자산)
└── README.md                     # 본 문서
```

## 사용법

### 1. 기본 열기

브라우저에서 `criterion-wroks.html` 파일을 직접 열거나, 
로컬 서버에서 제공합니다(모듈 스크립트 사용으로 CORS 필요).

```bash
# 간단한 로컬 서버 실행 (Python 3)
python -m http.server 8000
# 또는 (Python 2)
python -m SimpleHTTPServer 8000
```

그 후 `http://localhost:8000/website/` 접속.

### 2. 어필리에이트 ID 설정

HTML의 `<body>` 태그에서 `data-aff-id` 값을 변경:

```html
<body data-aff-id="YOUR_AFFILIATE_ID">
  ...
</body>
```

**YOUR_AFFILIATE_ID**를 실제 어필리에이트 ID로 교체합니다.

### 3. 호텔 정보 수정

각 호텔 항목은 `section#section-hotels` 내부의 `.hotel-item` 클래스 요소입니다:

```html
<div class="hotel-item" data-hotel-name="호텔명">
  <div class="hotel-images">
    <img src="path/to/image.jpg" alt="호텔 이미지" loading="lazy">
  </div>
  <div class="hotel-info">
    <h3>호텔명</h3>
    <p>설명...</p>
    <!-- 목록 및 예약 버튼 -->
  </div>
</div>
```

### 4. 예약 링크 업데이트

각 호텔의 예약 버튼 `href`를 실제 예약 링크로 변경:

```html
<a href="https://booking.com/hotel/YOUR_HOTEL_ID?" 
   data-aff="booking" 
   rel="nofollow sponsored noopener" 
   target="_blank"
   class="btn btn-primary">지금 예약하기</a>
```

**중요**: `data-aff` 속성은 어필리에이트 공급자 식별자이며, 클릭 시 URL에 `aid`, `utm_source`, `utm_medium`, `utm_campaign` 파라미터가 자동으로 추가됩니다.

## 아키텍처

### 어필리에이트 링크 데코레이터 (affiliate.js)

- `body[data-aff-id]`에서 어필리에이트 ID 읽음
- `a[data-aff]` 링크 감지
- 클릭 시 쿼리 파라미터 주입:
  - `aid`: 어필리에이트 ID
  - `utm_source=site`
  - `utm_medium=affiliate`
  - `utm_campaign=hotel`
  - `utm_content`: 호텔명(옵션)

### 스타일 시스템 (styles.css)

- **리셋**: 일관된 기본값(마진, 패딩, 박스 모델)
- **레이아웃**: 최대 너비 1200px, 반응형 그리드
- **타이포그래피**: 시스템 폰트, 명시적 색 대비
- **유틸 클래스**: `.stack`, `.grid`, `.flex`, `.mt-*`, `.mb-*` 등
- **컴포넌트**: 버튼, 카드, 호텔 항목
- **접근성**: 포커스 가시성, 스킵 링크, `sr-only` 숨김
- **반응형**: 모바일 퍼스트 미디어 쿼리
- **다크모드**: 선택적 지원(향후)

## SEO 및 메타정보

### 기본 메타 태그

```html
<meta name="description" content="...">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0066cc">
```

### 오픈그래프(소셜 공유)

```html
<meta property="og:type" content="website">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:url" content="...">
```

### JSON-LD(향후)

구조화된 데이터를 추가하려면 `<head>`에 다음을 삽입:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "호텔 가이드",
  "url": "https://example.com"
}
</script>
```

## 확장 로드맵

### Phase 1: 현재 (정적 HTML + 어필리에이트)
- ✅ 단일 페이지 라우팅
- ✅ 어필리에이트 링크 자동화
- ✅ 모바일 반응형
- ✅ 접근성 지원

### Phase 2: 데이터 관리 (JSON 외부화)
```javascript
// 향후 hotels.json 도입
const hotels = [
  { name: "호텔1", description: "...", link: "..." },
  ...
];
```
- `hotels.json`로 콘텐츠 분리
- JS로 DOM 생성(템플릿 엔진 고려)
- 콘텐츠 유지보수 편의성 증대

### Phase 3: 분석 및 추적
- 클라이언트 로컬 통계(localStorage)
- 서버 비콘 통합(배포 후)
- 클릭 전환 율(CTR) 모니터링

### Phase 4: 배포 및 최적화
- 정적 호스팅(Netlify, Vercel, GitHub Pages)
- CSS/JS 최소화
- 이미지 최적화 및 WebP 변환
- CDN 캐싱 설정

### Phase 5: 고급 기능 (선택사항)
- 다중 언어 지원
- 다크모드 토글
- 필터링 및 정렬
- 호텔별 상세 페이지
- 사용자 리뷰 또는 평점

## 성능 최적화

### 현재 적용

- CSS `display: block` 이미지 로드 최소화
- `loading="lazy"` 지연 로딩
- 시스템 폰트(외부 폰트 없음)
- `defer` 스크립트 로딩(비차단)
- 모듈 기반 JS(필요시만 파싱)

### 향후 권장사항

- CSS/JS 번들 최소화(Vite/esbuild)
- 이미지 스프라이트 또는 아이콘 폰트
- 서버 압축(gzip/brotli)
- 정적 페이지 생성(후속 배포)

## 보안 고려사항

### 현재 적용

- `rel="nofollow sponsored noopener"` 외부 링크
- `target="_blank"` 새 탭 열기
- URL 파싱 예외 처리(affiliate.js)
- 메타 태그로 콘텐츠 명시

### 향후 권장사항

- HTTPS 배포 필수
- Content Security Policy(CSP) 헤더
- 사용자 입력 검증(관리자 패널 시)
- 로깅 및 모니터링

## 접근성(a11y)

- ✅ 키보드 네비게이션(Tab, Enter)
- ✅ 포커스 가시성(2px 아웃라인)
- ✅ 스크린리더 지원(ARIA, 시맨틱 태그)
- ✅ 색 대비 명확(WCAG AA 준수)
- ✅ 스킵 링크(메인 콘텐츠로 이동)
- ✅ 이미지 alt 텍스트
- ✅ 동작 축소 지원(`prefers-reduced-motion`)

## 개발 팁

### 스크립트 모듈 디버깅

브라우저 콘솔(F12)에서 라우터 상태 확인:

```javascript
// 현재 활성 섹션 확인
document.querySelector('main > section:not([hidden])').id

// 해시 변경
location.hash = '#hotels'

// 어필리에이트 링크 확인
document.querySelectorAll('a[data-aff]')
```

### 로컬 테스트

```bash
# Python 3으로 서버 실행
cd website
python -m http.server 8000

# 접속
open http://localhost:8000/criterion-wroks.html
```

### 유틸 클래스 확장

`styles.css`의 유틸 섹션에 필요한 클래스 추가:

```css
/* 간격 유틸 확장 */
.mt-5 { margin-top: 2.5rem; }
.p-5 { padding: 2.5rem; }

/* 텍스트 유틸 확장 */
.text-lg { font-size: 1.125rem; }
.font-bold { font-weight: 700; }
```

## 라이선스

(프로젝트에 맞는 라이선스 추가)

## 지원

호텔 추가, 오류 신고, 개선 제안은 `contact@example.com`으로 연락주세요.
