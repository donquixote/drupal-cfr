<?php

namespace Donquixote\Cf\Util;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\Schema\CfSchemaInterface;

class PhpUtil extends UtilBase {

  /**
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   */
  public static function formatAsFile($php, $namespace = NULL) {

    $php = CodegenUtil::autoIndent($php, '  ');
    $aliases = CodegenUtil::aliasify($php);

    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    if ('' !== $aliases_php) {
      $aliases_php = "\n" . $aliases_php;
    }

    $php = <<<EOT
$aliases_php

$php
EOT;

    if (NULL !== $namespace) {
      $php = <<<EOT
namespace $namespace;
$php
EOT;

    }

    return <<<EOT
<?php
$php
EOT;

  }

  /**
   * @param string $message
   *
   * @return string
   */
  public static function incompatibleConfiguration($message) {

    return self::exception(
      EvaluatorException_IncompatibleConfiguration::class,
      $message);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $expectedClass
   * @param mixed $foundValue
   *
   * @return string
   */
  public static function misbehavingSTA(CfSchemaInterface $schema, $expectedClass, $foundValue) {

    $schemaClass = get_class($schema);

    $messagePhp = <<<EOT
''
. 'Attempted to create a ' . \\$expectedClass::class . ' object' . "\\n"
. 'from schema of class ' . \\$schemaClass::class . '.' . "\\n"
EOT;

    if (is_object($foundValue)) {
      $valueClass = get_class($foundValue);
      $messagePhp .= <<<EOT
. 'Found a ' . \\$valueClass::class . ' object instead.'
EOT;
    }
    else {
      $valueType = gettype($foundValue);
      $messagePhp .= <<<EOT

. 'Found a $valueType value instead.'
EOT;
    }

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedSchema::class,
      $messagePhp);
    
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $destinationClass
   *
   * @return string
   */
  public static function unableToSTA(CfSchemaInterface $schema, $destinationClass) {

    $schemaClass = get_class($schema);

    $messagePhp = <<<EOT
''
. 'Unable to create a ' . \\$destinationClass::class . ' object' . "\\n"
. 'from schema of class ' . \\$schemaClass::class . '.'
EOT;

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedSchema::class,
      $messagePhp);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|null $message
   *
   * @return string
   */
  public static function unsupportedSchema(CfSchemaInterface $schema, $message = NULL) {

    $schemaClass = get_class($schema);

    $messagePhp = <<<EOT

'Unsupported schema of class ' . \\$schemaClass::class
EOT;

    if (NULL !== $message) {
      $messagePhp .= ''
        . "\n" . self::export($message);
    }

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedSchema::class,
      $messagePhp);
  }

  /**
   * @param string $exceptionClass
   * @param string $message
   *
   * @return string
   */
  public static function exception($exceptionClass, $message) {

    $messagePhp = var_export($message, TRUE);

    return self::exceptionWithMessagePhp($exceptionClass, $messagePhp);
  }

  /**
   * @param string $exceptionClass
   * @param string $messagePhp
   *
   * @return string
   */
  private static function exceptionWithMessagePhp($exceptionClass, $messagePhp) {

    return <<<EOT
// @todo Fix the generated code manually.
call_user_func(
  function(){
    throw new \\$exceptionClass($messagePhp);
  })
EOT;
  }

  /**
   * @param string $function
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallFunction($function, array $argsPhp) {
    return $function . '(' . self::phpCallArglist($argsPhp) . ')';
  }

  /**
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallArglist(array $argsPhp) {
    return implode(', ', $argsPhp);
  }

  /**
   * @param string[] $valuesPhp
   *
   * @return string
   */
  public static function phpArray(array $valuesPhp) {

    if ([] === $valuesPhp) {
      return '[]';
    }

    $php = '';
    foreach ($valuesPhp as $k => $vPhp) {
      $kPhp = var_export($k, TRUE);
      $php .= "\n  $kPhp => $vPhp,";
    }

    return "[$php\n]";
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  private static function export($value) {
    return var_export($value, TRUE);
  }

}
