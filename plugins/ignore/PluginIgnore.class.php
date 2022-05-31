<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

if (!class_exists('Plugin')) {
	die('Hacking attempt!');
}

class PluginIgnore extends Plugin {

    public $aInherits = array(
        'action' => array(
            'ActionAjax',
            'ActionSettings',
        ),
        'module' => array(
			'ModuleComment',
			'ModuleTalk',
			'ModuleTopic',
        ),
        'mapper' => array(
            'ModuleComment_MapperComment',
            'ModuleTopic_MapperTopic',
            'ModuleUser_MapperUser',
        ),
        'entity' => array(
            'ModuleUser_EntityUser'
        ),
    );
    
	// Активация плагина
	public function Activate() {
        $this->Cache_Clean();
        if (!$this->isTableExists('prefix_ignore')) {
            $resutls = $this->ExportSQL(dirname(__FILE__) . '/sql/install.sql');
            if (!$resutls['result'])
                return false;
        }
        if (!$this->isTableExists('prefix_ignore_target')) {
            $resutls = $this->ExportSQL(dirname(__FILE__) . '/sql/convert_1.0.0_to_1.1.0.sql');
            if (!$resutls['result'])
                return false;
        }
        if (!$this->isFieldExists('prefix_ignore', 'ignore_extra')) {
            $resutls = $this->ExportSQL(dirname(__FILE__) . '/sql/convert_1.1.0_to_1.2.0.sql');
            if (!$resutls['result'])
                return false;
            $this->Database_GetConnect()->query("UPDATE `".Config::Get('db.table.prefix')."ignore` SET `ignore_extra` = 'a:2:{s:14:\"comment_rating\";i:8;s:12:\"profile_show\";i:0;}';");
        }
        return true;
	}
    
	// Деактивация плагина
	public function Deactivate(){
		//$this->ExportSQL(dirname(__FILE__).'/sql/uninstall.sql');
        $this->Cache_Clean();
		return true;
    }

	public function Init() {
        if (class_exists('MobileDetect') && MobileDetect::IsMobileTemplate()) {
            return;
        }
        LS::GetInstance()->Lang_AddLangJs(array(
            'plugin.ignore.js_ignore_block_comments',
            'plugin.ignore.js_ignore_post_my_wall',
            'plugin.ignore.js_ignore_reply_my_comment',
            'plugin.ignore.js_ignore_hide_me_comments',
            'plugin.ignore.js_ignore_post_comment_my_topic',
        ));
		$this->Viewer_AppendScript(Plugin::GetTemplatePath('PluginIgnore')."js/ignore.js");
		$this->Viewer_AppendStyle ( Plugin::GetTemplateWebPath ( 'PluginIgnore' ) . 'css/ignore.css' );
	}
}


