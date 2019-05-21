{if $pagination.should_be_displayed} 
  <nav class="pagination">
    <div class="pagination-container">
      {block name='pagination_summary'}
        <span class="pagination-total">
          {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=['%from%' => $pagination.items_shown_from ,'%to%' => $pagination.items_shown_to, '%total%' => $pagination.total_items]}
        </span>
      {/block}
      {block name='pagination_page_list'}
        <ul class="page-list clearfix text-xs-center">
          {foreach from=$pagination.pages item="page"}
            <li {if $page.current} class="current" {/if}>
              {if $page.type === 'spacer'}
                <span class="spacer">&hellip;</span>
              {else}
                <a
                  rel="{if $page.type === 'previous'}prev{elseif $page.type === 'next'}next{else}nofollow{/if}"
                  href="{$page.url}"
                  class="{if $page.type === 'previous'}previous {elseif $page.type === 'next'}next {/if}"
                >
                  {if $page.type === 'previous'}
                    <i class="fas fa-angle-left"></i>
                  {elseif $page.type === 'next'}
                    <i class="fas fa-angle-right"></i>
                  {else}
                    {$page.page}
                  {/if}
                </a>
              {/if}
            </li>
          {/foreach}
        </ul>
      {/block}
    </div>
  </nav>
{/if}
