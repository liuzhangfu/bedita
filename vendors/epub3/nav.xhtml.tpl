<?xml version="1.0" encoding="UTF-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en-US" lang="en-US">
	<head>
		<title>EPUB 3 Navigation Document</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="css/epub.css"/>
	</head>
	
	<body>
		<nav epub:type="toc" id="toc">
			<h1>Table of contents</h1>
			<ol>
				{if !empty($data.parts)}
				{foreach $data.parts as $p}
					<li><span>{$p.title}</span>
					{if !empty($p.chapters)}
					<ol>
						{foreach $p.chapters as $ch}
						<li><a href="{$ch.filename}.xhtml">{$ch.title}</a>
						{if !empty($ch.subchapters)}
						<ol>
						{foreach $ch.subchapters as $subch}
							<li><span>{$subch.title}</span></li>
						{/foreach}
						</ol>
						{/if}
						{/foreach}
						</li>
					</ol>
					{/if}
					</li>
				{/foreach}
				{else}
				{foreach $data.chapters as $ch}
				<li><a href="{$ch.filename}.xhtml">{$ch.title}</a></li>
				{/foreach}
				{/if}
			</ol>
		</nav>
	</body>
</html>