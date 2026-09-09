<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Sanitizer HTML sederhana berbasis allowlist (DOMDocument).
 *
 * Digunakan untuk membersihkan konten rich-text (field yang dirender
 * mentah dengan {!! !!}) dari ancaman XSS: tag berbahaya, event handler
 * (on*), atribut style, dan URL javascript:/vbscript:/data:.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del', 'a',
        'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'blockquote', 'img', 'span', 'div', 'table', 'thead', 'tbody',
        'tfoot', 'tr', 'td', 'th', 'caption', 'hr', 'sub', 'sup',
        'code', 'pre', 'figure', 'figcaption', 'col', 'colgroup',
    ];

    /**
     * Tag berbahaya yang dihapus beserta seluruh isinya.
     */
    private const DROP_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'meta', 'link',
        'form', 'input', 'button', 'select', 'textarea', 'template', 'noscript',
    ];

    private const ALLOWED_ATTRS = [
        'a'    => ['href', 'title', 'target', 'rel'],
        'img'  => ['src', 'alt', 'title', 'width', 'height', 'loading'],
        'td'   => ['colspan', 'rowspan'],
        'th'   => ['colspan', 'rowspan'],
        'ol'   => ['start'],
        'li'   => ['value'],
        'col'  => ['span', 'width'],
    ];

    /**
     * Bersihkan string HTML dari konten berbahaya.
     */
    public static function clean(?string $input): string
    {
        if ($input === null || trim($input) === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"?><body>' . $input . '</body>',
            LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED
        );
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body) {
            return static::stripAllTags($input);
        }

        foreach (iterator_to_array($body->childNodes) as $node) {
            static::walk($node);
        }

        $html = static::serialize($body, $dom);

        return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private static function walk(DOMNode $node): void
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tag = strtolower($node->nodeName);

            if (in_array($tag, self::DROP_TAGS, true)) {
                $node->parentNode?->removeChild($node);

                return;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                // Unwrap: pindahkan anak-anak ke posisi elemen, lalu hapus elemennya.
                $parent = $node->parentNode;
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);

                return;
            }

            static::sanitizeAttributes($node, $tag);
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMNode) {
                static::walk($child);
            }
        }
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRS[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attr) {
            $name = strtolower($attr->nodeName);
            $value = trim($attr->nodeValue ?? '');

            $isUrlAttr = in_array($name, ['href', 'src'], true);
            $keep = in_array($name, $allowed, true) && (! $isUrlAttr || static::isSafeUrl($value));

            if (! $keep) {
                $element->removeAttribute($name);
                continue;
            }

            if ($name === 'target') {
                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        $trimmed = ltrim($url);

        if (preg_match('#^data:image/(png|jpe?g|gif|webp);base64,#i', $trimmed)) {
            return true;
        }

        if (preg_match('#^(https?:)?//#i', $trimmed)) {
            return true;
        }

        if (str_starts_with($trimmed, '/')) {
            return true;
        }

        if (preg_match('#^mailto:#i', $trimmed)) {
            return true;
        }

        return false;
    }

    private static function serialize(DOMNode $root, DOMDocument $dom): string
    {
        $html = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $html .= $dom->saveHTML($child);
        }

        return $html;
    }

    private static function stripAllTags(string $input): string
    {
        return trim(strip_tags($input));
    }
}