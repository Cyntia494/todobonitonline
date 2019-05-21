{extends file='customer/page.tpl'}

{block name='page_content'}
	<div class="my-account-content">
		{include file='customer/_partials/menu.tpl'}
		<div class="content">
			<h2>
			  {if $editing}
			    {l s='Update your address' d='Shop.Theme.Customeraccount'}
			  {else}
			    {l s='New address' d='Shop.Theme.Customeraccount'}
			  {/if}
			</h2>
			<div class="account-content">
				<div class="address-form">
					{render template="customer/_partials/address-form.tpl" ui=$address_form}
				</div>
			</div>
		</div>
	</div>
{/block}
