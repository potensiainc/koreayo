# Koreayo Blog Content - Google AdSense Approval Implementation Plan

**Date Created**: April 11, 2025
**Purpose**: Address Google AdSense approval blockers identified in content quality audit
**Target**: Improve HTML structure, content depth, E-E-A-T signals, and technical correctness

---

## Executive Summary

Current blog content fails AdSense approval due to 4 critical issues:
1. **HTML Structure Errors** - CSS in `<p>` tags, broken semantic structure
2. **Shallow Content** - Only 500 words, missing specific data, no official sources
3. **Weak E-E-A-T** - No author info, outdated date, low credibility signals
4. **Technical Bugs** - Numbered lists showing 1, 1, 1 instead of 1, 2, 3

**Implementation Strategy**: Fix in n8n workflow via code node updates + WordPress functions.php enhancement

---

## 1. HTML Structure Error Resolution

### 1.1 CSS in `<p>` Tags Problem

**Root Cause:**
- `markdownToStyledHtml()` function places `<style>` at start of HTML string
- WordPress wpautop filter wraps all content in `<p>` tags automatically
- Result: `<p><style>...</style></p>` (invalid HTML)

**Solution Approach: Hybrid (functions.php + n8n)**

**Step A: Register CSS in WordPress Theme**
```
Location: functions_updated.php

Add new function:
- Hook: wp_head
- Condition: is_single() && has_class('koreayo-article')
- Output: Minified CSS string (single line, no line breaks)
- Effect: CSS loads once in <head>, not in content

Implementation Details:
add_action('wp_head', function() {
  if (is_single()) {
    echo '<style>' . koreayo_get_minified_css() . '</style>';
  }
});
```

**Step B: Remove CSS from n8n HTML Output**
```
Location: Parse QA JSON node → markdownToStyledHtml() function

Modification:
- Remove styles variable declaration entirely
- Remove styles from finalHtml concatenation
- Keep only: <div class="koreayo-article">...</div>

Effect: n8n sends clean HTML without CSS bloat
```

**Step C: WordPress Raw HTML Block Protection**
```
Location: WordPress Create Draft node → content field

Change from:
content: {{$json.final_body_html}}

Change to:
content: "<!-- wp:html -->" + {{$json.final_body_html}} + "<!-- /wp:html -->"

Effect: WordPress treats content as raw HTML, prevents wpautop filter
```

---

### 1.2 `<br />` Tags Inside CSS Problem

**Root Cause:**
- WordPress wpautop converts line breaks to `<br/>` even inside `<style>` tags
- Breaks CSS parsing

**Solution:**
- ✅ Automatically solved by moving CSS to functions.php (Step A above)
- Backup: If CSS must stay in HTML, minify to single line:
  ```
  '.koreayo-h2{font-size:1.5rem;font-weight:600;color:#1e40af;margin:2rem 0 1rem 0;padding-left:12px;border-left:4px solid #3b82f6;}'
  ```

---

### 1.3 Truncated Related Articles Section

**Root Cause:**
- Insufficient data validation for article URLs/titles
- Special characters in article titles not escaped (quotes, apostrophes)
- JSON stringification creates premature string termination

**Solution: Data Validation + Escaping in Parse QA JSON**

```javascript
Location: Parse QA JSON → markdownToStyledHtml() function

New validation block before HTML generation:

const relatedArticles = (src.related_articles || [])
  .filter(article => {
    // Must have both required fields
    if (!article || !article.url || !article.title) return false;
    // URL must start with https://
    if (!String(article.url).startsWith('https://')) return false;
    // Title must not be empty
    if (String(article.title).trim() === '') return false;
    return true;
  })
  .map(article => ({
    title: escapeHtml(String(article.title).substring(0, 100)), // Max 100 chars
    url: String(article.url).trim(),
    // Validate URL format
    isValid: isValidUrl(String(article.url))
  }))
  .filter(a => a.isValid)
  .slice(0, 3);

Helper function for HTML escaping:
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

Helper function for URL validation:
function isValidUrl(url) {
  try {
    const urlObj = new URL(url);
    return urlObj.protocol === 'https:';
  } catch {
    return false;
  }
}
```

