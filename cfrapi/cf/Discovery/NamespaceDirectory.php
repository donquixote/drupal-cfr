<?php

namespace Donquixote\Cf\Discovery;

final class NamespaceDirectory {

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
   * @return \Donquixote\Cf\Discovery\NamespaceDirectory
   */
  public static function create($directory, $namespace) {

    self::requireUnslashedDirectory($directory);

    $namespace = self::terminateNamespace($namespace);

    return new self($directory, $namespace);
  }

  /**
   * @param string $class
   *
   * @return \Donquixote\Cf\Discovery\NamespaceDirectory
   */
  public static function createFromClass($class) {

    $reflClass = new \ReflectionClass($class);

    return self::create(
      dirname($reflClass->getFileName()),
      $reflClass->getNamespaceName());
  }

  /**
   * @param string $directory
   */
  private static function requireUnslashedDirectory(&$directory) {

    if ('/' === substr($directory, -1)) {
      throw new \InvalidArgumentException('Path must be provided without trailing slash or backslash.');
    }
  }

  /**
   * @param string $namespace
   *
   * @return string
   *
   * @throws \InvalidArgumentException
   */
  private static function terminateNamespace($namespace) {

    if ('' === $namespace) {
      return '';
    }

    if ('\\' === substr($namespace, -1)) {
      throw new \InvalidArgumentException('Namespace must be provided without trailing backslash.');
    }

    if ('\\' === $namespace[0]) {
      throw new \InvalidArgumentException('Namespace must be provided without leading backslash.');
    }

    return $namespace . '\\';
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
   * @return self
   */
  public function withRealpath() {
    return new self(
      realpath($this->directory),
      $this->terminatedNamespace);
  }

  /**
   * @param string $namespace
   *
   * @return self|null
   */
  public function findNamespace($namespace) {

    $namespace = self::terminateNamespace($namespace);

    if (0 !== strpos($namespace, $this->terminatedNamespace)) {
      return NULL;
    }

    if ($namespace === $this->terminatedNamespace) {
      return $this;
    }

    $l = strlen($this->terminatedNamespace);

    $directory = $this->directory . '/' . str_replace(
        '\\',
        '/',
        substr($namespace, $l, -1));

    return new self($directory, $namespace);
  }

  /**
   * @return self
   */
  public function basedir() {

    $base = $this;
    while (null !== $parent = $base->parent()) {
      $base = $parent;
    }

    return $base;
  }

  /**
   * @return self|null
   */
  public function parent() {

    if ('' === $this->terminatedNamespace || '' === $this->directory) {
      return NULL;
    }

    if (FALSE === $pos = strrpos($this->directory, '/')) {
      $parentDir = '';
      $subdirName = $this->directory;
    }
    else {
      $parentDir = substr($this->directory, 0, $pos);
      $subdirName = substr($this->directory, $pos + 1);
    }

    if ($subdirName . '\\' === $this->terminatedNamespace) {
      return new self($parentDir, '');
    }

    $l = strlen($subdirName);

    if ('\\' . $subdirName . '\\' !== substr($this->terminatedNamespace, -$l - 2)) {
      return NULL;
    }

    return new self(
      $parentDir,
      substr($this->terminatedNamespace, 0, -($l + 1)));
  }

  /**
   * @param string $fragment
   *
   * @return self
   */
  public function subdir($fragment) {
    return new self(
      $this->directory . '/' . $fragment,
      $this->terminatedNamespace . '\\' . $fragment);
  }

  /**
   * @return string
   */
  public function getNamespace() {
    return rtrim($this->terminatedNamespace);
  }

  /**
   * @return string
   */
  public function getTerminatedNamespace() {
    return $this->terminatedNamespace;
  }

  /**
   * @return string
   */
  public function getDirectory() {
    return $this->directory;
  }
}
