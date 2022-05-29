<?php

namespace Drupal\workflow_email_template\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\workflow_email_template\Event\ModerationEvent;


/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\workflow_email_template\EventSubscriber
 */
class ModirationSubscriber implements EventSubscriberInterface {

  protected $notifier;

  public function __construct() {
      $this->notifier = \Drupal::service('workflow_email_template.notifier');
  }

    /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [
        ModerationEvent::MODERATION_PRESAVE => ['ModirationPresave', 0],
    ];
    return $events;
  }

 

  public function ModirationPresave(ModerationEvent $event) {
    $entity = $event->getEntity();
    if($entity->isNew()) {
      return;
    }
    $this->notifier->notify($entity->original->moderation_state->value, $entity->moderation_state->value, $entity);
  }

}