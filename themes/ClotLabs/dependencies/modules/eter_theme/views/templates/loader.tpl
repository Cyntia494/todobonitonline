
<div class="form-horizontal">
	<input type="hidden" name="installSamples" value="1">
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			{l s='Install demo' mod='eter_theme'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-sm-3">
					{l s='Select package' mod='eter_theme'}
				</label>
				<div class="col-sm-9">
					<select name="package" class=" fixed-width-xl" id="package">
						{foreach from=$themes item=theme}
							<option value="{$theme.id_option}" {if $theme.id_option == $currenttheme}selected="selected"{/if}>{$theme.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<div class="installing-proccess">
			<div class="modules hide-step">
				<p>
					<span>{l s='Installing module' mod='eter_theme'}</span>:
					<strong class="current-module"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
			<div class="categories hide-step">
				<p>
					<span>{l s='Installing category' mod='eter_theme'}</span>:
					<strong class="current-category"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
			<div class="features hide-step">
				<p>
					<span>{l s='Installing Feature' mod='eter_theme'}</span>:
					<strong class="current-feature"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
			<div class="attributes hide-step">
				<p>
					<span>{l s='Installing Attributes' mod='eter_theme'}</span>:
					<strong class="current-attribute"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
			<div class="products hide-step">
				<p>
					<span>{l s='Installing product' mod='eter_theme'}</span>:
					<strong class="current-product"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
			<div class="accessories hide-step">
				<p>
					<span>{l s='Installing Accesories' mod='eter_theme'}</span>:
					<strong class="current-accessory"></strong>
				</p>
				<div class="progress">
			  		<div class="progress-bar progress-bar-striped  active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
			  	</div>
			</div>
		</div>
		<div class="panel-footer">
			<img src="/modules/eter_theme/views/assets/img/loader.gif" class="loader" style="display: none;">
			<button type="submit" value="1" id="install_form_submit_btn" data-url="{$stepsUrl}" name="installSamples" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s='Install' mod='eter_theme'}
			</button>
		</div>
	</div>
</div>
