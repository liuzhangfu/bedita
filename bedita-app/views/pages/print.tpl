<div class="standardreport">
    <div class="modules" style="float: left">
        <label class="bedita">BEdita {$conf->version}<br /><br />{$conf->projectName|default:''|escape}</label>
    </div>
    <div class="modules" style="float: left">
        <label class="{$object.ObjectType.module_name|default:''}">{$object.ObjectType.name|default:''}</label>
    </div>

    <br style="clear:both" />
    <h1>{$object.title|escape|default:'<i>no title</i>'}</h1>

    <ul>
    {foreach $object|array_filter as $key => $value}
       <li>
            <label>{t}{$key}{/t}:</label>

            {if !is_array($value)}
                {$value}
            {else}
                <ul>
                    {foreach $value as $key2 => $value2}
                        <li {if ($value2@index == 0)}style="border:0px solid silver"{/if}>
                            <label>{t}{$key2}{/t}:</label>

                            {if !is_array($value2)}
                                {$value2}
                            {else}
                            <ul>
                                {foreach $value2 as $key3 => $value3}
                                    <li>
                                        <label>{t}{$key3}{/t}:</label>

                                        {if !is_array($value3)}
                                            {$value3}
                                        {else}
                                        <ul>
                                            {foreach $value3 as $key4 => $value4}
                                                <li>
                                                    <label>{t}{$key4}{/t}:</label>

                                                    {if !is_array($value4)}
                                                        {$value4}
                                                    {else}
                                                    <ul>
                                                        {foreach $value4 as $key5 => $value5}
                                                            <li>
                                                                <label>{t}{$key5}{/t}:</label>
                                                                {$value5}
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                    {/if}
                                                </li>
                                            {/foreach}
                                        </ul>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
            {/if}
       </li>
    {/foreach}
    </ul>
</div>

<script type="text/javascript">
    print();
</script>