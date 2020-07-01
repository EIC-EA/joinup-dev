<?php

declare(strict_types = 1);

namespace Drupal\collection\Entity;

use Drupal\joinup_bundle_class\JoinupBundleClassFieldAccessTrait;
use Drupal\joinup_bundle_class\ShortIdTrait;
use Drupal\joinup_group\Entity\GroupTrait;
use Drupal\rdf_entity\Entity\Rdf;

/**
 * Entity subclass for the 'collection' bundle.
 */
class Collection extends Rdf implements CollectionInterface {

  use GroupTrait;
  use JoinupBundleClassFieldAccessTrait;
  use ShortIdTrait;

}
