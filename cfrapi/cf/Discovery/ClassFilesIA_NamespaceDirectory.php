<?php

namespace Donquixote\Cf\Discovery;

class ClassFilesIA_NamespaceDirectory implements ClassFilesIAInterface {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  const CLASS_NAME_REGEX = '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(|\.php)$/';

  /**
   * @var string
   */
  private $directory;

  /**
   * @var string
   */
  private $terminatedNamespace;

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return \Donquixote\Cf\Discovery\ClassFilesIAInterface
   */
  public static function create($directory, $namespace) {

    $directory = rtrim($directory, '/\\');
    $terminatedNamespace = ltrim(rtrim($namespace, '\\') . '\\', '\\');

    if (!is_dir($directory)) {
      return new ClassFilesIA_Empty();
    }

    return new self($directory, $terminatedNamespace);
  }

  /**
   * @param \Donquixote\Cf\Discovery\NamespaceDirectory $nsdir
   *
   * @return \Donquixote\Cf\Discovery\ClassFilesIAInterface
   */
  public static function createFromNsdirObject(NamespaceDirectory $nsdir) {

    if (!is_dir($nsdir->getDirectory())) {
      return new ClassFilesIA_Empty();
    }

    return new self(
      $nsdir->getDirectory(),
      $nsdir->getTerminatedNamespace());
  }

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  private function __construct($directory, $terminatedNamespace) {
    $this->directory = $directory;
    $this->terminatedNamespace = $terminatedNamespace;
  }

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * This is useful when comparing the path to \ReflectionClass::getFileName().
   * Note that this does NOT send all discovered file paths through realpath(),
   * only the base path. E.g. if there is a symlink in a subfolder somewhere,
   * the resulting paths will not match up with \ReflectionClass::getFileName().
   *
   * @return static
   */
  public function withRealpathRoot() {
    $clone = clone $this;
    $clone->directory = realpath($this->directory);
    return $clone;
  }

  /**
   * @return \Iterator|string[]
   *   Format: $[file] = $class
   */
  public function getIterator() {

    return $this->findClassFilesRecursive(
      $this->directory,
      $this->terminatedNamespace);
  }

  /**
   * @param string $dir
   * @param string $terminatedNamespace
   *
   * @return \Iterator|string[]
   *   Format: $[$file] = $class
   */
  protected function findClassFilesRecursive($dir, $terminatedNamespace) {

    foreach (scandir($dir) as $candidate) {

      if ('.' === $candidate[0]) {
        // File or directory is hidden, or $candidate is '.' or '..'.
        continue;
      }

      $path = $dir . '/' . $candidate;

      if ('.php' === substr($candidate, -4)) {

        if (!is_file($path)) {
          continue;
        }

        $name = substr($candidate, 0, -4);

        if (!preg_match(self::CLASS_NAME_REGEX, $name)) {
          continue;
        }

        yield $path => $terminatedNamespace . $name;
      }
      else {

        if (!is_dir($path)) {
          continue;
        }

        if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
          continue;
        }

        // "yield from" is not supported in PHP 5.*.
        foreach ($this->findClassFilesRecursive(
          $path,
          $terminatedNamespace . $candidate . '\\') as $file => $class) {

          yield $file => $class;
        }
      }
    }
  }
}
