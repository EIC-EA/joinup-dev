<?php

declare(strict_types = 1);

namespace Drupal\solution\Entity;

use Drupal\collection\Entity\CollectionContentInterface;
use Drupal\joinup_bundle_class\ShortIdInterface;
use Drupal\joinup_group\Entity\GroupInterface;
use Drupal\joinup_workflow\EntityWorkflowStateInterface;
use Drupal\rdf_entity\RdfInterface;

/**
 * Interface for solution entities in Joinup.
 */
interface SolutionInterface extends RdfInterface, CollectionContentInterface, EntityWorkflowStateInterface, GroupInterface, ShortIdInterface {

  /**
   * Returns whether the challenge related to the solution is closed or ongoing.
   *
   * @return bool
   *   True if the challenge is closed or false if the challenge is ongoing. The
   *   status of the challenge must be 'archived' (Closed) in order to be
   *   considered closed.
   */
  function isRelatedChallengeClosed(): bool;

}
