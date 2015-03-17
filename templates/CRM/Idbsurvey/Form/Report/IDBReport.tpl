
{include file="CRM/Report/Form.tpl"}
<table class="report-layout display">
  <thead>
    <tr>
      <th width="80%">Question</th>
      <th>Answer</th>
    </tr>
  </thead>
  <tbody>
    <tr class="even-row  crm-report">
      <td>1. What was your organization's total income/revenue in 2014?</td>
      <td>{$answer1}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>3. What was the total amount raised from individuals in 2014? <div >Please include online and offiline donations from direct mail, email, major donors, and other individual donor strategies.</div> </td>
      <td>{$answer3}</td>
    </tr>
    <tr class="odd-row  crm-report">
      <td>4. How many individuals donated in 2014?</td>
      <td>{$answer4}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>5. How much did you raise from individuals online?</td>
      <td>{$answer5}</td>
    </tr>
    <tr class="odd-row  crm-report">
      <td>6. How many people gave online in 2014?</td>
      <td>{$answer6}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>7. How much from recurring donations in 2014?</td>
      <td>{$answer7}</td>
    </tr>
    <tr class="odd-row  crm-report">
      <td>8. How many people from recurring donations in 2014?</td>
      <td>{$answer8}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>9. How much did you raise from people giving $1,000 or more (in total) in 2014?</td>
      <td>{$answer9}</td>
    </tr>
    <tr class="odd-row  crm-report">
      <td>10. How many people made gifts of $1,000 or more (in total) in 2014?</td>
      <td>{$answer10}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>11. Does your organization offer memberships?</td>
      <td>{$answer11}</td>
    </tr>
    <tr class="odd-row  crm-report">
      <td>13. What was your organization's total income/revenue in 2013?</td>
      <td>{$answer13}</td>
    </tr>
    <tr class="even-row  crm-report">
      <td>14. What was your organization's total income from individual donors in 2013?</td>
      <td>{$answer14}</td>
    <tr class="odd-row  crm-report">
      <td>15. What was your organization's total income from online donations in 2013?</td>
      <td>{$answer15}</td>
    </tr>
  </tbody>
</table>
{literal}
<script type="text/javascript">
  cj(".crm-report_setting-accordion").hide();
  cj(".messages").hide();
  cj("#_qf_IDBReport_submit").val("Display Answers to the Survey");
</script>
{/literal}
