{**
 * navbar.tpl
 *
 * Copyright (c) 2005-2008 Alec Smecher and John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Navigation Bar
 *
 *}
<div id="navbar">
	<ul class="menu">
		<li><a href="{url page="index"}">{translate key="navigation.home"}</a></li>
		<li><a href="{url page="about"}">{translate key="navigation.about"}</a></li>

		{if $isUserLoggedIn}
			<li><a href="{url page="user"}">{translate key="navigation.userHome"}</a></li>
		{elseif $enableSubmit}
			<li><a href="{url page="login"}">{translate key="navigation.login"}</a></li>
			<li><a href="{url page="user" op="register"}">{translate key="navigation.register"}</a></li>
		{/if}{* $isUserLoggedIn *}

		<li><a href="{url page="browse"}">{translate key="navigation.browse"}</a></li>

		{call_hook name="Templates::Common::Header::Navbar"}

		{foreach from=$navMenuItems item=navItem}
			{if $navItem.url != '' && $navItem.name != ''}
				<li><a href="{if $navItem.isAbsolute}{$navItem.url|escape}{else}{url page=""}{$navItem.url|escape}{/if}">{if $navItem.isLiteral}{$navItem.name|escape}{else}{translate key=$navItem.name}{/if}</a></li>
			{/if}
		{/foreach}

		<li><a href="javascript:openHelp('{if $helpTopicId}{get_help_id key="$helpTopicId" url="true"}{else}{url page="help"}{/if}')">{translate key="help.help"}</a></li>
	</ul>
</div>