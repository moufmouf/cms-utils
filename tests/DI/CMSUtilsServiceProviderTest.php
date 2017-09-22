<?php

namespace TheCodingMachine\CMS\DI;

use PHPUnit\Framework\TestCase;
use Simplex\Container;
use TheCodingMachine\CMS\Block\BlockRendererInterface;
use TheCodingMachine\TwigServiceProvider;
use TheCodingMachine\CMS\Theme\AggregateThemeFactory;

class CMSUtilsServiceProviderTest extends TestCase
{
    public function testServiceProvider()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());
        $container->register(new CMSUtilsServiceProvider());

        $blockRenderer = $container->get(BlockRendererInterface::class);

        $this->assertInstanceOf(BlockRendererInterface::class, $blockRenderer);
        $this->assertInstanceOf(AggregateThemeFactory::class, $container->get(AggregateThemeFactory::class));
    }

    public function testServiceProvider2()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());
        $container->register(new CMSUtilsServiceProvider());

        $this->assertInstanceOf(AggregateThemeFactory::class, $container->get(AggregateThemeFactory::class));
        $this->assertInstanceOf(BlockRendererInterface::class, $container->get(BlockRendererInterface::class));
    }
}
