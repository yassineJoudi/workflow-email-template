<?php

namespace Drupal\workflow_email_template\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Notifications entities.
 */
interface NotificationsInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.
  public function getObject();
  public function getBody();
  public function getRecipients();

}
