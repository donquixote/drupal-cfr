<?php

namespace Drupal\cfrplugindiscovery\Annotation\Grammar;

use vektah\parser_combinator\parser\Parser;

abstract class GrammarBase {

  /**
   * @var \vektah\parser_combinator\parser\Parser[]
   */
  private $parsers = array();

  /**
   * @param string $key
   *
   * @return \vektah\parser_combinator\parser\Parser
   *
   * @throws \RuntimeException
   */
  function __get($key) {
    return array_key_exists($key, $this->parsers)
      ? $this->parsers[$key]
      : $this->parsers[$key] = $this->keyBuildParser($key);
  }

  /**
   * Alias of __get($key) to prevent PHP from blocking recursion.
   *
   * @param string $key
   *
   * @return \vektah\parser_combinator\parser\Parser
   *
   * @throws \RuntimeException
   */
  protected function keyGetParser($key) {
    return array_key_exists($key, $this->parsers)
      ? $this->parsers[$key]
      : $this->parsers[$key] = $this->keyBuildParser($key);
  }

  /**
   * @param string $key
   *
   * @return \vektah\parser_combinator\parser\Parser
   *
   * @throws \RuntimeException
   */
  protected function keyBuildParser($key) {
    $method = 'get_' . $key;
    $parser = $this->$method();
    if (NULL === $parser) {
      throw new \RuntimeException(format_string('Method "!class::@method()" returned NULL', array(
        '!class' => static::class,
        '@method' => $method,
      )));
    }
    $parser = Parser::sanitize($parser);
    if (!$parser->hasName()) {
      $parser->setName($key);
    }
    if (!$parser instanceof Parser) {
      throw new \RuntimeException('Not a parser: ' . $key);
    }
    return $parser;
  }

  /**
   * @param string $key
   * @param \vektah\parser_combinator\parser\Parser $parser
   */
  protected function setStubParser($key, Parser $parser) {
    $this->parsers[$key] = $parser;
  }

}
