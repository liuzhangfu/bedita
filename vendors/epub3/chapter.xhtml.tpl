<?xml version="1.0" encoding="UTF-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en-US" lang="en-US">
<head>
  <title>{$data.title}</title>
  <meta charset="utf-8"/>
  <link rel="stylesheet" type="text/css" href="css/epub.css"/>
</head>
<body>
<section epub:type="chapter" id="section-{$data.id}">
	<header><h1>{$data.title}</h1></header>
	{foreach $data.contents as $a}
		<article id="{$a.nickname}">
			<h1>{$a.title}</h1>
			{$a.body}
			{* TODO: understand and fix *}
			{if !empty($a.RelatedObject)}
			{foreach from=$a.RelatedObject item=item}
			{$item.switch}:
			{if !empty($item.uri)}
				{if $item.object_type_id == $conf->objectTypes.image.id}
				<figure id="{$item.nickname}">
					{assign var='obj_url' value=$beEmbedMedia->object($item, ['URLonly' => true, 'width' => 200, 'height' => 200])}
					{if $obj_url == $conf->imgMissingFile}
						{assign_concat var='obj_url' 1='./media' 2=$obj_url}
					{/if}
					<img src="{$obj_url}" alt="{$item.title}" />
					<figcaption>
						<h1>{$item.title}</h1>
						<p>{$item.description|default:''}</p>
					</figcaption>
				</figure>
				{elseif $item.object_type_id == $conf->objectTypes.audio.id}
				<audio id="{$item.nickname}" controls="true" autoplay="false"> {* other controls, autoplay? *}
					<source src="media{$item.uri}" type="{$item.mime_type}" />
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				</audio>
				{elseif $item.object_type_id == $conf->objectTypes.video.id}
				<video id="{$item.nickname}" width="320" height="240" controls="true" autoplay="false"> {* other width, height, controls, autoplay? *}
					<source src="media{$item.uri}" type="{$item.mime_type}" />
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				</video>
				{else} {* show only uri *}
					media{$item.uri}
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				{/if}
			{else}
				<aside epub:type="notice">
					<h1>{$item.title}</h1>
					<p>{$item.body|default:''}</p>
				</aside>
			{/if}
			{/foreach}
			{/if}
		</article>
	{/foreach}


{if !empty($data.subchapters)}
{foreach $data.subchapters as $subsection}
	<header><h2>{$subsection.title}</h2></header>
	{foreach $subsection.contents as $a}
		<article id="{$a.nickname}">
			<h1>{$a.title}</h1>
			{$a.body}
			{if !empty($a.RelatedObject)}
			{foreach from=$a.RelatedObject item=item}
			{$item.switch}:
			{if !empty($item.uri)}
				{if $item.object_type_id == $conf->objectTypes.image.id}
				<figure id="{$item.nickname}">
					{assign var='obj_url' value=$beEmbedMedia->object($item, ['URLonly' => true, 'width' => 200, 'height' => 200])}
					{if $obj_url == $conf->imgMissingFile}
						{assign_concat var='obj_url' 1='./media' 2=$obj_url}
					{/if}
					<img src="{$obj_url}" alt="{$item.title}" />
					<figcaption>
						<h1>{$item.title}</h1>
						<p>{$item.description|default:''}</p>
					</figcaption>
				</figure>
				{elseif $item.object_type_id == $conf->objectTypes.audio.id}
				<audio id="{$item.nickname}" controls="true" autoplay="false"> {* other controls, autoplay? *}
					<source src="media{$item.uri}" type="{$item.mime_type}" />
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				</audio>
				{elseif $item.object_type_id == $conf->objectTypes.video.id}
				<video id="{$item.nickname}" width="320" height="240" controls="true" autoplay="false"> {* other width, height, controls, autoplay? *}
					<source src="media{$item.uri}" type="{$item.mime_type}" />
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				</video>
				{else} {* show only uri *}
					media{$item.uri}
					<h1>{$item.title}</h1>
					<p>{$item.description|default:''}</p>
				{/if}
			{else}
				<aside epub:type="notice">
					<h1>{$item.title}</h1>
					{$item.body|default:''}
				</aside>
			{/if}
			{/foreach}
			{/if}
		</article>
	{/foreach}
{/foreach}
{/if}
</section>
</body>
</html>