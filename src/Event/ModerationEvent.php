<?php

namespace Drupal\workflow_email_template\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * 
 */
class ModerationEvent extends Event {

  const MODERATION_PRESAVE = 'workflow_email_template.modiration.presave';

  /**
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  public function getEntity() {
    return $this->entity;
  }
  
}

