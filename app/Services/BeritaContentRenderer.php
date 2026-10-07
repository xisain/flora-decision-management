<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use Illuminate\Support\HtmlString;

class BeritaContentRenderer
{
    public function render(string $content): HtmlString
    {
        if (blank($content)) {
            return new HtmlString('');
        }

        if ($content === strip_tags($content)) {
            return new HtmlString(nl2br(e($content)));
        }

        $document = new DOMDocument;
        $previousErrorHandling = libxml_use_internal_errors(true);

        try {
            $document->loadHTML('<?xml encoding="utf-8" ?>'.$content, LIBXML_NONET);
            $body = $document->getElementsByTagName('body')->item(0);

            return new HtmlString($body ? $this->renderChildren($body) : '');
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrorHandling);
        }
    }

    private function renderChildren(DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $this->renderNode($child);
        }

        return $html;
    }

    private function renderNode(DOMNode $node): string
    {
        if ($node instanceof DOMText) {
            return e($node->textContent);
        }

        if (! $node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'template'], true)) {
            return '';
        }

        $children = $this->renderChildren($node);

        if (! in_array($tag, ['div', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'h1', 'h2', 'h3', 'h4', 'a'], true)) {
            return $children;
        }

        if ($tag === 'br') {
            return '<br>';
        }

        if ($tag === 'a') {
            $href = preg_replace('/[\x00-\x20\x7F]/', '', $node->getAttribute('href'));
            $scheme = parse_url($href, PHP_URL_SCHEME);

            if ($href === '' || $scheme === false || ($scheme !== null && ! in_array(strtolower($scheme), ['http', 'https', 'mailto'], true))) {
                return $children;
            }

            return '<a href="'.e($href).'">'.$children.'</a>';
        }

        $tag = $tag === 'h1' ? 'h2' : $tag;

        return '<'.$tag.'>'.$children.'</'.$tag.'>';
    }
}
