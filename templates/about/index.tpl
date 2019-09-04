{**
 * index.tpl
 *
 * Copyright (c) 2005-2012 Alec Smecher and John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * About the site.
 *
 * $Id$
 *}
{strip}
{assign var="pageTitle" value="navigation.about"}
{include file="common/header.tpl"}
{/strip}

{if !empty($about)}
	<p>{$about|nl2br}</p>
{/if}

<p><a href="{url op="harvester"}">{translate key="about.harvester"}</a></p>
<p><a href="{url op="contact"}">{translate key="about.contact"}</a></p>

{include file="common/footer.tpl"}
