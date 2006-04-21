{**
 * index.tpl
 *
 * Copyright (c) 2005-2006 The Public Knowledge Project
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Reading Tool Administrator index.
 *
 * $Id$
 *}

{assign var="pageTitle" value="admin.rtAdmin"}
{include file="common/header.tpl"}

<h3>{translate key="rt.admin.status"}</h3>
<form action="{url op="selectVersion" path=$archiveId}" method="post">
<p>{translate key="rt.admin.selectedVersion"}:&nbsp;<select name="versionId" class="selectMenu" id="versionId">
	<option value="">{translate key="common.none"}</option>
	{iterate from=versions item=versionLoop}
		<option {if $version && $versionLoop->getVersionId() == $version->getVersionId()}selected {/if}value="{$versionLoop->getVersionId()}">{$versionLoop->getTitle()|escape}</option>
	{/iterate}
</select>&nbsp;&nbsp;<input type="submit" class="button defaultButton" value="{translate key="common.save"}"/></p>
</form>

<p>{translate key="rt.admin.rtEnable"}</p>

<h3>{translate key="rt.admin.configuration"}</h3>
<ul class="plain">
	<li>&#187; <a href="{url op="versions" path=$archiveId}">{translate key="rt.versions"}</a></li>
</ul>

<h3>{translate key="rt.admin.management"}</h3>
<ul class="plain">
	<li>&#187; <a href="{url op="validateUrls"}">{translate key="rt.admin.validateUrls"}</a></li>
</ul>

{include file="common/footer.tpl"}
