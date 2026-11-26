<?php

namespace App\Domain\Content;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Common questions pasted as one text, question above and answer below, turned into the
 * [{q, a}] list that is stored and shown. Answers keep their links and formatting.
 */
final class Faqs
{
    /** A heading over the whole block, not a question of its own. */
    private const BLOCK_TITLES = ['câu hỏi thường gặp', 'các câu hỏi thường gặp', 'câu hỏi thường gặp faq', 'faq', 'faqs', 'hỏi đáp', 'giải đáp thắc mắc'];

    private const ASKING_PREFIX = '/^\s*(hỏi|câu hỏi|q)\s*\d*\s*[:.)]/iu';

    /**
     * Which lines are questions, in order of what the writer meant most clearly: headings;
     * otherwise bold lines when some of them ask something; otherwise lines ending in "?".
     *
     * @return array{items: array<int, array{q: string, a: string}>, problems: array<int, string>}
     */
    public static function parse(?string $html): array
    {
        $lines = array_values(array_filter(
            self::lines(self::root((string) $html)),
            fn (array $line): bool => ! (($line['heading'] || $line['bold']) && in_array(self::normalize($line['text']), self::BLOCK_TITLES, true)),
        ));
        $isQuestion = self::questionTest($lines);
        $items = $problems = [];
        $current = null;

        foreach ($lines as $line) {
            if ($isQuestion($line)) {
                $items[] = ['q' => self::cleanQuestion($line['text']), 'a' => ''];
                $current = count($items) - 1;
            } elseif ($current === null) {
                $problems[] = 'Đoạn "'.self::shorten($line['text']).'" đứng trước câu hỏi đầu tiên.';
            } else {
                $items[$current]['a'] .= $line['html'];
            }
        }

        foreach ($items as $index => $item) {
            $items[$index]['a'] = self::cleanAnswer($item['a']);

            if (self::plain($items[$index]['a']) === '') {
                $problems[] = 'Câu hỏi "'.self::shorten($item['q']).'" chưa có câu trả lời.';
            }
        }

        if ($items === [] && $lines !== []) {
            $problems = ['Chưa nhận ra câu hỏi nào: câu hỏi cần nằm riêng một dòng, kết thúc bằng dấu ? hoặc in đậm.'];
        }

        return ['items' => $items, 'problems' => $problems];
    }

    /**
     * The stored list as text for the editor; parsing it again gives the same list.
     *
     * @param  array<int, array{q?: string|null, a?: string|null}>  $items
     */
    public static function toHtml(array $items): string
    {
        return implode('', array_map(
            fn (array $item): string => '<h3>'.e((string) ($item['q'] ?? '')).'</h3>'.self::answerHtml((string) ($item['a'] ?? '')),
            $items,
        ));
    }

    /** An answer safe to print. One typed as plain text keeps its lines and gets its addresses linked. */
    public static function answerHtml(string $answer): string
    {
        if (preg_match('/<[a-z][^>]*>/i', $answer) === 1) {
            return (string) str($answer)->sanitizeHtml();
        }

        if (trim($answer) === '') {
            return '';
        }

        $text = preg_replace('~https?://[^\s<]*[^\s<.,;:!?)\]]~u', '<a href="$0">$0</a>', e(trim($answer)));

        return '<p>'.nl2br((string) $text, false).'</p>';
    }

    /** The words alone, for search engines and comparisons. */
    public static function plain(string $answer): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace('<', ' <', $answer)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    /**
     * @param  array<int, array{html: string, text: string, heading: bool, bold: bool}>  $lines
     * @return callable(array{html: string, text: string, heading: bool, bold: bool}): bool
     */
    private static function questionTest(array $lines): callable
    {
        $asks = fn (array $line): bool => preg_match('/[?？]["”\')\]]*$/u', $line['text']) === 1 || preg_match(self::ASKING_PREFIX, $line['text']) === 1;
        $marked = fn (array $line): bool => $line['bold'] && ! str_ends_with($line['text'], ':');
        $all = collect($lines);

        return match (true) {
            $all->contains('heading', true) => fn (array $line): bool => $line['heading'],
            $all->contains(fn (array $line): bool => $marked($line) && $asks($line)) => $marked,
            $all->contains($asks) => $asks,
            default => $marked,
        };
    }

