<?php
// @codingStandardsIgnoreFile

/**
 * This file was generated via php core/scripts/generate-proxy-class.php 'Drupal\joinup\PinService' "profiles/joinup/src".
 */

namespace Drupal\joinup\ProxyClass {

    /**
     * Provides a proxy class for \Drupal\joinup\PinService.
     *
     * @see \Drupal\Component\ProxyBuilder
     */
    class PinService implements \Drupal\joinup\PinServiceInterface
    {

        use \Drupal\Core\DependencyInjection\DependencySerializationTrait;

        /**
         * The id of the original proxied service.
         *
         * @var string
         */
        protected $drupalProxyOriginalServiceId;

        /**
         * The real proxied service, after it was lazy loaded.
         *
         * @var \Drupal\joinup\PinService
         */
        protected $service;

        /**
         * The service container.
         *
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        protected $container;

        /**
         * Constructs a ProxyClass Drupal proxy object.
         *
         * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
         *   The container.
         * @param string $drupal_proxy_original_service_id
         *   The service ID of the original service.
         */
        public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container, $drupal_proxy_original_service_id)
        {
            $this->container = $container;
            $this->drupalProxyOriginalServiceId = $drupal_proxy_original_service_id;
        }

        /**
         * Lazy loads the real service from the container.
         *
         * @return object
         *   Returns the constructed real service.
         */
        protected function lazyLoadItself()
        {
            if (!isset($this->service)) {
                $this->service = $this->container->get($this->drupalProxyOriginalServiceId);
            }

            return $this->service;
        }

        /**
         * {@inheritdoc}
         */
        public function isEntityPinned(\Drupal\Core\Entity\ContentEntityInterface $entity, \Drupal\rdf_entity\RdfInterface $group = NULL)
        {
            return $this->lazyLoadItself()->isEntityPinned($entity, $group);
        }

        /**
         * {@inheritdoc}
         */
        public function setEntityPinned(\Drupal\Core\Entity\ContentEntityInterface $entity, \Drupal\rdf_entity\RdfInterface $group, bool $pinned)
        {
            return $this->lazyLoadItself()->setEntityPinned($entity, $group, $pinned);
        }

        /**
         * {@inheritdoc}
         */
        public function getGroupsWherePinned(\Drupal\Core\Entity\ContentEntityInterface $entity)
        {
            return $this->lazyLoadItself()->getGroupsWherePinned($entity);
        }

    }

}
