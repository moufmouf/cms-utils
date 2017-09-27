<?php

namespace TheCodingMachine\CMS\Theme;


class AggregateThemeFactoryTest extends AbstractThemeTestCase
{
    public function testEmptyAggregateThemeFactory()
    {
        $aggregateThemeFactory = new AggregateThemeFactory([]);

        $themeDescriptor = new TwigThemeDescriptor('index.html');

        $this->assertFalse($aggregateThemeFactory->canCreateTheme($themeDescriptor));
        $this->expectException(CannotHandleThemeDescriptorExceptionInterface::class);
        $aggregateThemeFactory->createTheme($themeDescriptor);
    }

    public function testAggregateThemeFactory()
    {
        $aggregateThemeFactory = new AggregateThemeFactory([]);
        $aggregateThemeFactory->addThemeFactory($this->getTwigThemeFactory());

        $themeDescriptor = new TwigThemeDescriptor('index.html');

        $this->assertTrue($aggregateThemeFactory->canCreateTheme($themeDescriptor));
        $this->assertInstanceOf(TwigTheme::class, $aggregateThemeFactory->createTheme($themeDescriptor));
    }

    public function testAggregateThemeFactorySetter()
    {
        $aggregateThemeFactory = new AggregateThemeFactory([]);
        $aggregateThemeFactory->setThemeFactories([$this->getTwigThemeFactory()]);

        $themeDescriptor = new TwigThemeDescriptor('index.html');

        $this->assertTrue($aggregateThemeFactory->canCreateTheme($themeDescriptor));
        $this->assertInstanceOf(TwigTheme::class, $aggregateThemeFactory->createTheme($themeDescriptor));
    }
}
