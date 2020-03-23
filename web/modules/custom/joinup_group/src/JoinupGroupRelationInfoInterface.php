<?php

declare(strict_types = 1);

namespace Drupal\joinup_group;

use Drupal\Core\Entity\EntityInterface;
use Drupal\og\OgMembershipInterface;
use Drupal\rdf_entity\RdfInterface;

/**
 * An interface for services that provide information about group relations.
 */
interface JoinupGroupRelationInfoInterface {

  /**
   * Retrieves the parent of the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The group content entity.
   *
   * @return \Drupal\rdf_entity\RdfInterface|null
   *   The rdf entity the passed entity belongs to, or NULL when no group is
   *    found.
   */
  public function getParent(EntityInterface $entity): ?RdfInterface;

  /**
   * Retrieves all the members with any role in a certain group.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The group entity.
   * @param array $states
   *   (optional) An array of membership states to retrieve. Defaults to active.
   *
   * @return array
   *   An array of users that are members of the entity group.
   */
  public function getGroupUsers(EntityInterface $entity, array $states = [OgMembershipInterface::STATE_ACTIVE]): array;

  /**
   * Returns the groups that relate to a contact information entity.
   *
   * @param \Drupal\rdf_entity\RdfInterface $entity
   *   The contact information entity.
   *
   * @return \Drupal\rdf_entity\RdfInterface[]
   *   A list of rdf entities that reference the given contact information
   *   entity.
   */
  public function getContactInformationRelatedGroups(RdfInterface $entity): array;

}
