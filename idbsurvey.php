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
  catch (CRM_Core_Exception $e) {
    CRM_Core_Session::setStatus($e->getMessage(), ts('API Error', array('domain' => 'com.aghstrategies.idbsurvey')), 'error');
  }

  // return _idbsurvey_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function idbsurvey_civicrm_navigationMenu(&$menu) {
  // We'd like the item to be beneath these two items.
  $idealTree = array(
    'Contributions',
  );
  // Walk down the menu to see if we can find them where we expect them.
  $walkMenu = $menu;
  $branches = array();
  foreach ($idealTree as $limb) {
    foreach ($walkMenu as $id => $item) {
      if ($item['attributes']['name'] == $limb) {
        $walkMenu = CRM_Utils_Array::value('child', $item, array());
        $branches[] = $id;
        $branches[] = 'child';
        continue 2;
      }
    }
    // If the expected parent isn't at this level of the menu, we'll just drop
    // it here.
    break;
  }
  $item = array(
    'attributes' => array(
      'label' => ts('Individual Donor Benchmark Survey', array('domain' => 'com.aghstrategies.idbsurvey')),
      'name' => 'idbsurvey_report',
      'url' => 'civicrm/report/contribute/idbreport?reset=1',
      'permission' => 'access Report Criteria',
      'operator' => 'AND',
      'separator' => 0,
      'active' => 1,
    ),
  );
  if (!empty($id)) {
    $item['parentID'] = $id;
  }
  // Need to put together exactly where the item should be added;
  $treeMenu = &$menu;
  foreach ($branches as $branch) {
    $treeMenu = &$treeMenu[$branch];
  }
  $newId = 0;
  idbsurvey_scanMaxNavId($menu, $newId);
  $newId++;
  $item['navID'] = $newId;
  $treeMenu[$newId] = $item;
}

/**
 * Scans recursively for the highest ID in the navigation.
 *
 * Better than searching the database because other extensions may have added
 * items in the meantime.
 *
 * @param array $menu
 *   The menu to scan.
 * @param int &$max
 *   The maximum found so far.
 */
function idbsurvey_scanMaxNavId($menu, &$max) {
  foreach ($menu as $id => $item) {
    $max = max($id, $max);
    if (!empty($item['child'])) {
      idbsurvey_scanMaxNavId($item['child'], $max);
    }
  }
}

// /**
//  * Implements hook_civicrm_postInstall().
//  *
//  * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
//  */
// function idbsurvey_civicrm_postInstall() {
//   _idbsurvey_civix_civicrm_postInstall();
// }

// /**
//  * Implements hook_civicrm_entityTypes().
//  *
//  * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
//  */
// function idbsurvey_civicrm_entityTypes(&$entityTypes) {
//   _idbsurvey_civix_civicrm_entityTypes($entityTypes);
// }
