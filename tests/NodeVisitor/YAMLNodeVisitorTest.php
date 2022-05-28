<?php

declare(strict_types=1);

namespace YAMLParser\Tests\NodeVisitor;

use PHPUnit\Framework\TestCase;
use YAMLParser\NodeTraverser\NodeTraverser;
use YAMLParser\NodeVisitor\Symfony\SubscriberToListenerNodeVisitor;
use YAMLParser\Parser\NEONParser;

final class YAMLNodeVisitorTest extends TestCase
{
    public function test(): void
    {
        $neonParser = new NEONParser();
        $node = $neonParser->parse(file_get_contents(__DIR__ . '/Fixture/before_services_listener.yaml'));

        $nodeTraverser = new NodeTraverser([
            new SubscriberToListenerNodeVisitor()
        ]);
        $nodeTraverser->traverse($node);

        $printedNode = rtrim($node->toString()) . PHP_EOL;

        $this->assertStringEqualsFile(__DIR__ . '/Fixture/after_services_subscriber.yaml', $printedNode);
    }
}
