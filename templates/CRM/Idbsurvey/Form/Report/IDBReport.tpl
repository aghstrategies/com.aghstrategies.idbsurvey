{if $section eq 2}
  <div class="crm-block crm-content-block crm-report-layoutTable-form-block">
    {*include the table layout*}
    {include file="CRM/Report/Form/Layout/Table.tpl"}
  </div>
{else}
  <div class="crm-block">
    <h3><i class="crm-i fa-line-chart" role="img" aria-hidden="true"></i> {ts domain='com.aghstrategies.idbsurvey'}See how your fundraising compares{/ts}</h3>
    <p>{ts domain='com.aghstrategies.idbsurvey' 1='https://www.thirdspacestudio.com/idbproject/' 2='https://www.thirdspacestudio.com/'}Use this report to calculate information for the <a href="%1" target="_blank">Individual Donor Benchmark Survey</a> from <a href="%2" target="_blank">Third Space Studio</a>. The survey is designed for US-based nonprofit organizations with revenues of $2 million or less to compare their fundraising against similar organizations.{/ts}</p>
    <p>{ts domain='com.aghstrategies.idbsurvey' 1='https://aghstrategies.com/'}While there are some subjective questions on the survey and some questions about expenses and staffing&mdash;information that isn&rsquo;t typically stored in CiviCRM&mdash;<a href="%1" target="_blank">AGH Strategies</a> developed this report to quickly calculate the answers to the questions about fundraising results.{/ts}</p>
    <h3><i class="crm-i fa-wrench" role="img" aria-hidden="true"></i> {ts domain='com.aghstrategies.idbsurvey'}Configure this report{/ts}</h3>
    <p>{ts domain='com.aghstrategies.idbsurvey'}The only thing the system can&rsquo;t automatically know is what financial types you consider to be &ldquo;donations&rdquo; as opposed to service fees or other revenue.  In the form above, <strong>select the financial types you want to count for the survey, and click &ldquo;Display Answers for the Survey&rdquo;</strong>.{/ts}</p>
    <p>{ts domain='com.aghstrategies.idbsurvey' 1=https://www.thirdspacestudio.com/countmein}The survey form is at <a href="%1" target="_blank">%1</a>.{/ts}</p>
  </div>

  {if $criteriaForm OR $instanceForm OR $instanceFormError}
    <div class="crm-block crm-form-block crm-report-field-form-block">
      {include file="CRM/Report/Form/Fields.tpl"}
    </div>
  {/if}
  <div class="crm-block crm-content-block crm-report-form-block">
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
            <td>{$question}</td>
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
