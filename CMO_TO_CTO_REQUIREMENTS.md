# CMO → CTO: 기술 요구사항

**작성일**: 2026-04-21
**From**: Koreayo CMO
**To**: Koreayo CTO
**목적**: 플랫폼 확장을 위한 기술 구현 요청

---

## 긴급 요청 (Priority 0)

### 1. 모바일 반응형 최적화
**문제**: 현재 GeneratePress Child 테마가 데스크톱 중심으로 설계됨
**영향**: 외국인 사용자 70%가 모바일 접속 예상
**요청**:
```
[ ] 모바일 우선 CSS 작성
[ ] 터치 인터페이스 최적화
[ ] 폰트 크기/간격 모바일 최적화
[ ] 이미지 레이지 로딩 구현
[ ] AMP (Accelerated Mobile Pages) 검토
```

**기대 결과**:
- Mobile PageSpeed Score: 90+ (현재 ~70)
- 모바일 이탈률 50% 감소

---

### 2. 사이트 속도 개선
**현재 상태**: PageSpeed Insights 점수 ~75점
**목표**: 90점 이상

**요청**:
```
[ ] 이미지 최적화 (WebP 변환, 압축)
[ ] CSS/JS 파일 미니파이 & 결합
[ ] CDN 도입 (Cloudflare 또는 AWS CloudFront)
[ ] 데이터베이스 쿼리 최적화
[ ] 캐싱 플러그인 설정 (WP Rocket / W3 Total Cache)
[ ] Lazy Loading 구현 (이미지, 비디오, iframe)
```

**측정 지표**:
- LCP (Largest Contentful Paint): < 2.5s
- FID (First Input Delay): < 100ms
- CLS (Cumulative Layout Shift): < 0.1

---

## 단기 구현 (1-3개월)

### 3. 커뮤니티 기능 구현

#### Option A: WordPress 플러그인 기반 (추천 - 빠른 구현)
```
플러그인 조합:
1. BuddyPress (소셜 네트워크 기능)
   - 사용자 프로필
   - 친구/팔로우 시스템
   - 활동 피드

2. bbPress (포럼)
   - Q&A 게시판
   - 카테고리/태그
   - 검색 기능

3. GamiPress (게임화)
   - 포인트 시스템
   - 뱃지/업적
   - 레더보드

커스터마이징 필요 사항:
- 브랜드 컬러 (#2563EB) 적용
- 모바일 최적화
- 한영 이중 언어 지원
```

#### Option B: Headless CMS (장기 추천)
```
아키텍처:
- 백엔드: WordPress REST API (콘텐츠 관리)
- 프론트엔드: Next.js 14 + React
- 스타일링: Tailwind CSS
- 상태관리: Zustand / Redux
- 인증: NextAuth.js
- 데이터베이스: PostgreSQL (커뮤니티 데이터)

장점:
- 완전한 커스터마이징
- 최고 성능
- 모바일 PWA 전환 용이

단점:
- 개발 기간: 3-4개월
- 개발자 2명 필요
- 유지보수 복잡도 증가
```

**CMO 추천**: Option A (빠른 출시) → Option B로 점진적 전환

---

### 4. 필수 기능 스펙

#### 4.1 Q&A 포럼
```
기능 요구사항:
- [x] 카테고리: Visa, Housing, Banking, Healthcare, Daily Life
- [x] 태그 시스템 (#e2visa, #deposit, #hikorea)
- [x] 투표 기능 (Upvote/Downvote)
- [x] "Best Answer" 선택
- [x] 검색 기능 (전체 텍스트 검색)
- [x] 댓글 중첩 (최대 3레벨)
- [x] 이미지 첨부 (드래그 앤 드롭)
- [x] 스팸 필터링 (Akismet)

UX 요구사항:
- 질문 작성 시 유사 질문 자동 제안 (중복 방지)
- 카테고리별 필터링
- "Unanswered Questions" 우선 표시
```

