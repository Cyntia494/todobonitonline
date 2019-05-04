<div id="home-menu">
	<div class="container">
		<div class="background-cover">
			{foreach from=$sections item=section}
				<div class="menu-section float-left">
					<div class="left-section float-left">
						<div class="list float-left">
							<h4>{$section.name}</h4>
							<ul>
								{foreach from=$section.categories item=cat} 
									<li>
										<a href="{$cat.url}">{$cat.name}</a>
									</li>
								{/foreach}
							</ul>
						</div>
						<div class="main-image float-left">
							<a href="{$section.banner_url}">
								<img src="{$section.banner}" alt="{$section.name}" title="{$section.name}">
							</a>
							<a href="{$section.banner_url}" class="btn btn-secondary">Ver más </a>
						</div>
					</div>
					<div class="right-section float-right">
						<div class="image1">
							<a href="{$section.image1_url}">
								<img src="{$section.image1}" alt="{$section.name}" title="{$section.name}">
							</a>
							<a class="anchor-link" href="{$section.banner_url}">Ver más <i class="fal fa-chevron-right"></i></a>
						</div>
						<div class="image2">
							<a href="{$section.image2_url}">
								<img src="{$section.image2}" alt="{$section.name}" title="{$section.name}">
							</a>
							<a class="anchor-link" href="{$section.banner_url}">Ver más <i class="fal fa-chevron-right"></i></a>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>