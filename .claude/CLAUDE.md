# Koreayo Project - Claude Code Configuration

## Project Overview
- **Website**: www.koreayo.com
- **Target Audience**: Foreigners living in Korea (expats, international students, workers)
- **Platform**: WordPress (GeneratePress Child Theme) on Hostinger
- **Brand Color**: #2563EB (Blue)

## C-Level Agent Structure

Koreayo operates with a virtual C-suite that can be invoked for domain-specific tasks:

### 🎭 Orchestrator (Chief of Staff) - `koreayo-orchestrator`
**PRIMARY ENTRY POINT** for all Koreayo tasks. Use this when:
- Task spans multiple domains
- Unclear which C-level should handle
- Need coordinated execution
- General business questions

### 🔧 CTO - `koreayo-cto`
Technical decisions, WordPress/PHP development, infrastructure, architecture

### 📈 CMO - `koreayo-cmo`
Marketing strategy, content analysis, SEO, growth metrics, user acquisition

### 💼 CBO - `koreayo-cbo`
Business strategy, revenue models, partnerships, market analysis

### 🎯 CPO - `koreayo-cpo`
Product strategy, feature prioritization, traffic generation, user experience

### 🔍 Content Explorer - `koreayo-content-explorer`
Content ideation, topic discovery, audience pain points research

## Usage Guidelines

1. **Default to Orchestrator**: When unsure, use `koreayo-orchestrator` - it will route appropriately
2. **Direct Access**: For clearly single-domain tasks, go directly to the relevant C-level
3. **Cross-functional**: For projects spanning multiple domains, always use orchestrator

## Current Technical Stack
- WordPress + GeneratePress (Parent) + GeneratePress Child (Koreayo customized)
- Hosting: Hostinger
- Theme files: `D:\n8n\koreayo\theme\generatepress-child\`
- n8n workflows: `D:\n8n\koreayo\`

## Key Files
- `functions.php` - Core theme functionality
- `content.php` - Archive/blog card template
- `style.css` - Main Koreayo styles
- `PLATFORM_EXPANSION_STRATEGY.md` - CMO's platform strategy document
