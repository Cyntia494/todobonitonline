<div class="tab-pane active" id="enviaya">
	<h4 class="visible-print">Envia ya<span class="badge"></span></h4>
	<div class="list-empty hidden-print">
		{if isset($tracking)}
			<div class="col-md-6 col-xs-12 col-sm-6">
				<p><strong>{l s='Envia Ya #:' mod='eter_enviaya'}</strong>{$tracking.enviaya_shipment_number}</p>
				<p><strong>{l s='Pickup date:' mod='eter_enviaya'}</strong>{$tracking.pickup_date}</p>
				<p><strong>{l s='Download Label:' mod='eter_enviaya'}</strong> 
					<a href="{$carrier.label}" target="_blank">{l s='download' mod='eter_enviaya'}</a>
				</p>
			</div>
			<div class="col-md-6 col-xs-12 col-sm-6">
				<p><strong>{l s='Tracking #:' mod='eter_enviaya'}</strong>{$tracking.carrier_tracking_number}</p>
				<p><strong>{l s='Shipment status:' mod='eter_enviaya'}</strong>{$tracking.shipment_status}</p>
			</div>
		{/if}
		<div class="table-responsive">
			<table class="table" id="enviaya_table">
				<thead>
					<tr>
						<th>
							<span class="title_box">{l s='Date' d='Shop.Theme'}</span>
						</th>
						<th>
							<span class="title_box">{l s='City' d='Shop.Theme'}</span>
						</th>
						<th>
							<span class="title_box">{l s='Description' d='Shop.Theme'}</span>
						</th>
						<th>
							<span class="title_box">{l s='Comments' d='Shop.Theme'}</span>
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{if isset($tracking) && count($tracking.checkpoints) > 1}
						{foreach from=$tracking.checkpoints item=checkpoint}
							<tr>
								<td>{$checkpoint.date}</td>
								<td>{$checkpoint.city}</td>
								<td>{$checkpoint.description}</td>
								<td>{$checkpoint.comments}</td>
							</tr>
						{/foreach}
					{else}
						<tr>
							<td colspan="5" class="list-empty">
								<div class="list-empty-msg">
									<i class="icon-warning-sign list-empty-icon"></i>
									{l s='There is not available tracking' mod='eter_enviaya'}
								</div>
								{if $abletoship}
								<a href="{$requesturl}" class="request-enviaya-shipment btn btn-default"
									data-id-order="{$orderid}">
									<i class="icon-pencil"></i>
									{l s='Create EnviaYa Shipment' mod='eter_enviaya'}
								</a>
								{/if}
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>
</div>
