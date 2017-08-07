<?php

namespace Drupal\cfrplugin\Util;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Util\StringUtil;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\Core\Render\Markup;

final class UiCodeUtil extends UtilBase {

  /**
   * @param string $class
   *
   * @return string
   */
  public static function classGetCodeAsHtml($class) {

    if (1
      && !interface_exists($class)
      && !class_exists($class)
    ) {
      return t(
        'There is no class or interface named !name.',
        ['@name' => Markup::create('<code>' . HtmlUtil::sanitize($class) . '</code>')]
      );
    }

    if (NULL === $php = self::classGetPhp($class)) {
      return t(
        'Cannot access the code of class @name.',
        ['@name' => Markup::create('<code>' . HtmlUtil::sanitize($class) . '</code>')]
      );
    }

    return self::highlightPhp($php);
  }

  /**
   * @param string $class
   *
   * @return string|null
   */
  public static function classGetPhp($class) {

    $reflectionClass = new \ReflectionClass($class);

    $filename = $reflectionClass->getFileName();
    if (FALSE === $filename || !is_readable($filename)) {
      return NULL;
    }

    return file_get_contents($filename);
  }

  /**
   * @param mixed $value
   *
   * @return string|\Drupal\Component\Render\MarkupInterface
   */
  public static function exportHighlightWrap($value) {

    $text = PhpUtil::phpValue($value);

    return self::highlightAndWrap($text);
  }

  /**
   * @param string $php
   * @param bool $tmp_prepend_opening_tag
   *
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public static function highlightAndWrap($php, $tmp_prepend_opening_tag = TRUE) {

    $php = CodegenUtil::autoIndent($php,'  ');

    if ($tmp_prepend_opening_tag) {
      $php = "<?php\n" . $php;
    }

    $html = highlight_string($php, TRUE);

    if ($tmp_prepend_opening_tag) {
      $html = preg_replace(
        StringUtil::regex(
          '<span style="color: #999999">&lt;?php<br /></span>',
          '@',
          ['999999' => '[a-fA-F0-9]{0,6}']),
        '',
        $html,
        1);
    }

    $html = '<div class="codeblock"><pre>' . $html . '</pre></div>';

    return class_exists(Markup::class)
      ? Markup::create($html)
      : $html;
  }

  /**
   * @param string $php
   *
   * @return string
   *
   * @see codefilter_process_php()
   */
  public static function highlightPhp($php) {

    // Undo the escaping in the prepare step.
    # $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

    // Trim leading and trailing linebreaks.
    # $text = trim($text, "\r\n");

    // Highlight as PHP.
    $text = highlight_string($php, TRUE);

    $text = '<div class="codeblock"><pre>' . $text . '</pre></div>';

    // Remove newlines to avoid clashing with the linebreak filter.
    # $text = str_replace("\n", '', $text);

    // Fix spaces.
    # $text = preg_replace('@&nbsp;(?!&nbsp;)@', ' ', $text);
    // A single space before text is ignored by browsers. If a single space
    // follows a break tag, replace it with a non-breaking space.
    # $text = preg_replace('@<br /> ([^ ])@', '<br />&nbsp;$1', $text);

    return $text;
  }
}
