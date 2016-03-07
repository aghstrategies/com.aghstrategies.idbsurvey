<?php

class CRM_Idbsurvey_Form_Report_IDBReport extends CRM_Report_Form {
  public function __construct() {
    $this->_columns = array(
      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'filters' => array(
          'financial_type_id' => array(
            'title' => ts('Choose which financial types count as donations'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT_SEPARATOR,
            'options' => CRM_Contribute_PseudoConstant::financialType(),
            'type' => CRM_Utils_Type::T_INT,
          ),
        ),
      ),
    );
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', ts('Individual Donor Benchmark Survey Report'));
    parent::preProcess();
  }


  public function postProcess() {
    /* Basic queries to be built upon */

    $select = "SELECT COUNT(contribution.total_amount) as total_amount_count,
                SUM(contribution.total_amount) as total_amount_sum,
                contribution.currency as currency";
    $from = " FROM civicrm_contact cc
                INNER JOIN civicrm_contribution contribution ON cc.id = contribution.contact_id AND contribution.is_test = 0
                INNER JOIN civicrm_option_value ov
                  ON ov.name = 'Completed'
                    AND ov.value = contribution.contribution_status_id
                INNER JOIN civicrm_option_group og
                  ON og.name = 'contribution_status'
                    AND og.id = ov.option_group_id
                LEFT JOIN civicrm_financial_type ft ON contribution.financial_type_id = ft.id";
    $where = " WHERE ( contribution.receive_date >= 20150101000000 )
                 AND ( contribution.receive_date < 20160101000000 )
                 AND cc.is_deleted = 0";
    $where2014 = " WHERE ( contribution.receive_date >= 20140101000000 )
                     AND ( contribution.receive_date < 20150101000000 )
                     AND cc.is_deleted = 0";

    // Most questions just looking at donations:
    if (array_key_exists('financial_type_id_value', $this->_submitValues)) {
      $types = implode(", ", $this->_submitValues['financial_type_id_value']);
    }
    $typesClause = empty($types) ? 'IS NOT NULL' : "IN ({$types})";
    $whereDon = "$where AND contribution.financial_type_id $typesClause";
    $whereDon2014 = "$where2014 AND contribution.financial_type_id $typesClause";

    // Individual donors (for the purposes of this, households are individuals):
    $whereInd = "$whereDon AND ( cc.contact_type in ('Individual', 'Household') )";
    $whereInd2014 = "$whereDon2014 AND ( cc.contact_type in ('Individual', 'Household') )";

    // Online donations by individuals:
    $whereOnlineInd = "$whereInd AND contribution.contribution_page_id IS NOT NULL";
    $whereOnlineInd2014 = "$whereInd2014 AND contribution.contribution_page_id IS NOT NULL";

    // Recurring donations by individuals:
    $whereRec = "$whereInd AND contribution.contribution_recur_id IS NOT NULL";

    // Getting number of individual donors.
    // This may count someone who gives as an individual and then as a household
    // twice, but there may well be others in the household not counted otherwise
    // so I think it'll all wash out.
    $selectContacts = $select . ", COUNT(DISTINCT contribution.contact_id) as total_contacts";

    /* Specific questions */

    // 1. Total income/revenue for 2015
    $this->runItemQuery($select . $from . $where, 'answer1');

    // 3. Total raised by individuals for 2015
    // 4. Total individual donors
    $this->runItemQuery($selectContacts . $from . $whereInd, 'answer3', 'answer4');

    // TODO: New question 5

    // 6. Total raised online from individuals
    // 7. Number of people that gave online
    $this->runItemQuery($selectContacts . $from . $whereOnlineInd, 'answer6', 'answer7');

    // 8. How much was given in total through recurring (monthly, quarterly, etc) donations in 2015?
    // 9. How many individuals made recurring donations in 2015?
    $this->runItemQuery($selectContacts . $from . $whereOnlineInd, 'answer9', 'answer10');

    // 10. How much did you raise from individuals giving $1,000 or more (in total) in 2015?
    // 11. How many people made gifts of $1,000 or more (in total) in 2015?
    $groupBy1K = " GROUP BY contribution.contact_id
                   HAVING total_amount_sum >= 1000";
    $query1K = "SELECT SUM(total_amount_sum) as total_amount_sum, COUNT(DISTINCT contact_id) as total_contacts
                FROM ($select, contribution.contact_id as contact_id $from $whereInd $groupBy1K)";
    $this->runItemQuery($query1K, 'answer10', 'answer11');

    // 12. Check if memberships are enabled and active
    $components = CRM_Core_Component::getEnabledComponents();
    if (empty($components['CiviMember'])) {
      $this->assign('answer12', ts('CiviMember is disabled', array('domain' => 'com.aghstrategies.idbsurvey')));
    }
    else {
      try {
        $memberships = civicrm_api3('Membership', 'getoptions', array(
          'field' => "membership_type_id",
          'context' => "search",
        ));
        if (empty($memberships['values'])) {
          $this->assign('answer12', ts('No memberships', array('domain' => 'com.aghstrategies.idbsurvey')));
        }
        else {
          $this->assign('answer12', '<ul><li>' . implode('</li><li>', $memberships['values']) . '</li></ul>');
        }
      }
      catch (CiviCRM_API3_Exception $e) {
        $this->assign('answer12', 'API Error: ' . $e->getMessage());
      }
    }

    // 14. What was your organization's total income/revenue in 2014?
    $this->runItemQuery($select . $from . $where2014, 'answer14');

    //15.  What was your organization's total income from individual donors in 2015?
    $this->runItemQuery($select . $from . $whereInd2014, 'answer15');

    //16.  What was your organization's total income from online donations in 2013?
    $this->runItemQuery($select . $from . $whereOnlineInd2014, 'answer16');

    // CRM_Report_Form_Instance::postProcess($this, FALSE);
  }

  public function runItemQuery($sql, $tplField, $tplFieldContact = NULL) {
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign($tplField, CRM_Utils_Money::format($dao->total_amount_sum));
      if ($tplFieldContact) {
        $this->assign($tplFieldContact, $dao->total_contacts);
      }
    }
  }

}
