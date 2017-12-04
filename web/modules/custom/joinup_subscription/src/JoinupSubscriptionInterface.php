<?php

declare(strict_types = 1);

namespace Drupal\joinup_subscription;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\UserInterface;

/**
 * Interface for Joinup subscription service.
 *
 * A subscription is defined as the flagging of certain content entity with a
 * specific flag. The user that performs this process is called subscriber.
 */
interface JoinupSubscriptionInterface {

  /**
   * Gets all the subscribers for a given content entity and a given flag.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity where users have subscribed.
   * @param string $flag_id
   *   The ID of the subscription flag entity.
   *
   * @return \Drupal\user\UserInterface[]
   *   An associative array of subscriber user accounts, keyed by user ID.
   */
  public function getSubscribers(ContentEntityInterface $entity, string $flag_id) : array;

  /**
   * Subscribes a user to a given content entity through a given flag.
   *
   * @param \Drupal\user\UserInterface $account
   *   The user to subscribe.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to subscribe the user to.
   * @param string $flag_id
   *   The ID of the subscription flag entity that keeps track of the
   *   subscription.
   *
   * @return bool
   *   TRUE if the subscription was successful, FALSE otherwise.
   */
  public function subscribe(UserInterface $account, ContentEntityInterface $entity, string $flag_id) : bool;

  /**
   * Unsubscribes a user from a given content entity through a given flag.
   *
   * @param \Drupal\user\UserInterface $account
   *   The user to unsubscribe.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to unsubscribe the user from.
   * @param string $flag_id
   *   The ID of the subscription flag entity that keeps track of the
   *   subscription.
   */
  public function unsubscribe(UserInterface $account, ContentEntityInterface $entity, string $flag_id) : void;

  /**
   * Checks whether a user is subscribed to a given entity through a given flag.
   *
   * @param \Drupal\user\UserInterface $account
   *   The user for which to check if a subscription exists.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity the user is possibly subscribed to.
   * @param string $flag_id
   *   The ID of the subscription flag entity that keeps track of the
   *   subscription.
   *
   * @return bool
   *   TRUE if the user is subscribed, FALSE otherwise.
   */
  public function isSubscribed(UserInterface $account, ContentEntityInterface $entity, string $flag_id) : bool;

}
