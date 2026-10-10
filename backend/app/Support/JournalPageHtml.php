<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/** A deliberately small HTML vocabulary for public journal content. Never evaluates Blade. */
class JournalPageHtml
{
    public static function clean(?string $html): string
    {
        if (!$html) return '';
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8"><html><body>'.$html.'</body></html>', LIBXML_NONET);
            $body = $document->getElementsByTagName('body')->item(0);
            if (!$body) return '';
            self::children($body);
            $result = '';
            foreach ($body->childNodes as $child) $result .= $document->saveHTML($child);
            return $result;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private static function children(DOMNode $node): void
    {
        $allowed = ['p','br','h2','h3','h4','h5','h6','strong','b','em','i','u','s','ul','ol','li','a','blockquote','table','thead','tbody','tfoot','tr','th','td','img','hr','div','span','sup','sub','pre','code'];
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof DOMElement) continue;
            $tag = strtolower($child->tagName);
            if (in_array($tag, ['script','style','iframe','object','embed','svg','math','form','input','button','textarea','select','template','noscript','link','meta','base'], true)) {
                $node->removeChild($child);
                continue;
            }
            self::children($child);
            if (!in_array($tag, $allowed, true)) {
                while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
                $node->removeChild($child);
                continue;
            }
            foreach (iterator_to_array($child->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = $attribute->value;
                $keep = in_array($name, ['title','alt'], true)
                    || ($name === 'class' && preg_match('/^[a-zA-Z0-9 _-]+$/D', $value))
                    || (in_array($name, ['colspan','rowspan','width','height'], true) && preg_match('/^[0-9]{1,4}$/D', $value))
                    || ($tag === 'a' && $name === 'href' && self::safeUrl($value))
                    || ($tag === 'img' && $name === 'src' && self::safeUrl($value, true));
                if (!$keep) $child->removeAttributeNode($attribute);
            }
            if ($tag === 'img') $child->setAttribute('loading', 'lazy');
        }
    }

    private static function safeUrl(string $url, bool $image = false): bool
    {
        if (preg_match('/[\x00-\x20\x7f\\\\]/', $url)) return false;
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) return true;
        if (!$image && str_starts_with($url, '#')) return true;
        if (preg_match('~^https?://~i', $url)) return filter_var($url, FILTER_VALIDATE_URL) !== false;
        if (!$image && preg_match('/^(mailto|tel):[^<>]+$/i', $url)) return true;
        return $image && preg_match('~^data:image/(png|jpeg|gif|webp);base64,[a-zA-Z0-9+/=]+$~D', $url);
    }
}
