{extends 'customer/page.tpl'}

{block name='page_content'}
	<div class="my-account-content">
	    {include file='customer/_partials/menu.tpl'}
	    <div class="content">
		    <h2>{l s='Your personal information' d='Shop.Theme.Customeraccount'}</h2>
		    <div class="account-content">
	  			{render file='customer/_partials/customer-form.tpl' ui=$customer_form}
		    </div>
	    </div>
	</div>
{/block}
