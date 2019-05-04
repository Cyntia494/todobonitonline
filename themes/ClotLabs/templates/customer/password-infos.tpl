{extends file='page.tpl'}

{block name='page_content'}
  <div class="section-login password">
    <div class="password-content">
        <div class="forgot-password">
            <h2>
              {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
            </h2>
            <ul class="ps-alert">
              {foreach $successes as $success}
                <li class="item">
                  <p>{$success}</p>
                </li>
              {/foreach}
            </ul>
            <ul>
              <li><a href="{$urls.pages.authentication}">{l s='Back to Login' d='Shop.Theme.Actions'}</a></li>
            </ul>
        </div>
    </div>
  </div>
{/block}