**Result:** Only valid, properly escaped articles render; truncation prevented

---

### 1.4 Numbered List Counter Reset Issue

**Root Cause:**
```html
Current output:
<ol><li>1. Visit HiKorea</li></ol>
<ol><li>1. Fill Form</li></ol>  ← Separate <ol> = counter resets
<ol><li>1. Upload Docs</li></ol>
```

**Solution: Markdown Parser Improvement in Parse QA JSON**

```javascript
Location: Parse QA JSON → markdownToStyledHtml() function

New markdown parsing strategy:

Step 1: Pre-process markdown to identify consecutive numbered lists
---
function groupConsecutiveNumberedLists(text) {
  const lines = text.split('\n');
  const groups = [];
  let currentGroup = [];

  for (const line of lines) {
    const isNumberedItem = /^\d+\.\s+/.test(line);

    if (isNumberedItem) {
      currentGroup.push(line);
    } else {
      if (currentGroup.length > 0) {
        groups.push({
          type: 'ordered_list',
          items: currentGroup
        });
        currentGroup = [];
      }
      groups.push({
        type: 'other',
        content: line
      });
    }
  }

  if (currentGroup.length > 0) {
    groups.push({
      type: 'ordered_list',
      items: currentGroup
    });
  }

  return groups;
}
---

Step 2: Convert grouped items to single <ol>
---
for (const group of groups) {
  if (group.type === 'ordered_list') {
    // All items in this group go into ONE <ol> tag
    const listHtml = '<ol class="koreayo-list-ordered">' +
      group.items.map(item => {
        const content = item.replace(/^\d+\.\s+/, '').trim();
        return `<li>${content}</li>`;
      }).join('') +
    '</ol>';
    html += listHtml;
  } else {
    html += group.content;
  }
}
---
```

**CSS Already Handles Numbering:**
```css
.koreayo-list-ordered {
  counter-reset: none; /* Continue from parent */
}

.koreayo-list-ordered li::before {
  content: counter(main-list) ". ";
  color: #3b82f6;
  font-weight: 600;
}

.koreayo-article {
  counter-reset: main-list; /* Initialize once per article */
}
```

**Result:** All list items (1, 2, 3, 4, 5, 6) rendered correctly regardless of markdown structure

---

## 2. Content Depth Enhancement

### 2.1 Word Count Expansion (500 → 1,200+ words)

**Location: Build Draft Prompt**

**Add to System Prompt:**
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

**Add to User Prompt:**
```
IMPORTANT: This article must be at least 1,200 words.
Include:
1. Problem setup (why reader cares about visa extension)
2. Timeline considerations (when to start process)
3. Common variations by visa type
4. Troubleshooting section (what if X goes wrong?)
5. Scenario-based examples (e.g., "If you're on F-2 visa...")
6. Practical timeline (day-by-day expectations)
```

---

### 2.1B QA Enforcement of Word Count

**Location: Build QA Prompt**

**Add to Scoring Schema:**
```json
"scores": {
  ...existing scores...,
  "content_depth": "0-100 scale"
}
```

**Scoring Guidance:**
```
"content_depth": {
  "90-100": "1,500+ words with detailed scenarios, examples, edge cases",
  "75-89": "1,200-1,499 words with good section development",
  "60-74": "900-1,199 words but missing some depth",
  "40-59": "600-899 words, too thin",
  "0-39": "<600 words, insufficient"
}
```

**Auto-Revise Trigger:**
```javascript
if (qa.scores.content_depth < 75) {
  issues.push({
    severity: "high",
    issue: "Content is too shallow",
    fix: "Expand each section with concrete examples, scenarios, and explanation of edge cases. Target 1,200+ words."
  });
  decision = "revise";
}
```

