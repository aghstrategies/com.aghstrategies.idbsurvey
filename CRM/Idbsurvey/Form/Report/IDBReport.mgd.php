<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array(
  0 => array(
    'name' => 'CRM_Idbsurvey_Form_Report_IDBReport',
    'entity' => 'ReportTemplate',
    'params' => array(
      'version' => 3,
      'label' => 'Individual Donor Benchmark Survey Report',
      'description' => 'Individual Donor Benchmark Survey Report  (com.aghstrategies.idbsurvey)',
      'class_name' => 'CRM_Idbsurvey_Form_Report_IDBReport',
      'report_url' => 'contribute/idbreport',
      'component' => 'CiviContribute',
    ),
  ),
);
