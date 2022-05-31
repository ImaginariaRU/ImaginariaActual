<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleStream extends PluginDelayedpost_Inherit_ModuleStream
{

    /**
     * @param $iUserId
     * @param $sEventType
     * @param $iTargetId
     * @param int $iPublish
     */
    public function Write($iUserId, $sEventType, $iTargetId, $iPublish = 1)
    {

        if ($sEventType == 'add_topic') {
            $oTopic = $this->Topic_GetTopicById($iTargetId);
            $sDateAdd = $oTopic->getDateAdd();
        } else $sDateAdd = date("Y-m-d H:i:s");

        if ($oEvent = $this->GetEventByTarget($sEventType, $iTargetId)) {
            /**
             * Событие уже было
             */
            if ($oEvent->getPublish() != $iPublish) {
                $oEvent->setPublish($iPublish);
                $this->UpdateEvent($oEvent);
            }
        } elseif ($iPublish) {
            /**
             * Создаем новое событие
             */
            $oEvent = Engine::GetEntity('Stream_Event');
            $oEvent->setEventType($sEventType);
            $oEvent->setUserId($iUserId);
            $oEvent->setTargetId($iTargetId);
            $oEvent->setDateAdded($sDateAdd);
            $oEvent->setPublish($iPublish);
            $this->AddEvent($oEvent);
        }
    }

}
