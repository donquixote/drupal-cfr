<?php

namespace Drupal\cfrplugin\Controller;

use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrreflection\Util\StringUtil;
use Drupal\controller_annotations\Configuration\Cache;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\RouteModifier\RouteIsAdmin;
use Drupal\controller_annotations\RouteModifier\RouteRequirePermission;
use Drupal\controller_annotations\RouteModifier\RouteTitle;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\routelink\RouteModifier\RouteMenuLink;

/**
 * @Route("/admin/reports/cfrplugin")
 * @Cache(expires="tomorrow")
 * @RouteIsAdmin
 * @RouteRequirePermission("view cfrplugin report")
 */
class Controller_ReportOverview extends ControllerBase {

  /**
   * @Route
   * @RouteTitle("Cfr plugins")
   * @RouteMenuLink
   */
  public function overview() {

    $services = CfrPluginHub::getContainer();
    $definitions = $services->definitionsByTypeAndId->getDefinitionsByTypeAndId();

    $schemaToConfigurator = $services->schemaToConfigurator;

    // @todo THis should be a service.
    $definitionToSchema = DefinitionToSchema_Mappers::create();

    $rows = [];
    $rows_grouped = [];
    foreach ($definitions as $interface => $interface_definitions) {

      $label = StringUtil::interfaceGenerateLabel($interface);
      $count = t('@n plugin definitions', ['@n' => count($interface_definitions)]);
      $interface_shortname = StringUtil::classGetShortname($interface);

      $brokenIds = [];
      foreach ($interface_definitions as $id => $definition) {

        try {
          // Just check if anything blows up.
          $schema = $definitionToSchema->definitionGetSchema($definition);
          $schemaToConfigurator->schemaGetConfigurator($schema);
        }
        catch (\Exception $e) {
          $brokenIds[] = $id;
          break;
        }
      }

      if ([] !== $brokenIds) {
        $nBroken = count($brokenIds);
        $label = "($nBroken broken) $label";
      }

      $row = [
        $label,

        Controller_ReportIface::route($interface)
          ->link($count),

        Controller_ReportIface::route($interface)
          ->subpage('code')
          ->link($interface_shortname),

        Markup::create('<code>' . $interface . '</code>'),
      ];

      $fragments = explode('\\', $interface);

      if (1
        && 'Drupal' === $fragments[0]
        && isset($fragments[2])
        # && module_exists($fragments[1])
      ) {
        $rows_grouped[$fragments[1]][] = $row;
      }
      else {
        $rows[] = $row;
      }

      if ([] !== $brokenIds) {
        break;
      }
    }

    $modules_info = system_get_info('module');

    foreach ($rows_grouped as $module => $module_rows) {

      $module_label = isset($modules_info[$module])
        ? $modules_info[$module]['name']
        : $module;

      $rows[] = [
        [
          'colspan' => 4,
          'data' => ['#markup' => '<h3>' . $module_label . '</h3>'],
        ],
      ];

      foreach ($module_rows as $row) {
        $rows[] = $row;
      }
    }

    return [
      '#header' => [
        t('Human name'),
        t('List'),
        t('Code'),
        t('Interface'),
      ],
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }
}
