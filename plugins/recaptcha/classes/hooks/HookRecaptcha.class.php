<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA v2
 * @Description: Replaces the standard captcha for reCAPTCHA v2
 *
 * @LiveStreet Version: 1.0.4
 * @Plugin Version:	1.1
 * ----------------------------------------------------------------------------
*/

class PluginRecaptcha_HookRecaptcha extends Hook
{
    const ConfigKey = 'recaptcha';
    const HooksArray = [

        // на странице https://imaginaria.ru/registration/
        'template_block_registration_captcha'       =>  'Recaptcha',

        // в модальном окне регистрации
        'template_block_popup_registration_captcha' => 'Recaptcha_modal'
    ];

    /*
     * Регистрация событий на хуки
     */
    public function RegisterHook()
    {
        $plugin_config_key = $this::ConfigKey;
        foreach ($this::HooksArray as $hook => $callback) {
            $this->AddHook(
                $hook,
                $callback,
                __CLASS__,
                Config::Get("plugin.{$plugin_config_key}.hook_priority.{$hook}") ?? 1
            );
        }
    }

    public function Recaptcha()
    {
        $this->Viewer_Assign('site_public_key', Config::Get('plugin.recaptcha.public_key'));
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.recaptcha.tpl');
    }

    public function Recaptcha_modal()
    {
        $this->Viewer_Assign('site_public_key', Config::Get('plugin.recaptcha.public_key'));

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.recaptcha_modal.tpl');
    }
}
