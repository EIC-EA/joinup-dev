<?php

declare(strict_types = 1);

namespace Drupal\joinup_core\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\rdf_entity\Entity\Rdf;
use Drupal\rdf_taxonomy\Entity\RdfTerm;
use Drupal\sparql_entity_storage\UriEncoder;

/**
 * Simple form that reopens a closed RDF based on the RDF entity URL.
 */
class RdfEntityUriWebReopenerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['entity_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('RDF entity ID or a URL'),
      '#description' => $this->t('Paste either a The URL or an aliased RDF entity RDF entity ID in order to be redirected to the RDF entity page to change the status to re-open. This only works if the workflow is applied.'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Go!'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    parent::validateForm($form, $form_state);

    $id = $path = trim($form_state->getValue('entity_id'), " ");

    // An absolute URL.
    if (UrlHelper::isExternal($id)) {
      // Potential RDF entity ID.
      if (!UrlHelper::externalIsLocal($id, $base_url)) {
        if (Rdf::load($id)) {
          $form_state->set('passed_as', 'id');
          $form_state->set('entity_type', 'rdf_entity');
        }
        elseif (RdfTerm::load($id)) {
          $form_state->set('passed_as', 'id');
          $form_state->set('entity_type', 'taxonomy_term');
        }
        else {
          $form_state->setErrorByName('id', $this->t('Not a valid RDF ID: :id.', [':id' => $id]));
        }
        return;
      }
      // Extract the path from the absolute URL.
      $path = parse_url($id, PHP_URL_PATH);
    }

    // Build a Drupal internal URI.
    $uri = 'internal:/' . trim($path, " /");
    $url = Url::fromUri($uri);
    // The path was build from a route.
    if ($url->isRouted()) {
      // This path contains a RDF parameter.
      $parameters = $url->getRouteParameters();
      if (!empty($parameters['rdf_entity'])) {

        $decoded_rdf_entity = UriEncoder::decodeUrl($parameters['rdf_entity']);
        $storage = \Drupal::entityTypeManager()->getStorage('rdf_entity');
        $challenge = null;
        if ($challenge = $storage->load($decoded_rdf_entity)) {
          $challenge->set('field_ar_state', 'validated');
          $challenge->skip_notifications = TRUE;
          $challenge->save();
        $response = $decoded_rdf_entity . " has been reopend.";
        }
        else {
          $response = $decoded_rdf_entity . " re-opening did not succeed.";
        }
        $form_state->set('passed_as', 'url');
        $form_state->setValue('entity_id',  $response);
        return;
      }
      elseif (!empty($parameters['taxonomy_term'])) {
        $form_state->set('passed_as', 'url');
        $form_state->setValue('entity_id', UriEncoder::decodeUrl($parameters['taxonomy_term']));
        return;
      }
    }
    $form_state->setErrorByName('id', $this->t('The entered value (:id) is neither a valid RDF entity ID, nor a valid RDF taxonomy ID, nor a RDF entity URL.', [':id' => $id]));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $passed_as = $form_state->get('passed_as');
    $id = trim($form_state->getValue('entity_id'), " ");
    $entity_type = $form_state->get('entity_type');
    if ($passed_as === 'id') {
      $form_state->setRedirect("entity.${entity_type}.canonical", [$entity_type => $id]);
    }
    elseif ($passed_as === 'url') {
      // We display only the ID, so it can be easily selected with a mouse
      // double/triple click and then copied.
      $this->messenger()->addMessage($id);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rdf_entity_uri_web_reopener';
  }

}
