<?php

namespace Drupal\cfrapi\CfrCodegenHelper;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelperBase;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;

class CfrCodegenHelper extends CodegenHelperBase implements CfrCodegenHelperInterface {

  /**
   * @var array
   */
  private $problems = [];

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function recursionDetected($conf, $message) {

    $this->problems[] = t('Recursion detected.');

    $php = ''
      . "\n" . '$conf = ' . var_export($conf, TRUE) . ';'
      . "\n" . 'throw new \\' . PhpGenerationNotSupportedException::class . '(' . var_export($message, TRUE) . ');';

    return '// @todo Fix the configuration to prevent recursion.'
      . "\n" . 'call_user_func('
      . "\n" . 'function(){' . $php
      . "\n" . '})';
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message) {

    $this->problems[] = t('Incompatible configuration.');

    $php = ''
      . "\n" . '$conf = ' . var_export($conf, TRUE) . ';'
      . "\n" . 'throw new \\' . InvalidConfigurationException::class . '(' . var_export($message, TRUE) . ');';

    return '// @todo Fix the configuration, before exporting this to code!'
      . "\n" . 'call_user_func('
      . "\n" . 'function(){' . $php
      . "\n" . '})';
  }

  /**
   * @param object $object
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function notSupported($object, $conf, $message) {

    $this->problems[] = t('PHP generation not supported.');

    $php = ''
      . "\n" . '$class = ' . var_export(get_class($object), TRUE) . ';'
      . "\n" . '$conf = ' . var_export($conf, TRUE) . ';'
      . "\n" . 'throw new \\' . PhpGenerationNotSupportedException::class . '(' . var_export($message, TRUE) . ');';

    return '// @todo Fix the generated code manually.'
      . "\n" . 'call_user_func('
      . "\n" . 'function(){' . $php
      . "\n" . '})';
  }

  /**
   * @param string $message
   */
  protected function addProblem($message) {
    $this->problems[] = t($message);
  }
}
