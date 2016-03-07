
{include file="CRM/Report/Form.tpl"}
<table class="report-layout display">
  <thead>
    <tr>
      <th width="80%">{ts domain='com.aghstrategies.idbsurvey'}Question{/ts}</th>
      <th>{ts domain='com.aghstrategies.idbsurvey'}Answer{/ts}</th>
    </tr>
  </thead>
  <tbody>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}1. What was your organization&rsquo;s total income/revenue in 2015?{/ts}</td>
      <td>{$answer1}</td>
    </tr>
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}3. What was the total amount raised from individuals in 2015?{/ts}
      <em>{ts domain='com.aghstrategies.idbsurvey'}Please include online and offiline donations from direct mail, email, major donors, and other individual donor strategies.{/ts}</em></td>
      <td>{$answer3}</td>
    </tr>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}4. How many individuals donated in 2015?{/ts}</td>
      <td>{$answer4}</td>
    </tr>
    {* TODO: Add Question 5 *}
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}6. How much did you raise online from individuals in 2015?{/ts}
      <em>{ts domain='com.aghstrategies.idbsurvey'}Please do not include recurring (monthly, quarterly, etc. in this total).{/ts}</em></td>
      <td>{$answer6}</td>
    </tr>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}7. How many people gave online in 2015?{/ts}</td>
      <td>{$answer7}</td>
    </tr>
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}8. How much was given in total through recurring (monthly, quarterly, etc) donations in 2015?{/ts}</td>
      <td>{$answer8}</td>
    </tr>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}9. How many individuals made recurring donations in 2015?{/ts}</td>
      <td>{$answer9}</td>
    </tr>
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}10. How much did you raise from individuals giving $1,000 or more (in total) in 2015?{/ts}</td>
      <td>{$answer10}</td>
    </tr>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}11. How many individuals made gifts of $1,000 or more (in total) in 2015?{/ts}</td>
      <td>{$answer11}</td>
    </tr>
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}12. Does your organization offer memberships?{/ts}</td>
      <td>{$answer12}</td>
    </tr>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}14. What was your organization&rsquo;s total income/revenue in 2014?{/ts}</td>
      <td>{$answer14}</td>
    </tr>
    <tr class="even-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}15. What was your organization&rsquo;s total income from individual donors in 2014?{/ts}</td>
      <td>{$answer15}</td>
    <tr class="odd-row crm-report">
      <td>{ts domain='com.aghstrategies.idbsurvey'}16. What was your organization&rsquo;s total income from online donations in 2014?{/ts}</td>
      <td>{$answer16}</td>
    </tr>
  </tbody>
</table>
{literal}
<script type="text/javascript">
  (function ($, ts){
    $(".crm-report_setting-accordion").hide();
    $(".messages").hide();
    $("#_qf_IDBReport_submit").val(ts("Display Answers to the Survey"));
  }(CRM.$, CRM.ts('com.aghstrategies.idbsurvey')));
</script>
{/literal}
