{$cssFile = $smarty.const.APP|cat:'webroot'|cat:$smarty.const.DS|cat:'css'|cat:$smarty.const.DS|cat:$printLayout|cat:'.css'}
<!DOCTYPE html>
<html lang="it">
<head>
    <title>BEdita | {$title_for_layout} | {$html->action}</title>
    {include file = './inc/meta.tpl'}


    {$html->css('print', null, ['media' => 'all'])}
    {if file_exists($cssFile)}{$html->css($printLayout, null, ['media' => 'all'])}{/if}

    {$scripts_for_layout}

</head>

<body>
    {$content_for_layout}

</body>
</html>