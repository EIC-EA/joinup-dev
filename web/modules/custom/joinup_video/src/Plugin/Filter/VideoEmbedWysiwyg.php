<?php

namespace Drupal\joinup_video\Plugin\Filter;


use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\video_embed_field\ProviderManagerInterface;
use Drupal\video_embed_wysiwyg\Plugin\Filter\VideoEmbedWysiwyg as VideoEmbedWysiwygOriginal;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The video_embed_field video filter that works with cck.
 */
class VideoEmbedWysiwyg extends VideoEmbedWysiwygOriginal {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * VideoEmbedWysiwyg constructor.
   *
   * @param array $configuration
   *   Plugin configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\video_embed_field\ProviderManagerInterface $provider_manager
   *   The video provider manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   The request stack.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ProviderManagerInterface $provider_manager, RendererInterface $renderer, AccountProxyInterface $current_user, RequestStack $request) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $provider_manager, $renderer, $current_user);
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('video_embed_field.provider_manager'),
      $container->get('renderer'),
      $container->get('current_user'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {

    $response = new FilterProcessResult($text);

    foreach ($this->getValidMatches($text) as $source_text => $embed_data) {
      if (!$provider = $this->providerManager->loadProviderFromInput($embed_data['video_url'])) {
        continue;
      }

      $autoplay = $this->currentUser->hasPermission('never autoplay videos') ? FALSE : $embed_data['settings']['autoplay'];
      $embed_code = $provider->renderEmbedCode($embed_data['settings']['width'], $embed_data['settings']['height'], $autoplay);

      // Override the default url and pass it to the ec cck url.
      if (!UrlHelper::externalIsLocal($embed_code['#url'], $this->request->getCurrentRequest()->getSchemeAndHttpHost())) {
        $embed_code['#url'] = JOINUP_VIDEO_EMBED_COOKIE_URL . urlencode($embed_code['#url']);
      }

      $embed_code = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [Html::cleanCssIdentifier(sprintf('video-embed-field-provider-%s', $provider->getPluginId()))],
        ],
        'children' => $embed_code,
      ];

      // Add the container to make the video responsive if it's been
      // configured as such. This usually is attached to field output in the
      // case of a formatter, but a custom container must be used where one is
      // not present.
      if ($embed_data['settings']['responsive']) {
        $embed_code['#attributes']['class'][] = 'video-embed-field-responsive-video';
      }

      // Replace the JSON settings with a video.
      $text = str_replace($source_text, $this->renderer->render($embed_code), $text);

      // Add the required responsive video library only when at least one match
      // is present.
      $response->setAttachments(['library' => ['video_embed_field/responsive-video']]);
      $response->setCacheContexts(['user.permissions']);
    }

    $response->setProcessedText($text);
    return $response;
  }

}
