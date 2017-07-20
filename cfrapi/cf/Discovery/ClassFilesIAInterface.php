<?php

namespace Donquixote\Cf\Discovery;

interface ClassFilesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator|string[]
   *   Format: $[file] = $class
   */
  public function getIterator();

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * @return self
   */
  public function withRealpathRoot();
}
