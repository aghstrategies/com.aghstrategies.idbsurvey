
{include file="CRM/Report/Form.tpl"}
<table class="report-layout display">
  <thead>
    <tr>
      <th width="80%">{ts domain='com.aghstrategies.idbsurvey'}Question{/ts}</th>
      <th>{ts domain='com.aghstrategies.idbsurvey'}Answer{/ts}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$questions item=question key=questionNumber}
      <tr class="{cycle values="odd,even"}-row crm-report">
        <td>{$questionNumber}. {$question}</td>
        <td>{$answers.$questionNumber}</td>
      </tr>
    {/foreach}
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
