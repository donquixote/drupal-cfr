<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\Cf\ATA\ATAInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class ATAPartial_SmartChain implements ATAPartialInterface {

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface[][][]
   *   Format: $[$sourceType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$sourceType] = $partials
   */
  private $partialsBySourceType = [];

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {
    $partials = LocalPackageUtil::collectATAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface[] $partials
   */
  public function __construct(array $partials) {

    $indices = [];
    $specifities = [];
    $i = 0;
    foreach ($partials as $partial) {
      ++$i;
      $indices[] = $i;
      $specifities[] = $partial->getSpecifity();
    }

    array_multisort($specifities, SORT_DESC, $indices, $partials);

    $this->partials = $partials;
  }

  /**
   * @return int
   */
  public function getSpecifity() {
    return 0;
  }

  /**
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   */
  public function cast(
    $source,
    $interface,
    ATAInterface $helper
  ) {

    $partials = $this->sourceTypeAndTargetTypeGetPartials(
      get_class($source),
      $interface);

    foreach ($partials as $partial) {

      $candidate = $partial->cast($source, $interface, $helper);

      if ($candidate instanceof $interface) {
        return $candidate;
      }
    }

    return NULL;
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeAndTargetTypeGetPartials($sourceType, $targetType) {

    return isset($this->partialsGrouped[$sourceType][$targetType])
      ? $this->partialsGrouped[$sourceType][$targetType]
      : $this->partialsGrouped[$sourceType][$targetType] = $this->sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType);
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType) {

    return array_intersect_key(
      $this->sourceTypeGetPartials($sourceType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function targetTypeGetPartials($interface) {

    return isset($this->partialsByTargetType[$interface])
      ? $this->partialsByTargetType[$interface]
      : $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function targetTypeCollectPartials($targetType) {

    $partials = [];
    /** @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->providesResultType($targetType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeGetPartials($interface) {

    return isset($this->partialsBySourceType[$interface])
      ? $this->partialsBySourceType[$interface]
      : $this->partialsBySourceType[$interface] = $this->sourceTypeCollectPartials($interface);
  }

  /**
   * @param string $sourceType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeCollectPartials($sourceType) {

    $partials = [];
    /** @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface) {
    return [] !== $this->targetTypeGetPartials($interface);
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSourceClass($interface) {
    return [] !== $this->sourceTypeGetPartials($interface);
  }
}
