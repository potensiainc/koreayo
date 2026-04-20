# PRD: Koreayo n8n Workflow v5 - Google AdSense Approval Optimization

**Document Version**: 1.0
**Created**: April 11, 2026
**Author**: Koreayo Development Team
**Target Workflow**: `koreayo_n8n_workflow_v4_content_quality.json` → `v5`
**Reference**: `IMPLEMENTATION_PLAN.md`

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Background & Problem Statement](#2-background--problem-statement)
3. [Goals & Success Metrics](#3-goals--success-metrics)
4. [Scope](#4-scope)
5. [Detailed Requirements](#5-detailed-requirements)
   - 5.1 [HTML Structure Fixes](#51-html-structure-fixes)
   - 5.2 [Content Depth Enhancement](#52-content-depth-enhancement)
   - 5.3 [E-E-A-T Signal Implementation](#53-e-e-a-t-signal-implementation)
6. [Technical Specifications](#6-technical-specifications)
7. [Node-by-Node Modification Plan](#7-node-by-node-modification-plan)
8. [Testing & Validation](#8-testing--validation)
9. [Rollback Plan](#9-rollback-plan)
10. [Timeline & Prioritization](#10-timeline--prioritization)

---

## 1. Executive Summary

### Purpose
Google AdSense 승인을 획득하기 위해 Koreayo 블로그 콘텐츠 품질 문제를 해결하는 n8n 워크플로우 v5 개선 계획.

### Current State (v4)
- HTML 구조 오류: CSS가 `<p>` 태그 내에 삽입됨
- 얕은 콘텐츠: 약 500단어, 구체적 데이터 부족
- 약한 E-E-A-T 신호: 저자 정보 없음, 날짜 표시 없음
- 기술적 버그: 번호 목록이 1, 1, 1로 표시됨

### Target State (v5)
- 유효한 HTML 구조 (W3C 검증 통과)
- 1,200+ 단어의 심층 콘텐츠
- 강력한 E-E-A-T 신호 (저자, 날짜, 공식 출처)
- 올바른 번호 목록 렌더링 (1, 2, 3...)

---

## 2. Background & Problem Statement

### 2.1 Google AdSense 거부 원인 분석

| 문제 영역 | 현재 상태 | 영향도 | 해결 난이도 |
|-----------|-----------|--------|-------------|
| HTML 구조 | `<p><style>...</style></p>` 무효 HTML | Critical | Medium |
| CSS 내 `<br/>` | WordPress wpautop이 CSS 내부에 `<br/>` 삽입 | Critical | Low |
| 번호 목록 | 각 항목이 개별 `<ol>`로 감싸져 1, 1, 1 표시 | High | Medium |
| 관련 글 잘림 | 특수문자 이스케이프 실패로 HTML 잘림 | High | Medium |
| 콘텐츠 깊이 | 500단어 미만, 구체적 데이터 없음 | Critical | Low |
| 공식 출처 | HiKorea, 1345 등 공식 소스 링크 없음 | High | Low |
| 저자 정보 | 저자 박스 없음, 익명 콘텐츠 | High | Low |
| 업데이트 날짜 | 날짜 메타데이터 미표시 | Medium | Low |

### 2.2 Root Cause Analysis

#### HTML 구조 오류 원인
```
n8n markdownToStyledHtml() → <style> 태그를 HTML 문자열 시작에 배치
                    ↓
WordPress 저장 → wpautop 필터가 모든 콘텐츠를 <p> 태그로 감쌈
                    ↓
결과: <p><style>...</style></p> (무효 HTML)
```

#### 번호 목록 오류 원인
```
Markdown:
1. Step One
2. Step Two
3. Step Three

현재 출력:
<ol><li>1. Step One</li></ol>
<ol><li>1. Step Two</li></ol>    ← 각각 별도 <ol>로 카운터 리셋
<ol><li>1. Step Three</li></ol>
```

---

## 3. Goals & Success Metrics

### 3.1 Primary Goals

| Goal ID | Description | Success Criteria |
|---------|-------------|------------------|
| G1 | HTML 구조 유효성 | W3C HTML Validator 오류 0개 |
| G2 | 콘텐츠 깊이 향상 | 평균 1,200+ 단어/글 |
| G3 | E-E-A-T 신호 강화 | 저자 박스 + 날짜 + 공식 출처 포함 |
| G4 | 기술적 버그 해결 | 번호 목록 1, 2, 3... 정상 표시 |
| G5 | AdSense 승인 | Google AdSense 승인 획득 |

### 3.2 Key Performance Indicators (KPIs)

| KPI | Current | Target | Measurement Method |
|-----|---------|--------|-------------------|
| HTML 유효성 점수 | ~60% | 100% | W3C Validator |
| 평균 단어 수 | 500 | 1,200+ | Word Count Tool |
| E-E-A-T 요소 포함률 | 0% | 100% | Manual Audit |
| 번호 목록 정확도 | 0% | 100% | Visual Inspection |
| 관련 글 렌더링 성공률 | ~70% | 100% | Output Analysis |

---

## 4. Scope

### 4.1 In Scope

#### n8n Workflow Modifications
- **Build Draft Prompt** 노드 수정
- **Build QA Prompt** 노드 수정
- **Parse QA JSON** 노드 수정
- **WordPress Create Draft** 노드 수정

#### New Features
- CSS 분리 (HTML 본문에서 제거)
- 콘텐츠 길이 요구사항 추가
- 공식 출처 섹션 자동 생성
- 저자 박스 삽입
- 업데이트 날짜 메타데이터 추가
- 연속 번호 목록 그룹화 로직

### 4.2 Out of Scope

- WordPress 테마 functions.php 수정 (별도 문서)
- 새로운 n8n 노드 추가 (기존 노드 수정만)
- Google Sheets 스키마 변경
- API 인증 정보 변경

### 4.3 Dependencies

| Dependency | Type | Owner | Status |
|------------|------|-------|--------|
| WordPress functions.php 수정 | External | DevOps | Required |
| CSS를 wp_head에 등록 | External | DevOps | Required |
| OpenAI API | Internal | Workflow | Active |

---

## 5. Detailed Requirements

### 5.1 HTML Structure Fixes

#### REQ-HTML-001: CSS 분리
**Priority**: P0 (Critical)
**Node**: Parse QA JSON

**Current Behavior**:
```javascript
const styles = `<style>...</style>`;
const finalHtml = `${styles}\n<div class="koreayo-article">...`;
```

**Required Behavior**:
```javascript
// CSS 완전 제거 - WordPress functions.php에서 로드
const finalHtml = `<div class="koreayo-article">...`;
```

**Acceptance Criteria**:
- [ ] `markdownToStyledHtml()` 함수에서 `styles` 변수 완전 제거
- [ ] 출력 HTML에 `<style>` 태그 없음
- [ ] WordPress 페이지에서 스타일 정상 적용 (functions.php 의존)

---

#### REQ-HTML-002: WordPress Raw HTML Block Wrapper
**Priority**: P0 (Critical)
**Node**: WordPress Create Draft

**Current Behavior**:
```javascript
content: "={{$json.final_body_html}}"
```

**Required Behavior**:
```javascript
content: "=<!-- wp:html -->{{$json.final_body_html}}<!-- /wp:html -->"
```

**Acceptance Criteria**:
- [ ] 콘텐츠가 `<!-- wp:html -->` 블록으로 감싸짐
- [ ] WordPress wpautop 필터가 콘텐츠에 적용되지 않음
- [ ] HTML 구조가 원본 그대로 저장됨

---

#### REQ-HTML-003: 번호 목록 그룹화
**Priority**: P0 (Critical)
**Node**: Parse QA JSON

**Current Behavior**:
```javascript
// 각 라인별로 개별 <ol> 생성
html = html.replace(/^\d+\.\s+(.+)$/gm, '<ol class="koreayo-list-ordered"><li>$1</li></ol>');
```

**Required Behavior**:
```javascript
// 연속된 번호 목록을 하나의 <ol>로 그룹화
function groupConsecutiveNumberedLists(lines) {
  const result = [];
  let currentOlItems = [];

  for (const line of lines) {
    const olMatch = line.match(/^\d+\.\s+(.+)$/);

    if (olMatch) {
      currentOlItems.push(`<li>${olMatch[1]}</li>`);
    } else {
      if (currentOlItems.length > 0) {
        result.push(`<ol class="koreayo-list-ordered">${currentOlItems.join('')}</ol>`);
        currentOlItems = [];
      }
      result.push(line);
    }
  }

  if (currentOlItems.length > 0) {
    result.push(`<ol class="koreayo-list-ordered">${currentOlItems.join('')}</ol>`);
  }

  return result;
}
```

**Acceptance Criteria**:
- [ ] 연속된 번호 항목이 단일 `<ol>` 태그 내에 포함됨
- [ ] 번호가 1, 2, 3, 4, 5... 순서로 표시됨
- [ ] 목록 사이에 다른 콘텐츠가 있으면 별도 `<ol>`로 분리됨

---

#### REQ-HTML-004: 관련 글 데이터 검증 및 이스케이프
**Priority**: P1 (High)
**Node**: Parse QA JSON

**Current Behavior**:
```javascript
const relatedArticles = src.related_articles || [];
// 직접 사용, 검증 없음
```

**Required Behavior**:
```javascript
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return String(text).replace(/[&<>"']/g, m => map[m]);
}

function isValidUrl(url) {
  try {
    const urlObj = new URL(url);
    return urlObj.protocol === 'https:';
  } catch {
    return false;
  }
}

const relatedArticles = (src.related_articles || [])
  .filter(article => {
    if (!article || !article.url || !article.title) return false;
    if (!String(article.url).startsWith('https://')) return false;
    if (String(article.title).trim() === '') return false;
    return true;
  })
  .map(article => ({
    title: escapeHtml(String(article.title).substring(0, 100)),
    url: String(article.url).trim()
  }))
  .filter(article => isValidUrl(article.url))
  .slice(0, 3);
```

**Acceptance Criteria**:
- [ ] 제목의 특수문자(`<`, `>`, `"`, `'`, `&`)가 HTML 엔티티로 이스케이프됨
- [ ] 유효하지 않은 URL을 가진 관련 글은 필터링됨
- [ ] HTTPS URL만 허용됨
- [ ] 최대 3개의 관련 글만 표시됨
- [ ] 제목이 100자로 제한됨

---

### 5.2 Content Depth Enhancement

#### REQ-CONTENT-001: 최소 단어 수 요구사항
**Priority**: P0 (Critical)
**Node**: Build Draft Prompt

**Addition to System Prompt**:
```
=== CONTENT LENGTH REQUIREMENTS ===

- **Minimum word count**: 1,200 words (English baseline)
- **Each H2 section**: 150-200 words minimum
- **Introduction**: 100+ words establishing problem context and solution overview
- **Body sections**: Concrete examples, scenarios, edge cases, exceptions
- **Prohibition**: No simple enumerations like "A, B, C are options"
  - Instead: Explain WHY each option matters, when to choose it, limitations

Paragraph structure:
- Minimum 3-5 sentences per paragraph
- Lead with main idea, follow with supporting details
- Maximum 2-3 short paragraphs before breaking for subheading or list
```

**Addition to User Prompt**:
```
IMPORTANT: This article must be at least 1,200 words.
Include:
1. Problem setup (why reader cares about this topic)
2. Timeline considerations (when to start process, deadlines)
3. Common variations by situation/visa type
4. Troubleshooting section (what if X goes wrong?)
5. Scenario-based examples (e.g., "If you're on F-2 visa...")
6. Practical timeline (day-by-day or step-by-step expectations)
```

**Acceptance Criteria**:
- [ ] 프롬프트에 최소 단어 수 요구사항 명시
- [ ] 각 섹션별 최소 단어 수 가이드라인 포함
- [ ] 시나리오 기반 예시 작성 지침 포함

---

#### REQ-CONTENT-002: QA 콘텐츠 깊이 점수
**Priority**: P1 (High)
**Node**: Build QA Prompt

**Addition to Scoring Schema**:
```json
"scores": {
  "search_alignment": "0-100",
  "clarity": "0-100",
  "practicality": "0-100",
  "trustworthiness": "0-100",
  "structure": "0-100",
  "seo_fit": "0-100",
  "prose_quality": "0-100",
  "content_depth": "0-100"  // NEW
}
```

**Scoring Guidance Addition**:
```
"content_depth": {
  "90-100": "1,500+ words with detailed scenarios, examples, edge cases",
  "75-89": "1,200-1,499 words with good section development",
  "60-74": "900-1,199 words but missing some depth",
  "40-59": "600-899 words, too thin",
  "0-39": "<600 words, insufficient"
}

IMPORTANT: If content_depth < 75, decision MUST be "revise" with instruction to expand content.
```

**Acceptance Criteria**:
- [ ] `content_depth` 점수가 QA 출력에 포함됨
- [ ] 점수 75 미만 시 자동으로 `revise` 결정
- [ ] 콘텐츠 확장 지침이 issues에 포함됨

---

#### REQ-CONTENT-003: 구체적 데이터 요구사항
**Priority**: P1 (High)
**Node**: Build Draft Prompt

**Addition to System Prompt**:
```
=== SPECIFIC DATA REQUIREMENTS ===

For EVERY relevant fact, provide specificity:

❌ WRONG: "The fee is charged"
✅ RIGHT: "The fee is approximately 30,000-60,000 KRW for standard visa extensions"
   Alternative if uncertain: "Fees vary by visa type (typically 30,000-100,000 KRW); confirm current rates at hikorea.go.kr"

❌ WRONG: "Processing takes a few days"
✅ RIGHT: "Standard processing takes 5-10 business days; expedited option available (1-2 days) with additional fee"

❌ WRONG: "You need supporting documents"
✅ RIGHT: "Required: passport, alien registration card, Form [specify].
   Supporting docs depend on visa type:
   - Employment visa: employment letter on company letterhead
   - Student visa: school enrollment proof + payment receipts
   - Spouse visa: marriage certificate copy"

❌ WRONG: "Contact the immigration office"
✅ RIGHT: "Contact the Seoul Immigration Office (02-2150-1550)
   Hours: Monday-Friday 9 AM-4 PM, Closed 12-1 PM lunch"

=== UNCERTAINTY HANDLING ===

If you cannot verify a specific number/date/procedure:
- Lead with timeframe: "As of [current month/year]..."
- Qualify: "Approximately X-Y (subject to change)"
- Direct to official source: "Confirm latest rates at hikorea.go.kr or call 1345"
```

**Acceptance Criteria**:
- [ ] 모든 수치 데이터에 구체적 범위 또는 출처 포함
- [ ] 불확실한 정보에 적절한 한정어 사용
- [ ] 공식 출처로의 확인 안내 포함

---

#### REQ-CONTENT-004: 공식 출처 섹션 필수화
**Priority**: P1 (High)
**Nodes**: Build Draft Prompt, Build QA Prompt, Parse QA JSON

**Draft Prompt Addition**:
```
=== MANDATORY OFFICIAL RESOURCES SECTION ===

Every article MUST include "Where to Find Official Information" section.

Location: H2 heading near end of article (before FAQ)

Must include (where applicable):
- Primary resource: "HiKorea (hikorea.go.kr) - Official portal for foreigner visa services"
- Contact: "Immigration Contact Center: 1345 (Korean, English, Chinese)
   Available: 24/7 for phone, business hours for in-person"
- Authority: "Republic of Korea Immigration Service"
- Phone number: "+82-2-2150-XXXX" for specific office
- In-person: "Seoul Immigration Office: [full address with metro access]"
```

**QA Prompt Addition**:
```javascript
// Check for official resources section
const hasOfficialResources = /official|where to|hikorea|immigration office|1345/i.test(content);

if (!hasOfficialResources) {
  issues.push({
    severity: "high",
    issue: "Missing official resource links",
    fix: "Add 'Where to Get Official Information' section with HiKorea link and 1345 contact number"
  });
  decision = "revise";
}
```

**Parse QA JSON Fallback** (자동 삽입):
```javascript
if (!bodyHtml.includes('hikorea') && !bodyHtml.includes('1345')) {
  const officialSection = `
  <h2 class="koreayo-h2">Where to Get Official Information</h2>
  <p class="koreayo-paragraph">
    For the most accurate and up-to-date information:
  </p>
  <ul class="koreayo-list">
    <li><strong>HiKorea Portal</strong> - hikorea.go.kr (Official visa services portal)</li>
    <li><strong>Immigration Contact Center</strong> - Call 1345 (24/7, multilingual)</li>
    <li><strong>Local Immigration Office</strong> - Visit nearest branch for in-person assistance</li>
  </ul>
  `;
  // Insert before FAQ section
  bodyHtml = bodyHtml.replace(
    '<div class="koreayo-faq-section">',
    officialSection + '<div class="koreayo-faq-section">'
  );
}
```

**Acceptance Criteria**:
- [ ] 모든 글에 공식 출처 섹션 포함
- [ ] HiKorea 링크 필수 포함
- [ ] 1345 연락처 필수 포함
- [ ] QA에서 누락 시 revise 결정
- [ ] Parse QA JSON에서 최종 fallback 삽입

---

### 5.3 E-E-A-T Signal Implementation

#### REQ-EEAT-001: 저자 박스 삽입
**Priority**: P1 (High)
**Node**: Parse QA JSON

**Implementation**:
```javascript
// Add author box before Related Articles section
const authorBox = `
<div class="koreayo-author-box">
  <div class="koreayo-author-header">
    <span class="koreayo-author-avatar">K</span>
    <div class="koreayo-author-info">
      <div class="koreayo-author-name">Koreayo Editorial Team</div>
      <div class="koreayo-author-role">Practical Guides for Foreigners in Korea</div>
    </div>
  </div>
  <div class="koreayo-author-bio">
    Koreayo provides verified, practical information for foreigners living in Korea.
    All articles are researched against official government sources and community feedback.
  </div>
  <div class="koreayo-author-contact">
    Questions? Contact: <a href="mailto:hello@koreayo.com">hello@koreayo.com</a>
  </div>
</div>
`;

// Insert before Related Articles or FAQ (whichever comes first)
if (bodyHtml.includes('koreayo-related-section')) {
  bodyHtml = bodyHtml.replace(
    '<div class="koreayo-related-section">',
    authorBox + '\n<div class="koreayo-related-section">'
  );
} else if (bodyHtml.includes('koreayo-faq-section')) {
  bodyHtml = bodyHtml.replace(
    '<div class="koreayo-faq-section">',
    authorBox + '\n<div class="koreayo-faq-section">'
  );
} else {
  // Append at end if no sections found
  bodyHtml = bodyHtml.replace('</div><!-- end koreayo-article -->', authorBox + '\n</div>');
}
```

**CSS** (functions.php에 추가):
```css
.koreayo-author-box {
  background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
  border-left: 4px solid #2563eb;
  padding: 1.5rem;
  border-radius: 8px;
  margin: 2rem 0;
  font-size: 0.95rem;
}

.koreayo-author-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1rem;
}

.koreayo-author-avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: #2563eb;
  color: white;
  border-radius: 50%;
  font-weight: 700;
  font-size: 1.1rem;
}

.koreayo-author-name {
  font-weight: 600;
  color: #1a1a1a;
}

.koreayo-author-role {
  font-size: 0.85rem;
  color: #6b7280;
}

.koreayo-author-bio {
  color: #4b5563;
  line-height: 1.6;
  margin: 0.5rem 0;
}

.koreayo-author-contact {
  font-size: 0.9rem;
  color: #6b7280;
  margin-top: 1rem;
}

.koreayo-author-contact a {
  color: #2563eb;
  text-decoration: none;
}
```

**Acceptance Criteria**:
- [ ] 모든 글에 저자 박스 표시
- [ ] 저자 박스가 Related Articles 또는 FAQ 앞에 위치
- [ ] 이메일 링크 작동
- [ ] 스타일이 일관되게 적용됨

---

#### REQ-EEAT-002: 업데이트 날짜 메타데이터
**Priority**: P2 (Medium)
**Node**: Parse QA JSON

**Implementation**:
```javascript
// Generate formatted date
const currentDate = new Date();
const lastUpdated = currentDate.toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});
const currentYear = currentDate.getFullYear();

// Create metadata block
const metaBlock = `
<div class="koreayo-meta">
  <span class="koreayo-date">Last updated: ${lastUpdated}</span>
  <span class="koreayo-version">Information verified for ${currentYear}</span>
</div>
`;

// Insert at very beginning of article content (after opening div)
bodyHtml = bodyHtml.replace(
  '<div class="koreayo-article">',
  '<div class="koreayo-article">\n' + metaBlock
);
```

**CSS** (functions.php에 추가):
```css
.koreayo-meta {
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
  color: #6b7280;
  display: flex;
  align-items: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.koreayo-date::before {
  content: "📅 ";
}

.koreayo-version::before {
  content: "✓ ";
}
```

**Acceptance Criteria**:
- [ ] 글 상단에 업데이트 날짜 표시
- [ ] 날짜 형식: "Last updated: April 11, 2026"
- [ ] 연도 정보 표시: "Information verified for 2026"
- [ ] 모바일에서도 가독성 확보

---

#### REQ-EEAT-003: 신뢰성 언어 가이드라인
**Priority**: P2 (Medium)
**Node**: Build Draft Prompt

**Addition to System Prompt**:
```
=== CREDIBILITY & EXPERTISE LANGUAGE ===

PERMITTED (Builds Trust):
- "According to the Korea Immigration Service..."
- "As outlined on HiKorea..."
- "Based on official documentation..."
- "Commonly reported by foreigners living in Korea..."
- "The official process requires..."

FORBIDDEN (Appears AI-Generated):
- "I recommend..." (AI cannot recommend)
- "From my experience..." (AI has no experience)
- "I personally did this..." (AI cannot do things)
- "I found that..." (AI cannot find things personally)
- "In my opinion..." (AI should not give opinions)

SPECIFIC SCENARIOS (Increases Relatability):
- "If you're on an E-2 visa and changing employers..."
- "For F-2 visa holders with dependent family..."
- "When your visa expires within 30 days..."

AVOID VAGUE CLAIMS:
❌ "Many people have issues"
✅ "Common issues include [specific problems with solutions]"

QUALIFY UNCERTAIN INFORMATION:
❌ "The fee is $50"
✅ "The fee is typically around 60,000 KRW (approximately $50 USD as of 2026), though this may vary"
```

**Acceptance Criteria**:
- [ ] AI 느낌의 1인칭 표현 금지
- [ ] 공식 출처 인용 방식 사용
- [ ] 구체적 시나리오 기반 설명
- [ ] 불확실한 정보에 한정어 사용

---

## 6. Technical Specifications

### 6.1 Node ID Reference

| Node Name | Node ID | Type |
|-----------|---------|------|
| Build Draft Prompt | 9cfaabfa-61fd-4aef-9996-5aacba09d275 | Code |
| Build QA Prompt | f222399b-1bc8-4db8-90db-2e7e1280d94d | Code |
| Parse QA JSON | df1cc8b3-d2d5-4527-8561-796b96e1f2c3 | Code |
| WordPress Create Draft | c2b1bced-e526-44d2-aefc-1b1080074344 | WordPress |

### 6.2 Data Flow

```
Manual Trigger
    ↓
Read QUEUE / Read CONTROL / Read PUBLISHED List (Parallel)
    ↓
Wait for All → Prepare Candidates
    ↓
Reserve QUEUE Processing
    ↓
Build Draft Prompt ← [REQ-CONTENT-001, REQ-CONTENT-003, REQ-CONTENT-004, REQ-EEAT-003]
    ↓
OpenAI Draft → Parse Draft JSON
    ↓
Build QA Prompt ← [REQ-CONTENT-002, REQ-CONTENT-004]
    ↓
OpenAI QA → Parse QA JSON ← [REQ-HTML-001, REQ-HTML-003, REQ-HTML-004, REQ-CONTENT-004, REQ-EEAT-001, REQ-EEAT-002]
    ↓
IF Should Save
    ├─ True → WordPress Create Draft ← [REQ-HTML-002]
    │           ↓
    │         Update Yoast SEO → Merge WP Result
    │           ↓
    │         Build Success Update / Build PUBLISHED Row (Parallel)
    │           ↓
    │         QUEUE Update Success API / Append PUBLISHED (Parallel)
    │
    └─ False → Build Fail Update → QUEUE Update Fail API
```

### 6.3 Modified Functions

#### `markdownToStyledHtml()` in Parse QA JSON

**Current Signature**:
```javascript
function markdownToStyledHtml(md, faq, relatedArticles)
```

**New Signature**:
```javascript
function markdownToStyledHtml(md, faq, relatedArticles, options = {})
```

**New Options Parameter**:
```javascript
options = {
  includeAuthorBox: true,      // REQ-EEAT-001
  includeMetaDate: true,       // REQ-EEAT-002
  includeOfficialSources: true // REQ-CONTENT-004
}
```

---

## 7. Node-by-Node Modification Plan

### 7.1 Build Draft Prompt (ID: 9cfaabfa-61fd-4aef-9996-5aacba09d275)

#### Changes to `systemPrompt`:

**ADD after line "12. Match the content type and search intent exactly."**:
```javascript
const contentLengthRequirements = `
=== CONTENT LENGTH REQUIREMENTS ===

- **Minimum word count**: 1,200 words (English baseline)
- **Each H2 section**: 150-200 words minimum
- **Introduction**: 100+ words establishing problem context and solution overview
- **Body sections**: Concrete examples, scenarios, edge cases, exceptions
- **Prohibition**: No simple enumerations like "A, B, C are options"
  - Instead: Explain WHY each option matters, when to choose it, limitations

Paragraph structure:
- Minimum 3-5 sentences per paragraph
- Lead with main idea, follow with supporting details
- Maximum 2-3 short paragraphs before breaking for subheading or list
`;

const specificDataRequirements = `
=== SPECIFIC DATA REQUIREMENTS ===

For EVERY relevant fact, provide specificity:

❌ WRONG: "The fee is charged"
✅ RIGHT: "The fee is approximately 30,000-60,000 KRW for standard visa extensions"

❌ WRONG: "Processing takes a few days"
✅ RIGHT: "Standard processing takes 5-10 business days"

❌ WRONG: "You need supporting documents"
✅ RIGHT: "Required: passport, alien registration card, specific form numbers"

=== UNCERTAINTY HANDLING ===

If uncertain about specific data:
- Lead with timeframe: "As of [current year]..."
- Qualify: "Approximately X-Y (subject to change)"
- Direct to official source: "Confirm latest at hikorea.go.kr or call 1345"
`;

const credibilityLanguage = `
=== CREDIBILITY & EXPERTISE LANGUAGE ===

PERMITTED:
- "According to the Korea Immigration Service..."
- "As outlined on HiKorea..."
- "Based on official documentation..."

FORBIDDEN:
- "I recommend..." / "From my experience..." / "In my opinion..."
- Any first-person AI-like phrasing
`;

const officialSourcesRequirement = `
=== MANDATORY OFFICIAL RESOURCES SECTION ===

Every article MUST include a "Where to Get Official Information" section near the end (before FAQ).

Must include:
- HiKorea Portal: hikorea.go.kr
- Immigration Contact Center: 1345 (24/7, multilingual)
- Relevant local office contact if applicable
`;
```

**MODIFY `userPrompt` section**:
```javascript
const userPrompt = `Create one article draft for Koreayo using the structured input below.

[... existing topic input ...]

=== CRITICAL CONTENT REQUIREMENTS ===

1. This article MUST be at least 1,200 words.
2. Include these sections:
   - Problem setup (why reader cares about this topic)
   - Timeline considerations (when to start, deadlines)
   - Common variations by situation
   - Troubleshooting section (what if X goes wrong?)
   - Scenario-based examples
   - Practical step-by-step expectations
   - Where to Get Official Information (HiKorea, 1345)

3. Every numeric fact must have a source or qualifier
4. Use official-sounding language, avoid AI phrasing
5. Include specific data ranges, not vague statements

[... existing instructions ...]`;
```

---

### 7.2 Build QA Prompt (ID: f222399b-1bc8-4db8-90db-2e7e1280d94d)

#### Changes to `systemPrompt`:

**MODIFY scores schema**:
```javascript
// Current
"scores": {
  "search_alignment": "0-100",
  "clarity": "0-100",
  "practicality": "0-100",
  "trustworthiness": "0-100",
  "structure": "0-100",
  "seo_fit": "0-100",
  "prose_quality": "0-100"
}

// New
"scores": {
  "search_alignment": "0-100",
  "clarity": "0-100",
  "practicality": "0-100",
  "trustworthiness": "0-100",
  "structure": "0-100",
  "seo_fit": "0-100",
  "prose_quality": "0-100",
  "content_depth": "0-100"  // NEW
}
```

**ADD scoring guidance**:
```javascript
const contentDepthScoring = `
=== CONTENT DEPTH SCORING ===

"content_depth" scoring criteria:
- 90-100: 1,500+ words with detailed scenarios, examples, edge cases
- 75-89: 1,200-1,499 words with good section development
- 60-74: 900-1,199 words but missing some depth
- 40-59: 600-899 words, too thin
- 0-39: <600 words, insufficient

CRITICAL RULE: If content_depth < 75, decision MUST be "revise"
`;

const officialSourcesCheck = `
=== OFFICIAL SOURCES CHECK ===

The article MUST contain:
- Reference to HiKorea (hikorea.go.kr)
- Reference to 1345 immigration hotline
- "Where to Get Official Information" section

If missing, add to issues with severity "high" and set decision to "revise"
`;
```

---

### 7.3 Parse QA JSON (ID: df1cc8b3-d2d5-4527-8561-796b96e1f2c3)

#### Complete Rewrite of `markdownToStyledHtml()`:

```javascript
function markdownToStyledHtml(md, faq, relatedArticles) {
  if (!md) return '';

  let html = md;

  // ========== HELPER FUNCTIONS ==========

  function escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return String(text || '').replace(/[&<>"']/g, m => map[m]);
  }

  function isValidUrl(url) {
    try {
      const urlObj = new URL(url);
      return urlObj.protocol === 'https:';
    } catch {
      return false;
    }
  }

  // ========== CONTENT CLEANUP ==========

  // Remove unwanted sections from markdown
  html = html.replace(/^##\s*Related\s*(Guides|guides|Articles|articles).*$/gm, '');
  html = html.replace(/^-\s*(How to|Understanding|Tips for|Guide to).*$/gm, '');
  html = html.replace(/^##\s*Internal\s*Link\s*Suggestions.*$/gm, '');
  html = html.replace(/^##\s*Source\s*Caution\s*Notes.*$/gm, '');
  html = html.replace(/^-\s*Always confirm.*$/gm, '');
  html = html.replace(/^##\s*(Conclusion|In Conclusion|Final Thoughts|Closing Thoughts|Wrapping Up|To Wrap Up|Summary|The Bottom Line|Key Takeaways).*$/gmi, '');

  // ========== CODE BLOCK PROTECTION ==========

  const codeBlocks = [];
  html = html.replace(/```[\s\S]*?```/g, (match) => {
    codeBlocks.push(match);
    return `__CODE_BLOCK_${codeBlocks.length - 1}__`;
  });

  // ========== HEADERS ==========

  html = html.replace(/^### (.+)$/gm, '<h3 class="koreayo-h3">$1</h3>');
  html = html.replace(/^## (.+)$/gm, '<h2 class="koreayo-h2">$1</h2>');
  html = html.replace(/^# (.+)$/gm, '<h1 class="koreayo-h1">$1</h1>');

  // ========== TEXT FORMATTING ==========

  html = html.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
  html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
  html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');

  // ========== LIST PROCESSING (FIXED: Consecutive grouping) ==========

  const listLines = html.split('\n');
  const listProcessed = [];
  let currentListType = null; // 'ul', 'ol', or null
  let listBuffer = [];

  for (let i = 0; i < listLines.length; i++) {
    const line = listLines[i];
    const ulMatch = line.match(/^- (.+)$/);
    const olMatch = line.match(/^\d+\.\s+(.+)$/);

    if (ulMatch) {
      // If switching from ol to ul, close ol first
      if (currentListType === 'ol' && listBuffer.length > 0) {
        listProcessed.push(`<ol class="koreayo-list-ordered">${listBuffer.join('')}</ol>`);
        listBuffer = [];
      }
      currentListType = 'ul';
      listBuffer.push(`<li>${ulMatch[1]}</li>`);
    } else if (olMatch) {
      // If switching from ul to ol, close ul first
      if (currentListType === 'ul' && listBuffer.length > 0) {
        listProcessed.push(`<ul class="koreayo-list">${listBuffer.join('')}</ul>`);
        listBuffer = [];
      }
      currentListType = 'ol';
      // Remove the number prefix from content (CSS will add it)
      listBuffer.push(`<li>${olMatch[1]}</li>`);
    } else {
      // Non-list line: close any open list
      if (listBuffer.length > 0) {
        if (currentListType === 'ul') {
          listProcessed.push(`<ul class="koreayo-list">${listBuffer.join('')}</ul>`);
        } else if (currentListType === 'ol') {
          listProcessed.push(`<ol class="koreayo-list-ordered">${listBuffer.join('')}</ol>`);
        }
        listBuffer = [];
        currentListType = null;
      }
      listProcessed.push(line);
    }
  }

  // Close any remaining list
  if (listBuffer.length > 0) {
    if (currentListType === 'ul') {
      listProcessed.push(`<ul class="koreayo-list">${listBuffer.join('')}</ul>`);
    } else if (currentListType === 'ol') {
      listProcessed.push(`<ol class="koreayo-list-ordered">${listBuffer.join('')}</ol>`);
    }
  }

  html = listProcessed.join('\n');

  // ========== PARAGRAPH PROCESSING ==========

  const lines = html.split('\n');
  const processedLines = [];
  let inParagraph = false;

  for (const line of lines) {
    const trimmed = line.trim();
    if (!trimmed) {
      if (inParagraph) {
        processedLines.push('</p>');
        inParagraph = false;
      }
      continue;
    }

    if (trimmed.startsWith('<h') || trimmed.startsWith('<ul') ||
        trimmed.startsWith('<ol') || trimmed.startsWith('<li') ||
        trimmed.startsWith('</') || trimmed.startsWith('<div')) {
      if (inParagraph) {
        processedLines.push('</p>');
        inParagraph = false;
      }
      processedLines.push(trimmed);
    } else {
      if (!inParagraph) {
        processedLines.push('<p class="koreayo-paragraph">' + trimmed);
        inParagraph = true;
      } else {
        processedLines.push('<br>' + trimmed);
      }
    }
  }

  if (inParagraph) {
    processedLines.push('</p>');
  }

  html = processedLines.join('\n');

  // ========== CODE BLOCK RESTORATION ==========

  codeBlocks.forEach((block, i) => {
    const code = block.replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre class="koreayo-code"><code>$2</code></pre>');
    html = html.replace(`__CODE_BLOCK_${i}__`, code);
  });

  // ========== CLEANUP EMPTY TAGS ==========

  html = html.replace(/<p class="koreayo-paragraph"><\/p>/g, '');
  html = html.replace(/<ul class="koreayo-list"><\/ul>/g, '');
  html = html.replace(/<ol class="koreayo-list-ordered"><\/ol>/g, '');

  // ========== OFFICIAL SOURCES FALLBACK ==========

  const hasOfficialSources = /hikorea|1345/i.test(html);
  let officialSourcesSection = '';

  if (!hasOfficialSources) {
    officialSourcesSection = `
<h2 class="koreayo-h2">Where to Get Official Information</h2>
<p class="koreayo-paragraph">
  For the most accurate and up-to-date information:
</p>
<ul class="koreayo-list">
  <li><strong>HiKorea Portal</strong> - hikorea.go.kr (Official visa services portal)</li>
  <li><strong>Immigration Contact Center</strong> - Call 1345 (24/7, multilingual support)</li>
  <li><strong>Local Immigration Office</strong> - Visit your nearest branch for in-person assistance</li>
</ul>
`;
  }

  // ========== FAQ SECTION ==========

  let faqHtml = '';
  if (faq && Array.isArray(faq) && faq.length > 0) {
    faqHtml = `
<div class="koreayo-faq-section">
  <h2 class="koreayo-h2">Frequently Asked Questions</h2>
  <div class="koreayo-faq-list">
    ${faq.map((item) => `
    <div class="koreayo-faq-item">
      <div class="koreayo-faq-question">
        <span class="koreayo-faq-icon">Q</span>
        <span>${escapeHtml(item.question || '')}</span>
      </div>
      <div class="koreayo-faq-answer">
        <span class="koreayo-faq-icon koreayo-faq-icon-a">A</span>
        <span>${escapeHtml(item.answer || '')}</span>
      </div>
    </div>`).join('')}
  </div>
</div>`;
  }

  // ========== AUTHOR BOX ==========

  const authorBox = `
<div class="koreayo-author-box">
  <div class="koreayo-author-header">
    <span class="koreayo-author-avatar">K</span>
    <div class="koreayo-author-info">
      <div class="koreayo-author-name">Koreayo Editorial Team</div>
      <div class="koreayo-author-role">Practical Guides for Foreigners in Korea</div>
    </div>
  </div>
  <div class="koreayo-author-bio">
    Koreayo provides verified, practical information for foreigners living in Korea.
    All articles are researched against official government sources and community feedback.
  </div>
  <div class="koreayo-author-contact">
    Questions? Contact: <a href="mailto:hello@koreayo.com">hello@koreayo.com</a>
  </div>
</div>
`;

  // ========== RELATED ARTICLES (VALIDATED) ==========

  const validatedRelatedArticles = (relatedArticles || [])
    .filter(article => {
      if (!article || !article.url || !article.title) return false;
      if (!String(article.url).startsWith('https://')) return false;
      if (String(article.title).trim() === '') return false;
      return true;
    })
    .map(article => ({
      title: escapeHtml(String(article.title).substring(0, 100)),
      url: String(article.url).trim()
    }))
    .filter(article => isValidUrl(article.url))
    .slice(0, 3);

  let relatedHtml = '';
  if (validatedRelatedArticles.length > 0) {
    relatedHtml = `
<div class="koreayo-related-section">
  <h2 class="koreayo-h2">Related Articles</h2>
  <div class="koreayo-related-list">
    ${validatedRelatedArticles.map((article) => `
    <a href="${article.url}" class="koreayo-related-item">
      <span class="koreayo-related-title">${article.title}</span>
      <span class="koreayo-related-arrow">→</span>
    </a>`).join('')}
  </div>
</div>`;
  }

  // ========== META DATE ==========

  const currentDate = new Date();
  const lastUpdated = currentDate.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
  const currentYear = currentDate.getFullYear();

  const metaBlock = `
<div class="koreayo-meta">
  <span class="koreayo-date">Last updated: ${lastUpdated}</span>
  <span class="koreayo-version">Information verified for ${currentYear}</span>
</div>
`;

  // ========== FINAL HTML ASSEMBLY ==========
  // NOTE: CSS removed - will be loaded from WordPress functions.php

  const finalHtml = `<div class="koreayo-article">
${metaBlock}
${html}
${officialSourcesSection}
${authorBox}
${faqHtml}
${relatedHtml}
</div>`;

  return finalHtml;
}
```

---

### 7.4 WordPress Create Draft (ID: c2b1bced-e526-44d2-aefc-1b1080074344)

#### Change `additionalFields.content`:

**Current**:
```javascript
"content": "={{$json.final_body_html}}"
```

**New**:
```javascript
"content": "=<!-- wp:html -->{{$json.final_body_html}}<!-- /wp:html -->"
```

---

## 8. Testing & Validation

### 8.1 Unit Tests

| Test ID | Description | Expected Result | Node |
|---------|-------------|-----------------|------|
| UT-001 | 번호 목록 그룹화 (3개 연속 항목) | 단일 `<ol>` 내에 3개 `<li>` | Parse QA JSON |
| UT-002 | 번호 목록 중간에 텍스트 | 2개의 별도 `<ol>` 생성 | Parse QA JSON |
| UT-003 | 관련 글 특수문자 이스케이프 | `"` → `&quot;` | Parse QA JSON |
| UT-004 | 잘못된 URL 필터링 | `http://` URL 제외 | Parse QA JSON |
| UT-005 | 공식 출처 누락 시 삽입 | HiKorea 섹션 자동 추가 | Parse QA JSON |
| UT-006 | CSS 제거 확인 | `<style>` 태그 없음 | Parse QA JSON |
| UT-007 | 저자 박스 삽입 | `koreayo-author-box` 존재 | Parse QA JSON |
| UT-008 | 날짜 메타데이터 | `koreayo-meta` 존재 | Parse QA JSON |

### 8.2 Integration Tests

| Test ID | Description | Validation Method |
|---------|-------------|-------------------|
| IT-001 | 전체 워크플로우 실행 | Manual trigger → WordPress draft 생성 확인 |
| IT-002 | WordPress HTML 렌더링 | Draft 페이지에서 스타일 정상 적용 확인 |
| IT-003 | wpautop 비활성화 | `<!-- wp:html -->` 블록 내 HTML 구조 유지 확인 |
| IT-004 | QA 점수 계산 | content_depth 점수 포함 여부 확인 |

### 8.3 Acceptance Tests

| Test ID | Criteria | Pass Condition |
|---------|----------|----------------|
| AT-001 | HTML 유효성 | W3C Validator 오류 0개 |
| AT-002 | 콘텐츠 길이 | 1,200+ 단어 |
| AT-003 | E-E-A-T 요소 | 저자 + 날짜 + 공식 출처 모두 포함 |
| AT-004 | 번호 목록 | 1, 2, 3... 순차 표시 |
| AT-005 | 관련 글 | 3개 모두 정상 렌더링 |

### 8.4 Validation Checklist

```markdown
## Pre-AdSense Resubmission Checklist

### HTML Validation
- [ ] W3C HTML Validator 통과
- [ ] `<style>` 태그가 `<body>` 내에 없음
- [ ] `<p>` 태그 내에 블록 요소 없음
- [ ] 모든 태그 정상 닫힘

### Content Quality
- [ ] 단어 수: 1,200+ 확인
- [ ] 모든 수치에 출처 또는 한정어 포함
- [ ] 공식 출처 섹션 존재 (HiKorea, 1345)
- [ ] 저자 정보 표시
- [ ] 업데이트 날짜 표시

### Technical
- [ ] 번호 목록 1, 2, 3... 정상 표시
- [ ] 관련 글 3개 모두 렌더링
- [ ] 링크 클릭 가능
- [ ] 모바일 반응형 정상

### E-E-A-T
- [ ] AI 느낌 언어 없음 ("I recommend", "From my experience")
- [ ] 공식 출처 링크 포함
- [ ] 저자 정보 명시
- [ ] 날짜 정보 명시
```

---

## 9. Rollback Plan

### 9.1 Version Control

| Version | File | Status |
|---------|------|--------|
| v4 | `koreayo_n8n_workflow_v4_content_quality.json` | Current Production |
| v5 | `koreayo_n8n_workflow_v5_adsense_optimized.json` | Development |

### 9.2 Rollback Procedure

1. **n8n Admin 접속**
2. **Workflow 비활성화**: v5 workflow disable
3. **Workflow 가져오기**: v4 JSON 파일 import
4. **Credentials 확인**: Google Sheets, WordPress, OpenAI API 연결 확인
5. **테스트 실행**: Manual trigger로 1건 테스트
6. **활성화**: v4 workflow enable

### 9.3 Rollback Triggers

- WordPress draft 생성 실패율 > 10%
- HTML 유효성 검사 실패
- QA 점수 급격한 하락 (평균 < 60)
- OpenAI API 오류 증가

---

## 10. Timeline & Prioritization

### 10.1 Implementation Phases

#### Phase 1: Critical HTML Fixes (Day 1-2)
**Priority: P0**

| Task | Node | Estimated Effort |
|------|------|------------------|
| CSS 제거 | Parse QA JSON | 1 hour |
| WordPress HTML 블록 래퍼 | WordPress Create Draft | 30 min |
| 번호 목록 그룹화 로직 | Parse QA JSON | 2 hours |
| 관련 글 데이터 검증 | Parse QA JSON | 1 hour |

#### Phase 2: Content Enhancement (Day 3-4)
**Priority: P1**

| Task | Node | Estimated Effort |
|------|------|------------------|
| 최소 단어 수 요구사항 추가 | Build Draft Prompt | 1 hour |
| QA 콘텐츠 깊이 점수 추가 | Build QA Prompt | 1 hour |
| 구체적 데이터 요구사항 추가 | Build Draft Prompt | 1 hour |
| 공식 출처 섹션 필수화 | All relevant nodes | 2 hours |

#### Phase 3: E-E-A-T Signals (Day 5-6)
**Priority: P2**

| Task | Node | Estimated Effort |
|------|------|------------------|
| 저자 박스 삽입 | Parse QA JSON | 1 hour |
| 업데이트 날짜 메타데이터 | Parse QA JSON | 30 min |
| 신뢰성 언어 가이드라인 | Build Draft Prompt | 1 hour |

#### Phase 4: Testing & Validation (Day 7)
**Priority: P0**

| Task | Estimated Effort |
|------|------------------|
| Unit tests 실행 | 2 hours |
| Integration tests 실행 | 2 hours |
| WordPress 렌더링 확인 | 1 hour |
| W3C Validator 검증 | 1 hour |

### 10.2 Dependencies Diagram

```
[Phase 1: HTML Fixes]
       │
       ├── CSS 제거 ─────────────────────────┐
       │                                      │
       ├── 번호 목록 그룹화 ─────────────────┼──→ [Phase 4: Testing]
       │                                      │
       └── WordPress HTML 블록 ──────────────┘

[Phase 2: Content]
       │
       ├── 단어 수 요구사항 ──────────────────┐
       │                                      │
       ├── QA 깊이 점수 ─────────────────────┼──→ [Phase 4: Testing]
       │                                      │
       └── 공식 출처 섹션 ───────────────────┘

[Phase 3: E-E-A-T]
       │
       ├── 저자 박스 ────────────────────────┐
       │                                      │
       └── 날짜 메타데이터 ──────────────────┴──→ [Phase 4: Testing]
```

---

## Appendix A: CSS for WordPress functions.php

```php
<?php
// Add to functions.php or create separate koreayo-styles.php

add_action('wp_head', function() {
    if (is_single()) {
        ?>
        <style>
        .koreayo-article{font-family:'Pretendard',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;line-height:1.8;color:#333;max-width:100%;counter-reset:main-list}
        .koreayo-h1{font-size:2rem;font-weight:700;color:#1a1a1a;margin:2rem 0 1rem 0;padding-bottom:0.5rem;border-bottom:3px solid #2563eb}
        .koreayo-h2{font-size:1.5rem;font-weight:600;color:#1e40af;margin:2rem 0 1rem 0;padding-left:12px;border-left:4px solid #3b82f6}
        .koreayo-h3{font-size:1.25rem;font-weight:600;color:#374151;margin:1.5rem 0 0.75rem 0}
        .koreayo-paragraph{font-size:1.05rem;color:#4b5563;margin:1rem 0;text-align:justify}
        .koreayo-list{margin:1rem 0 1rem 1.5rem;padding:0;list-style-type:disc}
        .koreayo-list-ordered{margin:1rem 0 1rem 1.5rem;padding:0;list-style:none}
        .koreayo-list li,.koreayo-list-ordered li{margin:0.5rem 0;padding-left:0.5rem;color:#4b5563}
        .koreayo-list-ordered li{counter-increment:main-list}
        .koreayo-list-ordered li::before{content:counter(main-list) ". ";color:#3b82f6;font-weight:600}
        .koreayo-list li::marker{color:#3b82f6}
        .koreayo-code{background:#f3f4f6;border-radius:8px;padding:1rem;overflow-x:auto;font-family:'Fira Code',monospace;font-size:0.9rem;border:1px solid #e5e7eb}
        .koreayo-faq-section{margin-top:3rem;padding:2rem;background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 100%);border-radius:16px}
        .koreayo-faq-list{margin-top:1.5rem}
        .koreayo-faq-item{background:#fff;border-radius:12px;padding:1.25rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);border:1px solid #e0e7ff}
        .koreayo-faq-question{display:flex;align-items:flex-start;gap:12px;font-weight:600;color:#1e40af;margin-bottom:0.75rem}
        .koreayo-faq-answer{display:flex;align-items:flex-start;gap:12px;color:#4b5563;padding-left:0}
        .koreayo-faq-icon{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:28px;background:#3b82f6;color:#fff;border-radius:50%;font-weight:700;font-size:0.85rem}
        .koreayo-faq-icon-a{background:#10b981}
        .koreayo-related-section{margin-top:3rem;padding:2rem;background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);border-radius:16px}
        .koreayo-related-list{margin-top:1.5rem;display:flex;flex-direction:column;gap:12px}
        .koreayo-related-item{display:flex;align-items:center;justify-content:space-between;background:#fff;border-radius:12px;padding:1rem 1.25rem;text-decoration:none;color:#1e40af;font-weight:500;box-shadow:0 2px 8px rgba(0,0,0,0.06);border:1px solid #fcd34d;transition:all 0.2s ease}
        .koreayo-related-item:hover{background:#fffbeb;transform:translateX(4px);box-shadow:0 4px 12px rgba(0,0,0,0.1)}
        .koreayo-related-title{flex:1}
        .koreayo-related-arrow{font-size:1.25rem;color:#f59e0b;font-weight:700}
        .koreayo-meta{background:#f9fafb;border-bottom:1px solid #e5e7eb;padding:1rem;margin-bottom:1.5rem;font-size:0.9rem;color:#6b7280;display:flex;align-items:center;gap:2rem;flex-wrap:wrap}
        .koreayo-author-box{background:linear-gradient(135deg,#f5f7fa 0%,#e8ecf1 100%);border-left:4px solid #2563eb;padding:1.5rem;border-radius:8px;margin:2rem 0;font-size:0.95rem}
        .koreayo-author-header{display:flex;align-items:center;gap:12px;margin-bottom:1rem}
        .koreayo-author-avatar{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;background:#2563eb;color:white;border-radius:50%;font-weight:700;font-size:1.1rem}
        .koreayo-author-name{font-weight:600;color:#1a1a1a}
        .koreayo-author-role{font-size:0.85rem;color:#6b7280}
        .koreayo-author-bio{color:#4b5563;line-height:1.6;margin:0.5rem 0}
        .koreayo-author-contact{font-size:0.9rem;color:#6b7280;margin-top:1rem}
        .koreayo-author-contact a{color:#2563eb;text-decoration:none}
        .koreayo-author-contact a:hover{text-decoration:underline}
        strong{color:#1e40af;font-weight:600}
        em{color:#6b7280;font-style:italic}
        </style>
        <?php
    }
});
?>
```

---

## Appendix B: Complete Modified Parse QA JSON Code

전체 코드는 섹션 7.3에 포함되어 있습니다. 해당 코드를 n8n Parse QA JSON 노드의 jsCode 필드에 복사하여 사용하세요.

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-04-11 | Koreayo Dev Team | Initial PRD creation |

---

**END OF DOCUMENT**
