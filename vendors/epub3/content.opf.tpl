<?xml version="1.0" encoding="UTF-8"?>
<package version="3.0" xml:lang="en" xmlns="http://www.idpf.org/2007/opf" unique-identifier="pub-id">
	<metadata xmlns:dc="http://purl.org/dc/elements/1.1/">
		<dc:identifier id="pub-id">urn:uuid: {$data.uniqid}</dc:identifier>
		<dc:title>{$data.title}</dc:title>
		{if !empty($data.description)}
			<dc:description>{$data.description}</dc:description>
		{elseif !empty($data.abstract)}
			<dc:description>{$data.abstract|truncate:255}</dc:description>
		{elseif !empty($data.body)}
			<dc:description>{$data.body|truncate:255}</dc:description>
		{/if}
		<dc:language>{$data.lang}</dc:language>
		{if !empty($data.creator)}
			<dc:creator id="creator">{$data.creator}</dc:creator>
		{/if}
		{if !empty($data.publisher)}
			<dc:publisher>{$data.publisher}</dc:publisher>
		{/if}
		{if !empty($data.created)}
			<dc:date>{$data.created|date_format:$conf->dateTimePattern}</dc:date>
		{/if}
		{if !empty($data.modified)}
			<meta property="dcterms:modified">{$data.modified|date_format:'%Y-%m-%dT%H:%M:%SZ'}</meta>
		{else}
			<meta property="dcterms:modified">{$smarty.now|date_format:'%Y-%m-%dT%H:%M:%SZ'}</meta>
		{/if}
	</metadata>
	<manifest>
		<item id="nav" href="nav.xhtml" media-type="application/xhtml+xml" properties="nav"/>
		<item id="cover" href="cover.xhtml" media-type="application/xhtml+xml"/>
		<item id="cover-image" properties="cover-image" href="media/cover.png" media-type="image/png"/>
		<item id="style" href="css/epub.css" media-type="text/css"/>
		{if !empty($data.parts)}
			{foreach $data.parts as $p}
				{foreach $p.chapters as $ch}
		<item id="{$ch.filename}" href="{$ch.filename}.xhtml" media-type="application/xhtml+xml"/>

				{/foreach}
			{/foreach}
		{else}
			{foreach $data.chapters as $ch}
		<item id="{$ch.filename}" href="{$ch.filename}.xhtml" media-type="application/xhtml+xml"/>
			{/foreach}
		{/if}
		{foreach $data.manifest.file as $f}
		<item id="{$f.nickname}" href="{$f.path}" media-type="{$f.mime_type}"/>
		{/foreach}
		
		{foreach $data.media as $obj}
			{if $obj.object_type_id == $conf->objectTypes.image.id}
				{assign var='obj_url' value=$beEmbedMedia->object($obj, 
					['URLonly' => true, 'width' => 200, 'height' => 200])}
					{if $obj_url == $conf->imgMissingFile}
						{assign_concat var='obj_url' 1='./media' 2=$obj_url}
					{/if}
				<item id="{$obj.nickname}" href="{$obj_url}" media-type="{$obj.mime_type}" />
			{/if}
		{/foreach}

	</manifest>
	<spine>
		<itemref idref="cover" linear="no"/>
		{if !empty($data.parts)}
		{foreach $data.parts as $p}
		{foreach $p.chapters as $ch}
		<itemref idref="{$ch.filename}" linear="yes"/>
		{/foreach}
		{/foreach}
		{else}
		{foreach $data.chapters as $ch}
		<itemref idref="{$ch.filename}" linear="yes"/>
		{/foreach}
		{/if}
		<itemref idref="nav" linear="no"/>
	</spine>
</package>