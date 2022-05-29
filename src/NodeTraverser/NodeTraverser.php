<?php

declare(strict_types=1);

namespace YAMLParser\NodeTraverser;

use Nette\Neon\Traverser;
use Nette\Neon\Node;
use YAMLParser\Contract\NodeVisitor\NodeVisitorInterface;

final class NodeTraverser
{
    /**
     * @param NodeVisitorInterface[] $nodeVisitors
     */
    public function __construct(private array $nodeVisitors)
    {
    }

    public function traverse(Node $node): void
    {
        $traverser = new Traverser();

        // node visitor
        $traverser->traverse($node, function (Node $node) {
            foreach ($this->nodeVisitors as $nodeVisitor) {
                if (! is_a($node, $nodeVisitor->getNodeType())) {
                    continue;
                }

                $nodeVisitor->refactor($node);
            }

            return null;
        });
    }
}