
{assign var=_counter value=0}
{function name="menu" nodes=[] depth=0 parent=null}
    {if $nodes|count}
        <ul class="top-menu" {if $depth == 0}id="top-menu"{/if} data-depth="{$depth}">
            
            <div class="content-menu">

                <!--This section add custom links in menu-->
                {if $depth === 0}

                    {if $customer.is_logged }
                        <!--My account-->
                        <li class="option-menu menu-sm hidden-md-up">
                            <a href="{$urls.pages.my_account}" class="anchor-menu etertheme-menulink dropdown-item my-account icon"  data-depth="{$depth}" >
                                <span itemprop="name">
                                    {l s='My account' d='Shop.Theme.Actions'}
                                </span>
                                {assign var=_expand_id value=10|mt_rand:100000}
                                <span class="pull-xs-right hidden-md-up name-category1">
                                    <span data-target="#top_sub_menu_{$_expand_id}" class="navbar-toggler collapse-icons">
                                        <div class="add"></div>
                                        <div class="remove"></div>
                                    </span>
                                </span>
                            </a>
                            {block name='customer_myaccount'}
                                {include file='cms/_partials/myaccount-menu.tpl'}
                            {/block}

                        </li>

                    {else}
                        <!--Log out-->
                        <li class="option-menu menu-sm hidden-md-up">
                            <a class="anchor-menu dropdown-item logout_url icon etertheme-menulink" href="{$urls.pages.my_account}" data-depth="{$depth}">
                                <span itemprop="name">
                                    {l s='Log in' d='Shop.Theme.Actions'}
                                </span>
                            </a>
                        </li>
                    {/if}
                {/if}

                {foreach from=$nodes item=node}
                    <li class="{$node.type}{if $node.current} current {/if} option-menu {if $node.children|count}parent{/if}" id="{$node.page_identifier}" itemscope itemtype="http://www.schema.org/SiteNavigationElement">
                        {assign var=_counter value=$_counter+1}
                        <a  itemprop="url"
                        class="anchor-menu etertheme-menulink {if $depth >= 0}dropdown-item{/if}{if $depth === 1} dropdown-submenu{/if}"
                        href="{$node.url}" title="{$node.label}" data-depth="{$depth}"
                        {if $node.open_in_new_window} target="_blank" {/if}
                        >
                            {if $node.children|count}
                                {* Cannot use page identifier as we can have the same page several times *}
                                {assign var=_expand_id value=10|mt_rand:100000}
                                <span class="pull-xs-right hidden-md-up name-category1">
                                    <span data-target="#top_sub_menu_{$_expand_id}" class="navbar-toggler collapse-icons">
                                        <div class="add"></div>
                                        <div class="remove"></div>
                                    </span>
                                </span>
                            {/if}
                            <span class="name-category2 {if !parent} no-parent {/if}" itemprop="name">{$node.label}</span>
                        </a>
                        {if $node.children|count}
                            <div {if $depth === 0} class="sub-menu js-sub-menu "{else} class="intern-submenu" {/if} id="top_sub_menu_{$_expand_id}">
                            {menu nodes=$node.children depth=$node.depth parent=$node}
                            </div>
                        {/if}
                    </li>
                {/foreach}
            </div>
        </ul>
    {/if}    
{/function}
<div class="menu-container">
  <div class="menu position-static" id="_desktop_top_menu" >
      {menu nodes=$menu.children}
      <div class="clearfix"></div>
  </div>
</div>


