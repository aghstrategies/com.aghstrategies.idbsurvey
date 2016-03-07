<?php

require_once 'idbsurvey.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function idbsurvey_civicrm_config(&$config) {
  _idbsurvey_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function idbsurvey_civicrm_xmlMenu(&$files) {
  _idbsurvey_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function idbsurvey_civicrm_install() {
  $message = ts('Congratulations!  You have installed the extension successfully.', array('domain' => 'com.aghstrategies.idbsurvey'));
  $message .= ' ' . CRM_Utils_System::href(ts('Continue to generate a report.', array('domain' => 'com.aghstrategies.idbsurvey')), 'civicrm/report/com.aghstrategies.idbsurvey/idbreport', 'reset=1');
  CRM_Core_Session::setStatus($message, ts('Continue to Report', array('domain' => 'com.aghstrategies.idbsurvey')), 'success');
  return _idbsurvey_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function idbsurvey_civicrm_uninstall() {
  return _idbsurvey_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function idbsurvey_civicrm_enable() {

  return _idbsurvey_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function idbsurvey_civicrm_disable() {
  return _idbsurvey_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function idbsurvey_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  switch ($op) {
    case 'check':
      try {
        $result = civicrm_api3('ReportTemplate', 'getcount', array(
          'value' => "com.aghstrategies.idbsurvey/idbreport",
        ));
      }
      catch (CiviCRM_API3_Exception $e) {
        CRM_Core_Session::setStatus($e->getMessage(), CRM_Idbsurvey_Form_Report_IDBReport::tsLocal('API Error'), 'error');
      }
      break;

    case 'enqueue':
      try {
        $result = civicrm_api3('ReportTemplate', 'get', array(
          'value' => "com.aghstrategies.idbsurvey/idbreport",
          'api.ReportTemplate.create' => array('id' => "\$value.id", 'value' => "contribute/idbreport"),
        ));
      }
      catch (CiviCRM_API3_Exception $e) {
        CRM_Core_Session::setStatus($e->getMessage(), CRM_Idbsurvey_Form_Report_IDBReport::tsLocal('API Error'), 'error');
      }
      break;
  }

  // return _idbsurvey_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function idbsurvey_civicrm_managed(&$entities) {
  return _idbsurvey_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function idbsurvey_civicrm_caseTypes(&$caseTypes) {
  _idbsurvey_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function idbsurvey_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _idbsurvey_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
