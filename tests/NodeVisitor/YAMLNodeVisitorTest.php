<?php

declare(strict_types=1);

namespace YAMLParser\Tests\NodeVisitor;

use Nette\Neon\Node\ArrayItemNode;
use Nette\Neon\Node\BlockArrayNode;
use Nette\Neon\Node\InlineArrayNode;
use Nette\Neon\Node\LiteralNode;
use Nette\Neon\Traverser;
use PHPUnit\Framework\TestCase;
use YAMLParser\Parser\NEONParser;

final class YAMLNodeVisitorTest extends TestCase
{
    /**
     * @var string
     */
    private const EVENT_SUBSCRIBER_EVENT_NAME = 'kernel.event_subscriber';

    /**
     * @var string
     */
    private const EVENT_LISTENER_EVENT_NAME = 'kernel.event_listener';

    public function test(): void
    {
        $neonParser = new NEONParser();
        $node = $neonParser->parse(file_get_contents(__DIR__ . '/../before_services_listener.yaml'));

        $neonTraverser = new Traverser();

        // node visitor
        $neonTraverser->traverse($node, function ($node) {
            if (! $node instanceof ArrayItemNode) {
                return null;
            }

            if ($node->key === null) {
                return null;
            }

            if ($node->key->toString() !== 'tags') {
                return null;
            }

            if (! $node->value instanceof BlockArrayNode) {
                return null;
            }

            // replace tag node
            foreach ($node->value->items as $arrayItemNode) {
                if (! $arrayItemNode->value instanceof InlineArrayNode) {
                    continue;
                }

                $arrayInlineNode = $arrayItemNode->value;
                foreach ($arrayInlineNode->items as $key => $inlineArrayItemNode) {
                    if ($inlineArrayItemNode->key->toString() === 'name') {
                        // rename only if event subsbibers
                        if ($inlineArrayItemNode->value->toString() === self::EVENT_LISTENER_EVENT_NAME) {
                            $inlineArrayItemNode->value = new LiteralNode(self::EVENT_SUBSCRIBER_EVENT_NAME);
                        }
                    }

                    if ($inlineArrayItemNode->key->toString() === 'event') {
                        // remove completely
                        unset($arrayInlineNode->items[$key]);
                    }
                }
            }
        });

        $printedNode = rtrim($node->toString()) . PHP_EOL;
        $this->assertStringEqualsFile(__DIR__ . '/../after_services_subscriber.yaml', $printedNode);
    }
}
