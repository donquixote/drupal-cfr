<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialD7Formator_Options implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return mixed
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required) {

    if (!$schema instanceof CfSchema_OptionsInterface) {
      return $helper->unknownSchema();
    }

    return D7FormUtil::optionsSchemaBuildSelectElement(
      $schema,
      ConfUtil::confGetId($conf),
      $label,
      $required);
  }
}
