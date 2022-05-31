<dl class="form-item">
    <dt>
        <label for="registration-user-captcha">{$aLang.registration_captcha}:</label>
    </dt>
    <dd>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <div class="g-recaptcha" data-sitekey="{$site_public_key}"></div>

        <small class="validate-error-hide validate-error-field-captcha"></small>
    </dd>
</dl>