#### 4.2 사용자 프로필
```
기본 정보:
- 닉네임 (필수)
- 국적 (선택)
- 비자 타입 (선택)
- 거주 도시 (선택)
- 한국 거주 기간 (선택)

활동 지표:
- 작성한 질문/답변 수
- 받은 추천 수
- 뱃지/업적
- 기여도 점수 (Karma)

프라이버시:
- 프로필 공개/비공개 설정
- 이메일 숨김
- 활동 내역 숨김 옵션
```

#### 4.3 알림 시스템
```
알림 유형:
1. 실시간 알림 (브라우저)
   - 내 질문에 답변 달림
   - 내 답변에 댓글 달림
   - 내 답변이 "Best Answer" 선택됨

2. 이메일 알림 (일일 요약)
   - 팔로우한 태그의 새 질문
   - 답변 받지 못한 내 질문 (24시간 후)
   - 주간 인기 질문 (매주 월요일)

3. 푸시 알림 (PWA)
   - 긴급 알림만 (설정 가능)

기술 구현:
- OneSignal (푸시 알림 서비스)
- 또는 Firebase Cloud Messaging
- 알림 설정 페이지 (사용자 맞춤화)
```

---

### 5. 뉴스레터 시스템 통합
```
요구사항:
- WordPress 회원가입 시 자동 뉴스레터 구독 옵션
- 구독자 세그먼트 (관심사, 비자 타입)
- 이메일 템플릿 (HTML)

기술 옵션:
A. Mailchimp (추천)
   - WordPress 플러그인 있음
   - 무료 플랜: 500명까지
   - 자동화 기능

B. ConvertKit
   - 크리에이터 친화적
   - 태그 기반 세그먼트

C. SendGrid (커스텀)
   - API 통합
   - 발송 제어 가능

구현 필요:
- [ ] 회원가입 폼에 구독 체크박스
- [ ] 프로필 페이지에서 구독 관리
- [ ] 이메일 템플릿 디자인
- [ ] A/B 테스팅 설정
```

---

### 6. SEO 기술 구현
```
필수 구현:
- [x] Schema.org 마크업
  - Article (블로그 포스트)
  - FAQPage (Q&A)
  - BreadcrumbList (빵가루)
  - Organization (사이트 정보)

- [x] Open Graph 태그 (소셜 공유)
  - og:title, og:description, og:image
  - Twitter Cards

- [x] Sitemap 최적화
  - XML Sitemap (Yoast SEO)
  - 카테고리별 Sitemap
  - 이미지 Sitemap

- [x] Robots.txt 설정
  - 크롤 우선순위
  - 불필요한 페이지 제외

- [ ] Canonical URLs
  - 중복 콘텐츠 방지

- [ ] Structured Data 테스트
  - Google Rich Results Test 통과
```

---

### 7. 분석 & 추적 시스템
```
Google Analytics 4:
- [x] 기본 설치 완료
- [ ] 이벤트 추적 설정
  - 회원가입
  - 질문 작성
  - 답변 작성
  - 프리미엄 구독
  - 외부 링크 클릭

- [ ] 전환 목표 설정
  - 회원가입 완료
  - 첫 질문 작성
  - 7일 재방문
  - 프리미엄 전환

Google Tag Manager:
- [ ] GTM 컨테이너 설치
- [ ] 이벤트 태그 설정
- [ ] 변수/트리거 구성

Hotjar (선택):
- [ ] 히트맵 설치
- [ ] 세션 녹화 (샘플링 10%)
- [ ] 피드백 위젯
```

---

## 중기 구현 (3-6개월)

### 8. PWA (Progressive Web App) 전환
```
목표:
- 홈 화면에 추가 가능
- 오프라인 기본 기능
- 푸시 알림 지원
- 빠른 로딩 (캐싱)

기술 스택:
- Service Worker 등록
- Web App Manifest 파일
- Workbox (Google의 SW 라이브러리)

구현 순서:
1. HTTPS 완전 전환 (필수)
2. Manifest.json 작성
3. Service Worker 구현
4. 오프라인 폴백 페이지
5. 푸시 알림 권한 요청 UX
6. App Icon 디자인 (512x512)
```

---

