<?php

declare(strict_types=1);

namespace YAMLParser\Parser;

use Nette\Neon\Lexer;
use Nette\Neon\Node;
use Nette\Neon\Parser;
use Nette\Utils\FileSystem;

final class NEONParser
{
    public function parseFile(string $filePath): Node
    {
        $fileContents = FileSystem::read($filePath);
        return $this->parse($fileContents);
    }

    public function parse(string $content): Node
    {
        $parser = new Parser();
        $lexer = new Lexer();
        $tokenStream = $lexer->tokenize($content);

        return $parser->parse($tokenStream);
    }
}