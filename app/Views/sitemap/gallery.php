

<?php
echo '<?xml version="1.0" encoding="UTF-8"?>'; // Tambahkan header xml
?>
<xsl:stylesheet version="2.0"
	xmlns:html="http://www.w3.org/TR/REC-html40"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
	xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>XML Sitemap</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		body {
			font-family: Helvetica, Arial, sans-serif;
			font-size: 13px;
			color: #545353;
		}
		table {
			border: none;
			border-collapse: collapse;
		}
		#sitemap tr:nth-child(odd) td {
			background-color: #eee !important;
		}
		#sitemap tbody tr:hover td {
			background-color: #ccc;
		}
		#sitemap tbody tr:hover td, #sitemap tbody tr:hover td a {
			color: #000;
		}
		#content {
			margin: 0 auto;
			width: 1000px;
		}
		.expl {
			margin: 18px 3px;
			line-height: 1.2em;
		}
		.expl a {
			color: #da3114;
			font-weight: 600;
		}
		.expl a:visited {
			color: #da3114;
		}
		a {
			color: #000;
			text-decoration: none;
		}
		a:visited {
			color: #777;
		}
		a:hover {
			text-decoration: underline;
		}
		td {
			font-size:11px;
		}
		th {
			text-align:left;
			padding-right:30px;
			font-size:11px;
		}
		thead th {
			border-bottom: 1px solid #000;
		}
	</style>
</head>
<body>
	<div id="content">
		<h1>XML Sitemap</h1>
		<p class="expl">
			Generated by <a href="https://yoa.st/1y5" target="_blank" rel="noopener noreferrer">Yoast</a> <a href="https://yoa.st/1y6" target="_blank" rel="noopener noreferrer">SEO</a>, this is an XML Sitemap, meant for consumption by search engines.<br/>
			You can find more information about XML sitemaps on <a href="http://sitemaps.org" target="_blank" rel="noopener noreferrer">sitemaps.org</a>.
		</p>
		<xsl:if test="count(sitemap:sitemapindex/sitemap:sitemap) &gt; 0">
			<p class="expl">
				This XML Sitemap Index file contains <?= count($data) ?> sitemaps.
			</p>
			<table id="sitemap" cellpadding="3">
				<thead>
				<tr>
					<th width="75%">Sitemap</th>
					<th width="25%">Last Modified</th>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="<?= base_url('gallery'); ?>"><loc><?= base_url('gallery'); ?></loc></a></td>
						<td><lastmod><?= date('Y-m-d') ?></lastmod></td>
					</tr>
					<?php foreach($data as $m): ?>
					<tr>
						<td><a href="https://office.mitrarenov.com/assets/main/images/photo_promo_paket/<?= $m->image ?>"><loc><?= base_url('gallery/' . $m->slug); ?></loc></a></td>
						<td><lastmod><?= $m->created ?></lastmod></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</xsl:if>
	</div>
</body>
</html>