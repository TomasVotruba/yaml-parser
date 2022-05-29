<?php

declare(strict_types=1);

namespace YAMLParser\Parser;

use Nette\Neon\Lexer;
use Nette\Neon\Node;
use Nette\Neon\Parser;
use Nette\Utils\FileSystem;

final class YAMLParser
{
    public function __construct()
    {
    }

    private function parseWithNEONParser(string $fileContent): Node
    {
        $parser = new Parser();
        $lexer = new Lexer();
        $tokenStream = $lexer->tokenize($fileContent);

        return $parser->parse($tokenStream);
    }

    public function parseFile(string $filePath): Node
    {
        $fileContents = FileSystem::read($filePath);
        return $this->parseWithNEONParser($fileContents);
    }

    public function parse(string $contents): Node
    {
        return $this->parseWithNEONParser($contents);
    }
}
