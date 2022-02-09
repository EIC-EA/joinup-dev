<?php

declare(strict_types = 1);

namespace Drupal\collection\Entity;

use Drupal\joinup_bundle_class\ShortIdInterface;
use Drupal\joinup_group\Entity\GroupInterface;
use Drupal\joinup_workflow\EntityWorkflowStateInterface;
use Drupal\rdf_entity\RdfInterface;

/**
 * Interface for collection entities in Joinup.
 */
interface CollectionInterface extends RdfInterface, EntityWorkflowStateInterface, GroupInterface, ShortIdInterface {

  /**
   * Returns the solutions that are affiliated with this collection.
   *
   * @return \Drupal\solution\Entity\SolutionInterface[]
   *   The solutions.
   */
  public function getSolutions(): array;

  /**
   * Returns the IDs of the solutions that are affiliated with this collection.
   *
   * @return string[]
   *   The solutions.
   */
  public function getSolutionIds(): array;

  /**
   * Returns whether the challenge is closed or ongoing.
   *
   * @return bool
   *   True if the challenge is closed or false if the challenge is ongoing. The
   *   status of the challenge must be 'archived' (Closed) in order to be
   *   considered closed.
   */
  public function isChallengeClosed(): bool;

}
