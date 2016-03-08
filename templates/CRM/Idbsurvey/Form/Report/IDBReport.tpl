{if $section eq 2}
  <div class="crm-block crm-content-block crm-report-layoutTable-form-block">
    {*include the table layout*}
    {include file="CRM/Report/Form/Layout/Table.tpl"}
  </div>
{else}

  {if $criteriaForm OR $instanceForm OR $instanceFormError}
    <div class="crm-block crm-form-block crm-report-field-form-block">
      {include file="CRM/Report/Form/Fields.tpl"}
    </div>
  {/if}
  <div class="crm-block crm-content-block crm-report-form-block">
    <div class="help">
      <p>{ts domain='com.aghstrategies.idbsurvey'}Use this report to calculate information for the <a href="http://www.thirdspacestudio.com/idbproject/" target="_blank">Individual Donor Benchmark Survey</a> from <a href="http://www.thirdspacestudio.com/" target="_blank">Third Space Studio</a>.  The survey is designed for US-based nonprofit organizations with revenues of $2 million or less to compare their fundraising against similar organizations.{/ts}</p>
      <p>{ts domain='com.aghstrategies.idbsurvey'}While there are some subjective questions on the survey and some questions about expenses and staffing&mdash;information that isn&rsquo;t typically stored in CiviCRM&mdash;<a href="https://aghstrategies.com/" target="_blank">AGH Strategies</a> developed this report to quickly calculate the answers to the questions about fundraising results.  The only thing we can&rsquo;t automatically know is what financial types you consider to be &ldquo;donations&rdquo; as opposed to service fees or other revenue.  In the form above, select the financial types you want to count, and click &ldquo;Display Answers for the Survey&rdquo;.{/ts}</p>
      <p>{ts domain='com.aghstrategies.idbsurvey'}The survey form is at <a href="http://www.thirdspacestudio.com/idbsurvey/" target="_blank">http://www.thirdspacestudio.com/idbsurvey/</a>, and the question numbers here correspond to those on the &ldquo;Your Results&rdquo; section.{/ts}</p>
    </div>
    {if $questions}
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
    {/if}
  </div>
  {literal}
  <script type="text/javascript">
    (function ($, ts){
      $('#financial_type_id_op option[value="mhas"]').html(ts('Include'));
      $('#financial_type_id_op option[value="mnot"]').html(ts('Exclude'));
    }(CRM.$, CRM.ts('com.aghstrategies.idbsurvey')));
  </script>
  {/literal}
{/if}
