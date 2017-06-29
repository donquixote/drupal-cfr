<?php

namespace Drupal\cfrapi\SchemaConfToValue;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaUtil;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Legend\LegendInterface;

class SchemaConfToValue_Legend implements SchemaConfToValueInterace {

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param mixed $conf
   * @param \Drupal\cfrapi\SchemaConfToValue\SchemaConfToValueInterace $schemaConfToValue
   *
   * @return mixed
   */
  public function schemaConfGetValue(CfrSchemaInterface $cfrSchema, $conf, SchemaConfToValueInterace $schemaConfToValue) {

    if (!$cfrSchema instanceof LegendInterface) {
      return CfrSchemaUtil::schemaIsUnknown();
    }

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
}
