<?php
/**
 * Attachments plugin
 * (P) Rafrica.net Studio, 2010 - 2012
 *
 * (C) Rewrite by Karel Wintersky
 */

class PluginAttachments_ActionAttachments extends ActionPlugin
{
    protected $oUserCurrent = null;

    /**
     *
     */
    public function Init()
    {
        if ($this->User_IsAuthorization()) {
            $this->oUserCurrent = $this->User_GetUserCurrent();
        }
        $this->SetDefaultEvent('receive');

    }

    /**
     *
     */
    public function EventShutdown()
    {
        $this->Viewer_Assign('oUserCurrent', $this->oUserCurrent);
    }

    /**
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('receive', 'EventReceive');
        $this->AddEvent('get', 'EventGet');
        $this->AddEvent('delete', 'EventDelete');
        $this->AddEvent('link', 'EventLink');

        $this->AddEvent('debug', 'EventDebug');
        $this->AddEvent('debugreceiver', 'EventDebugReceiver');
    }

    /**
     * @return mixed
     */
    protected function CheckErrorStatus()
    {
        return $this->PluginAttachments_Attachments_CheckErrorStatus();
    }

    /**
     *
     */
    protected function EventLink()
    {
        $this->Viewer_SetResponseAjax('json');
        $iFileId = $this->getParam(0);
        $iFormId = $this->getParam(1);

        if ((!empty($iFileId)) and (!empty($iFormId))) {
            $this->PluginAttachments_Attachments_LinkFileToFormId($iFileId, $iFormId);
        } else {
            $this->Message_AddErrorSingle('error: empty fileid or formid', '');
        }
    }

    /**
     *
     */
    protected function EventReceive()
    {
        if (!$this->CheckParams('upload')) die($this->Lang_Get('plugin.attachments.upload_universal_answer'));

        $sResult = 'Null';
        $iTopicId = getRequest('topic_id');
        $iFormId = getRequest('form_id');

	$iId = null;
        if (isset($_FILES['newfile']) and is_uploaded_file($_FILES['newfile']['tmp_name'])) {
            if ($this->CheckFile() == true) {
                $sFileName = $_FILES['newfile']['name'];
                $sTmpName = $_FILES['newfile']['tmp_name'];
                $iFileSize = $_FILES['newfile']['size'];
                
                $sFileName_parts = explode(".", $sFileName); // only variables should be passed by reference in PHP7
                $sExtension = strtolower(array_pop($sFileName_parts));
                
                $iUserId = $this->oUserCurrent->getId();

                $iId = $this->PluginAttachments_Attachments_AttachFile($sTmpName, $sFileName, $iFileSize, $sExtension, $iTopicId, $iFormId, $iUserId);
                if ($iId) 
                {
                    $sResult = 'success';
                } else {
                    $sResult = $this->ShowErrors();
                }

            } else {
                $sResult = $this->ShowErrors();
            }
        } else {
            print $this->Lang_Get('plugin.attachments.upload_universal_answer');
        }
        $this->SetDebug($sResult);

        $this->Viewer_Assign('Attachments_Last_Uploaded_File_ID', $iId);
        $this->Viewer_Assign('Attachments_Upload_Result', $sResult);
        $this->SetTemplateAction('upload');
    }

    /**
     * @param $sMode
     * @return bool
     */
    protected function CheckParams($sMode)
    {
        if (!$this->oUserCurrent) {
            $this->SetError($this->Lang_Get('plugin.attachments.upload_not_logged_in'), 1, 1);
            return false;
        }

        $this->SetDebug($sMode);

        if ($sMode == 'upload') {
            $iTopicId = getRequest('topic_id');
        } elseif ($sMode == 'delete') {
            $iFileId = $this->getParam(0);
            if ($aFile = $this->PluginAttachments_Attachments_GetFileById($iFileId)) {
                $iTopicId = $aFile['topic_id'];
            } else return false;
        }

        $iFormId = getRequest('form_id');
        if (!empty($iTopicId)) {
            if (!$oTopic = $this->Topic_GetTopicById($iTopicId)) {
                $this->SetError('t: ' . $iTopicId, 1, 1);
                return false;
            }
        } elseif (!empty($iFormId)) {
            $this->SetDebug($iFormId);
        } else {
            if (empty($iFileId)) return false;
        }

        return true;

    }

    /**
     * @param $sMessage
     * @param int $iToShow
     * @param int $iToLog
     */
    protected function SetError($sMessage, $iToShow = 0, $iToLog = 1)
    {
        $this->PluginAttachments_Attachments_SetError($sMessage, $iToShow, $iToLog);
    }

    /**
     * @param $sMessage
     */
    protected function SetDebug($sMessage)
    {
        $this->PluginAttachments_Attachments_SetDebug($sMessage);
    }

