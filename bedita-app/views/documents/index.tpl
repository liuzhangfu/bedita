{*
Pagina d'entrata modulo Areas.
*}
{php}$vs = &$this->get_template_vars() ;{/php}

{$html->css('module.area')}
	
{$javascript->link("jquery.treeview.pack")}
{$javascript->link("interface")}
{$javascript->link("module.documents")}
{$javascript->link("form")}
{$javascript->link("jquery.changealert")}

<script type="text/javascript">
{literal}

$(document).ready(function(){

});

{/literal}
</script>	
	
</head>
<body>

{include file="head.tpl"}

<div id="centralPage">
	
	{include file="submenu.tpl" method="index"}
	
	{include file="list_documents.tpl" method="index"}
	
</div>
