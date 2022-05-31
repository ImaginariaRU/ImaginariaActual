<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

class PluginSimplesearch_ModuleSimplesearch_MapperSimplesearch extends Mapper
{

    public function GetTopicsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        $sql = '
      SELECT DISTINCT t.topic_id,
      CASE
        {WHEN ((LOWER(t.topic_title) REGEXP ?) AND (LOWER(tc.topic_text) REGEXP ?)) THEN 6} -- strict
        {WHEN (LOWER(t.topic_title) REGEXP ?) THEN 5}
        {WHEN (LOWER(tc.topic_text) REGEXP ?) THEN 4}
        {WHEN ((LOWER(t.topic_title) REGEXP ?) AND (LOWER(tc.topic_text) REGEXP ?)) THEN 3} -- not strict
        {WHEN (LOWER(t.topic_title) REGEXP ?) THEN 2}
        {WHEN (LOWER(tc.topic_text) REGEXP ?) THEN 1}
        WHEN 1 = 1 THEN 0 -- for compability
      END AS weight
      FROM
        ?# AS t,
        ?# AS tc,
        ?# AS b{,
        ?# AS f}
      WHERE
        t.topic_publish = 1
      AND
        t.topic_id = tc.topic_id
      AND
        t.blog_id = b.blog_id
      {AND
        f.user_id = ?d
      AND
        f.target_publish = 1
      AND
        f.target_type = "topic"
      AND
        f.target_id = t.topic_id}
      AND
        (
          (b.blog_type IN (?a) {AND b.blog_id = ?d})
          OR
          (b.blog_type IN (?a) AND b.blog_id IN (?a))
        )
      AND -- base search conditions
        (
          (LOWER(t.topic_title) REGEXP ?) -- strict
          OR
          (LOWER(tc.topic_text) REGEXP ?)
          {OR
          (LOWER(t.topic_title) REGEXP ?)} -- not strict
          {OR
          (LOWER(tc.topic_text) REGEXP ?)}
        )
      ORDER BY
        ' . (isset ($sSubQuery) ? 'weight DESC, ' : '')
            . 't.' . implode(', t.', Config::Get('plugin.simplesearch.Topics_Order')) . '
      LIMIT ?d, ?d
    ';
        // maybe use LEFT JOIN for other topic types if them doesnt have content?
        $iTotalCount = 0;
        $aTopicsIds = array();
        $aMatching = array();

