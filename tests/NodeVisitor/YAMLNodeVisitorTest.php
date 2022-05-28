<?php

declare(strict_types=1);

namespace YAMLParser\Tests\NodeVisitor;

use PHPUnit\Framework\TestCase;
use YAMLParser\NodeTraverser\NodeTraverser;
use YAMLParser\NodeVisitor\Symfony\SubscriberToListenerNodeVisitor;
use YAMLParser\Parser\NEONParser;

final class YAMLNodeVisitorTest extends TestCase
{
    private NEONParser $neonParser;

    protected function setUp(): void
    {
        $this->neonParser = new NEONParser();
    }

    public function test(): void
    {

        // make the change happen
        $nodeTraverser = new NodeTraverser([
            new SubscriberToListenerNodeVisitor()
        ]);

        $node = $this->neonParser->parseFile(__DIR__ . '/Fixture/before_services_listener.yaml');
        $nodeTraverser->traverse($node);

        $printedNode = rtrim($node->toString()) . PHP_EOL;
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/after_services_subscriber.yaml', $printedNode);
    }
}
