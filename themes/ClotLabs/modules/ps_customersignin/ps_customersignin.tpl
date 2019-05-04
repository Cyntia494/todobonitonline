<div id="_desktop_user_info">
  <div class="user-info">
    {if $logged}
      <i class="material-icons logged">&#xE7FF;</i>
      <div class="my-account-div">
        <a class="account" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
          <span class="hidden-sm-down">{l s='My account' d='Shop.Theme.Actions'}</span>
        </a>
        <a class="logout hidden-sm-down" href="{$logout_url}" rel="nofollow">
          {l s='Sign out' d='Shop.Theme.Actions'}
          <i class="material-icons hidden-md-up logged">&#xE7FF;</i>
        </a>
      </div>
    {else}
      <a href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
        <i class="material-icons">&#xE7FF;</i>
      </a>
    {/if}
  </div>
</div>
