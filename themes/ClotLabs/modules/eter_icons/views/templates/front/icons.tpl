{if $data}
    <div id="footericons"  >
        <div class="icons" >
            <div class="title">
                <strong>{l s='Payment' d='eter_icons'}</strong> 
                {l s='Methods' d='eter_icons'}
            </div>
            <div class="icons-group"> 
                {foreach from=$data item=row}
                    {if $row.active} 
                        {if $row.url}<a href="{$row.url}">{/if}
                            <div class="icon">
                                <img src="{$row.image}" alt="{l s='Payment Icons' d='eter_icons'}" class="image">
                            </div>
                        {if $row.url}</a>{/if}
                    {/if}
                {/foreach}
            </div>
        </div>
    </div>
{/if}
