<?php
/**
 * Attachments plugin
 * (P) Rafrica.net Studio, 2010 - 2012
 *
 * (C) Rewrite by Karel Wintersky
 */

class PluginAttachments_ModuleAttachments_EntityTopic extends PluginAttachments_Inherit_ModuleTopic_EntityTopic
{
    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
    }

    /**
     * @param $sExtension
     * @return mixed
     */
    public function getAttachmentsByExtension($sExtension)
    {
        return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
    }
}