    /**
     * @return bool
     */
    protected function CheckFile()
    {
        if ($_FILES['newfile']['error'] != 0) {
            switch ($_FILES['newfile']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_ini_size'), 1, 1);
                        break;
                    }
                case UPLOAD_ERR_FORM_SIZE:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_form_size'), 1, 1);
                        break;
                    }
                case UPLOAD_ERR_PARTIAL:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_partial'), 1, 1);
                        break;
                    }
                case UPLOAD_ERR_NO_FILE:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_no_file'), 1, 1);
                        break;
                    }

                case UPLOAD_ERR_NO_TMP_DIR:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_no_tmp_dir'), 1, 1);
                        break;
                    }

                case UPLOAD_ERR_CANT_WRITE:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_cant_write'), 1, 1);
                        break;
                    }

                case UPLOAD_ERR_EXTENSION:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_extension'), 1, 1);
                        break;
                    }
                default:
                    {
                        $this->SetError($this->Lang_Get('plugin.attachments.upload_err_unknown'), 1, 1);
                    }
            }
            return false;
        }
        
        $_max_files_per_topic = Config::Get('plugin.attachments.max_files_per_topic');
        
        $iTopicId = getRequest('topic_id');
        
        if (!empty($iTopicId)) {
            $aFiles = $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($iTopicId);
            
            if (is_countable($aFiles) && (count($aFiles) >= $_max_files_per_topic)) {
                $this->SetError($this->Lang_Get('plugin.attachments.upload_error_topic_if_full'), 1, 1);
                return false;
            }
        }
        
        // unlinked attachments 
        $uFiles = $this->PluginAttachments_Attachments_GetUnlinkedAttachmentsByUserId($this->oUserCurrent->getId());
        $_max_unattached_files_per_user = Config::Get('plugin.attachments.max_unattached_files_per_user');

        if (is_countable($uFiles) && (count($uFiles) >= $_max_unattached_files_per_user)) {
            $this->SetError($this->Lang_Get('plugin.attachments.upload_unattached_limit'), 1, 1);
            return false;
        }

        $iFormId = getRequest('form_id');
        if (!empty($iFormId)) {
            $aFiles = $this->PluginAttachments_Attachments_GetAttachedFilesByFormId($iFormId);
            if (is_countable($aFiles) && (count($aFiles) >= Config::Get('plugin.attachments.max_files_per_topic'))) {
                $this->SetError($this->Lang_Get('plugin.attachments.upload_error_topic_if_full'), 1, 1);
                return false;
            }
        }

        if ($this->oUserCurrent->getRating() < Config::Get('plugin.attachments.min_rating_to_post_files')) {
            $this->SetError($this->Lang_Get('plugin.attachments.upload_rating_to_low'), 1, 1);
            return false;
        }

        if ($_FILES['newfile']['size'] > Config::Get('plugin.attachments.max_filesize_limit')) {
            $this->SetError($this->Lang_Get('plugin.attachments.upload_file_to_big'), 1, 1);
            return false;
        }

        return true;

    }

    /**
     * @param int $iToScreen
     * @return string
     */
    protected function ShowErrors($iToScreen = 0)
    {
        $aErrors = $this->PluginAttachments_Attachments_GetErrors();
        $sResult = '';
        foreach ($aErrors as $sError) {
            if ($iToScreen == 1) print $sError . "\n";
            $sResult .= $sError . ", ";
        }

        return $sResult;
    }

    /**
     * @return string
     */
    protected function EventGet()
    {
        if (!Config::Get('plugin.attachments.ShowAttachedFiles')) {
            if (!$this->oUserCurrent) {
                return Router::Action('error');
            }
            else {
                if (!$this->oUserCurrent->isAdministrator()) {
                    return Router::Action('error');
                }
            }
        }

        $iFileId = $this->getParam(0);
        if ($sFullPath = $this->PluginAttachments_Attachments_GetFilePathById($iFileId)) {
            $sFilename = $this->PluginAttachments_Attachments_GetFileNameById($iFileId);//so sorry
            $sFullPath = Config::Get('path.root.server') . $sFullPath;
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $sFilename . '"');
            print file_get_contents($sFullPath);
        } else {
            print $this->Lang_Get('plugin.attachments.upload_universal_answer');
        }
        
        $this->SetTemplateAction('null');
    }

    /**
     *
     */
    protected function EventDelete()
    {
        $this->Viewer_SetResponseAjax('json');
        if (!$this->CheckParams('delete')) die($this->Lang_Get('plugin.attachments.upload_universal_answer'));

        $iFileId = $this->getParam(0);
        $this->PluginAttachments_Attachments_DeleteFileById($iFileId);
    }

    /**
     *
     */
    protected function EventDebugReceiver()
    {
        if (isset($_FILES['newfile'])) {
            print "Files array is set<br>";
            if (is_uploaded_file($_FILES['newfile']['tmp_name'])) {
                print "File is uploaded<br>";
                if ($this->CheckFile() == true) {
                    print "OK<br>";
                } else print $this->ShowErrors();
            } else print "File is not uploaded<br>";
        } else print "Files array is not set <br>";
        $this->SetTemplateAction('null');
    }

    /**
     * @return string
     */
    protected function EventDebug()
    {
        print "OK";
        if (!$this->oUserCurrent) return Router::Action('error');
        else {
            if (!$this->oUserCurrent->isAdministrator()) return Router::Action('error');
        }
    }
}
