<?php

namespace Drupal\workflow_email_template\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\AppendCommand;

/**
 * Class NotificationsForm.
 */
class NotificationsForm extends EntityForm {

  protected $workflow;

  public function __construct() {
    $this->workflow = \Drupal::service('workflow_email_template.notifier');
  }
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $notifications = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $notifications->label(),
      '#description' => $this->t("Label for the Notifications."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $notifications->id(),
      '#machine_name' => [
        'exists' => '\Drupal\workflow_email_template\Entity\Notifications::load',
      ],
      '#disabled' => !$notifications->isNew(),
    ];
    $form['workflow'] = [
      '#type' => 'select',
      '#title' => $this->t('Choisir workflow'),
      '#options' => $this->workflow->getWorkflows()['workflow'],
      '#default_value' => $notifications->get('workflow'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [$this, 'getListOfStates'],
        'event' => 'click',
      ],
    ];

    $form['template'] = [
      '#type' => 'details',
      '#title' => $this->t('Paramétrages Template Email'),
      '#open' => TRUE,
      
    ];
    $workflow_selected = !empty($this->entity->id()) ? $this->entity->get('from_state') : $form_state->getValue("from_state");
    $form['template']['from_state'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('De l\'état'),
      '#options' => !empty($notifications->get('workflow')) ? $this->workflow->_prepared_workflow_options($notifications->get('workflow')) : [],
      '#default_value' => $notifications->get('from_state'),
    ];

    $form['template']['to_state'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Vers l\'état'),
      '#options' => !empty($notifications->get('workflow')) ? $this->workflow->_prepared_workflow_options($notifications->get('workflow')) : [],
      '#default_value' => $notifications->get('to_state'),
    ];
    
    $form['template']['object'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Object'),
      '#maxlength' => 255,
      '#default_value' => $notifications->getObject(),
      '#description' => $this->t("Objet du mail."),
      '#required' => TRUE,
    ];
    $form['template']['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Corps du mail'),
      '#format' => 'full_html',
      '#default_value' => $notifications->get('body')['value'],
      '#required' => TRUE,
    ];
    $form['template']['recipients'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Destinataires'),
      '#format' => 'full_html',
      '#default_value' => $notifications->get('recipients'),
      '#required' => TRUE,
      "#description" => $this->t('Le champ destinataires contient des adresses mail séparées par des virgules.')
    ];
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $notifications = $this->entity;
    $status = $notifications->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Notifications.', [
          '%label' => $notifications->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Notifications.', [
          '%label' => $notifications->label(),
        ]));
    }
    $form_state->setRedirectUrl($notifications->toUrl('collection'));
  }


  public function getListOfStates(array &$form, FormStateInterface $form_state) {
    $options = "<option></option>";
    $type = $form_state->getValue('workflow');
    $states = $this->workflow->getWorkflowsStates($type);
    foreach ($states as $state) {
      $options .= "<option value=" . $state['machine_name'] . ">" . $state['label'] . "</option>";
    }
    $response = new AjaxResponse();
    $response->addCommand(new RemoveCommand('select[name="from_state"] option'))
             ->addCommand(new RemoveCommand('select[name="to_state"] option'))
             ->addCommand(new AppendCommand('select[name="to_state"]', $options))
             ->addCommand(new AppendCommand('select[name="from_state"]', $options));
    return $response;
  }

}
