<?php
/**
 * @file
 *
 */

namespace Drupal\rdf_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\rdf_entity\Entity\Rdf;

/**
 * Provides route responses for the Example module.
 */
class RdfDemoController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple render array.
   */
  public function repositories() {
    /** @var \Drupal\rdf_entity\Entity\RdfEntitySparqlStorage $entity_storage */
    $entity_storage = \Drupal::service('entity.manager')->getStorage('rdf_entity');
    $query = $entity_storage->getQuery()
      ->sort('id')
      ->condition('?entity', 'rdf:type', '<http://www.w3.org/ns/adms#AssetRepository>')
      ->pager(10);

    $rids = $query->execute();
    $entities = $entity_storage->loadMultiple($rids);
    $list = array('#theme' => 'item_list');
    /** @var Rdf $entity */
    foreach ($entities as $entity) {
      $list['#items'][] = array('#markup' => $entity->link());
    }

    // @todo Find out why paging is not working...
    $build = array(
      'list' => $list,
      'pager' => array(
        '#theme' => 'pager',
      ),
    );
    return $build;
  }
}