<?php


namespace TheCodingMachine\CMS\Theme;

use Psr\Http\Message\StreamInterface;
use TheCodingMachine\CMS\Block\BlockInterface;
use TheCodingMachine\CMS\Block\BlockRendererInterface;
use TheCodingMachine\CMS\CMSException;
use TheCodingMachine\CMS\RenderableInterface;
use Zend\Diactoros\Stream;

class TwigTheme implements RenderableInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $template;
    /**
     * @var BlockRendererInterface
     */
    private $blockRenderer;

    public function __construct(\Twig_Environment $twig, string $template, BlockRendererInterface $blockRenderer)
    {
        $this->twig = $twig;
        $this->template = $template;
        $this->blockRenderer = $blockRenderer;
    }


    /**
     * Renders (as a stream) the data passed in parameter.
     *
     * @param mixed[] $context
     * @return StreamInterface
     */
    public function render(array $context): StreamInterface
    {
        $parent = $context['parent'] ?? null;
        unset($context['parent']);
        $page = $context['page'] ?? null;
        unset($context['page']);

        foreach ($context as $key => &$value) {
            $value = $this->contextValueToString($value, $context);
        }

        if ($parent !== null) {
            $context['parent'] = $parent;
        }
        if ($page !== null) {
            $context['page'] = $page;
        }

        $text = $this->twig->render($this->template, $context);

        $stream = new Stream('php://temp', 'wb+');
        $stream->write($text);
        $stream->rewind();

        return $stream;
    }

    /**
     * @param mixed $value
     * @param mixed[] $context
     * @return string
     * @throws CMSException
     */
    private function contextValueToString($value, array $context) : string
    {
        if ($value instanceof BlockInterface) {
            $additionalContext = [
                'parent' => $context,
                'page' => $context['page'] ?? $context
            ];

            return (string) $this->blockRenderer->renderBlock($value, $additionalContext);
        }
        if (is_array($value)) {
            $str = '';
            foreach ($value as $item) {
                $str .= $this->contextValueToString($item, $context);
            }
            return $str;
        }
        if (is_string($value)) {
            return $value;
        }
        throw new CMSException('Unable to handle a context value. It must be a string or an array or a BlockInterface');
    }
}
