<?php

declare(strict_types=1);

namespace YAMLParser\NodeVisitor\Symfony;

use Nette\Neon\Node;
use Nette\Neon\Node\ArrayItemNode;
use Nette\Neon\Node\BlockArrayNode;
use Nette\Neon\Node\InlineArrayNode;
use Nette\Neon\Node\LiteralNode;
use YAMLParser\Contract\NodeVisitor\NodeVisitorInterface;

/**
 * @implements NodeVisitorInterface<ArrayItemNode>
 */
final class SubscriberToListenerNodeVisitor implements NodeVisitorInterface
{
    /**
     * @var string
     */
    private const EVENT_SUBSCRIBER_EVENT_NAME = 'kernel.event_subscriber';

    /**
     * @var string
     */
    private const EVENT_LISTENER_EVENT_NAME = 'kernel.event_listener';

    /**
     * @var string
     */
    private const TAGS_KEY = 'tags';

    public function getNodeType(): string
    {
        return ArrayItemNode::class;
    }

    /**
     * @param ArrayItemNode $node
     */
    public function refactor(Node $node)
    {
        if ($node->key === null) {
            return null;
        }

        if ($node->key->toString() !== self::TAGS_KEY) {
            return null;
        }

        // 1. one way, another is list of array items
        if (! $node->value instanceof BlockArrayNode) {
            return null;
        }

        $arrayItemNodes = $node->value->items;

        // replace tag node
        foreach ($arrayItemNodes as $arrayItemNode) {
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
    }
}