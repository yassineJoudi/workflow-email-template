<?php

namespace Drupal\workflow_email_template\Service;

class Notifier{

    protected $langcode;

    public function __construct() {
        $this->langcode = \Drupal::currentUser()->getPreferredLangcode();  
    }

    function notify($state_from, $state_to, $entity) {
        $templates = $this->getTemplates($state_from, $state_to, $entity);
        foreach($templates as $template) {
            $emails = explode(",", $template['recipients']);
            $params = [
                'subject' => $template['object'],
                'body' =>  $template['body']['value'],
            ];
            foreach($emails as $email) {
                \Drupal::service('plugin.manager.mail')->mail('workflow_email_template', 'workflow_email_notification', $email, $this->langcode, ['params'=> $params]);
            }
        }
        return [];
    }


    function getTemplates($state_from, $state_to, $entity) {
        $templates = $result = [];
        if(empty($state_from) && empty($state_to)) {
            return $templates;
        }
        $storage = \Drupal::entityTypeManager()->getStorage('notifications');
        $ids = \Drupal::entityQuery('notifications')
                ->condition('from_state', $state_from)
                ->condition('to_state', $state_to)
                ->condition('workflow', $entity->workflow->target_id)
                ->execute();

        $items = $storage->loadMultiple($ids);
        foreach($items as $key => $entity) {
            if(!$entity->get('status')) {
                continue;
            }
            $templates[] = [
                'from_state' => $entity->get("from_state"),
                'to_state' => $entity->get("to_state"),
                'object' => $entity->get("object"),
                'body' => $entity->get("body"),
                'recipients' => $entity->get("recipients"),
                'originalId' => $entity->get("originalId"),
                'status' => $entity->get('status')
            ];
        }
        return $templates;
    }




    function getWorkflows() {
        $workflows_list =  $workflow_options = $options = [];
        $workflows = \Drupal::entityTypeManager()->getStorage('workflow')->loadByProperties();
        if(count($workflows) > 0) {
            foreach($workflows as $key => $workflow) {
                $workflow_options[$workflow->id()] = $key;
                $states = $workflow->get('type_settings')['states'];
                foreach($states as $_key => $state) {
                    $options[$_key] = $state['label'];
                }
                $workflows_list['workflow'] = $workflow_options;
                $workflows_list['states'] = $options;
            }
        }
        return $workflows_list;
    }


    function getWorkflowsStates($type) {
        $options = [];
        $workflow = \Drupal::entityTypeManager()->getStorage('workflow')->load($type);
        if(empty($workflow)) {
            return $options;
        }
        $states = $workflow->get('type_settings')['states'];
        foreach($states as $key => $state) {
            $state['machine_name'] = $key;
            $options[] = $state;
        }
        usort($options, function ($a, $b) {
            return ($a['weight'] < $b['weight']) ? -1 : 1;
        });
        return $options;
    }


    function _prepared_workflow_options($type) {
        $options = [];
        $states = $this->getWorkflowsStates($type);
        foreach ($states as $state) {
          $options[$state['machine_name']] = $state['label'];
        }
        return $options;
      }

}