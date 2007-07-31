{*
Frammento di codice per inserire la gestioen delle custom properties
*}
<script type="text/javascript">
{literal}
/*
Script per la gestione delle custom properties
*/
var postfix_customProp = "_customPropTR" ;

$(document).ready(function(){
{/literal}	
	{foreach name="setCPForm" key="name" item="property" from=$el.CustomProperties}
		_setupCustomPropTR("{$name}"+postfix_customProp) ;
	{/foreach}
{literal}
	$('input[@name=cmdAdd]', "#frmCustomProperties").bind("click", function (e) {
		addCustomPropTR() ;
	}) ;
});

{/literal}
{capture name=i}
{html_options options=$conf->customPropTypeOptions}
{/capture}

{literal}
// Procedura per l'aggiunta di una proprieta'
var htmlTemplateCustomProp = ' \
					<tr id=""> \
						<td><input type="hidden" name=""></td> \
						<td><select name="">{/literal}{$smarty.capture.i|strip}{literal}</select></td> \
						<td><input type="" name="" value=""> </td> \
						<td><input type="button" name="delete" value=" x "></td> \
					</tr> \
' ;

function addCustomPropTR() {
	var name 	= $.trim($("#addCustomPropTR TD/input[@name=name]").fieldValue()[0].replace(/[^_a-z0-9]/g, ""));	
	var value 	= $.trim($("#addCustomPropTR TD/input[@name=value]").fieldValue()[0]) ;
	var type 	= $("#addCustomPropTR TD/select[@name=type]").fieldValue()[0] ;
	
	// Se non completa esce
	if(!name.length || !value.length) {
		alert("Dati non completi") ;
		
		return false ;
	}
	
	// se gia' presente o vuota esce
	if($("#"+name+postfix_customProp).size()) {
		alert("Proprieta' gia' presente") ;
		
		return false ;
	}

	// Inserisce il nuovo elemento
	var newTR = $("#endLineCustomPropTR").before(htmlTemplateCustomProp).prev() ;
	
	// Setup nomi, id e comandi degli elementi
	newTR.attr("id", name+postfix_customProp) ;
	$("TD:nth-child(1)/input", newTR).attr("name", "data[CustomProperties]["+name+"][name]") ;
	$("TD:nth-child(2)/select", newTR).attr("name", "data[CustomProperties]["+name+"][type]") ;
	$("TD:nth-child(3)/input", newTR).attr("name", "data[CustomProperties]["+name+"][value]") ;
	$('TD:nth-child(4)/input[@name=delete]', newTR).bind("click", function (e) { deleteTRCustomProp(this)}) ;
	
	// setup dei valori
	$("TD:nth-child(1)/input", newTR).attr("value", name) ;
	$("TD:nth-child(1)", newTR).append(name) ;
	$("TD:nth-child(3)/input", newTR).attr("value", value) ;

	var options = $("TD:nth-child(2)/select", newTR).get(0).options ;
	for(var i = 0 ; i < options.length ; i++) {
		if(options[i].value == type) options[i].selected = true ; 
	}
	
	// resetta i campi per l'input di una nuova prop
	$("#addCustomPropTR TD/input[@type=text]").attr("value", "") ;
	$("#addCustomPropTR TD/select").get(0).options[0].selected = true ;
	
	// Indica l'avvenuto cambiamento dei dati
	try {
		$().alertSignal() ;
	} catch(e) {}
}


// Setta i comandi per la gestione delle righe della tabella delle custom properites
function _setupCustomPropTR(id) {
	// Definisce il comando per la cancellazione
	$('#'+id+' TD:last/input[@name=delete]').bind("click", function (e) {
		deleteTRCustomProp(this)
	}) ;
}

// cancella l'elemento
function deleteTRCustomProp(el) {
	if(!confirm("Confermi la cancellazione delle proprieta'?")) return false ;
	$(el).parent().parent().remove() ;		
	
	// Indica l'avvenuto cambiamento dei dati
	try {
		$().alertSignal() ;
	} catch(e) {}
}

{/literal}
</script>


				<table class="tableForm" border="0" id="frmCustomProperties">
					<tr>
						<td class="label" style="text-align:left;">nome</td>
						<td class="label" style="text-align:left;">tipo</td>
						<td class="label" style="text-align:left;">valore</td>
						<td class="label">&nbsp;</td>
					</tr>
					{foreach key="name" item="property" from=$el.CustomProperties}
					<tr id="{$name}_customPropTR">
						<td>
							<input type="hidden" name="data[CustomProperties][{$name}][name]">
							{$name}
						</td>
						<td>
						<select name="data[CustomProperties][{$name}][type]">
						{html_options options=$conf->customPropTypeOptions selected=$property|get_type}
						</select>
						</td>
						<td>
							<input type="text" name="data[CustomProperties][{$name}][value]" value="{$property|escape:'html'}">
						</td>
						<td>
							<input type="button" name="delete" value=" x ">
						</td>
					</tr>
					{/foreach}
					<tr id="endLineCustomPropTR">
						<td colspan="4"><hr></td>
					</tr>
					<tr id="addCustomPropTR">
						<td>
							<input type="text" name="name">
						</td>
						<td>
						<select name="type">
						{html_options options=$conf->customPropTypeOptions}
						</select>
						</td>
						<td>
							<input type="text" name="value" value="">
						</td>
						<td>
							<input type="button" name="cmdAdd" value=" add ">
						</td>
					</tr>
				</table>