    private static function cleanQuestion(string $text): string
    {
        return trim((string) preg_replace('/^\s*(?:(?:câu\s*)?\d+\s*[.):\-–]\s+|(?:hỏi|câu hỏi|q)\s*\d*\s*[:.)]\s*)/iu', '', $text));
    }

    private static function cleanAnswer(string $html): string
    {
        $html = (string) preg_replace('/^<p>\s*(?:<strong>\s*(?:trả lời|đáp|a)\s*:\s*<\/strong>|(?:trả lời|đáp|a)\s*:)\s*/iu', '<p>', $html);

        return $html === '' ? '' : trim((string) str($html)->sanitizeHtml());
    }

    private static function root(string $html): ?DOMElement
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>');
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $document->getElementsByTagName('body')->item(0);
    }

    /**
     * The text as lines: a paragraph broken with Shift+Enter counts as several, and a list
     * that holds questions is opened up.
     *
     * @return array<int, array{html: string, text: string, heading: bool, bold: bool}>
     */
    private static function lines(?DOMNode $parent): array
    {
        $lines = [];
        $inline = [];
        $flush = function () use (&$lines, &$inline): void {
            if ($line = self::paragraph($inline)) {
                $lines[] = $line;
            }
            $inline = [];
        };

        foreach ($parent ? iterator_to_array($parent->childNodes) : [] as $node) {
            $name = strtolower($node->nodeName);

            if ($name === 'br') {
                $flush();
            } elseif ($name === 'p') {
                $flush();
                array_push($lines, ...self::lines($node));
            } elseif (preg_match('/^h[1-6]$/', $name)) {
                $flush();
                if (($text = self::text($node)) !== '') {
                    $lines[] = ['html' => self::outer($node), 'text' => $text, 'heading' => true, 'bold' => false];
                }
            } elseif (in_array($name, ['ul', 'ol'], true)) {
                $flush();
                $items = array_map(fn (DOMNode $item): array => self::lines($item), iterator_to_array($node->childNodes));
                $holdsQuestions = collect($items)->contains(fn (array $item): bool => isset($item[0]) && (preg_match('/[?？]$/u', $item[0]['text']) === 1));

                if ($holdsQuestions) {
                    array_push($lines, ...array_merge(...$items));
                } else {
                    $lines[] = ['html' => self::outer($node), 'text' => self::text($node), 'heading' => false, 'bold' => false];
                }
            } elseif (in_array($name, ['div', 'li', 'section', 'article'], true)) {
                $flush();
                array_push($lines, ...self::lines($node));
            } elseif (in_array($name, ['blockquote', 'table', 'pre', 'details', 'figure', 'img', 'hr'], true)) {
                $flush();
                $lines[] = ['html' => self::outer($node), 'text' => self::text($node), 'heading' => false, 'bold' => false];
            } else {
                $inline[] = $node;
            }
        }
        $flush();

        return $lines;
    }

    /**
     * @param  array<int, DOMNode>  $nodes
     * @return array{html: string, text: string, heading: bool, bold: bool}|null
     */
    private static function paragraph(array $nodes): ?array
    {
        $text = self::squash(implode('', array_map(fn (DOMNode $node): string => $node->textContent, $nodes)));

        if ($text === '') {
            return null;
        }

        $bold = '';
        foreach ($nodes as $node) {
            if (in_array(strtolower($node->nodeName), ['strong', 'b'], true)) {
                $bold .= $node->textContent;
            } elseif ($node instanceof DOMElement) {
                foreach ($node->getElementsByTagName('strong') as $strong) {
                    $bold .= $strong->textContent;
                }
            }
        }

        return [
            'html' => '<p>'.trim(implode('', array_map(self::outer(...), $nodes))).'</p>',
            'text' => $text,
            'heading' => false,
            'bold' => self::squash($bold) === $text,
        ];
    }

    private static function outer(DOMNode $node): string
    {
        return (string) $node->ownerDocument?->saveHTML($node);
    }

    private static function text(DOMNode $node): string
    {
        return self::squash($node->textContent);
    }

    private static function squash(string $text): string
    {
        return trim((string) preg_replace('/[\s\x{00A0}]+/u', ' ', $text));
    }

    private static function normalize(string $text): string
    {
        return trim((string) preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower($text)));
    }

    private static function shorten(string $text): string
    {
        return mb_strimwidth($text, 0, 60, '…');
    }
}
