<?php

class CRM_Idbsurvey_Form_Report_IDBReport extends CRM_Report_Form {
  protected $_noFields = TRUE;

  public $answers = array();

  public function __construct() {
    $this->_columns = array(
      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'filters' => array(
          'financial_type_id' => array(
            'title' => self::tsLocal('Financial types considered to be donations'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT_SEPARATOR,
            'options' => CRM_Contribute_PseudoConstant::financialType(),
            'type' => CRM_Utils_Type::T_INT,
          ),
        ),
      ),
    );
    parent::__construct();
  }

  /**
   * Build the Title and Format tab without adding buttons.
   */
  public function buildInstanceAndButtons() {
    CRM_Report_Form_Instance::buildForm($this);

    $label = $this->_id ? ts('Update Report') : ts('Create Report');
    $this->addElement('submit', $this->_instanceButtonName, $label);

    if ($this->_id) {
      $this->addElement('submit', $this->_createNewButtonName,
        ts('Save a Copy') . '...');
    }
    $this->assign('instanceForm', $this->_instanceForm);

    $this->addButtons(array(
        array(
          'type' => 'submit',
          'name' => self::tsLocal('Display Answers for the Survey'),
          'isDefault' => TRUE,
        ),
    ));
  }

  public function postProcess() {
    $this->beginPostProcess();

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
    $where = " WHERE ( contribution.receive_date >= 20160101000000 )
                 AND ( contribution.receive_date < 20170101000000 )
                 AND cc.is_deleted = 0";
    $where2015 = " WHERE ( contribution.receive_date >= 20150101000000 )
                     AND ( contribution.receive_date < 20160101000000 )
                     AND cc.is_deleted = 0";

    // Most questions just looking at donations:
    if (array_key_exists('financial_type_id_value', $this->_submitValues)) {
      $types = implode(", ", $this->_submitValues['financial_type_id_value']);
    }
    $incExc = (CRM_Utils_Array::value('financial_type_id_op', $this->_submitValues) == 'mnot') ? 'NOT IN' : 'IN';
    $typesClause = empty($types) ? 'IS NOT NULL' : "$incExc ({$types})";
    $whereDon = "$where AND contribution.financial_type_id $typesClause";
    $whereDon2015 = "$where2015 AND contribution.financial_type_id $typesClause";

    // Individual donors (for the purposes of this, households are individuals):
    $whereInd = "$whereDon AND ( cc.contact_type in ('Individual', 'Household') )";
    $whereInd2015 = "$whereDon2015 AND ( cc.contact_type in ('Individual', 'Household') )";

    // Online donations by individuals:
    $whereOnlineInd = "$whereInd
      AND contribution.contribution_page_id IS NOT NULL
      AND contribution.contribution_recur_id IS NULL";
    $whereOnlineInd2015 = "$whereInd2015
      AND contribution.contribution_page_id IS NOT NULL
      AND contribution.contribution_recur_id IS NULL";

    // Recurring donations by individuals:
    $whereRec = "$whereInd AND contribution.contribution_recur_id IS NOT NULL";

    // Getting number of individual donors.
    // This may count someone who gives as an individual and then as a household
    // twice, but there may well be others in the household not counted otherwise
    // so I think it'll all wash out.
    $selectContacts = $select . ", COUNT(DISTINCT contribution.contact_id) as total_contacts";

    $questions = array();

    /* Specific questions */

    // 1. Total income/revenue for 2016
    $questions[1] = self::tsLocal("What was your organization’s total revenue?");
    $this->runItemQuery($select . $from . $where, 1);

    // 3. Total raised by individuals for 2016
    // 4. Total individual donors
    $questions[2] = self::tsLocal('What was the total amount raised from individuals? Please include online and offline donations from direct mail, email, major donors, and other individual donor strategies.');
    $questions[3] = self::tsLocal('How many individuals donated?');
    $this->runItemQuery($selectContacts . $from . $whereInd, 2, 3);

    // 5. What is your retention rate?
    $questions[4] = self::tsLocal('What is your retention rate? Retention rate is the percentage of people who gave last year that gave again this year.');
    $selectRetained = "$selectContacts, IF(og2.id IS NULL,0,1) as retained";
    $retainedFrom = "$from
      LEFT JOIN civicrm_contribution c2
        ON c2.contact_id = contribution.contact_id
        AND c2.receive_date >= 20160101000000
        AND c2.receive_date < 20170101000000
        AND c2.financial_type_id $typesClause
      LEFT JOIN civicrm_option_value ov2
        ON ov2.name = 'Completed'
        AND ov2.value = c2.contribution_status_id
      LEFT JOIN civicrm_option_group og2
        ON og2.name = 'contribution_status'
        AND og2.id = ov2.option_group_id
      LEFT JOIN civicrm_financial_type ft2
        ON c2.financial_type_id = ft2.id";

    $dao = CRM_Core_DAO::executeQuery($selectRetained . $retainedFrom . $whereInd2015 . ' GROUP BY retained');
    $results = array();
    while ($dao->fetch()) {
      $results[$dao->retained] = $dao->total_contacts;
    }
    if (empty($results[1])) {
      $this->answers[4] = '0%';
    }
    elseif (empty($results[0])) {
      $this->answers[4] = '100%';
    }
    else {
      $pct = 100 * $results[1] / ($results[0] + $results[1]);
      $pct = round($pct, 2);
      $this->answers[4] = "$pct%";
    }

    // 6. Total raised online from individuals
    // 7. Number of people that gave online
    $questions[5] = self::tsLocal('How much did you raise online from individuals? Please do not include recurring (monthly, quarterly, etc.) in this total.');
    $questions[6] = self::tsLocal('How many individuals gave online?');
    $this->runItemQuery($selectContacts . $from . $whereOnlineInd, 5, 6);

    // 8. How much was given in total through recurring (monthly, quarterly, etc) donations in 2016?
    // 9. How many individuals made recurring donations in 2016?
    $questions[7] = self::tsLocal('How much was given in total through recurring (monthly, quarterly, etc) donations?');
    $questions[8] = self::tsLocal('How many individuals made recurring donations?');
    $this->runItemQuery($selectContacts . $from . $whereRec, 7, 8);

    // 10. How much did you raise from individuals giving $1,000 or more (in total) in 2016?
    $questions[9] = self::tsLocal('How much did you raise from individuals giving $1,000 or more?');
    $groupBy1K = " GROUP BY contribution.contact_id
                   HAVING total_amount_sum >= 1000";
    $query1K = "SELECT SUM(total_amount_sum) as total_amount_sum, COUNT(DISTINCT contact_id) as total_contacts
                FROM ($select, contribution.contact_id as contact_id $from $whereInd $groupBy1K) bigdonors";
    $this->runItemQuery($query1K, 9);

    // 12. Check if memberships are enabled and active
    $questions[10] = self::tsLocal('Does your organization offer memberships?');
    $components = CRM_Core_Component::getEnabledComponents();
    if (empty($components['CiviMember'])) {
      $this->answers[10] = self::tsLocal('CiviMember is disabled');
    }
    else {
      try {
        $memberships = civicrm_api3('Membership', 'getoptions', array(
          'field' => "membership_type_id",
          'context' => "search",
        ));
        if (empty($memberships['values'])) {
          $this->answers[10] = self::tsLocal('No memberships');
        }
        else {
          $this->answers[10] = self::tsLocal('Yes:') . ' <ul><li>' . implode('</li><li>', $memberships['values']) . '</li></ul>';
        }
      }
      catch (CiviCRM_API3_Exception $e) {
        $this->answers[10] = 'API Error: ' . $e->getMessage();
      }
    }

    $this->assign('questions', $questions);
    $this->assign('answers', $this->answers);

    // CRM_Report_Form_Instance::postProcess($this, FALSE);
  }

  public function runItemQuery($sql, $tplField, $tplFieldContact = NULL) {
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->answers[$tplField] = CRM_Utils_Money::format($dao->total_amount_sum);
      if ($tplFieldContact) {
        $this->answers[$tplFieldContact] = $dao->total_contacts;
      }
    }
  }

  public static function tsLocal($string, $variables = array()) {
    $variables['domain'] = 'com.aghstrategies.idbsurvey';
    return ts($string, $variables);
  }

}
