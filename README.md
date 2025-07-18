# Markdown to HTML API

**Plugin Name:** Markdown to HTML API  
**Description:** Provides a REST API endpoint to convert Markdown to safe, styled HTML.  
**Version:** 1.0  
**Author:** You

---

## 📦 What It Does

This plugin adds a custom REST API endpoint to your WordPress site that accepts Markdown and returns sanitized, converted HTML. It supports:

- GitHub-style Markdown syntax
- Syntax-highlighted code blocks (via Prism.js or Highlight.js)
- Task lists with checkboxes
- Safe HTML output with `<pre><code>` support for PHP and other languages

---

## 🧪 API Endpoint

**POST** `/wp-json/md/v1/convert`

### 🔸 Request Body (JSON)

```json
{
  "markdown": "# Hello World\n**This is bold**"
}
