<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

final class YAMLParser
{
    const INPUT = 'key: value';

    const FILE_INPUT = __DIR__ . '/../tests/before_services_listener.yaml';

    private \YAMLParser\Parser\NEONParser $neonParser;

    public function __construct()
    {
        $this->neonParser = new \YAMLParser\Parser\NEONParser();
    }


    public function run()
    {
        $yamlparser = new Symfony\Component\Yaml\Parser();

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


//    private function parserWithNeon(string $contenet): \Nette\Neon\Node
//    {
//        $parser = new \Nette\Neon\Parser();
//        $lexer = new \Nette\Neon\Lexer();
//        $tokenStream = $lexer->tokenize($contenet);
//
//        return $parser->parse($tokenStream);
//    }
}

$yamlParser = new YAMLParser();
$yamlParser->run();