<?php

namespace Donquixote\Cf\Discovery;

class ClassFilesIA_Empty implements ClassFilesIAInterface {

  /**
   * @return \Iterator|string[]
   *   Format: $[file] = $class
   */
  public function getIterator() {
    return new \EmptyIterator();
  }

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * @return static
   */
  public function withRealpathRoot() {
    return $this;
  }
}
