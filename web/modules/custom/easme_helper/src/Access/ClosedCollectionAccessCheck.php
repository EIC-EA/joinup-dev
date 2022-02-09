<?php

declare(strict_types = 1);

namespace Drupal\easme_helper\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\collection\Entity\CollectionInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;

/**
 * Provides an access checker for the '_closed_collection' requirement.
 */
class ClosedCollectionAccessCheck implements AccessInterface {

  /**
   * Checks access for routes having the '_closed_collection' requirement.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The parameterized route.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   *
   * @throws \Exception
   *   If the '_closed_collection' has an invalid value.
   */
  public function access(Route $route, RouteMatchInterface $route_match, AccountInterface $account): AccessResultInterface {
    $requirement = $route->getRequirement('_closed_collection');
    if ($requirement !== 'TRUE') {
      throw new \Exception("Invalid value '{$requirement}' for route '_closed_collection' requirement.");
    }
    // Allow admin access.
    if ($account->hasPermission('administer users')) {
      return AccessResult::allowed();
    }
    $collection = $route_match->getParameter('rdf_entity');

    // If the collection is archived (aka challenge is closed), content creation is not allowed.
    if ($collection instanceof CollectionInterface && $collection->get('field_ar_state')->value === 'archived') {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }

}
