<div class="menu">
  <h2>
    {l s='Menu' d='Shop.Theme.Customeraccount'}
    <div class="mobile">
      <i class="material-icons less">expand_less</i>
      <i class="material-icons more">expand_more</i>
    </div>
  </h2>
  <div class="links">
    <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'identity'} current{/if}" id="  identity-link" href="{$urls.pages.identity}">
      <span class="link-item">
        {l s='Information' d='Shop.Theme.Customeraccount'}
      </span>
    </a>

      <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'addresses'} current{/if}" id="addresses-link" href="{$urls.pages.addresses}">
        <span class="link-item">
          {l s='Addresses' d='Shop.Theme.Customeraccount'}
        </span>
      </a>

    {if !$configuration.is_catalog}
      <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'history'} current{/if}" id="history-link" href="{$urls.pages.history}">
        <span class="link-item">
          {l s='My orders' d='Shop.Theme.Customeraccount'}
        </span>
      </a>
    {/if}

    {if !$configuration.is_catalog}
      <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'order-slip'} current{/if}" id="order-slips-link" href="{$urls.pages.order_slip}">
        <span class="link-item">
          {l s='Credit slips' d='Shop.Theme.Customeraccount'}
        </span>
      </a>
    {/if}

    {if $configuration.voucher_enabled && !$configuration.is_catalog}
      <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'discount'} current{/if}" id="discounts-link" href="{$urls.pages.discount}">
        <span class="link-item">
          {l s='Vouchers' d='Shop.Theme.Customeraccount'}
        </span>
      </a>
    {/if}

    {if $configuration.return_enabled && !$configuration.is_catalog}
      <a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 {if $page.page_name == 'order-follow'} current{/if}" id="returns-link" href="{$urls.pages.order_follow}">
        <span class="link-item">
          {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
        </span>
      </a>
    {/if}
    {block name='display_customer_account'}
      {hook h='displayCustomerAccount'}
    {/block}
  </div>
</div>
