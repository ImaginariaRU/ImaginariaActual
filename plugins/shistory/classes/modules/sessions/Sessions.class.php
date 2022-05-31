<?php

class PluginSHistory_ModuleSessions extends Module
{

    protected $oMapper;

    function init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
        return true;
    }

    function update($user)
    {
        $sessionData = $user->getSession();
        $skey = $sessionData->getSessionKey();
        $ip = $sessionData->getSessionIpLast();

        $aDate = explode(' ', $sessionData->getSessionDateLast());

        if (!$this->oMapper->isCurrent($sessionData->getUserId(), $sessionData->getSessionKey())) {
            $this->oMapper->addCurrent(
                $sessionData->getUserId(),
                $sessionData->getSessionKey(),
                $aDate[0],
                $aDate[1],
                $this->getUserOs(),
                $this->getUserBrowser(),
                $sessionData->getSessionIpLast()
            );
        }
    }

    function getUserOs()
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win')) return "Windows";
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Linux')) return "Linux";
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Unix')) return "Unix";
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac')) return "Macintosh";
        else return "Unknown";
    }

    function getUserBrowser()
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox')) return 'firefox';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome')) return 'chrome';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Safari')) return 'safari';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Opera')) return 'opera';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) return 'ie6';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) return 'ie7';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) return 'ie8';
        elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) return 'ie9';
        else return 'unknown';
    }

    function getSessionsRows($user_id)
    {
        return $this->oMapper->getHistoryRows($user_id);
    }
}
