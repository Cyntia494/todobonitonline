{if $tracking}
	{if count($tracking.checkpoints) > 1}
		<div class="box">
			<h3>{l s='Follow your shipment' d='Shop.Theme.Checkout'}</h3>
			<p class="date title">
				<strong>{l s='Carrier' d='Shop.Theme.Checkout'}:</strong>
				{$carrier.name}
			</p>
			<p class="tracking title">
				<strong>{l s='Tracking number' d='Shop.Theme.Checkout'}:</strong>
				{$carrier.tracking_number}
			</p>
			<table class="table table-striped table-bordered table-labeled ">
				<tr>
					<th>{l s='Date' d='Shop.Theme'}</th>
					<th>{l s='City' d='Shop.Theme'}</th>
					<th>{l s='Description' d='Shop.Theme'}</th>
					<th>{l s='Comments' d='Shop.Theme'}</th>
				</tr>
				
				{foreach from=$tracking.checkpoints item=checkpoint}
					<tr>
						<td>{$checkpoint.date}</td>
						<td>{$checkpoint.city}</td>
						<td>{$checkpoint.description}</td>
						<td>{$checkpoint.comments}</td>
					</tr>
				{/foreach}
			</table>
		</div>
	{/if}
{/if}