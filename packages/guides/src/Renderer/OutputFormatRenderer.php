<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Renderer;

use InvalidArgumentException;
use phpDocumentor\Guides\NodeRenderers\FullDocumentNodeRenderer;
use phpDocumentor\Guides\NodeRenderers\NodeRendererFactory;
use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\RenderContext;

class OutputFormatRenderer
{
    private NodeRendererFactory $nodeRendererFactory;

    private string $format;

    private TemplateRenderer $templateRenderer;

    public function __construct(
        string $format,
        NodeRendererFactory $nodeRendererFactory,
        TemplateRenderer $templateRenderer
    ) {
        $this->format = $format;
        $this->nodeRendererFactory = $nodeRendererFactory;
        $this->templateRenderer = $templateRenderer;
    }

    public function supports(string $format): bool
    {
        return $this->format === $format;
    }

    /**
     * Our noderenderes are consuming this method. It should only be called by them. Nobody else!
     *
     * @param array<string, mixed> $context
     */
    public function renderTemplate(string $template, array $context): string
    {
        return $this->templateRenderer->render($template, $context);
    }

    public function render(Node $node, RenderContext $environment): string
    {
        return $this->nodeRendererFactory->get($node)->render($node, $environment);
    }
}
