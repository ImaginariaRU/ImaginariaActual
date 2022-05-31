<?php
/**
 * Attachments plugin
 * (P) Rafrica.net Studio, 2010 - 2012
 *
 * (C) Rewrite by Karel Wintersky
 */

/**
 * Class PluginAttachments
 */
class PluginAttachments extends Plugin
{

    protected $aInherits = array(
        'entity' => array('ModuleTopic_EntityTopic' => '_ModuleAttachments_EntityTopic')
    );

    public function Activate()
    {
        if (!$this->isTableExists('attachments')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump.sql');
        }

        // check up php settings
        $PostMaxSize = ini_get('post_max_size');
        $UploadMaxFilesize = ini_get('upload_max_filesize');
        $MaxInputTime = ini_get('max_input_time');
        
        $strings_to_inform = [];
        if ($PostMaxSize)
            $strings_to_inform[] = "- Maximum post file size : <strong>{$PostMaxSize} b</strong> (post_max_size)";
        if ($UploadMaxFilesize)
            $strings_to_inform[] = "- Maximum uploaded file size : <strong>{$UploadMaxFilesize}b</strong> (upload_max_filesize)";
        if ($MaxInputTime)
            $strings_to_inform[] = "- Maximum execution time : <strong>{$MaxInputTime} seconds</strong> (max_input_time)";
        
        $strings_to_inform[] = 'Change your server configuration as you need in your <strong>php.ini</strong> file or contact your hoster for more info.';
        $strings_to_inform[] = 'Plugin "Attachments" controls file size after server check up, so first you need to config correctly your php.ini.';
        
        $StringToInform = implode('<br />', $strings_to_inform);

        /*        
        $StringToInform = '';
        $StringToInform .= ($PostMaxSize ? "<br />- Maximum post file size : <strong>{$PostMaxSize} b</strong> (post_max_size)" : '');
        $StringToInform .= ($UploadMaxFilesize ? "<br />- Maximum uploaded file size : <strong>{$UploadMaxFilesize}b</strong> (upload_max_filesize)" : '');
        $StringToInform .= ($MaxInputTime ? "<br />- Maximum execution time : <strong>{$MaxInputTime} seconds</strong> (max_input_time)" : '');

        $StringToInform .= '<br />Change your server configuration as you need in your <strong>php.ini</strong> file or contact your hoster for more info.';
        $StringToInform .= '<br />Plugin "Attachments" controls file size after server check up, so first you need to config correctly your php.ini.';
*/

        if ($StringToInform) {
            $this->Message_AddNoticeSingle($StringToInform, 'Attention! Your server configuration allows ', true);
        }

        return true;
    }

    public function Deactivate()
    {
        return true;
    }

    public function Init()
    {
    }

}
