<?php

declare(strict_types = 1);

namespace Drupal\easme_pledge;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\collection\Entity\CollectionInterface;
use Drupal\joinup_community_content\CommunityContentWorkflowAccessControlHandler;
use Drupal\joinup_community_content\Entity\CommunityContentInterface;
use Drupal\solution\Entity\SolutionInterface;

/**
 * Access handler for the Pledge content bundle.
 */
class PledgeAccessHandler extends CommunityContentWorkflowAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function entityCreateAccess(CommunityContentInterface $content, AccountInterface $account): AccessResultInterface {
    if ($account->isAnonymous()) {
      return AccessResult::forbidden();
    }

    $parent = $content->getGroup();
    // The parent will never be empty because an exception would be thrown in
    // that case.
    if (($parent instanceof CollectionInterface) && $parent->isChallengeClosed()) {
      return AccessResult::forbidden();
    }
    elseif ($parent instanceof SolutionInterface && $parent->isRelatedChallengeClosed()) {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
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
