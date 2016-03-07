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
    // Basic queries to be built upon
    $select = "SELECT COUNT(contribution.total_amount) as total_amount_count,
                SUM(contribution.total_amount) as total_amount_sum,
                contribution.currency as currency";
    $from = " FROM civicrm_contact cc
                INNER JOIN civicrm_contribution contribution ON cc.id = contribution.contact_id AND contribution.is_test = 0
                LEFT JOIN civicrm_financial_type ft ON contribution.financial_type_id = ft.id";
    $where = " WHERE ( contribution.receive_date >= 20140101000000 )
                 AND ( contribution.receive_date < 20150101000000 )
                 AND ( contribution.contribution_status_id IN (
                      SELECT value FROM civicrm_option_value WHERE name = 'Completed' AND option_group_id = (
                         SELECT id FROM civicrm_option_group WHERE name = 'contribution_status' ) ) )
                 AND cc.is_deleted = 0";

    //1. Total income/revenue for 2014
    $sql = $select . $from . $where;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer1", CRM_Utils_Money::format($dao->total_amount_sum));
    }
    //3. Total raised by individuals  for 2014

    // for the purposes of this, households are individuals
    $where1 = $where . " AND ( cc.contact_type in ('Individual', 'Household') ) ";
    $sql = $select . $from . $where1;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer3", CRM_Utils_Money::format($dao->total_amount_sum));
    }

    //4. Total donations by individuals
    $types = "";
    if (array_key_exists('financial_type_id_value', $this->_submitValues)) {
      $types = implode(", ", $this->_submitValues['financial_type_id_value']);
    }
    if ($types == "") {
      $types_sentence = "IS NOT NULL";
    }
    else {
      $types_sentence = "IN ({$types})";
    }
    $select1 = $select . ", COUNT(DISTINCT contribution.contact_id) as total_contacts  ";
    $where2 = $where1 . " AND contribution.financial_type_id {$types_sentence}";
    $sql = $select1 . $from . $where2;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      $this->assign("answer4", $dao->total_contacts);
    }

    //5. Total raised online from individuals
    //6. Number of people that gave online

    //Where contact_type = Inidivudal and contribution came through a contribution page
    $where3 = $where1 . " AND contribution.contribution_page_id IS NOT NULL  ";
    $sql = $select1 . $from . $where2;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer5", CRM_Utils_Money::format($dao->total_amount_sum));

      // Where contact_type = Individual  Count(unique contact ids)
      // This may count someone who gives as an individual and then as a household
      // twice, but there may well be others in the household not counted otherwise
      // so I think it'll all wash out.
      $this->assign("answer6", $dao->total_contacts);
    }

    //7. How much was given in total through recurring (monthly, quarterly, etc) donations in 2014?
    //8. How many people made recurring donations in 2014?

    $whereRec = $where . " AND contribution.contribution_recur_id IS NOT NULL";
    $sql = $select1 . $from . $whereRec;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer7", CRM_Utils_Money::format($dao->total_amount_sum));
      $this->assign("answer8", $dao->total_contacts);
    }

    // 9. How much did you raise from people giving $1,000 or more (in total) in 2014?
    // 10. How many people made gifts of $1,000 or more (in total) in 2014?

    // TODO: this counts individual gifts of $1,000 or more.  I had to clarify with Heather
    // and found that she meant donors giving > $1,000 over the course of the year.
    // I think you need to do a subquery grouped by donors and get the sum & count from there.
    $where4 = $where . " AND contribution.total_amount > 1000";
    $sql = $select1 . $from . $where4;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer9", CRM_Utils_Money::format($dao->total_amount_sum));
      $this->assign("answer10", $dao->total_contacts);
    }

    //11. Check if memberships are enabled and active

    $memberships = CRM_Member_PseudoConstant::membershipType();
    if ($memberships) {
      $answer11 = "<ul>";
      foreach ($memberships as $membership) {
        $answer11 .= "<li>{$membership}</li>";
      }
      $answer11 .= "</ul>";

    }
    else {
      $answer11 = "No Memberships";
    }
    $this->assign("answer11", $answer11);

    //13. What was your organization's total income/revenue in 2013?

    $where2013 = " WHERE ( contribution.receive_date >= 20130101000000 )
                     AND ( contribution.receive_date < 20140101000000 )
                     AND ( contribution.contribution_status_id IN (1) )
                     AND cc.is_deleted = 0";
    $sql = $select . $from . $where2013;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer13", CRM_Utils_Money::format($dao->total_amount_sum));
    }

    //14.  What was your organization's total income from individual donors in 2013?

    $where2013_1 = $where2013 . "  AND ( cc.contact_type in ('Individual', 'Household') ) ";
    $sql = $select . $from . $where2013_1;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer14", CRM_Utils_Money::format($dao->total_amount_sum));
    }

    //15.  What was your organization's total income from online donations in 2013?

    $where2013_2 = $where2013_1 . " AND contribution.contribution_page_id IS NOT NULL";
    $sql = $select . $from . $where2013_2;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      if ($dao->total_amount_sum == "") {
        $dao->total_amount_sum = 0;
      }
      $this->assign("answer15", CRM_Utils_Money::format($dao->total_amount_sum));
    }
    // CRM_Report_Form_Instance::postProcess($this, FALSE);
  }

}
