{if $data}
    <div id="boedoslide" class="home-blockslider col-sm-12 col-md-12 col-lg-12" >
        <div class="title-fill">
            <h3><span class="fill"></span><span class="tap"></span>{l s='Find your style' mod='ps_featuredproducts'}<span class="fill"></span></h3>            
        </div>
        <div class="html-container">
            <div class="blockslider owl-carousel owl-theme" >
                {foreach from=$data item=row}
                    {if $row.active}
                        <div class="blockslide">
                            <div class="blockcontent etertheme-homeblock">
                                <img src="{$row.image_url}" alt="{$row.title|escape}" class="image">
                                <div class="details">
                                    <div class="white-back">
                                        <h3 class="title">{$row.title|escape}</h3>
                                    </div>
                                    <div class="line-mobile" style='width: 100%; height: 95%;top: 0;position: absolute;left: 0;'>
                                    <svg style='width: 100%; height: 99.2%;'>
                                        <svg style='width: 100%; height: 100%;'>\
                                           <line x1="20%" y1="70%" x2="70%" y2="20%" style="stroke:rgb(148, 84, 252);stroke-width:3"/>
                                           <line x1="0" y1="99.5%" x2="99.5%" y2="0" style="stroke:rgb(34, 92, 208);stroke-width:1; height: 90%;"/>
                                           <line x1="30%" y1="80%" x2="80%" y2="30%" style="stroke:rgb(148, 84, 252);stroke-width:3"/>
                                        </svg>
                                    </svg>
                                    </div>
                                    {if $row.url_name && $row.url}
                                        <a href="{$row.url}"  itemprop="url">
                                            {$row.url_name}
                                        </a>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    {/if}
                {/foreach}
            </div>
        </div>
    </div>
{/if}