### 9. 프리미엄 멤버십 시스템
```
기술 요구사항:
- [ ] 결제 게이트웨이 통합
  - 국내: 토스페이먼츠 / 카카오페이
  - 해외: Stripe (카드 결제)
  - 정기 결제 지원

- [ ] 구독 관리 시스템
  - 플랜 변경 (Free ↔ Premium ↔ Pro)
  - 자동 갱신
  - 취소/환불 처리

- [ ] 접근 제어
  - 프리미엄 콘텐츠 락
  - 광고 숨김 (조건부 CSS)
  - 다운로드 링크 생성

추천 플러그인:
- MemberPress (WordPress)
- Restrict Content Pro
- 또는 커스텀 개발 (User Role 기반)

보안:
- [ ] PCI DSS 준수 (카드 정보 비저장)
- [ ] 정기 결제 실패 시 알림
- [ ] 구독 만료 전 알림 (7일, 3일, 1일)
```

---

### 10. 다국어 지원 (i18n)
```
언어 우선순위:
1. English (기본)
2. 中文 (중국어 간체)
3. 日本語 (일본어)
4. Tiếng Việt (베트남어)

기술 구현:
Option A: WPML 플러그인 (WordPress)
- 장점: 쉬운 설정
- 단점: 라이센스 비용 ($99/년)

Option B: Polylang (무료)
- 장점: 무료, 가벼움
- 단점: 기능 제한적

Option C: Next.js i18n (Headless 전환 시)
- 장점: 최고 성능
- 단점: 커스텀 개발 필요

번역 워크플로우:
1. 자동 번역 (Google Translate API)
2. 네이티브 에디터 검수
3. 번역 메모리 구축 (재사용)
```

---

## 장기 구현 (6-12개월)

### 11. 고급 기능

#### 11.1 AI 챗봇 (FAQ 자동 응답)
```
기능:
- 사용자 질문 → AI가 기존 콘텐츠에서 답변 추출
- 24/7 즉각 응답
- 답변 불가 시 커뮤니티 질문 등록 유도

기술:
- OpenAI GPT-4 API
- RAG (Retrieval-Augmented Generation)
  - 벡터 DB: Pinecone / Weaviate
  - 임베딩: OpenAI Embeddings
- 챗봇 UI: Intercom / Drift 또는 커스텀

비용:
- OpenAI API: ~$0.03/1K tokens
- 월 예상: $200-500 (질문 수에 따라)
```

#### 11.2 마켓플레이스
```
기능:
- 중고 거래 (가구, 전자제품)
- 룸메이트 찾기
- 서비스 교환

기술:
- WooCommerce (WordPress)
- 또는 커스텀 리스팅 시스템
- 결제: 에스크로 (안전 거래)
- 채팅: Sendbird / Twilio

보안:
- 사기 방지 시스템
- 사용자 신원 인증 (선택)
- 신고 기능
```

#### 11.3 이벤트 관리 시스템
```
기능:
- 오프라인 밋업 등록
- 온라인 웨비나 예약
- 캘린더 통합 (Google Calendar)

기술:
- The Events Calendar (플러그인)
- 또는 Eventbrite API 연동
- Zoom API (웨비나)
```

---

## 인프라 & 보안

### 12. 호스팅 & 서버
```
현재 상태 파악 필요:
- [ ] 현재 호스팅 제공업체?
- [ ] 서버 스펙 (CPU, RAM, 저장공간)?
- [ ] 월 트래픽 용량 제한?
- [ ] 백업 정책?

권장 사항:
- 호스팅: Kinsta / WP Engine (관리형 WordPress)
  - 자동 백업
  - 스테이징 환경
  - CDN 포함
  - 보안 패치 자동

- 또는 AWS / Google Cloud (확장성)
  - EC2 / Compute Engine
  - RDS / Cloud SQL (DB)
  - S3 / Cloud Storage (미디어)

예산:
- 관리형 WordPress: $30-100/월
- 클라우드 (직접 관리): $50-200/월
```

### 13. 보안 강화
```
필수 구현:
- [x] SSL/TLS 인증서 (HTTPS)
- [ ] 방화벽 (Cloudflare / Sucuri)
- [ ] DDoS 방어
- [ ] 정기 백업 (일일)
- [ ] 악성코드 스캔 (주간)
- [ ] 2FA (관리자 계정)
- [ ] 비밀번호 정책 강화
- [ ] IP 블랙리스트
- [ ] Rate Limiting (API 요청 제한)

모니터링:
- [ ] Uptime 모니터링 (UptimeRobot)
- [ ] 에러 로깅 (Sentry)
- [ ] 성능 모니터링 (New Relic / Datadog)
```

