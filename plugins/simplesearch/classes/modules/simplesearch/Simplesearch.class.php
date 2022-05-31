<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

class PluginSimplesearch_ModuleSimplesearch extends Module
{
    protected $oMapper = null;

    // ---

    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    // ---

    public function GetTopicsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        return $this->GetUsingCache(
            array(
                $this->oMapper,
                'GetTopicsByQuery'
            ),
            'topic_update, topic_new',              // cache tags
            60 * 60 * 24 * 7,                       // cache live time - 7 days
            $sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId  // list of parameters
        );
    }

    // ---

    public function GetUsingCache(
        $FunctionToCall = null,
        $aTags = array(),
        $iTime = 3600             // 60 * 60 * 1 == 1 hour
    )
    {
        $Params = array_slice(func_get_args(), 3);

        $QueryHash = '';
        foreach ($Params as $Item) {
            $QueryHash .= serialize($Item);
        }

        if ((is_array($FunctionToCall)) and (isset ($FunctionToCall [1])) and (is_string($FunctionToCall [1]))) {
            $FuncName = $FunctionToCall [1];
        } else {
            $FuncName = (string)$FunctionToCall;
        }
        $Tags = (array)$aTags;
        if (!is_array($aTags)) {
            $Tags = explode(',', str_replace(' ', '', $aTags));
        }
        $Time = (int)$iTime;

        $Cache_Key = $FuncName . '_' . $QueryHash;
        if (false === ($Data = $this->Cache_Get($Cache_Key))) {
            $Data = call_user_func_array($FunctionToCall, $Params);
            if ($Data !== false) {
                $this->Cache_Set($Data, $Cache_Key, $Tags, $Time);
            }
        }
        return $Data;
    }

    // ---

    public function GetCommentsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        return $this->GetUsingCache(
            array(
                $this->oMapper,
                'GetCommentsByQuery'
            ),
            array(
                'comment_new_topic',                  // new comment
                'comment_update_status_topic',        // deleted or published
                'comment_update',                     // general update
                'comment_update_rating_topic'         // when rating of comment was changed
            ),
            60 * 60 * 24 * 7,                       // 7 days
            $sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId
        );
    }

    // ---

    public function GetPeopleByQuery($sQuery, $iPage, $iPerPage)
    {
        return $this->GetUsingCache(
            array(
                $this->oMapper,     // mapper object
                'GetPeopleByQuery'    // function to call
            ),
            'user_new, user_update',      // tags (string or array)
            60 * 60 * 24 * 7,             // cache live time
            $sQuery, $iPage, $iPerPage    // additional parameters that should be set
        );
    }

    // ---

    public function GetBlogsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch)
    {
        return $this->GetUsingCache(
            array(
                $this->oMapper,
                'GetBlogsByQuery'
            ),
            'blog_update, blog_new',                // cache tags
            60 * 60 * 24 * 7,                       // cache live time - 7 days
            $sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch  // list of parameters
        );
    }

    // ---

    public function GetTopicsByTagLikeByQuery($sQuery, $iPage, $iPerPage, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        return $this->GetUsingCache(
            array(
                $this->oMapper,
                'GetTopicsByTagLikeByQuery'
            ),
            'topic_update, topic_new',              // cache tags
            60 * 60 * 24 * 7,                       // cache live time - 7 days
            $sQuery, $iPage, $iPerPage, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId  // list of parameters
        );
    }

    // ---

    // Little wrapper for Cache module, version 2.2
    // Can call user function through cache (check if already cache exists or call function and save result)
    //
    // Usage example:
    // GetUsingCache ('ClassName::FunctionName', 'tag1, tag2', 60 * 60 * 1, 'SELECT * FROM table LIMIT 1', $aParams2);
    // or
    // GetUsingCache (array ($this -> oMapper, 'FunctionName'), 'tag1, tag2', 60 * 60 * 1, 'SELECT * FROM table', $aParam2, $aParam3);
    //
    // (P) PSNet, 2008 - 2013
    // http://psnet.lookformp3.net/
    // http://livestreet.ru/profile/PSNet/
    // http://livestreetcms.com/profile/PSNet/
    // http://livestreetguide.com/developer/PSNet/
    //

    public function GetOpenClosePersonalBlogsByUser($oUser, $aTypes = array('open', 'personal', 'close'))
    {
        //
        // always get open blogs ids
        //
        $aOpenBlogsIds = array();
        if (in_array('open', $aTypes)) {
            $aOpenBlogs = $this->Blog_GetBlogsByFilter(array('type' => 'open'), array(), 1, PHP_INT_MAX, array());
            // get blog ids only from objects
            foreach ($aOpenBlogs ['collection'] as $oBlog) $aOpenBlogsIds [] = $oBlog->getId();
        }

        //
        // get personal blogs ids
        //
        $aPersonalBlogsIds = array();
        if (in_array('personal', $aTypes)) {
            $aPersonalBlogs = $this->Blog_GetBlogsByFilter(array('type' => 'personal'), array(), 1, PHP_INT_MAX, array());
            // get blog ids only from objects
            foreach ($aPersonalBlogs ['collection'] as $oBlog) $aPersonalBlogsIds [] = $oBlog->getId();
        }

        //
        // get open and closed blogs ids for user (needed only to get closed blogs from this list)
        //
        $aClosedBlogsIds = array();
        if (in_array('close', $aTypes)) {
            $aClosedBlogsIds = array_diff(($oUser ? $this->Blog_GetAccessibleBlogsByUser($oUser) : array()), $aOpenBlogsIds);
        }

        //
        // set lists of blogs
        //
        $aOpenCloseBlogsIds = array_merge($aOpenBlogsIds, $aClosedBlogsIds);
        return array(
            'open' => $aOpenBlogsIds,
            'personal' => $aPersonalBlogsIds,
            'close' => $aClosedBlogsIds,
            'openclose' => $aOpenCloseBlogsIds,
        );
    }

}
