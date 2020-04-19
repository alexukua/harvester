{strip}
    {assign var="pageTitle" value="about.contact"}
    {include file="common/header.tpl"}
{/strip}


<form name="contact" method="post" action="{url op="sendemail"}">

    <h3>{translate key="user.contact"}</h3>

    {include file="common/formErrors.tpl"}
    {if $send}
        {translate key="user.contactsuccess"}
    {/if}

    {if $source}
        <input type="hidden" name="source" value="{$source|escape}" />
    {/if}

    <table class="data" width="100%">
       {if $captchaEnabled}
            <tr>
                <td class="label" valign="bottom">
                    {fieldLabel name="adminEmail" key="plugins.harvesters.oai.archive.form.adminEmail"}
                    {fieldLabel name="captcha" required="true" key="common.captchaField"}</td>
                <td class="value">
                    <img src="{url page="user" op="viewCaptcha" path=$captchaId}" alt="{translate key="common.captchaField.altText"}" /><br />
                    <span class="instruct">{translate key="common.captchaField.description"}</span><br />
                    <input name="captcha" id="captcha" value="" size="20" maxlength="32" class="textField" />
                    <input type="hidden" name="captchaId" value="{$captchaId|escape:"quoted"}" />
                </td>
            </tr>
        {/if}{* $captchaEnabled *}

        <tr valign="top">
            <td class="label">{translate key="user.username"}</td>
            <td class="value">
                <input type="text" id="username" name="username" value="{$username|escape}" size="20" maxlength="40" class="textField"/>
            </td>
        </tr>
        {*<tr valign="top">*}
            {*<td></td>*}
            {*<td class="instruct">{translate key="user.register.usernameRestriction"}</td>*}
        {*</tr>*}
        <tr valign="top">
            <td class="label">{translate key="user.email"}</td>
            <td class="value">
                <input type="text" id="contactemail" name="contactemail" value="{$contactemail|escape}" size="20" maxlength="90" class="textField" />
            </td>
        </tr>
      <tr valign="top">
            <td class="label">{translate key="user.message"}</td>
            <td class="value">
                <textarea id="message" name="message" rows="5" cols="40" class="textField" value="{$message|escape}"></textarea>
            </td>
       </tr>
        <tr valign="top">
            <td class="label">{translate key="user.phone"} {fieldLabel name="phone" key="user.phone"}</td>
            <td class="value">
                <input type="text" name="phone" id="phone" value="{$phone|escape}" size="15" maxlength="24" class="textField" />
            </td>
        </tr>
    </table>
    <p><input type="submit" value="{translate key="user.submit"}" class="button defaultButton" />
        <input type="button" value="{translate key="common.cancel"}" class="button" onclick="document.location.href='{url page="index" escape=false}'" /></p>

    <p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>
</div>
{include file="common/footer.tpl"}