---

## 개발 프로세스

### 14. 개발 환경 구성
```
필요 환경:
1. Local Development
   - Local by Flywheel (WordPress)
   - 또는 Docker 컨테이너

2. Staging Environment
   - 프로덕션 미러 사이트
   - 새 기능 테스트

3. Production
   - 실제 서비스

Git Workflow:
- 브랜치 전략: Git Flow
  - main (프로덕션)
  - develop (개발)
  - feature/* (기능 개발)
  - hotfix/* (긴급 수정)

CI/CD:
- GitHub Actions 또는 GitLab CI
- 자동 배포 (Staging → Production)
```

---

## 개발 리소스 산정

### 15. 인력 & 시간
```
Phase 1 (0-3개월):
- 백엔드 개발자: 1명 (풀타임)
  - 커뮤니티 플러그인 커스터마이징
  - API 개발
  - 데이터베이스 설계

- 프론트엔드 개발자: 1명 (파트타임)
  - 모바일 반응형 CSS
  - UI/UX 개선

예상 공수: 480시간 (3개월)

Phase 2 (3-6개월):
- 풀스택 개발자: 1명 (풀타임)
  - PWA 전환
  - 프리미엄 기능 개발

예상 공수: 480시간 (3개월)

Phase 3 (6-12개월):
- 풀스택 개발자: 1-2명
  - 고급 기능 (AI, 마켓플레이스)
  - 성능 최적화

예상 공수: 960시간 (6개월)
```

### 16. 예산
```
개발 인건비:
- 주니어 개발자: ₩4,000,000/월
- 시니어 개발자: ₩7,000,000/월

Phase 1: ₩15,000,000 (개발자 2명 × 3개월)
Phase 2: ₩21,000,000 (개발자 1명 × 3개월)
Phase 3: ₩42,000,000 (개발자 2명 × 3개월)

총 개발 비용: ₩78,000,000 (12개월)

서버/도구 비용:
- 호스팅: ₩100,000/월 × 12 = ₩1,200,000
- CDN: ₩50,000/월 × 12 = ₩600,000
- 플러그인 라이센스: ₩500,000/년
- 모니터링/분석 도구: ₩300,000/월 × 12 = ₩3,600,000

총 인프라 비용: ₩5,900,000 (12개월)

총 예산: ₩83,900,000 (약 ₩84,000,000)
```

---

## 질문 리스트 (CTO 답변 필요)

1. **현재 호스팅 환경은?** (제공업체, 플랜, 스펙)
2. **WordPress 버전 및 설치된 플러그인 목록**
3. **데이터베이스 크기 및 성능 상태**
4. **백엔드 개발자 확보 가능 여부** (사내 또는 외주)
5. **Headless CMS 전환에 대한 의견** (찬성/반대/유보)
6. **PWA 전환 타임라인 현실성** (6개월 내 가능?)
7. **보안 감사 최근 실시 여부**
8. **CI/CD 파이프라인 구축 경험**
9. **다국어 지원 기술 선호도** (WPML vs Polylang vs 커스텀)
10. **AI 챗봇 도입 의견** (필요성, 우선순위)

---

## 다음 미팅 안건

**일시**: 2026년 4월 28일 (월) 14:00
**장소**: 온라인 (Zoom)
**참석자**: CMO, CTO, CBO

**안건**:
1. 기술 스택 최종 결정 (WordPress vs Headless)
2. Phase 1 개발 범위 확정
3. 개발 리소스 확보 방안
4. 예산 승인 프로세스
5. 타임라인 합의

**사전 준비**:
- CTO: 현재 시스템 진단 리포트
- CMO: 경쟁사 기술 분석
- CBO: 재무 계획 검토

---

**문서 작성자**: Koreayo CMO
**최종 수정**: 2026-04-21
**문서 상태**: 초안 (CTO 리뷰 대기)
