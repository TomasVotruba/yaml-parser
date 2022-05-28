<?php

declare(strict_types=1);

namespace YAMLParser\Contract\NodeVisitor;

use Nette\Neon\Node;

/**
 * @template TNode as \Nette\Neon\Node
 */
interface NodeVisitorInterface
{
    /**
     * @return class-string<TNode>
     */
    public function getNodeType(): string;

    /**
     * @param TNode $node
     * @return Node|null|int
     */
    public function refactor(Node $node);
}
