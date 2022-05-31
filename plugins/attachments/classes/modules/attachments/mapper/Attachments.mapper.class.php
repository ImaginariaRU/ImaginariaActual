<?php
/**
 * Attachments plugin
 * (P) Rafrica.net Studio, 2010 - 2012
 *
 * (C) Rewrite by Karel Wintersky
 */

class PluginAttachments_ModuleAttachments_MapperAttachments extends Mapper
{

    /**
     * @param $sDestFullPath
     * @param $sName
     * @param $iFileSize
     * @param $sExtension
     * @param $iTopicId
     * @param $iFormId
     * @param $iUserId
     * @return mixed
     */
    public function AttachFile($sDestFullPath, $sName, $iFileSize, $sExtension, $iTopicId, $iFormId, $iUserId)
    {
        $sql = "
            INSERT INTO `attachments` (
			`attachment_id` ,
			`topic_id` ,
			`user_id` ,
			`attachment_url`,
			`attachment_name`,
			`attachment_size`,
			`attachment_extension`,
			`attachment_form_id`
		)
		VALUES ( NULL , ?d, ?d, ?, ?, ?d, ?, ?);";

        return $this->oDb->query($sql, $iTopicId, $iUserId, $sDestFullPath, $sName, $iFileSize, $sExtension, $iFormId);
    }

    /**
     * @param $iTopicId
     * @return array
     */
    public function GetAttachedFilesByTopicId($iTopicId)
    {
        $sql = "SELECT * FROM attachments WHERE topic_id = ?d AND topic_id <> ''";
        if ($aRows = $this->oDb->select($sql, $iTopicId)) {
            return $aRows;
        }
        return array();
    }

    /**
     * @param $iFormId
     * @return bool
     */
    public function GetAttachedFilesByFormId($iFormId)
    {
        $sql = "SELECT * FROM attachments WHERE attachment_form_id = ? AND attachment_form_id <> ''";
        if ($aRows = $this->oDb->select($sql, $iFormId)) {
            return $aRows;
        }
        return false;
    }

    /**
     * @param $iTopicId
     * @param $sExtension
     * @return array
     */
    public function GetAttachedFilesByExtensionByTopicId($iTopicId, $sExtension)
    {
        $sql = "SELECT * FROM attachments WHERE topic_id = ?d AND attachment_extension = ?";
        if ($aRows = $this->oDb->select($sql, $iTopicId, $sExtension)) {
            return $aRows;
        } 
        return array();
    }

    /**
     * @param $iFileId
     * @return bool
     */
    public function GetFilePathById($iFileId)
    {
        $sql = "SELECT attachment_url FROM attachments WHERE attachments.attachment_id = ?d";
        if ($aRows = $this->oDb->select($sql, $iFileId)) {
            return $aRows;
        }
        return false;
    }

    /**
     * @param $iFileId
     * @return bool
     */
    public function GetFileNameById($iFileId)
    {
        $sql = "SELECT attachment_name FROM attachments WHERE attachments.attachment_id = ?d";
        if ($aRows = $this->oDb->select($sql, $iFileId)) {
            return $aRows;
        }
        return false;

    }

    /**
     * @param $iFileId
     * @return bool
     */
    public function GetFileById($iFileId)
    {
        $sql = "SELECT * FROM attachments WHERE attachments.attachment_id = ?d";
        if ($aRows = $this->oDb->select($sql, $iFileId)) {
            return $aRows;
        } 
        return false;
    }

    /**
     * @param $iFileId
     * @return mixed
     */
    public function DeleteFileById($iFileId)
    {
        $sql = "DELETE FROM attachments WHERE attachments.attachment_id = ?d LIMIT 1";
        return $this->oDb->query($sql, $iFileId);
    }

    /**
     * @param $iFormId
     * @param $iTopicId
     * @return mixed
     */
    public function LinkFormIdToTopicId($iFormId, $iTopicId)
    {
        $sql = "UPDATE attachments SET `topic_id` = ?d, `attachment_form_id` = '' WHERE  attachment_form_id = ?";
        return $this->oDb->query($sql, $iTopicId, $iFormId);
    }

    /**
     * @param $iTopicId
     * @param $iUserId
     * @return mixed
     */
    public function LinkFilesToUserTopic($iTopicId, $iUserId)
    {
        $sql = "UPDATE attachments SET `topic_id` = ?d, `attachment_form_id` = '' WHERE  attachment_form_id <> '' AND user_id = ?d";
        return $this->oDb->query($sql, $iTopicId, $iUserId);
    }

    /**
     * @param $iUserId
     * @return bool
     */
    public function GetUnlinkedAttachmentsByUserId($iUserId)
    {
        $sql = "SELECT * FROM attachments WHERE user_id = ?d AND topic_id = 0";
        if ($aRows = $this->oDb->select($sql, $iUserId)) {
            return $aRows;
        } 
        return false;
    }

    /**
     * @param $iFileId
     * @param $iFormId
     * @return mixed
     */
    public function LinkFileToFormId($iFileId, $iFormId)
    {
        $sql = "UPDATE attachments SET `attachment_form_id` = ? WHERE attachment_id = ?d";
        return $this->oDb->query($sql, $iFormId, $iFileId);
    }
}
