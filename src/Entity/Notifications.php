<?php

namespace Drupal\workflow_email_template\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Notifications entity.
 *
 * @ConfigEntityType(
 *   id = "notifications",
 *   label = @Translation("Notifications"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\workflow_email_template\NotificationsListBuilder",
 *     "form" = {
 *       "add" = "Drupal\workflow_email_template\Form\NotificationsForm",
 *       "edit" = "Drupal\workflow_email_template\Form\NotificationsForm",
 *       "delete" = "Drupal\workflow_email_template\Form\NotificationsDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\workflow_email_template\NotificationsHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "notifications",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "workflow" = "workflow",
 *     "from_state" = "from_state",
 *     "to_state" = "to_state",
 *     "object" = "object", 
 *     "body" = "body",
 *     "recipients" = "recipients"
 *   },
 *   config_export = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "workflow" = "workflow",
 *     "from_state" = "from_state",
 *     "to_state" = "to_state",
 *     "object" = "object", 
 *     "body" = "body",
 *     "recipients" = "recipients"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/workflow/notifications/{notifications}",
 *     "add-form" = "/admin/config/workflow/notifications/add",
 *     "edit-form" = "/admin/config/workflow/notifications/{notifications}/edit",
 *     "delete-form" = "/admin/config/workflow/notifications/{notifications}/delete",
 *     "collection" = "/admin/config/workflow/notifications"
 *   }
 * )
 */
class Notifications extends ConfigEntityBase implements NotificationsInterface {

  /**
   * The Notifications ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Notifications label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $workflow;

  /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $from_state;

   /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $to_state;


   /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $object;


   /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $body;


   /**
   * The Notification Template current state.
   *
   * @var string
   */
  protected $recipients;

  /**
   * {@inheritdoc}
   */
  public function getObject() {
    return $this->object;
  }

  /**
   * {@inheritdoc}
   */
  public function getBody() {
    return $this->body;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getRecipients() {
    return $this->recipients;
  }

}
