<?php

declare(strict_types=1);

namespace YAMLParser\Parser;

use Nette\Neon\Node;

final class YAMLParser
{
    /**
     * @var string
     */
    private const INPUT = 'key: value';

    private NEONParser $neonParser;

    public function __construct()
    {
        $this->neonParser = new NEONParser();
    }

    public function parseFile(string $filePath): Node
    {
        return $this->neonParser->parseFile($filePath);
    }

    public function parse()
    {
        $yamlparser = new \Symfony\Component\Yaml\Parser();

        $result = $yamlparser->parse(self::INPUT);
        // 1. here we have array
        dump($result);

        // 2. but we want nodes :)
        $nodes = $this->neonParser->parse(self::INPUT);
        dump($nodes);

        // 3. what about advanced file?
        $fileNodes = $this->neonParser->parse(file_get_contents(self::FILE_INPUT));
        dump($fileNodes);

        // 4. travnerse and compare
        dump($fileNodes->toString());
    }
}
