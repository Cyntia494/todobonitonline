<div class="listing-add-to-cart">
  <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
    <input type="hidden" name="token" value="{$static_token}">
    <input type="hidden" name="id_product" value="{$product.id}">
    {if $groups}
      <div class="product-variants">
        {foreach from=$groups key=id_attribute_group item=group}
          <div class="clearfix product-variants-item">
            <span class="control-label">{$group.name}</span>
            {if $group.group_type == 'select'}
              <select
                id="group_{$id_attribute_group}-{$product.id}"
                data-product-attribute="{$id_attribute_group}"
                name="group[{$id_attribute_group}]">
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <option value="{$id_attribute}" title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if}>{$group_attribute.name}</option>
                {/foreach}
              </select>
            {elseif $group.group_type == 'color'}
              <ul id="group_{$id_attribute_group}-{$product.id}">
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <li class="input-container">
                    <label for="color-{$id_attribute}-{$product.id}">
                      <input class="input-color hidden" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" id="color-{$id_attribute}-{$product.id}" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                      <span
                        {if $group_attribute.html_color_code}class="color" style="background-color: {$group_attribute.html_color_code}" {/if}
                        {if $group_attribute.texture}class="color texture" style="background-image: url({$group_attribute.texture})" {/if}
                      ><span class="sr-only">{$group_attribute.name}</span></span>
                    </label>
                  </li>
                {/foreach}
              </ul>
            {elseif $group.group_type == 'radio'}
              <ul id="group_{$id_attribute_group}-{$product.id}">
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <li class="input-container">
                    <label for="radio-{$id_attribute}-{$product.id}">
                      <input class="input-radio hidden" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" id="radio-{$id_attribute}-{$product.id}" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                      <span class="radio-label">{$group_attribute.name}</span>
                    </label>
                  </li>
                {/foreach}
              </ul>
            {/if}
          </div>
        {/foreach}
      </div>
    {/if}
    <div class="add">
      <button
        class="btn btn-primary add-to-cart"
        data-button-action="add-to-cart"
        type="submit">
        <i class="material-icons shopping-cart">&#xE547;</i>
        {l s='Add to cart' d='Shop.Theme.Actions'}
      </button>
    </div>
  </form>
</div>