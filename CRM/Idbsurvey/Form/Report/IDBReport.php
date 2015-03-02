<?php

class CRM_Idbsurvey_Form_Report_IDBReport extends CRM_Report_Form {

  function __construct() {
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', ts('IDB Report'));
    parent::preProcess();
  }


  function postProcess() {
    //1. Total income/revenue for 2014
    $select  = "SELECT COUNT(contribution.total_amount ) as total_amount_count,
                            SUM(contribution.total_amount ) as total_amount_sum,
                            contribution.currency as currency";
    $from  = " FROM civicrm_contact cc
                            INNER JOIN civicrm_contribution contribution ON cc.id = contribution.contact_id AND contribution.is_test = 0
                            LEFT JOIN civicrm_financial_type ft ON contribution.financial_type_id =ft.id";
    $where = "  WHERE ( contribution.receive_date >= 20140101000000 )
                            AND ( contribution.receive_date < 20150101000000 )
                            AND ( contribution.contribution_status_id IN (1) )
                            AND cc.is_deleted = 0";
   $sql = $select.$from.$where;
   $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
         $this->assign("answer1", $dao->total_amount_sum);
    }
    //3. Total raised by individuals  for 2014
   $where1 = $where."  AND cc.contact_type = 'Individual' ";
    $sql = $select.$from.$where1;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if  ($dao->fetch()) {
        $this->assign("answer3", $dao->total_amount_sum);
    }
    //4. Total donations by individuals
    $select1 = $select.", COUNT(DISTINCT contribution.contact_id) as total_contacts  ";
    $where2 = $where1." AND contribution.financial_type_id = (SELECT id FROM civicrm_financial_type WHERE name = 'Donation')";
    $sql = $select1.$from.$where2;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
       $this->assign("answer4", $dao->total_contacts);
    }
    //5. Total raised online from individuals
    //Where contact_type = Inidivudal and contribution_source does not include Offline
    $where3 = $where2 ." AND contribution.source NOT LIKE '%Offline%'  ";
    $sql = $select1.$from.$where3;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      $this->assign("answer5", $dao->total_amount_sum);
      //6. Number of people that gave online
      //Where contact_type = Individual  Count(unique contact ids)
      $this->assign("answer6", $dao->total_contacts);
    }


    //7. How much was given in total through recurring (monthly, quarterly, etc) donations in 2014?
    //8.  How many people made recurring donations in 2014?
    $whereRec = $where." AND contribution.contribution_recur_id IS NOT NULL";
    $sql = $select1.$from.$whereRec;
    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      $this->assign("answer7", $dao->total_amount_sum);
      $this->assign("answer8", $dao->total_contacts);
    }
    // 9. How much did you raise from people giving $1,000 or more (in total) in 2014?
    // 10. How many people made gifts of $1,000 or more (in total) in 2014?
    $where4 = $where. " AND contribution.total_amount > 1000";
    $sql = $select1.$from.$where4;
    $dao = CRM_Core_DAO::executeQuery($sql);
     if ($dao->fetch()) {
      $this->assign("answer9", $dao->total_amount_sum);
      $this->assign("answer10", $dao->total_contacts);
    }

    //11. Check if memberships are enabled and active
    $memberships = CRM_Member_PseudoConstant::membershipType();
    if ($memberships  ){
      $answer11 = "<ul>";
      foreach($memberships as $membership){
        $answer11 .= "<li>{$membership}</li>";
      }
      $answer11 .= "</ul>";

    } else {
      $answer11 = "No Memberships";
   }
   $this->assign("answer11", $answer11);
   //13. What was your organization's total income/revenue in 2013?
   $where2013 = " WHERE ( contribution.receive_date >= 20130101000000 )
     AND ( contribution.receive_date < 20140101000000 )
     AND ( contribution.contribution_status_id IN (1) )
     AND cc.is_deleted = 0";
     $sql = $select.$from.$where2013;
     $dao = CRM_Core_DAO::executeQuery($sql);
     if ($dao->fetch()) {
       $this->assign("answer13", $dao->total_amount_sum);
     }
     //14.  What was your organization's total income from individual donors in 2013?
     $where2013_1 = $where2013."  AND cc.contact_type = 'Individual' AND contribution.financial_type_id = (SELECT id FROM civicrm_financial_type WHERE name = 'Donation') ";
     $sql = $select.$from.$where2013_1;
     $dao = CRM_Core_DAO::executeQuery($sql);
     if  ($dao->fetch()) {
       $this->assign("answer14", $dao->total_amount_sum);
     }
     //15.  What was your organization's total income from online donations in 2013?
     $where2013_2 = $where2013_1." AND contribution.source NOT LIKE '%Offline%'";
     $sql = $select.$from.$where2013_2;
     $dao = CRM_Core_DAO::executeQuery($sql);
     if  ($dao->fetch()) {
       $this->assign("answer15", $dao->total_amount_sum);
     }
  }

}
