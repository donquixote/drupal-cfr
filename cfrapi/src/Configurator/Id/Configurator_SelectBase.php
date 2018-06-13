<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class Configurator_SelectBase implements OptionalConfiguratorInterface {

  /**
   * @var bool
   */
  private $required;

  /**
   * @var string|null
   */
  private $defaultId;

  /**
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct($required = TRUE, $defaultId = NULL) {
    $this->required = $required;
    $this->defaultId = $defaultId;
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $form = [
      '#title' => $label,
      '#type' => 'select',
      '#options' => $this->getSelectOptions(),
      '#default_value' => $id = $this->confGetId($conf),
    ];

    if (NULL !== $id && !$this->idIsKnown($id)) {
      $form['#options'][$id] = t("Unknown id '@id'", ['@id' => $id]);
      $form['#illegal_options'][$id] = true;
      /* @see elementValidateOrphanId() */
      $form['#element_validate'][] = [self::class, 'elementValidateOrphanId'];
    }

    if ($this->required) {
      $form['#required'] = TRUE;
    }
    else {
      $form['#empty_value'] = '';
    }

    return $form;
  }

  /**
   * @param array $element
   */
  public static function elementValidateOrphanId(array $element) {
    $id = $element['#value'];
    if (!empty($element['#illegal_options'][$id])) {
      form_error(
        $element,
        t(
          "Unknown id %id. Maybe the id did exist in the past, but it currently does not.",
          ['%id' => $id]));
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    if (NULL !== $id = $this->confGetId($conf)) {
      return $this->idGetLabel($id);
    }

    return $this->getEmptySummary();
  }

  /**
   * @return string
   */
  protected function getEmptySummary() {
    return $this->required
      ? '- ' . t('Missing') . ' -'
      : '- ' . t('None') . ' -';
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

    if (NULL === $id = $this->confGetId($conf)) {
      if ($this->required) {
        throw new InvalidConfigurationException('Required id missing.');
      }

      return NULL;
    }

    if (!$this->idIsKnown($id)) {
      throw new InvalidConfigurationException("Unknown id '$id'.");
    }

    return $id;
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
    if (is_numeric($conf)) {
      $conf = (string)$conf;
    }
    elseif (NULL === $conf || '' === $conf) {
      if ($this->required) {
        return $helper->incompatibleConfiguration($conf, "Required id missing.");
      }

      return var_export(NULL, TRUE);
    }
    elseif (!is_string($conf)) {
      return $helper->incompatibleConfiguration($conf, "Id must be a string or integer.");
    }
    elseif (!$this->idIsKnown($conf)) {
      return $helper->incompatibleConfiguration($conf, "Unknown id.");
    }
    return $helper->export($conf);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return $this->required
      ? NULL
      : new ConfEmptyness_Enum();
  }

  /**
   * @param mixed $conf
   *
   * @return string|null
   */
  private function confGetId($conf) {

    if (is_numeric($conf)) {
      return (string)$conf;
    }

    if (NULL === $conf || '' === $conf || !is_string($conf)) {
      return $this->defaultId;
    }

    return $conf;
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  abstract protected function getSelectOptions();

  /**
   * @param string $id
   *
   * @return string
   */
  abstract protected function idGetLabel($id);

  /**
   * @param string $id
   *
   * @return bool
   */
  abstract protected function idIsKnown($id);
}
