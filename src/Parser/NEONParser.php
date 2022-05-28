<?php

declare(strict_types=1);

namespace YAMLParser\Parser;

use Nette\Neon\Lexer;
use Nette\Neon\Node;
use Nette\Neon\Parser;

final class NEONParser
{
    public function parse(string $content): Node
    {
        $parser = new Parser();
        $lexer = new Lexer();
        $tokenStream = $lexer->tokenize($content);

        return $parser->parse($tokenStream);
    }
}