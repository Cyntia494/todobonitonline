{extends file='page.tpl'}

{block name='page_content'}
  <div class="section-login password">
    <div class="password-content">
        <div class="forgot-password">
            <h2>
              {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
            </h2>
            <form action="{$urls.pages.password}" class="forgotten-password" method="post">
                <ul class="ps-alert-error">
                    {foreach $errors as $error}
                        <li class="item">
                             <i>
                              <svg viewBox="0 0 24 24">
                                <path fill="#fff" d="M11,15H13V17H11V15M11,7H13V13H11V7M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20Z"></path>
                              </svg>
                            </i>
                            <p>{$error}</p>
                        </li>
                    {/foreach}
                </ul>
                <header>
                    <p class="send-renew-password-link">{l s='Please enter the email address you used to register. You will receive a temporary link to reset your password.' d='Shop.Theme.Customeraccount'}</p>
                </header>
                <section class="form-fields">
                    <div class="form-group center-email-fields">
                        <label class="col-md-3 form-control-label required">{l s='Email address' d='Shop.Forms.Labels'}<em>*</em></label>
                        <div class="col-md-5 email">
                            <input type="email" name="email" id="email" value="{if isset($smarty.post.email)}{$smarty.post.email|stripslashes}{/if}" class="form-control" required>
                        </div>
                        <button class="form-control-submit btn btn-primary" name="submit" type="submit">
                            {l s='Reset' d='Shop.Theme.Actions'}
                        </button>
                    </div>
                </section>
            </form>
        </div>
    </div>
  </div>
{/block}

