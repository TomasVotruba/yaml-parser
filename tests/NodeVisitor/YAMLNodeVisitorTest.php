<?php

declare(strict_types=1);

namespace YAMLParser\Tests\NodeVisitor;

use PHPUnit\Framework\TestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;
use YAMLParser\NodeTraverser\NodeTraverser;
use YAMLParser\NodeVisitor\Symfony\SubscriberToListenerNodeVisitor;
use YAMLParser\Parser\YAMLParser;

final class YAMLNodeVisitorTest extends TestCase
{
    private YAMLParser $yamlParser;

    protected function setUp(): void
    {
        $this->yamlParser = new YAMLParser();
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $inputAndExpected = StaticFixtureSplitter::splitFileInfoToInputAndExpected($fileInfo);

        // make the change happen
        $nodeTraverser = new NodeTraverser([
            new SubscriberToListenerNodeVisitor()
        ]);

        $node = $this->yamlParser->parse($inputAndExpected->getInput());
        $nodeTraverser->traverse($node);

        $printedNode = rtrim($node->toString()) . PHP_EOL;
        $this->assertEquals($inputAndExpected->getExpected(), $printedNode);
    }

    public function provideData(): \Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.yaml');
    }
}