        if ($aResult = $this->oDb->selectPage(
            $iTotalCount,
            $sql,
            // relevance (weight)
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // (title AND       // strict
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // text)
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // title
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // text

            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // (title AND       // not strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // text)
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // title
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // text
            // tables list
            Config::Get('db.table.topic'),
            Config::Get('db.table.topic_content'),
            Config::Get('db.table.blog'),
            // if search in favorite list selected
            ($iFavoriteUserId ? Config::Get('db.table.favourite') : DBSIMPLE_SKIP),
            ($iFavoriteUserId ? $iFavoriteUserId : DBSIMPLE_SKIP),
            // public blog types list
            array_merge(Config::Get('plugin.simplesearch.Allowed_Blog_Types'), (array)($iBlogIdForSearch ? 'close' : null)),
            // if search in one blog selected
            ($iBlogIdForSearch ? $iBlogIdForSearch : DBSIMPLE_SKIP),
            // search in "close" blog type
            !empty ($aClosedBlogsIdsForCurrentUser) ? array('close') : array(null),
            // "close" blog type ids
            !empty ($aClosedBlogsIdsForCurrentUser) ? $aClosedBlogsIdsForCurrentUser : array(null),
            // base search conditions vars setup
            $sQuery,                                            // title            // strict
            $sQuery,                                            // text
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // title            // not strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // text
            // limit
            ($iPage - 1) * $iPerPage,
            $iPerPage
        )
        ) {
            foreach ($aResult as $curTopic) {
                $aTopicsIds [] = $curTopic ['topic_id'];
                $aMatching [] = $curTopic ['weight'];
            }
        }
        return array(
            'result' => $aTopicsIds,
            'count' => $iTotalCount,
            'matches' => $aMatching
        );
    }

    // ---

    public function GetCommentsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        $sql = '
      SELECT DISTINCT c.comment_id,
      CASE
        {WHEN (LOWER(c.comment_text) REGEXP ?) THEN 4} -- strict
        {WHEN (LOWER(c.comment_text) REGEXP ?) THEN 1} -- not strict
        WHEN 1 = 1 THEN 0 -- for compability
      END AS weight
      FROM
        ?# AS c,
        ?# AS b{,
        ?# AS f}
      WHERE
        c.comment_delete = 0
      AND
        c.comment_publish = 1
      AND
        c.target_type = "topic"
      AND
        c.target_parent_id = b.blog_id
      {AND
        f.user_id = ?d
      AND
        f.target_publish = 1
      AND
        f.target_type = "comment"
      AND
        f.target_id = c.comment_id}
      AND
        (
          (b.blog_type IN (?a) {AND b.blog_id = ?d})
          OR
          (b.blog_type IN (?a) AND b.blog_id IN (?a))
        )
      AND -- base search conditions
        (
          (LOWER(c.comment_text) REGEXP ?) -- strict
          {OR
          (LOWER(c.comment_text) REGEXP ?)} -- not strict
        )
      ORDER BY
        ' . (isset ($sSubQuery) ? 'weight DESC, ' : '')
            . 'c.' . implode(', c.', Config::Get('plugin.simplesearch.Comments_Order')) . '
      LIMIT ?d, ?d
    ';
        $iTotalCount = 0;
        $aCommentsIds = array();
        $aMatching = array();

        if ($aResult = $this->oDb->selectPage(
            $iTotalCount,
            $sql,
            // relevance (weight)
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // not strict
            // tables list
            Config::Get('db.table.comment'),
            Config::Get('db.table.blog'),
            // if search in favorite list selected
            ($iFavoriteUserId ? Config::Get('db.table.favourite') : DBSIMPLE_SKIP),
            ($iFavoriteUserId ? $iFavoriteUserId : DBSIMPLE_SKIP),
            // public blog types list
            array_merge(Config::Get('plugin.simplesearch.Allowed_Blog_Types'), (array)($iBlogIdForSearch ? 'close' : null)),
            // if search in one blog selected
            ($iBlogIdForSearch ? $iBlogIdForSearch : DBSIMPLE_SKIP),
            // search in "close" blog type
            !empty ($aClosedBlogsIdsForCurrentUser) ? array('close') : array(null),
            // "close" blog type ids
            !empty ($aClosedBlogsIdsForCurrentUser) ? $aClosedBlogsIdsForCurrentUser : array(null),
            // base search conditions vars setup
            $sQuery,                                            // strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // not strict
            // limit
            ($iPage - 1) * $iPerPage,
            $iPerPage
        )
        ) {
            foreach ($aResult as $curComment) {
                $aCommentsIds [] = $curComment ['comment_id'];
                $aMatching [] = $curComment ['weight'];
            }
        }
        return array(
            'result' => $aCommentsIds,
            'count' => $iTotalCount,
            'matches' => $aMatching
        );
    }

    // ---

    public function GetPeopleByQuery($sQuery, $iPage, $iPerPage)
    {
        $sql = '
      SELECT user_id,
      CASE
        WHEN (LOWER(user_login) REGEXP ?) THEN 1
        WHEN (LOWER(user_profile_name) REGEXP ?) THEN 2
        WHEN (LOWER(user_profile_country) REGEXP ?) THEN 3
        WHEN (LOWER(user_profile_city) REGEXP ?) THEN 4
        WHEN (LOWER(user_profile_about) REGEXP ?) THEN 8
        WHEN 1 = 1 THEN 0
      END AS matchnum
      FROM
        ?#
      WHERE
        user_activate = 1
      AND
        (
          (LOWER(user_login) REGEXP ?)
          OR
          (LOWER(user_profile_name) REGEXP ?)
          OR
          (LOWER(user_profile_country) REGEXP ?)
          OR
          (LOWER(user_profile_city) REGEXP ?)
          OR
          (LOWER(user_profile_about) REGEXP ?)
        )
      ORDER BY
        ' . implode(', ', Config::Get('plugin.simplesearch.People_Order')) . '
      LIMIT ?d, ?d
    ';
        $iTotalCount = 0;
        $aPeopleIds = array();
        $aMatching = array();

        if ($aResult = $this->oDb->selectPage(
            $iTotalCount,
            $sql,
            $sQuery,
            $sQuery,
            $sQuery,
            $sQuery,
            $sQuery,
            // tables list
            Config::Get('db.table.user'),

            $sQuery,
            $sQuery,
            $sQuery,
            $sQuery,
            $sQuery,
            // limit
            ($iPage - 1) * $iPerPage,
            $iPerPage
        )
        ) {
            foreach ($aResult as $curUser) {
                $aPeopleIds [] = $curUser ['user_id'];
                $aMatching [] = $curUser ['matchnum'];
            }
        }
        return array(
            'result' => $aPeopleIds,
            'count' => $iTotalCount,
            'matches' => $aMatching
        );
    }

    // ---

    public function GetBlogsByQuery($sQuery, $iPage, $iPerPage, $sSubQuery, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch)
    {
        $sql = '
      SELECT DISTINCT b.blog_id,
      CASE
        {WHEN ((LOWER(b.blog_title) REGEXP ?) AND (LOWER(b.blog_description) REGEXP ?)) THEN 6} -- strict
        {WHEN (LOWER(b.blog_title) REGEXP ?) THEN 5}
        {WHEN (LOWER(b.blog_description) REGEXP ?) THEN 4}
        {WHEN ((LOWER(b.blog_title) REGEXP ?) AND (LOWER(b.blog_description) REGEXP ?)) THEN 3} -- not strict
        {WHEN (LOWER(b.blog_title) REGEXP ?) THEN 2}
        {WHEN (LOWER(b.blog_description) REGEXP ?) THEN 1}
        WHEN 1 = 1 THEN 0 -- for compability
      END AS weight
      FROM
        ?# AS b
      WHERE
        (
          (b.blog_type IN (?a) {AND b.blog_id = ?d})
          OR
          (b.blog_type IN (?a) AND b.blog_id IN (?a))
        )
      AND -- base search conditions
        (
          (LOWER(b.blog_title) REGEXP ?) -- strict
          OR
          (LOWER(b.blog_description) REGEXP ?)
          {OR
          (LOWER(b.blog_title) REGEXP ?)} -- not strict
          {OR
          (LOWER(b.blog_description) REGEXP ?)}
        )
      ORDER BY
        ' . (isset ($sSubQuery) ? 'weight DESC, ' : '')
            . 'b.' . implode(', b.', Config::Get('plugin.simplesearch.Blogs_Order')) . '
      LIMIT ?d, ?d
    ';
        $iTotalCount = 0;
        $aBlogsIds = array();
        $aMatching = array();

        if ($aResult = $this->oDb->selectPage(
            $iTotalCount,
            $sql,
            // relevance (weight)
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // (title AND       // strict
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // description)
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // title
            (isset ($sSubQuery) ? $sQuery : DBSIMPLE_SKIP),     // description

            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // (title AND       // not strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // description)
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // title
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // description
            // tables list
            Config::Get('db.table.blog'),
            // public blog types list
            array_merge(
                array_diff(Config::Get('plugin.simplesearch.Allowed_Blog_Types'), array('personal')),      // ATT! w/o personal blog type
                (array)($iBlogIdForSearch ? 'close' : null)
            ),
            // if search in one blog selected
            ($iBlogIdForSearch ? $iBlogIdForSearch : DBSIMPLE_SKIP),
            // search in "close" blog type
            !empty ($aClosedBlogsIdsForCurrentUser) ? array('close') : array(null),
            // "close" blog type ids
            !empty ($aClosedBlogsIdsForCurrentUser) ? $aClosedBlogsIdsForCurrentUser : array(null),
            // base search conditions vars setup
            $sQuery,                                            // title            // strict
            $sQuery,                                            // description
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // title            // not strict
            (isset ($sSubQuery) ? $sSubQuery : DBSIMPLE_SKIP),  // description
            // limit
            ($iPage - 1) * $iPerPage,
            $iPerPage
        )
        ) {
            foreach ($aResult as $curBlog) {
                $aBlogsIds [] = $curBlog ['blog_id'];
                $aMatching [] = $curBlog ['weight'];
            }
        }
        return array(
            'result' => $aBlogsIds,
            'count' => $iTotalCount,
            'matches' => $aMatching
        );
    }

    // ---

    public function GetTopicsByTagLikeByQuery($sQuery, $iPage, $iPerPage, $aClosedBlogsIdsForCurrentUser, $iBlogIdForSearch, $iFavoriteUserId)
    {
        $sql = '
      SELECT DISTINCT tt.topic_id
      FROM
        ?# as tt,
        ?# AS b{,
        ?# AS f}
      WHERE
        tt.topic_tag_text LIKE ?
      AND
        tt.blog_id = b.blog_id
      {AND
        f.user_id = ?d
      AND
        f.target_publish = 1
      AND
        f.target_type = "topic"
      AND
        f.target_id = tt.topic_id}
      AND
        (
          (b.blog_type IN (?a) {AND b.blog_id = ?d})
          OR
          (b.blog_type IN (?a) AND b.blog_id IN (?a))
        )
      ORDER BY
        tt.topic_id DESC
      LIMIT ?d, ?d
    ';
        $iTotalCount = 0;
        $aTopicsIds = array();

        if ($aResult = $this->oDb->selectPage(
            $iTotalCount,
            $sql,
            // tables list
            Config::Get('db.table.topic_tag'),
            Config::Get('db.table.blog'),
            // if search in favorite list selected
            ($iFavoriteUserId ? Config::Get('db.table.favourite') : DBSIMPLE_SKIP),
            '%' . $sQuery . '%',
            // if search in favorite list selected # 2nd part, ATT! splitted into two parts with search query between
            ($iFavoriteUserId ? $iFavoriteUserId : DBSIMPLE_SKIP),
            // public blog types list
            array_merge(Config::Get('plugin.simplesearch.Allowed_Blog_Types'), (array)($iBlogIdForSearch ? 'close' : null)),
            // if search in one blog selected
            ($iBlogIdForSearch ? $iBlogIdForSearch : DBSIMPLE_SKIP),
            // search in "close" blog type
            !empty ($aClosedBlogsIdsForCurrentUser) ? array('close') : array(null),
            // "close" blog type ids
            !empty ($aClosedBlogsIdsForCurrentUser) ? $aClosedBlogsIdsForCurrentUser : array(null),
            // limit
            ($iPage - 1) * $iPerPage,
            $iPerPage
        )
        ) {
            foreach ($aResult as $curTopic) {
                $aTopicsIds [] = $curTopic ['topic_id'];
            }
        }
        return array(
            'result' => $aTopicsIds,
            'count' => $iTotalCount
        );
    }

}
