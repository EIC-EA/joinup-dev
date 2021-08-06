<?php

declare(strict_types = 1);

namespace Drupal\easme_pledge;

use Drupal\collection\Entity\Collection;
use Drupal\collection\Entity\CollectionInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\joinup_community_content\CommunityContentWorkflowAccessControlHandler;
use Drupal\joinup_community_content\Entity\CommunityContentInterface;

/**
 * Access handler for the Pledge content bundle.
 */
class PledgeAccessHandler extends CommunityContentWorkflowAccessControlHandler {



  /**
   * {@inheritdoc}
   */
  protected function entityCreateAccess(CommunityContentInterface $content, AccountInterface $account): AccessResultInterface {
    $parent = $content->getGroup();
    if (($parent instanceof CollectionInterface) && $parent->isChallengeClosed()) {
      return AccessResult::forbidden();
    }
    return parent::entityCreateAccess($content, $account);
  }

  /**
   * Returns the configured permission scheme for the given operation.
   *
   * @param string $operation
   *   The operation for which to return the permission scheme. Can be one of
   *   'create', 'view', 'update', 'delete'.
   *
   * @return array
   *   The permission scheme.
   */
  protected function getPermissionScheme(string $operation): array {
    \assert(\in_array($operation, ['create', 'view', 'update', 'delete']), 'A valid operation should be passed');
    return $this->configFactory->get('easme_pledge.permission_scheme')->get($operation);
  }

}
