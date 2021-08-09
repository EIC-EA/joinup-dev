<?php

declare(strict_types = 1);

namespace Drupal\joinup_group\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A block showing the introduction text on collection and solution overviews.
 *
 * @Block(
 *  id = "overview_message_block",
 *  admin_label = @Translation("Overview message"),
 * )
 */
class OverviewMessageBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Routing\CurrentRouteMatch definition.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * Constructs a new OverviewMessageBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $route_name = $this->currentRouteMatch->getRouteName();
    $build = [];
    if ($route_name === 'view.solutions.page_1') {
      $build['header_description'] = [
        '#type' => 'inline_template',
        '#template' => "<p>Submit your proposed solution for a specific challenge. By default, only the Challenge Platform and the challenger can view your solution. In some cases, the Challenge Solver may be asked to submit the solution directly on the external platform of the Challenge Owner.</p><p>Thinking about submitting your solution? We've got you covered, read more <a href='/challenges/why-create-solution'>here</a>. </p> ",
      ];
    }
    elseif ($route_name === 'view.collections.page_1') {
      $build['header_description'] = [
        '#type' => 'inline_template',
        '#template' => "<p>Discover all the challenges: take a look below and participate in the challenge that interests you most!<br/>How can you contribute? Think big and submit your solution!</p> <p>Thinking about submitting your solution? We've got you covered, read more <a href='/challenges/why-create-solution'>here</a>. </p> <p>For the challenges and solutions regarding COVID-19, you can find them <a href='/challenges/covid-19'>here</a>. </p>",
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts(): array {
    $cache_contexts = parent::getCacheContexts();
    return Cache::mergeContexts($cache_contexts, ['route']);
  }

}
