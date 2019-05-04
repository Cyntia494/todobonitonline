<div class="home-menu">
	<div class="home-image">
		<img src="{$base_dir}{$MENU_IMAGE}" alt="{$MENU_TITLE}">
		<div class="imagetable">
			<div class="imagecell">
				<h3>{$MENU_TITLE}</h3>
				<p>{$MENU_DESC}</p>	
				<div class="bottom">
					<a href="{$MENU_URL}" itemprop="url">
						{$MENU_URL_LABEL}
					</a>
				</div>
			</div>
		</div>	
	</div>
	<div class="home-menu-ul">
		<ul>
			{if $MENU_URL1 && $MENU_LABEL1}
				<li>
					<a href="{$MENU_URL1}"  itemprop="url">
						<span>
							{$MENU_LABEL1} 
						</span>
					</a>
				</li>
			{/if}
			{if $MENU_URL2 && $MENU_LABEL2}
				<li>
					<a href="{$MENU_URL2}"  itemprop="url">
						<span>
							{$MENU_LABEL2} 
						</span>
					</a>
				</li>
			{/if}
			{if $MENU_URL3 && $MENU_LABEL3}
				<li>
					<a href="{$MENU_URL3}"  itemprop="url">
						<span>
							{$MENU_LABEL3} 
						</span>
					</a>
				</li>
			{/if}
			{if $MENU_URL4 && $MENU_LABEL4}
				<li>
					<a href="{$MENU_URL4}"  itemprop="url">
						<span>
							{$MENU_LABEL4} 
						</span>
					</a>
				</li>
			{/if}
			{if $MENU_URL5 && $MENU_LABEL5}
				<li>
					<a href="{$MENU_URL4}"  itemprop="url">
						<span>
							{$MENU_LABEL4} 
						</span>
					</a>
				</li>
			{/if}
		</ul>
	</div>
</div>



