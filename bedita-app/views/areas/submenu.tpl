{*
Template incluso.
Menu a SX valido per tutte le pagine del controller.
*}
{assign var='method' value=$method|default:'index'}
	<div id="menuLeftPage">
		<div class="menuLeft">
			<h1 onClick="window.location='./'" class="aree"><a href="./">Areas</a></h1>
	
			<div class="inside">
				<ul class="simpleMenuList" style="margin:10px 0px 10px 0px">
					<li {if $method eq 'index'}class="on"{/if}>		<b>&#8250;</b> {$html->link('Albero delle Aree', '/areas')}</li>
					<li {if $method eq 'viewArea'}class="on"{/if}>	<b>&#8250;</b> {$html->link('Nuova Area', '/areas/viewArea')}</li>
					<li {if $method eq 'viewSection'}class="on"{/if}>	<b>&#8250;</b> {$html->link('Nuova Sezione', '/areas/viewSection')}</li>
				</ul>	
				<hr>
			</div>
		</div>
	<p>
	Testo a seguire
	</p>
	<hr>
	
	<div id="handlerChangeAlert">

	</div>
	<br>
<br>
<br>

	</div>

	