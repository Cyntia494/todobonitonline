{if $customer.is_logged }
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

    <form id="login-form" action="{$urls.pages.authentication}" method="post">

        
            <div class="button-login">
                {block name='login_form_footer'}
                    <footer class="form-footer clearfix">
                        <input type="hidden" name="submitLogin" value="1">
                        {block name='form_buttons'}
                            <button class="btn btn-primary" data-link-action="sign-in" type="submit" class="form-control-submit">
                                {l s='Sign in' d='Shop.Theme.Actions'}
                            </button>
                        {/block}
                    </footer>
                {/block}
            </div>

    </form>

{/if}