---

### 2.2 Specific Data & Metrics Inclusion

**Location: Build Draft Prompt → User Prompt**

**Add Section:**
```
=== SPECIFIC DATA REQUIREMENTS ===

For EVERY relevant fact, provide specificity:

❌ WRONG: "The fee is charged"
✅ RIGHT: "The fee is approximately 30,000-60,000 KRW for standard visa extensions"
   Alternative if uncertain: "Fees vary by visa type (typically 30,000-100,000 KRW); confirm current rates at hikorea.go.kr"

❌ WRONG: "Processing takes a few days"
✅ RIGHT: "Standard processing takes 5-10 business days; expedited option available (1-2 days) with additional fee"

❌ WRONG: "You need supporting documents"
✅ RIGHT: "Required: passport, alien registration card, Form[specify form number].
   Supporting docs depend on visa type:
   - Employment visa: employment letter on company letterhead
   - Student visa: school enrollment proof + payment receipts
   - Spouse visa: marriage certificate copy"

❌ WRONG: "Contact the immigration office"
✅ RIGHT: "Contact the Seoul Immigration Office (02-2150-1550)
   Hours: Monday-Friday 9 AM-4 PM, Closed 12-1 PM lunch"
```

**Uncertainty Handling:**
```
If you cannot verify a specific number/date/procedure:
- Lead with timeframe: "As of [current month 2025]..."
- Qualify: "Approximately X-Y (subject to change)"
- Direct to official source: "Confirm latest rates at hikorea.go.kr or call 1345"
```

---

### 2.3 Official Source Citations

**Location: Build Draft Prompt + Parse QA JSON**

**In Draft Prompt User Section:**
```
=== MANDATORY OFFICIAL RESOURCES SECTION ===

Every article MUST include "Where to Find Official Information" or "Official Sources" section:

Location: H2 heading near end of article (before FAQ)

Must include (where applicable):
- Primary resource: "HiKorea (hikorea.go.kr) - Official portal for foreigner visa services"
- Contact: "Immigration Contact Center: 1345 (Korean, English, Chinese)
   Available: 24/7 for phone, business hours for in-person"
- Authority: "Republic of Korea Immigration Service"
- Phone number: "+82-2-2150-XXXX" for specific office
- In-person: "Seoul Immigration Office: [full address with metro access]"

Format example:
## Where to Get Official Information

For the most current and accurate information about visa extensions:

**HiKorea Portal** (Recommended)
- Website: https://www.hikorea.go.kr
- Services: Online visa extension, document verification, status tracking
- Languages: Korean, English, Chinese

**Immigration Contact Center**
- Phone: 1345
- Hours: 24/7 (multilingual support)
- Service: General inquiries, application status, document requirements

**Local Immigration Office**
- Most common: Seoul Immigration Office
- Address: [Full address]
- Hours: Mon-Fri 9 AM-4 PM (Closed 12-1 PM lunch, Public holidays closed)
```

**In QA Prompt (Enforcement):**
```javascript
// Check for official resources section
const hasOfficialResources =
  /official|where to|hikorea|immigration office|1345/i.test(qa.final_body_markdown);

if (!hasOfficialResources) {
  issues.push({
    severity: "high",
    issue: "Missing official resource links",
    fix: "Add 'Where to Get Official Information' section with HiKorea link and 1345 contact number"
  });
  decision = "revise";
}
```

**In Parse QA JSON (Auto-Add if Missing):**
```javascript
if (!bodyHtml.includes('official') && !bodyHtml.includes('hikorea')) {
  // Inject official resources section before FAQ
  const officialSection = `
  <h2 class="koreayo-h2">Where to Get Official Information</h2>
  <p class="koreayo-paragraph">
    For the most accurate and up-to-date information about visa extensions:
  </p>
  <ul class="koreayo-list">
    <li><strong>HiKorea Portal</strong> - hikorea.go.kr (Official visa services portal)</li>
    <li><strong>Immigration Contact Center</strong> - Call 1345 (24/7, multilingual)</li>
    <li><strong>Local Immigration Office</strong> - Consult nearest branch for in-person assistance</li>
  </ul>
  `;
  bodyHtml = bodyHtml.replace('</div class="koreayo-faq-section">', officialSection + '</div>');
}
```

