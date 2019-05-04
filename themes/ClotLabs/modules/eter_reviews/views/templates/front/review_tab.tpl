<div class="review">
	<div class="comment-section">
		<div class="global-score">
			<div class="average ">
				<span class="star star1 {if 1 <= $average}full{/if}">
					<i class="material-icons full star-full">star</i>
				</span>
				<span class="star star2 {if 2 <= $average}full{/if}">
					<i class="material-icons full star-full">star</i>
				</span>
				<span class="star star3 {if 3 <= $average}full{/if}">
					<i class="material-icons full star-full">star</i>
				</span>
				<span class="star star4 {if 4 <= $average}full{/if}">
					<i class="material-icons full star-full">star</i>
				</span>
				<span class="star star5 {if 5 <= $average}full{/if}">
					<i class="material-icons full star-full">star</i>
				</span>
			</div>
		</div>
		<div class="score-resume">
			<div class="bars">
				<span class="mr10">{l s='5 stars' mod='eter_reviews'}</span>
				<div class="bar">
					{if isset($star5)}
						<div class="bar-average" style="width: {$star5.average}%"></div>
					{/if}
				</div>
				<span>
					{if isset($star5)}
						{$star5.average}%
					{else}
						0%
					{/if}
				</span>
			</div>
			<div class="bars">
				<span class="mr10">{l s='4 stars' mod='eter_reviews'}</span>
				<div class="bar">
					{if isset($star4)}
						<div class="bar-average" style="width: {$star4.average}%"></div>
					{/if}
				</div>
				<span>
					{if isset($star4)}
						{$star4.average}%
					{else}
						0%
					{/if}
				</span>
			</div>
			<div class="bars">
				<span class="mr10">{l s='3 stars' mod='eter_reviews'}</span>
				<div class="bar">
					{if isset($star3)}
						<div class="bar-average" style="width: {$star3.average}%"></div>
					{/if}
				</div>
				<span>
					{if isset($star3)}
						{$star3.average}%
					{else}
						0%
					{/if}
				</span>
			</div>
			<div class="bars">
				<span class="mr10">{l s='2 stars' mod='eter_reviews'}</span>
				<div class="bar">
					{if isset($star2)}
						<div class="bar-average" style="width: {$star2.average}%"></div>
					{/if}
				</div>
				<span>
					{if isset($star2)}
						{$star2.average}%
					{else}
						0%
					{/if}
				</span>
			</div>
			<div class="bars">
				<span class="mr10">{l s='1 stars' mod='eter_reviews'}</span>
				<div class="bar">
					{if isset($star1)}
						<div class="bar-average" style="width: {$star1.average}%"></div>
					{/if}
				</div>
				<span>
					{if isset($star1)}
						{$star1.average}%
					{else}
						0%
					{/if}
				</span>
			</div>

		</div>
		{if $reviews}
			<div class="coments">
				<strong>{l s='Top rated reviews' mod='eter_reviews'}</strong>
				{foreach from=$reviews item=review}
					{if $review.show}
						<div class="comment-item">
							<div class="average ">
								<span class="star star1 {if 1 <= $review.score}full{/if}">
									<i class="material-icons full star-full">star</i>
								</span>
								<span class="star star2 {if 2 <= $review.score}full{/if}">
									<i class="material-icons full star-full">star</i>
								</span>
								<span class="star star3 {if 3 <= $review.score}full{/if}">
									<i class="material-icons full star-full">star</i>
								</span>
								<span class="star star4 {if 4 <= $review.score}full{/if}">
									<i class="material-icons full star-full">star</i>
								</span>
								<span class="star star5 {if 5 <= $review.score}full{/if}">
									<i class="material-icons full star-full">star</i>
								</span>
							</div>
							<p class="title">{$review.title}</p>
							<p class="author">{l s='By' mod='eter_reviews'}<span class="blue"> {$review.author}</span> {$smarty.now|date_format:"%A, %B %e, %Y"}</p>
							<p class="comment">{$review.comment}</p>
							<p></p>
						</div>
					{/if}
				{/foreach}
			</div>
		{/if}
	</div>
	<div class="review-section">
		<div class="thanksreview" style="display: none;">
			<h3>{l s='Thanks for your review' mod='eter_reviews'}</h3>
		</div>
		<div class="writereview">
			<h3>{l s='Write a review' mod='eter_reviews'}</h3>
			<form class="review-form" action="{url entity='module' name='eter_reviews' controller='reviews'}">
				<div class="radios">
					<div class="score">
						<label for="score1" class="star star1">
							<i class="material-icons full star-full">star</i>
							<input id="score1" type="radio" name="score" value="1" >
						</label>
						<label for="score2" class="star star2">
							<i class="material-icons full star-full">star</i>
							<input id="score2" type="radio" name="score" value="2">
						</label>
						<label for="score3" class="star star3">
							<i class="material-icons full star-full">star</i>
							<input id="score3" type="radio" name="score" value="3">
						</label>
						<label for="score4" class="star star4">
							<i class="material-icons full star-full">star</i>
							<input id="score4" type="radio" name="score" value="4">
						</label>
						<label for="score5" class="star star5">
							<i class="material-icons full star-full">star</i>
							<input id="score5" type="radio" name="score" value="5">
						</label>
					</div>
				</div>
				<div class="input">
					<input type="hidden" name="id_product" value="{$id_product}" >
					<label>{l s='Name' mod='eter_reviews'}</label>
					<input type="text" name="name" required>
				</div>
				<div class="input">
					<label>{l s='Title' mod='eter_reviews'}</label>
					<input type="text" name="title" required>
				</div>
				<div class="input">
					<label>{l s='Comment' mod='eter_reviews'}</label>
					<textarea name="comment" required></textarea>
				</div>
				<button class="btn btn-primary">{l s='Save' mod='eter_reviews'}</button>
			</form>		
		</div>
	</div>
</div>
