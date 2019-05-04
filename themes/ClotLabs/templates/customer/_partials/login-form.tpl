{block name='login_form'}

  {block name='login_form_errors'}
    {include file='_partials/form-errors.tpl' errors=$errors['']}
  {/block}

  <form id="login-form" action="{block name='login_form_actionurl'}{$action}{/block}" method="post">

    <section>
      {block name='login_form_fields'}
        {foreach from=$formFields item="field"}
          {block name='form_field'}
            {form_field field=$field}
          {/block}
        {/foreach}
      {/block}
    </section>

    {block name='login_form_footer'}
      <footer class="form-footer text-xs-center clearfix">
        <div class="forgot-password">
          <a href="{$urls.pages.password}" rel="nofollow">
            {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
          </a>
        </div>
        <input type="hidden" name="submitLogin" value="1">
        {block name='form_buttons'}
          <button class="button login-button" data-link-action="sign-in" type="submit" class="form-control-submit">
            {l s='Sign in' d='Shop.Theme.Actions'}
          </button>
        {/block}
      </footer>
    {/block}

  </form>
{/block}