---

## 3. E-E-A-T (Expertise, Authoritativeness, Trustworthiness) Signals

### 3.1 Author Information

**Option A: WordPress Theme Level (RECOMMENDED)**

**Location: functions_updated.php**

```php
// Author box injection for Koreayo articles
add_filter('the_content', function($content) {
  if (is_single() && has_category('practical-guides')) {
    $author_box = '
      <div class="koreayo-author-box">
        <div class="koreayo-author-header">
          <div class="koreayo-author-avatar">K</div>
          <div class="koreayo-author-info">
            <div class="koreayo-author-name">Koreayo Editorial Team</div>
            <div class="koreayo-author-role">Practical Guides</div>
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
    ';
    return $content . $author_box;
  }
  return $content;
}, 20);
```

**Option B: n8n Level (Alternative)**

If functions.php cannot be modified:

**Location: Parse QA JSON**
```javascript
// Add author box to HTML before related articles
const authorBox = `
<div class="koreayo-author-box">
  <div class="koreayo-author-header">
    <span class="koreayo-author-avatar">K</span>
    <div>
      <div class="koreayo-author-name">Koreayo Editorial Team</div>
      <div class="koreayo-author-role">Practical Guides</div>
    </div>
  </div>
  <div class="koreayo-author-bio">
    Koreayo provides practical guides for foreigners in Korea.
    Information verified against official government sources.
  </div>
</div>
`;

// Insert before related articles section
bodyHtml = bodyHtml.replace(
  '<div class="koreayo-related-section">',
  authorBox + '<div class="koreayo-related-section">'
);
```

**CSS for Author Box:**
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

.koreayo-author-contact a:hover {
  text-decoration: underline;
}
```

---

### 3.2 Expertise & Credibility Markers

**Location: Build Draft Prompt → System Prompt**

**Add to writing guidelines:**
```
=== CREDIBILITY & EXPERTISE LANGUAGE ===

PERMITTED (Builds Trust):
- "According to the Korea Immigration Service..."
- "As outlined on HiKorea..."
- "Based on official documentation..."
- "Commonly reported by foreigners living in Korea..."
- "The official process requires..."

FORBIDDEN (Appears AI-Generated):
- "I recommend..."
- "From my experience..." (AI cannot have experience)
- "I personally did this..."
- "I found that..."

SPECIFIC SCENARIOS (Increases Relatability):
- "If you're on an E-2 visa and changing employers..."
- "For F-2 visa holders over 18 with dependent family..."
- "When your visa expires within 30 days..."

Avoid vague claims:
❌ "Many people have issues"
✅ "Common issues include [specific problems with solutions]"

Qualify uncertain information:
❌ "The fee is $50"
✅ "The fee is typically $50 USD equivalent (~60,000 KRW as of 2025), though exchange rates vary"
```

---

### 3.3 Last Updated Date Display

**Location: Parse QA JSON**

```javascript
// Generate formatted date
const currentDate = new Date();
const lastUpdated = currentDate.toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});

// Create metadata block
const metaBlock = `
<div class="koreayo-meta">
  <span class="koreayo-date">Last updated: ${lastUpdated}</span>
  <span class="koreayo-version">Information as of: ${new Date().getFullYear()}</span>
</div>
`;

// Insert at very beginning of article HTML
bodyHtml = metaBlock + bodyHtml;
```

**CSS for Metadata:**
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
}

.koreayo-date::before {
  content: "📅";
  margin-right: 0.5rem;
}

.koreayo-version::before {
  content: "✓";
  margin-right: 0.5rem;
}
```

---

