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
  $message = array(ts('Now that you have installed the extension, run the report to get your data.', array('domain' => 'com.aghstrategies.idbsurvey')));
  $message[] = CRM_Utils_System::href(ts('Continue to generate a report.', array('domain' => 'com.aghstrategies.idbsurvey')), 'civicrm/report/contribute/idbreport', 'reset=1');
  CRM_Core_Session::setStatus(implode(' ', $message), ts('Continue to Report', array('domain' => 'com.aghstrategies.idbsurvey')), 'success');
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
  // Just get rid of old path to report--no need for full upgrader
  try {
    $result = civicrm_api3('ReportTemplate', 'get', array(
      'value' => "com.aghstrategies.idbsurvey/idbreport",
      'api.ReportTemplate.create' => array(
        'id' => '$value.id',
        'value' => 'contribute/idbreport',
      ),
    ));
  }
  catch (CiviCRM_API3_Exception $e) {
    CRM_Core_Session::setStatus($e->getMessage(), ts('API Error', array('domain' => 'com.aghstrategies.idbsurvey')), 'error');
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
