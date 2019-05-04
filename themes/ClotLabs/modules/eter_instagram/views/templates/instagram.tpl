{if isset($data)}
	<div id="instagram">
		<div class="title-fill">
            <h3><span class="fill"></span><span class="tap"></span>{l s='Follow us on Instagram' mod='eter_instagram'}<span class="fill"></span></h3>
        </div>
	    <div class="instagram-carousel ">
			<div class="lines2">
		        <div class="line1"></div>
		        <div class="line2"></div>
		        <div class="line3"></div>
		    </div>
			<div class="instagram-images owl-carousel owl-theme">
	    		{foreach from=$data item=value}
	    			<a href="{$value.link}" target="_blank">
		    			<div class="instagrampost">
							<img src="{$value.image}" alt="banner information"/>
							
		    			</div>
	    			</a>
				{/foreach}
	        </div>
			<div class="lines2">
		        <div class="line1"></div>
		        <div class="line2"></div>
		        <div class="line3"></div>
		    </div>
		</div>
	</div>
{/if}