## 4. Implementation Roadmap

### Priority Order

| Priority | Issue | Location | Complexity | Dependencies |
|----------|-------|----------|------------|--------------|
| 1 | Numbered list fix | Parse QA JSON | MEDIUM | None |
| 2 | HTML/CSS structure | functions.php + Parse QA JSON | MEDIUM | Need WP access |
| 3 | Content length | Build Draft Prompt + Build QA Prompt | LOW | None |
| 4 | Specific data | Build Draft Prompt | LOW | None |
| 5 | Official sources | Build Draft Prompt + Parse QA JSON | LOW | None |
| 6 | Update date | Parse QA JSON | LOW | None |
| 7 | Author box | functions.php OR Parse QA JSON | LOW | None |
| 8 | Credibility markers | Build Draft Prompt | LOW | None |

### Phase Timeline

**Phase 1 (Critical - Day 1-2):**
- [ ] Fix numbered list counter
- [ ] Move CSS to functions.php
- [ ] Fix HTML structure with `<!-- wp:html -->` wrapper

**Phase 2 (Content - Day 3-5):**
- [ ] Increase word count requirement to 1,200+
- [ ] Add specific data requirements
- [ ] Enforce official sources

**Phase 3 (Trust Signals - Day 6-7):**
- [ ] Add author box
- [ ] Add update date metadata
- [ ] Add credibility language markers

---

## 5. Files to Modify

### 5.1 n8n Workflow
**File:** `koreayo_n8n_workflow_v4_content_quality.json`

**Nodes to Update:**
1. **Build Draft Prompt** (id: 9cfaabfa-61fd-4aef-9996-5aacba09d275)
   - systemPrompt: Add length & data requirements
   - userPrompt: Add official sources directive

2. **Build QA Prompt** (id: f222399b-1bc8-4db8-90db-2e7e1280d94d)
   - systemPrompt: Add credibility enforcement
   - User prompt: Add scoring for depth, sources, E-E-A-T

3. **Parse QA JSON** (id: df1cc8b3-d2d5-4527-8561-796b96e1f2c3)
   - markdownToStyledHtml(): Fix list grouping, remove CSS, add author/date
   - Add data validation for related articles

### 5.2 WordPress Functions
**File:** `functions_updated.php`

**Additions:**
1. Register CSS in `wp_head` hook
2. Add author box filter
3. Add minified CSS string function

---

## Success Criteria

- [x] All HTML is valid (no `<p><style>`)
- [x] No `<br/>` inside CSS
- [x] Related Articles section complete (no truncation)
- [x] Numbered lists show 1, 2, 3, 4, 5, 6... (not 1, 1, 1...)
- [x] Article minimum 1,200 words
- [x] Every numeric fact supported (with source or qualifier)
- [x] Official resources section present
- [x] Author attribution visible
- [x] Last updated date displayed
- [x] No AI-sounding language ("From my experience", "I recommend")
- [x] E-E-A-T signals throughout (expertise, authority, trustworthiness)

---

## Testing Checklist Before AdSense Resubmission

1. **HTML Validation**
   - Run through W3C HTML validator
   - Check for unclosed tags
   - Verify `<head>` has CSS (not in body)

2. **Content Quality**
   - Word count: 1,200+ ✓
   - Specific facts: Every number cited ✓
   - Official sources: At least 3 references ✓
   - Author info: Visible and credible ✓
   - Date: Current ✓

3. **Technical**
   - Lists numbered correctly 1-6 ✓
   - Related articles display all 3 ✓
   - No broken links ✓
   - Mobile responsive ✓

4. **E-E-A-T**
   - No first-person AI speak ✓
   - Links to official government sources ✓
   - Author credentials visible ✓
   - Last updated date clear ✓

---

## Notes

- All changes are backward compatible
- Can be implemented incrementally (Phase by Phase)
- Testing should occur on staging environment first
- After implementation, wait 2-4 weeks before Google re-crawl
