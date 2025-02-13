<?php

use Tatter\Assets\Asset;
use Tatter\Assets\Bundle;
use Tests\Support\Bundles\FruitSalad;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class BundleTest extends TestCase
{
    public function testConstructorPaths()
    {
        $bundle                    = new class () extends Bundle {
            protected array $paths = ['apple.css'];
        };

        $assets = $bundle->getAssets();

        $this->assertCount(1, $assets);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertSame((string) Asset::createFromPath('apple.css'), (string) $assets[0]);
    }

    public function testConstructorBundles()
    {
        $bundle                      = new class () extends Bundle {
            protected array $bundles = [FruitSalad::class];
        };

        $assets = $bundle->getAssets();

        $this->assertCount(2, $assets);
        $this->assertSame((string) Asset::createFromPath('apple.css'), (string) $assets[0]);
        $this->assertSame((string) Asset::createFromPath('banana.js'), (string) $assets[1]);
    }

    public function testConstructorStrings()
    {
        $bundle                      = new class () extends Bundle {
            protected array $strings = ['foobar'];
        };

        $assets = $bundle->getAssets();

        $this->assertCount(1, $assets);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertSame('foobar', (string) $assets[0]);
    }

    public function testStringable()
    {
        $asset                     = new class () extends Bundle {
            protected array $paths = ['apple.css'];
        };

        $this->assertSame('<link href="https://example.com/assets/apple.css" rel="stylesheet" type="text/css" />', (string) $asset);
    }

    public function testHead()
    {
        $asset                     = new class () extends Bundle {
            protected array $paths = [
                'apple.css',
                'banana.js',
            ];
        };

        $this->assertSame('<link href="https://example.com/assets/apple.css" rel="stylesheet" type="text/css" />', $asset->head());
    }

    public function testSerializing()
    {
        $bundle = new FruitSalad();

        $result = unserialize(serialize($bundle));

        $this->assertSame($bundle->body(), $result->body());
    }
}
