<?php

namespace Drupal\cfrplugindiscovery\Annotation\Grammar;

use vektah\parser_combinator\combinator\Choice;
use vektah\parser_combinator\combinator\Many;
use vektah\parser_combinator\combinator\OptionalChoice;
use vektah\parser_combinator\combinator\Sequence;
use vektah\parser_combinator\formatter\Closure;
use vektah\parser_combinator\formatter\ClosureWithResult;
use vektah\parser_combinator\formatter\Ignore;
use vektah\parser_combinator\Input;
use vektah\parser_combinator\language\php\annotation\ConstLookup;
use vektah\parser_combinator\language\php\annotation\DoctrineAnnotation;
use vektah\parser_combinator\language\php\annotation\NonDoctrineAnnotation;
use vektah\parser_combinator\parser\literal\FloatLiteral;
use vektah\parser_combinator\parser\literal\IntLiteral;
use vektah\parser_combinator\parser\literal\StringLiteral;
use vektah\parser_combinator\parser\PositiveMatch;
use vektah\parser_combinator\parser\RegexParser;
use vektah\parser_combinator\parser\RepSep;
use vektah\parser_combinator\parser\WhitespaceParser;
use vektah\parser_combinator\Result;

/**
 * @property \vektah\parser_combinator\parser\Parser $ws
 * @property \vektah\parser_combinator\parser\Parser $identifier
 * @property \vektah\parser_combinator\parser\Parser $string
 * @property \vektah\parser_combinator\parser\Parser $float
 * @property \vektah\parser_combinator\parser\Parser $int
 * @property \vektah\parser_combinator\parser\Parser $const
 * @property \vektah\parser_combinator\parser\Parser $type
 * @property \vektah\parser_combinator\parser\Parser $arguments
 * @property \vektah\parser_combinator\parser\Parser $array
 * @property \vektah\parser_combinator\parser\Parser $doctrine_annotation
 * @property \vektah\parser_combinator\parser\Parser $doctrine_annotation_body
 * @property \vektah\parser_combinator\parser\Parser $non_doctrine_annotations
 * @property \vektah\parser_combinator\parser\Parser $comment
 * @property \vektah\parser_combinator\parser\Parser $root
 */
class Grammar_DoctrineLikeAnnotation extends GrammarBase {

  /**
   * @return \vektah\parser_combinator\formatter\Ignore
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$ws
   */
  protected function get_ws() {
    return new Ignore(new Many(new WhitespaceParser(1), '(/\*\*|\*/|\*)'));
  }

  /**
   * @return string
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$identifier
   */
  protected function get_identifier() {
    return '[a-zA-Z_][a-zA-Z0-9_]*';
  }

  /**
   * @return \vektah\parser_combinator\parser\literal\StringLiteral
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$string
   */
  protected function get_string() {
    return new StringLiteral();
  }

  /**
   * @return \vektah\parser_combinator\parser\literal\FloatLiteral
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$float
   */
  protected function get_float() {
    return new FloatLiteral();
  }

  /**
   * @return \vektah\parser_combinator\parser\literal\IntLiteral
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$int
   */
  protected function get_int() {
    return new IntLiteral();
  }

  /**
   * @return \vektah\parser_combinator\formatter\ClosureWithResult
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$const
   */
  protected function get_const() {
    return new ClosureWithResult(
      new Sequence(
        $this->identifier,
        new OptionalChoice(
          new Sequence(
            '::',
            $this->identifier
          )
        )
      ),
      function (Result $result, Input $input) {
        $data = $result->data;
        $line = $input->getLine($result->offset);

        if ($data[1]) {
          return new ConstLookup($data[1][1], $data[0], $line);
        }

        return new ConstLookup($data[0], NULL, $line);
      }
    );
  }

  /**
   * @return \vektah\parser_combinator\combinator\Choice
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$type
   */
  protected function get_type() {
    $parser = new Choice(
      $this->string,
      $this->float,
      $this->int,
      $this->const,
      new RegexParser('true', 'i'),
      new RegexParser('false', 'i'),
      new RegexParser('null', 'i')
    );
    /* @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$type */
    $this->setStubParser('type', $parser);
    $parser->append($this->array);
    /* @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$doctrine_annotation */
    $parser->append($this->keyGetParser('doctrine_annotation'));
    return $parser;
  }

