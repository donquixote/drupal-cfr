<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\Core\Form\FormStateInterface;

class Configurator_IntegerInRange implements ConfiguratorInterface {

  /**
   * @var int|null
   */
  private $min;

  /**
   * @var int|null
   */
  private $max;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @param int|null $min
   * @param int|null $max
   */
  public function __construct($min = NULL, $max = NULL) {
    $this->min = $min;
    $this->max = $max;
  }

  /**
   * @return static
   */
  public function optional() {
    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $element = [
      '#title' => $label,
      '#type' => 'textfield',
      '#element_validate' => [
        /* @see element_validate_integer() */
        'element_validate_integer',
      ],
      '#description' => $this->buildDescription(),
    ];

    if ($this->required) {
      $element['#required'] = TRUE;
    }

    if (NULL !== $this->min) {
      $element['#min'] = $this->min;
      /* @see elementValidateMin() */
      $element['#element_validate'][] = [self::class, 'elementValidateMin'];
    }

    if (NULL !== $this->max) {
      $element['#max'] = $this->max;
      /* @see elementValidateMax() */
      $element['#element_validate'][] = [self::class, 'elementValidateMax'];
    }

    if (is_int($conf) || is_string($conf)) {
      $element['#default_value'] = $conf;
    }

    return $element;
  }

  /**
   * @return string|null
   */
  private function buildDescription() {

    if (NULL === $this->max) {
      if (NULL === $this->min) {
        return NULL;
      }
      elseif (0 === $this->min) {
        return t('Non-negative integer.');
      }
      elseif (1 === $this->min) {
        return t('Positive integer.');
      }
      else {
        return t('Integer greater or equal to @min', ['@min' => $this->min]);
      }
    }
    elseif (NULL === $this->min) {
      return t('Integer up to @max', ['@max' => $this->max]);
    }
    else {
      return t('Integer in range [@min...@max]', ['@min' => $this->min, '@max' => $this->max]);
    }
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public static function elementValidateMin(array $element, FormStateInterface $form_state) {

    $v = $element['#value'];

    $min = $element['#min'];
    if ($v < $min) {
      if (0 === $min) {
        $form_state->setError(
          $element,
          t('%name must be non-negative.',
            ['%name' => $element['#title']]));
      }
      elseif (1 === $min) {
        $form_state->setError(
          $element,
          t('%name must be positive.',
            ['%name' => $element['#title']]));
      }
      else {
        $form_state->setError(
          $element,
          t('%name must be greater than or equal to @min.',
            ['%name' => $element['#title'], '@min' => $min]));
      }
    }
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public static function elementValidateMax(array $element, FormStateInterface $form_state) {

    $v = $element['#value'];

    $max = $element['#max'];
    if ($v > $max) {
      $form_state->setError(
        $element,
        t('%name must be no greater than @max.',
          array('%name' => $element['#title'], '@max' => $max)));
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *   An object that controls the format of the summary.
   *
   * @return mixed|string|null
   *   A string summary is always allowed. But other values may be returned if
   *   $summaryBuilder generates them.
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return HtmlUtil::sanitize(var_export($conf, TRUE));
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    if (is_string($conf)) {
      if ((string)(int)$conf !== $conf) {
        throw new InvalidConfigurationException("Value must be an integer.");
      }
      $conf = (int)$conf;
    }
    elseif (!is_int($conf)) {
      throw new InvalidConfigurationException("Value must be an integer.");
    }

    if (NULL !== $this->min && $conf < $this->min) {
      throw new InvalidConfigurationException("Value must be greater than or equal to $this->min.");
    }

    if (NULL !== $this->max && $conf > $this->max) {
      throw new InvalidConfigurationException("Value must be no greater than $this->max.");
    }

    return $conf;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    if (is_string($conf)) {
      if ((string)(int)$conf !== $conf) {
        return $helper->incompatibleConfiguration($conf, "Value must be an integer.");
      }
      $conf = (int)$conf;
    }
    elseif (!is_int($conf)) {
      return $helper->incompatibleConfiguration($conf, "Value must be an integer.");
    }

    if (NULL !== $this->min && $conf < $this->min) {
      return $helper->incompatibleConfiguration($conf, "Value must be greater than or equal to $this->min.");
    }

    if (NULL !== $this->max && $conf > $this->max) {
      return $helper->incompatibleConfiguration($conf, "Value must be no greater than $this->max.");
    }

    return var_export($conf, TRUE);
  }
}
