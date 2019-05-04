{extends file='customer/page.tpl'}

{block name='page_content'}
  <div class="my-account-content">
      {include file='customer/_partials/menu.tpl'}
      <div class="content">
        <h2>
          {l s='Credit slips' d='Shop.Theme.Customeraccount'}
        </h2>
        <div class="account-content">

          {if $credit_slips}
           
            {foreach from=$credit_slips item=slip}
              <div class="table-slip">    
                <div class="container-slip">  

                  <h4>{l s='Order' d='Shop.Theme.Customeraccount'}</h4>
                  <p><a href="{$slip.order_url_details}" data-link-action="view-order-details">{$slip.order_reference}</a></p>

                  <h4>{l s='Credit slip' d='Shop.Theme.Customeraccount'}</h4>
                  <p>{$slip.credit_slip_number}</p>

                  <h4>{l s='Date issued' d='Shop.Theme.Customeraccount'}</h4>
                  <p>{$slip.credit_slip_date}</p>

                  <h4>{l s='View credit slip' d='Shop.Theme.Customeraccount'}</h4>
                  <p><a href="{$slip.url}"><i class="fal fa-file-pdf"></i></a></p>

                </div>
              </div>
            {/foreach}

            {else}
            <div class="no-orders">
              <div class="icon"></div>
              <p>{l s='You do not have credits yet' d='Shop.Theme.Customer'}</p>
            </div>
          {/if}
        </div>
      </div>
  </div>
{/block}
