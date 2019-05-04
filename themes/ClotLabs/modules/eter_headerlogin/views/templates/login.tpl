<div class="headerlogin {if $customer.is_logged}logged{/if} hidden-sm-down ">
    <div class="login">
        <i class="fas fa-user login-icon"></i>
    </div>
    <div class="content {if !$customer.is_logged }not-loged{/if}">
        {if $customer.is_logged }
            <div class="title">
                {l s='My account' mod='eter_minicart'}
            </div>
            <div class="header-account-links">

                <a class="account-link" id="identity-link" href="{$urls.pages.identity}">
                    <span class="link-item">
                        {l s='Information' d='Shop.Theme.Customeraccount'}
                    </span>
                </a>

                <a class="account-link" id="addresses-link" href="{$urls.pages.addresses}">
                    <span class="link-item">
                        {l s='Addresses' d='Shop.Theme.Customeraccount'}
                    </span>
                </a>

                {if !$configuration.is_catalog}
                    <a class="account-link" id="history-link" href="{$urls.pages.history}">
                        <span class="link-item">
                            {l s='My orders' d='Shop.Theme.Customeraccount'}
                        </span>
                    </a>
                {/if}

                {if !$configuration.is_catalog}
                    <a class="account-link" id="order-slips-link" href="{$urls.pages.order_slip}">
                        <span class="link-item">
                            {l s='Credit slips' d='Shop.Theme.Customeraccount'}
                        </span>
                    </a>
                {/if}

                {if $configuration.voucher_enabled && !$configuration.is_catalog}
                    <a class="account-link" id="discounts-link" href="{$urls.pages.discount}">
                        <span class="link-item">
                            {l s='Vouchers' d='Shop.Theme.Customeraccount'}
                        </span>
                    </a>
                {/if}

                {if $configuration.return_enabled && !$configuration.is_catalog}
                    <a class="account-link" id="returns-link" href="{$urls.pages.order_follow}">
                        <span class="link-item">
                            {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
                        </span>
                    </a>
                {/if}
                {block name='display_customer_account'}
                    {hook h='displayCustomerAccount'}
                {/block}
                <a class="account-link" id="logout" href="{$urls.actions.logout}">
                    <span class="link-item">
                        {l s='Log Out' d='Shop.Theme.Customeraccount'}
                    </span>
                </a>
            </div>
        {else}
            <h4 class="title">{l s='Sign in' d='Shop.Theme.Actions'}</h4>
            <form id="login-form" action="{$urls.pages.authentication}" method="post">
                <section>
                    <div class="form-group form-section  email ">
                        <label class="form-label form-control-label required">
                            {l s='Email' d='Shop.Theme.Actions'}<em>*</em>
                        </label>
                        <div class="form-input ">
                            <input class="form-control" name="email" type="email" placeholder="{l s='Email' d='Shop.Theme.Actions'}"  value="" required="">
                        </div>
                    </div>
                    <div class="form-group form-section  password ">
                        <label class="form-label form-control-label required">
                            {l s='Password' d='Shop.Theme.Actions'}<em>*</em>
                        </label>
                        <div class="form-input">
                            <div class="input-group js-parent-focus">
                                <input class="form-control js-child-focus js-visible-password" 
                                    name="password" 
                                    type="text" 
                                    value="" 
                                    placeholder="{l s='Password' d='Shop.Theme.Actions'}" 
                                    style="text-security: disc;-webkit-text-security: disc;" 
                                    pattern=".{literal}{{/literal}5,{literal}}{/literal}"
                                    required="">
                            </div>
                        </div>
                    </div>
                    <div class="forgot-password">
                        <a href="{$urls.pages.password}" rel="nofollow">
                            {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
                        </a>
                    </div>
                    <div class="button-login">
                        {block name='login_form_footer'}
                            <footer class="form-footer clearfix">
                                <input type="hidden" name="submitLogin" value="1">
                                {block name='form_buttons'}
                                    <button class="btn btn-primary" data-link-action="sign-in" type="submit" class="form-control-submit">
                                        {l s='Sign in' d='Shop.Theme.Actions'}
                                    </button>
                                {/block}
                                <a href="{$urls.pages.register}" class="button" data-link-action="display-register-form">
                                    <div class="button-register">
                                        {l s='Register' d='Shop.Theme.Customeraccount'}
                                    </div>
                                </a>
                            </footer>
                        {/block}
                    </div>
                </section>

            </form>

        {/if}
    </div>
</div>
