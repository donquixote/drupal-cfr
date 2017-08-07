<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD7_IfaceTagged implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7_Drilldown
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed
   */
  private $schema;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(
    CfSchema_Neutral_IfaceTransformed $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    $decorated = D7FormSTAUtil::formator(
      $schema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD7_Drilldown) {
      return NULL;
    }

    return new self($decorated, $schema);
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7_Drilldown $decorated
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed $schema
   */
  public function __construct(
    FormatorD7_Drilldown $decorated,
    CfSchema_Neutral_IfaceTransformed $schema
  ) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator() {

    if (NULL === $decorated = $this->decorated->getOptionalFormator()) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD7_Drilldown) {
      return NULL;
    }

    return new self($decorated, $this->schema);
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label) {

    $form = $this->decorated->confGetD7Form($conf, $label);

    /* @see cfrplugin_element_info() */
    $form['#type'] = 'cfrplugin_drilldown_container';
    $form['#cfrplugin_interface'] = $this->schema->getInterface();
    $form['#cfrplugin_context'] = $this->schema->getContext();

    $form['#attached']['library'][] = 'cfrplugin/drilldown-tools';

    return $form;
  }
}
