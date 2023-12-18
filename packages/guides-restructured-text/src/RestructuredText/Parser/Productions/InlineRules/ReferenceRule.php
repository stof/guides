<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\Parser\Productions\InlineRules;

use phpDocumentor\Guides\Nodes\Inline\AbstractLinkInlineNode;
use phpDocumentor\Guides\Nodes\Inline\DocReferenceNode;
use phpDocumentor\Guides\Nodes\Inline\HyperLinkNode;
use phpDocumentor\Guides\RestructuredText\Parser\BlockContext;

use function filter_var;
use function preg_replace;
use function str_ends_with;
use function str_replace;
use function substr;
use function trim;

use const FILTER_VALIDATE_URL;

abstract class ReferenceRule extends AbstractInlineRule
{
    protected function createReference(BlockContext $blockContext, string $link, string|null $embeddedUrl = null, bool $registerLink = true): AbstractLinkInlineNode
    {
        // the link may have a new line in it, so we need to strip it
        // before setting the link and adding a token to be replaced
        $link = str_replace("\n", ' ', $link);
        $link = trim(preg_replace('/\s+/', ' ', $link) ?? '');

        $targetLink = $embeddedUrl ?? $link;
        if (str_ends_with($targetLink, '.rst') && filter_var($targetLink, FILTER_VALIDATE_URL) === false) {
            $targetLink = substr($targetLink, 0, -4);

            return new DocReferenceNode($targetLink, $link);
        }

        if ($registerLink && $embeddedUrl !== null) {
            $blockContext->getDocumentParserContext()->setLink($link, $embeddedUrl);
        }

        return new HyperLinkNode($link, $targetLink);
    }
}