  /**
   * @return \vektah\parser_combinator\formatter\Closure
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$arguments
   */
  protected function get_arguments() {
    return new Closure(
      new RepSep(
        new Sequence(
          $this->ws,
          new OptionalChoice(
            new Sequence(
              $this->identifier,
              $this->ws,
              '=',
              $this->ws
            )
          ),
          /* @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$type */
          $this->keyGetParser('type'),
          $this->ws
        )
      ),
      function ($data) {
        $arguments = [];

        foreach ($data as $datum) {
          if ($datum[0]) {
            $arguments[$datum[0][0]] = $datum[1];
          }
          else {
            $arguments['value'] = $datum[1];
          }
        }

        return $arguments;
      }
    );
  }

  /**
   * @return \vektah\parser_combinator\formatter\Closure
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$array
   */
  protected function get_array() {
    return new Closure(
      new Sequence(
        new Ignore('{'),
        new RepSep(
          new Sequence(
            $this->ws,
            new OptionalChoice(
              new Sequence(
                $this->string,
                $this->ws,
                '=',
                $this->ws
              )
            ),
            /* @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$type */
            $this->keyGetParser('type'),
            $this->ws
          )
        ),
        new Ignore('}')
      ),
      function ($data) {
        $result = [];
        foreach ($data[0] as $datum) {
          if ($datum[0]) {
            $result[$datum[0][0]] = $datum[1];
          }
          else {
            $result[] = $datum[1];
          }
        }

        return $result;
      }
    );
  }

  /**
   * @return \vektah\parser_combinator\formatter\ClosureWithResult
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$doctrine_annotation
   */
  protected function get_doctrine_annotation() {
    return new ClosureWithResult(
      $sequence = new Sequence(
        new Ignore('@'),
        $this->identifier,
        $this->ws,
        $this->keyGetParser('doctrine_annotation_body')
      ),
      function (Result $result, Input $input) {
        $data = $result->data;
        $arguments = $data[1][0] ? $data[1][0] : [];
        return new DoctrineAnnotation($data[0], $arguments, $input->getLine($result->offset));
      }
    );
  }

  /**
   * @return \vektah\parser_combinator\formatter\ClosureWithResult
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$doctrine_annotation_body
   */
  protected function get_doctrine_annotation_body() {
    $parser = new OptionalChoice();
    $this->setStubParser('doctrine_annotation_body', $parser);
    $parser->append(
      new Sequence(
        new Ignore('('),
        PositiveMatch::instance(),
        $this->arguments,
        new Ignore(')')
      )
    );
    return $parser;
  }

  /**
   * @return \vektah\parser_combinator\formatter\ClosureWithResult
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$non_doctrine_annotations
   */
  protected function get_non_doctrine_annotations() {
    return new ClosureWithResult(
      new Sequence('@', '[a-z][a-zA-Z0-9_\[\]]*', $this->ws, new RegexParser('[^@]*', 'ms')),
      function (Result $result, Input $input) {
        $value = str_replace('*/', '', $result->data[2]);
        $value = str_replace('*', '', $value);
        return new NonDoctrineAnnotation($result->data[1], trim($value), $input->getLine($result->offset));
      }
    );
  }

  /**
   * @return string
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$comment
   */
  protected function get_comment() {
    return '[^@].*';
  }

  /**
   * @return \vektah\parser_combinator\formatter\Closure
   *
   * @see \Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation::$root
   */
  protected function get_root() {
    return new Closure(
      new Sequence(
        $this->ws,
        new Many(
          new Sequence(
            new Choice(
              $this->non_doctrine_annotations,
              $this->doctrine_annotation,
              $this->comment
            ),
            $this->ws
          )
        )
      ),
      function ($data) {
        return array_map(
          function ($value) {
            return $value[0];
          },
          $data[0]
        );
      }
    );
  }
}
