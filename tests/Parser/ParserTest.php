<?php

declare(strict_types=1);

namespace YAMLParser\Tests\Parser;

use Nette\Neon\Node;
use PHPUnit\Framework\TestCase;
use YAMLParser\Parser\YAMLParser;

final class ParserTest extends TestCase
{
    /**
     * @var string[]
     */
    private const FILE_PATHS = [
        'https://raw.githubusercontent.com/symfony/demo/main/config/services.yaml',
    ];

    private YAMLParser $yamlParser;

    protected function setUp(): void
    {
        $this->yamlParser = new YAMLParser();
    }

    public function testParserVariousFiles(): void
    {
        foreach (self::FILE_PATHS as $filePath) {
            $node = $this->yamlParser->parseFile($filePath);

            $this->assertInstanceOf(Node::class, $node);
        }
    }
}
