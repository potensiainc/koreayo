# Koreayo 프롬프트 업데이트 가이드 v7

**날짜**: 2026-04-20
**목적**: E-E-A-T 강화를 통한 Google AdSense 승인률 향상

---

## 변경 사항 요약

### 신규 추가된 E-E-A-T 요구사항

| 요구사항 ID | 설명 | 영향 |
|------------|------|------|
| REQ-EEAT-001 | 팀 전문성 참조 | 글에서 Koreayo 팀의 경험 자연스럽게 언급 |
| REQ-EEAT-002 | 간접 경험 표현 | "I recommend" 금지, "First-time applicants often..." 권장 |
| REQ-EEAT-004 | 제품/서비스 구체화 | "various options" 금지, 실제 이름 2-3개 필수 |
| REQ-EEAT-005 | 커뮤니티 참조 규칙 | 일반 경험에만 사용, 구체적 수치에는 사용 금지 |
| REQ-EEAT-006 | 시나리오 섹션 필수 | 비자 유형/상황별 2-3개 시나리오 필수 |

### QA 점수 항목 추가

```json
"scores": {
  // 기존 항목
  "search_alignment": 0-100,
  "clarity": 0-100,
  "practicality": 0-100,
  "trustworthiness": 0-100,
  "structure": 0-100,
  "seo_fit": 0-100,
  "prose_quality": 0-100,
  "content_depth": 0-100,

  // 신규 항목
  "eeat_signals": 0-100  // ← 새로 추가
}
```

---

## n8n 워크플로우 적용 방법

### 1단계: Build Draft Prompt 노드 수정

1. n8n 에디터에서 **"Build Draft Prompt"** 노드 클릭
2. Code 내용 전체를 `prompts/build_draft_prompt_v7.js` 파일 내용으로 교체
3. 저장

### 2단계: Build QA Prompt 노드 수정

1. n8n 에디터에서 **"Build QA Prompt"** 노드 클릭
2. Code 내용 전체를 `prompts/build_qa_prompt_v7.js` 파일 내용으로 교체
3. 저장

### 3단계: 워크플로우 저장 및 테스트

1. 워크플로우 저장
2. 테스트 실행 (1-2개 글)
3. 출력물 E-E-A-T 체크리스트로 검증

---

## E-E-A-T 체크리스트 (출력물 검증용)

### 필수 확인 항목

```markdown
## 제품/서비스 구체성
- [ ] 제품/서비스 언급 시 2-3개 이상 실제 이름 포함
- [ ] 각 제품에 대한 구분점(가격, 특징 등) 언급
- [ ] "various options", "several providers" 같은 모호한 표현 없음

## 시나리오 섹션
- [ ] 비자 유형/상황별 2-3개 시나리오 존재
- [ ] 각 시나리오가 구체적이고 실용적
- [ ] "If you're on D-2 visa...", "If you're an E-2 teacher..." 같은 형식

## 경험 신호
- [ ] "I recommend", "From my experience" 등 금지 문구 없음
- [ ] "First-time applicants often...", "Experienced expats suggest..." 등 간접 경험 표현 있음
- [ ] AI가 쓴 것 같은 느낌 없음

## 공식 출처
- [ ] "Where to Get Official Information" 섹션 존재
- [ ] HiKorea (hikorea.go.kr) 언급
- [ ] 1345 (Immigration Contact Center) 언급
- [ ] 관련 사무소 주소/전화번호 포함

## 콘텐츠 깊이
- [ ] 1,200 단어 이상
- [ ] 각 H2 섹션 150-200 단어 이상
- [ ] 문제 해결, 트러블슈팅, 타임라인 정보 포함

## 제목 및 헤딩
- [ ] 제목에 특수문자 없음 (콤마, 물음표만 허용)
- [ ] "Conclusion", "Final Thoughts" 등 AI 톤 헤딩 없음
- [ ] 주제 관련 구체적 헤딩 사용
```

---

## 예시: 개선 전 vs 개선 후

### 제품 구체성

**개선 전 (FAIL):**
```
There are several eSIM providers you can choose from when visiting Korea.
Research different options and compare their data plans before making a decision.
```

**개선 후 (PASS):**
```
Popular eSIM options for Korea include Airalo (starting around $4.50/GB with good
coverage), eSIM Korea (local provider with competitive rates), Holafly (unlimited
data plans popular with tourists), and KT's official tourist eSIM available at
Incheon Airport. Each has different strengths depending on your data needs and
trip length.
```

### 시나리오 섹션

**개선 전 (FAIL):**
```
## Extending Your Visa
If you need to extend your visa, follow the steps outlined above.
```

**개선 후 (PASS):**
```
## Different Situations and What to Do

**If you're a student on a D-2 visa:**
Your extension process is straightforward as long as you maintain enrollment.
Visit your university's international office first to get an enrollment certificate,
then bring this along with your ARC and passport to the immigration office. Students
typically receive extensions matching their remaining program duration.

**If you're an English teacher on an E-2 visa switching schools:**
Changing employers mid-contract adds complexity. Your new school must file a
change of workplace notification within 15 days of your start date. You'll need
a release letter from your previous employer, a new contract, and potentially
an updated background check if yours is older than 5 years.

**If you're married to a Korean citizen (F-6 visa):**
Spouse visa holders have different documentation requirements. Beyond the standard
documents, you'll need your marriage certificate, your spouse's 가족관계증명서
(family relations certificate), proof of cohabitation, and your spouse may need
to show income documentation.
```

### 경험 신호

**개선 전 (FAIL - AI 톤):**
```
I recommend visiting the immigration office early in the morning. From my experience,
the wait times are shorter before 10 AM.
```

**개선 후 (PASS - 간접 경험):**
```
First-time visitors to the immigration office often underestimate wait times.
Arriving before 9 AM typically results in shorter queues, especially at the busy
Seoul Mokdong office. The Koreayo team has found that Wednesday mornings tend to
be the least crowded throughout the week.
```

---

## 트러블슈팅

### 문제: QA에서 계속 "revise" 판정

**원인 확인:**
1. `eeat_signals` 점수가 75 미만인지 확인
2. `content_depth` 점수가 75 미만인지 확인
3. `issues` 배열에서 "high" severity 항목 확인

**해결:**
- Draft 프롬프트가 제대로 적용되었는지 확인
- 입력 토픽의 `source_note` 필드에 구체적 제품/서비스 힌트 추가

### 문제: 제품명이 여전히 누락됨

**원인:** GPT-4o-mini의 지식 한계

**해결:**
1. Google Sheets QUEUE에 `product_hints` 컬럼 추가
2. 토픽별로 언급할 제품/서비스 목록 미리 입력
3. Draft 프롬프트에 해당 힌트 전달

예시:
```
product_hints: "Airalo, eSIM Korea, Holafly, KT tourist eSIM"
```

---

## 파일 목록

```
prompts/
├── build_draft_prompt_v7.js    # Draft 생성 프롬프트 (신규)
├── build_qa_prompt_v7.js       # QA 검증 프롬프트 (신규)
└── PROMPT_UPDATE_GUIDE.md      # 이 가이드 문서
```

---

## 다음 단계

1. ✅ 프롬프트 파일 생성 완료
2. ⬜ n8n 워크플로우에 적용
3. ⬜ 테스트 실행 (2-3개 글)
4. ⬜ 출력물 E-E-A-T 체크리스트 검증
5. ⬜ 필요시 추가 조정
6. ⬜ 기존 글 품질 개선 (상위 10개 우선)
7. ⬜ AdSense 재신청

---

**작성자**: Claude (CTO role)
**검토 필요**: 실제 적용 전 테스트 필수
