{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div id="eter_search_filters">
  <section id="js-active-search-filters" class="{if $activeFilters|count}active_filters{else}hide{/if}">
    {if $activeFilters|count}
      {block name='active_filters_title'}
        <h1 class="h6 {if $activeFilters|count}active-filter-title{else}hidden-xs-up{/if}">{l s='Active filters' d='Shop.Theme.Global'}</h1>
      {/block}

      <ul>
        {foreach from=$activeFilters item="filter"}
          {block name='active_filters_item'}
            <li class="filter-block">
              {l s='%1$s: ' d='Shop.Theme.Catalog' sprintf=[$filter.facetLabel]}
              {$filter.label}
              <a class="js-search-link" href="{$filter.nextEncodedFacetsURL}"><i class="material-icons close">&#xE5CD;</i></a>
            </li>
          {/block}
        {/foreach}
      </ul>
      {block name='facets_clearall_button'}
        <div id="_desktop_search_filters_clear_all" class="clear-all-wrapper">
            <a data-search-url="{$clear_all_link}" class="btn js-search-filters-clear-all">
                {l s='Clear all' d='Shop.Theme.Actions'}
            </a>
        </div>
      {/block}
    {/if}
  </section>
  {if count($facets) > 0 || $activeFilters|count }
    <div id="search_filter_controls" class="hidden-md-up">
      <span id="_mobile_search_filters_clear_all"></span>
      <div id="_desktop_search_filters_clear_all" class="clear-all-wrapper">
          <a class="btn js-search-mobile-filters-clear-all">
            <i class="fal fa-trash-alt"></i>
            {l s='Clear filters' d='Shop.Theme.Actions'}
          </a>
      </div>
      <button class="btn btn-secondary ok search_filter_toggler" id="search_filter_toggler">
        <i class="fal fa-filter"></i>
        {l s='Show Filters' d='Shop.Theme.Actions'}
      </button>
    </div>
  {/if}
  <div id="search_filters_wrapper" class="filters-content">
    {if count($facets) > 0}
      <div id="search_filters">
          <div class="filter-div">
              {block name='facets_title'}
                  <h4 class="text-uppercase h6">{l s='Filter By' d='Shop.Theme.Actions'}</h4>
                  <span class="pull-xs-right">
                    <span class="close-filter">
                      <i class="fas fa-times hidden-md-up"></i>
                    </span>
                  </span>
              {/block}
          </div>
          {foreach from=$facets item="facet"}
            {if $facet.displayed}
              <section class="facet clearfix {$facet.type}">
                {assign var=_expand_id value=10|mt_rand:100000}
                {assign var=_collapse value=true}
                {foreach from=$facet.filters item="filter"}
                  {if $filter.active}{assign var=_collapse value=false}{/if}
                {/foreach}
                <div class="title">
                  <h1 class="h6 facet-title">{$facet.label}</h1>
                  <span class="pull-xs-right">
                    <span class="toggle-arrow">
                      <i class="fal fa-plus add "></i>
                      <i class="fal fa-minus remove"></i>
                    </span>
                  </span>
                </div>

                {if $facet.widgetType !== 'dropdown'}

                  {block name='facet_item_other'}
                    <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
                      {foreach from=$facet.filters item="filter"}
                        {if $filter.displayed}
                          <li>
                            <label class="facet-label{if $filter.active} active {/if}">
                              {if $facet.multipleSelectionAllowed}
                                <span class="custom-checkbox">
                                  <input
                                    data-search-url="{$filter.nextEncodedFacetsURL}"
                                    type="checkbox"
                                    {if $filter.active } checked {/if}
                                  >
                                  {if isset($filter.properties.color)}
                                    <span class="color" style="background-color:{$filter.properties.color}"></span>
                                    {elseif isset($filter.properties.texture)}
                                      <span class="color texture" style="background-image:url({$filter.properties.texture})"></span>
                                    {else}
                                    <span {if !$js_enabled} class="ps-shown-by-js" {/if}><i class="material-icons checkbox-checked">&#xE5CA;</i></span>
                                  {/if}
                                </span>
                              {else}
                                <span class="custom-checkbox">
                                  <input
                                    data-search-url="{$filter.nextEncodedFacetsURL}"
                                    type="radio"
                                    name="filter {$facet.label}"
                                    {if $filter.active } checked {/if}
                                  >
                                  <span {if !$js_enabled} class="ps-shown-by-js" {/if}></span>
                                </span>
                              {/if}

                              <a
                                href="{$filter.nextEncodedFacetsURL}"
                                class="_gray-darker search-link js-search-link"
                                rel="nofollow"
                              >
                                {$filter.label}
                                {if isset($show_facet_quantities) && $show_facet_quantities}
                                  {if $filter.magnitude}
                                    ({$filter.magnitude})
                                  {/if}
                                {/if}
                              </a>
                            </label>
                          </li>
                        {/if}
                      {/foreach}
                    </ul>
                  {/block}

                {else}

                  {block name='facet_item_dropdown'}
                    <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
                      <li>
                        <div class="col-sm-12 col-xs-12 col-md-12 facet-dropdown dropdown">
                          <a class="select-title" rel="nofollow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {$active_found = false}
                            <span>
                              {foreach from=$facet.filters item="filter"}
                                {if $filter.active}
                                  {$filter.label}
                                  {if isset($show_facet_quantities) && $show_facet_quantities}
                                    {if $filter.magnitude}
                                      ({$filter.magnitude})
                                    {/if}
                                  {/if}
                                  {$active_found = true}
                                {/if}
                              {/foreach}
                              {if !$active_found}
                                {l s='(no filter)' d='Shop.Theme'}
                              {/if}
                            </span>
                            <i class="material-icons pull-xs-right">&#xE5C5;</i>
                          </a>
                          <div class="dropdown-menu">
                            {foreach from=$facet.filters item="filter"}
                              {if !$filter.active}
                                <a
                                  rel="nofollow"
                                  href="{$filter.nextEncodedFacetsURL}"
                                  class="select-list"
                                >
                                  {$filter.label}
                                  {if isset($show_facet_quantities) && $show_facet_quantities}
                                    {if $filter.magnitude}
                                      ({$filter.magnitude})
                                    {/if}
                                  {/if}
                                </a>
                              {/if}
                            {/foreach}
                          </div>
                        </div>
                      </li>
                    </ul>
                  {/block}

                {/if}
              </section>
            {/if}
          {/foreach}
      </div>
    {/if}
  </div>
</div>
