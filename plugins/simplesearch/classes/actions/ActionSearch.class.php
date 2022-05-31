<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

class PluginSimplesearch_ActionSearch extends ActionPlugin
{
    protected $OriginalQuerySafe = null;
    protected $SQL_Search_Query = null;
    protected $SQL_Not_Strict_Sub_Query = null;
    protected $iCurrentPage = 1;
    protected $iPerPage = 20;
    protected $iResultCount = 0;

    protected $oUserCurrent = null;
    protected $aClosedBlogsIdsForCurrentUser = array();                   // for sql query closed blog type ids
    protected $iSearchInOneBlogSelected = null;                            // to show in template and for sql query
    protected $aAccessibleOpenCloseBlogsForCurrentUserObj = array();      // to show in template
    protected $iFavoriteUserId = null;                                     // to select favorite in sql query and to show in template

    // ---

    public function Init()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->SetDefaultEvent('index');
    }

    // ---

    public function EventIndex()
    {
    }

    // ---

    public function EventOpenSearch()
    {
        Router::SetIsShowStats(false);
        $this->Viewer_Assign('sAdminMail', Config::Get('sys.mail.from_email'));
    }

    // ---

    public function EventTopics()
    {
        $this->SetTemplateAction('results');
        if (!Config::Get('plugin.simplesearch.Enable_Topics_Search')) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Topics_Search_Is_Disabled'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }
        if (!$this->PrepareSearchQuery()) return false;

        // make sql query
        $aResult = $this->PluginSimplesearch_Simplesearch_GetTopicsByQuery(
            $this->SQL_Search_Query,
            $this->iCurrentPage,
            $this->iPerPage,
            $this->SQL_Not_Strict_Sub_Query,
            $this->aClosedBlogsIdsForCurrentUser,
            $this->iSearchInOneBlogSelected,
            $this->iFavoriteUserId
        );

        $aTopics = $this->Topic_GetTopicsAdditionalData($aResult ['result']);

        // highlight searched words in texts
        $RegexpForHighlight = $this->BuildPhpRegExpFromSQLExp();

        foreach ($aTopics as $oTopic) {
            $sText = $oTopic->getText();
            $sTextShort = $oTopic->getTextShort();
            $oTopic->setText($this->ReplaceSearchedItems($RegexpForHighlight, $sText));
            $oTopic->setTextShort($this->ReplaceSearchedItems($RegexpForHighlight, $sTextShort));
        }

        $this->Viewer_Assign('aTopics', $aTopics);
        $this->Viewer_Assign('aPaging', $this->MakePaging($aResult ['count']));
        $this->Viewer_Assign('aMatching', $aResult ['matches']);
    }

    // ---

    public function PrepareSearchQuery($bAllowGetStrictFromRequest = false)
    {
        //
        // get input data
        //
        $OriginalSearchString = getRequest('q');
        $bStrictSearch = $bAllowGetStrictFromRequest ? (bool)getRequest('strict') : Config::Get('plugin.simplesearch.Strict_Search_By_Defalut');
        $iBlogIdForSearch = (int)getRequest('blogid');
        $bSearchInFavorite = (bool)getRequest('favorite');

        //
        // base search string convertations
        //

        // if not string given into the URL (php 5.4 Array to String convertion warning)
        if (!is_string($OriginalSearchString)) return false;

        // if encoding was broken - try to recover it
        if (!mb_check_encoding($OriginalSearchString)) {
            $OriginalSearchString = mb_convert_encoding($OriginalSearchString, 'UTF-8', 'auto');
        }

        $OriginalSearchString = trim($OriginalSearchString);
        $OriginalSearchString = mb_strtolower($OriginalSearchString, 'UTF-8');

        //
        // validate full search string with rules
        //

        // remove tags or validate string with text parser
        if (Config::Get('plugin.simplesearch.Strip_All_Tags')) {
            $OriginalSearchString = strip_tags($OriginalSearchString);
        } else {
            $OriginalSearchString = $this->Text_JevixParser($OriginalSearchString);
        }

        // remove not allowed symbols with spaces
        $OriginalSearchString = preg_replace(Config::Get('plugin.simplesearch.Validate_Search_Query_With'), ' ', $OriginalSearchString);

        // replace more than 1 space with one
        $OriginalSearchString = trim(preg_replace('#\s{2,}#', ' ', $OriginalSearchString));

        // check length of parsed request string
        if (!func_check(
            $OriginalSearchString,
            'text',
            Config::Get('plugin.simplesearch.Min_Length_Of_Search_Query'),
            Config::Get('plugin.simplesearch.Max_Length_Of_Search_Query')
        )) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Search_Query_Is_Wrong'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }

        //
        // full search string checking complete, check each word
        //

        // full string is checked, save it
        $this->OriginalQuerySafe = $OriginalSearchString;

        $OriginalSearchArray = explode(' ', $OriginalSearchString);
        $OriginalSearchArray = array_unique($OriginalSearchArray);

        // here we store all words that we tested for correct length
        $NewSearchArray = array();

        foreach ($OriginalSearchArray as $CurrentWord) {
            if (!func_check(
                $CurrentWord,
                'text',
                Config::Get('plugin.simplesearch.Min_Length_Of_Search_Word'),
                Config::Get('plugin.simplesearch.Max_Length_Of_Search_Word')
            )) {
                $this->Message_AddError(
                    $this->Lang_Get('plugin.simplesearch.Search_Word_Is_Wrong', array('word' => $CurrentWord)),
                    $this->Lang_Get('plugin.simplesearch.Error')
                );
            } else {
                $NewSearchArray [] = $CurrentWord;
            }
        }

        //
        // checking length of each word complete, checking dictionaries
        //

        $NewSearchArray = $this->CheckDictionaries($NewSearchArray);

        // if no words to search (all words are not passed checking in previous 2 steps)
        if (count($NewSearchArray) == 0) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Search_Query_Is_Empty'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }

        //
        // making SQL queries
        //

        $SQLQueryArray = array();

        $bAlsoNotStrictSearchAllowed = Config::Get('plugin.simplesearch.Allow_Not_Strict_Search_Additionally');

        // here we will store sub-query for not strict sub search
        $SQLQueryNotStrictString = null;

        if (($bStrictSearch) and (!$bAlsoNotStrictSearchAllowed)) {
            // ONLY strict search
            foreach ($NewSearchArray as $NewWord) {
                $SQLQueryArray [] = '[[:<:]]' . $NewWord . '[[:>:]]';
            }
        } elseif (!$bStrictSearch) {
            // allow ALWAYS not strict
            $SQLQueryArray = $NewSearchArray;
        } else {
            //
            // make STRICT search + NOT STRICT additionally
            //
            // make strict query as default
            foreach ($NewSearchArray as $NewWord) {
                $SQLQueryArray [] = '[[:<:]]' . $NewWord . '[[:>:]]';
            }
            // and also make not strict query
            $SQLQueryNotStrictString = implode('|', $NewSearchArray);
        }

        $SQLQueryString = implode('|', $SQLQueryArray);

        //
        // store SQL queries
        //

        $this->SQL_Search_Query = $SQLQueryString;
        $this->SQL_Not_Strict_Sub_Query = $SQLQueryNotStrictString;

        //
        // get current page and results per page
        //

        if (!$this->iCurrentPage = intval(preg_replace('#^page(\d+)$#', '\1', $this->getParam(0)))) {
            $this->iCurrentPage = 1;
        }
        $this->iPerPage = Config::Get('plugin.simplesearch.Results_Per_Page');

        //
        // --- Blogs selectors ---
        //

        // Check up user and get accessible blogs for this user
        $aBlogsData = $this->PluginSimplesearch_Simplesearch_GetOpenClosePersonalBlogsByUser($this->oUserCurrent);

        // set lists of blogs for sql query
        $this->aClosedBlogsIdsForCurrentUser = $aBlogsData ['close'];
        $aOpenCloseBlogsIds = $aBlogsData ['openclose'];

        // if search only in one blog selected
        if ($iBlogIdForSearch and in_array($iBlogIdForSearch, $aOpenCloseBlogsIds)) {
            $this->iSearchInOneBlogSelected = $iBlogIdForSearch;
            $this->aClosedBlogsIdsForCurrentUser = array();          // disable closed blog type search in second sql condition
        }

        // set list of blogs for template
        $this->aAccessibleOpenCloseBlogsForCurrentUserObj = $this->Blog_GetBlogsAdditionalData($aOpenCloseBlogsIds, array());

        //
        // --- Check favorite flag ---
        //
        if ($bSearchInFavorite and $this->oUserCurrent) {
            $this->iFavoriteUserId = $this->oUserCurrent->getId();
        }

        return true;
    }

    // ---

    private function CheckDictionaries($aWords)
    {
        if (!Config::Get('plugin.simplesearch.Allow_Checking_Dictionaries')) return $aWords;
        // first check stopwords (remove them from array)
        if ($aStopWords = $this->GetDictionaryFileContent('stopwords.txt')) {
            $aWords = array_diff($aWords, $aStopWords);
        }
        // find word forms and add into original array aliases
        if ($aWordsForms = $this->GetDictionaryFileContent('wordforms.txt')) {
            $aWordsFormsAssoc = array();
            // split words associations from file into associative array
            foreach ($aWordsForms as $sWord) {
                if (strpos($sWord, '#') !== false) continue;                        // if this is just comment
                $aParts = array_map('trim', explode('>', $sWord, 2));
                $aWordsFormsAssoc [$aParts [0]] = $aParts [1];
            }
            // find matched words in search query and splitted array from file
            $aFoundWords = array_intersect($aWords, array_keys($aWordsFormsAssoc));
            // add new synonyms
            foreach ($aFoundWords as $sFoundWord) {
                $aWords [] = $aWordsFormsAssoc [$sFoundWord];
            }
        }
        return $aWords;
    }

    // ---

    private function GetDictionaryFileContent($sFilename)
    {
        if (($sWords = @file_get_contents(Config::Get('path.root.server') . '/plugins/simplesearch/dictionaries/' . $sFilename)) !== false) {
            return preg_split('#\r\n|\r|\n#iuU', $sWords, -1, PREG_SPLIT_NO_EMPTY);
        }
        return false;
    }

    // ---

    public function BuildPhpRegExpFromSQLExp()
    {
        $NewRegexp = str_replace('[[:<:]]', Config::Get('plugin.simplesearch.Highlight_Words_Border'), $this->SQL_Search_Query);
        $NewRegexp = str_replace('[[:>:]]', Config::Get('plugin.simplesearch.Highlight_Words_Border'), $NewRegexp);
        $NewRegexp = '#' . $NewRegexp . '#iuUS';
        return $NewRegexp;
    }

    // ---

    public function ReplaceSearchedItems($RegExp, $sTextIn)
    {
        // get powered spoiler tag
        $sPSTag = Config::Get('plugin.poweredspoiler.Tag_Name') ? '<' . Config::Get('plugin.poweredspoiler.Tag_Name') . '>' : '';
        // merge allowed tags together
        $sAllowedTags = $sPSTag . '<br><br/>';  // and so on
        $sText = preg_replace(
            $RegExp,
            Config::Get('plugin.simplesearch.Highlight_Wrapper_Before') .
            '$0' .
            Config::Get('plugin.simplesearch.Highlight_Wrapper_After'),
            strip_tags($sTextIn, $sAllowedTags)
        );
        return $sText;
    }

    // ---

    private function MakePaging($iCount)
    {
        $this->iResultCount = $iCount;
        return $this->Viewer_MakePaging(
            $iCount,
            $this->iCurrentPage,
            $this->iPerPage,
            Config::Get('pagination.pages.count'),
            Router::GetPath('search') . strtolower(Router::GetActionEvent()),
            array(
                'q' => $this->OriginalQuerySafe,
                'blogid' => $this->iSearchInOneBlogSelected,
                'favorite' => (bool)$this->iFavoriteUserId
            )
        );
    }

    // ---

    public function EventComments()
    {
        $this->SetTemplateAction('results');
        if (!Config::Get('plugin.simplesearch.Enable_Comments_Search')) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Comments_Search_Is_Disabled'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }
        if (!$this->PrepareSearchQuery()) return false;

        // make sql query
        $aResult = $this->PluginSimplesearch_Simplesearch_GetCommentsByQuery(
            $this->SQL_Search_Query,
            $this->iCurrentPage,
            $this->iPerPage,
            $this->SQL_Not_Strict_Sub_Query,
            $this->aClosedBlogsIdsForCurrentUser,
            $this->iSearchInOneBlogSelected,
            $this->iFavoriteUserId
        );

        $aComments = $this->Comment_GetCommentsAdditionalData($aResult ['result']);

        // highlight searched words in texts
        $RegexpForHighlight = $this->BuildPhpRegExpFromSQLExp();

        foreach ($aComments as $oComment) {
            $sText = $oComment->getText();
            $oComment->setText($this->ReplaceSearchedItems($RegexpForHighlight, $sText));
        }

        $this->Viewer_Assign('aComments', $aComments);
        $this->Viewer_Assign('aPaging', $this->MakePaging($aResult ['count']));
        $this->Viewer_Assign('aMatching', $aResult ['matches']);
    }

    // ---

    public function EventPeople()
    {
        $this->SetTemplateAction('people');
        if (!Config::Get('plugin.simplesearch.Enable_People_Search')) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.People_Search_Is_Disabled'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }
        if (!$this->PrepareSearchQuery(true)) return false;

        // make sql query
        $aResult = $this->PluginSimplesearch_Simplesearch_GetPeopleByQuery(
            $this->SQL_Search_Query,
            $this->iCurrentPage,
            $this->iPerPage
        );

        $aUsers = $this->User_GetUsersAdditionalData($aResult ['result']);

        // highlight searched words in texts
        $RegexpForHighlight = $this->BuildPhpRegExpFromSQLExp();

        $i = 0;
        foreach ($aUsers as $oUser) {
            $sOriginalText = '';
            switch ($aResult ['matches'] [$i]) {
                case 8:
                    $sOriginalText = $oUser->getProfileAbout();
                    break;
                case 4:
                    $sOriginalText = $oUser->getProfileCity();
                    break;
                case 3:
                    $sOriginalText = $oUser->getProfileCountry();
                    break;
                case 2:
                    $sOriginalText = $oUser->getProfileName();
                    break;
                case 1:
                    $sOriginalText = $oUser->getLogin();
                    break;
                default:
                    $sOriginalText = 'error';
            }
            // our own method
            $oUser->setFoundPlace($this->ReplaceSearchedItems($RegexpForHighlight, $sOriginalText));
            $i++;
        }

        $this->Viewer_Assign('aUsersRating', $aUsers);
        $this->Viewer_Assign('aPaging', $this->MakePaging($aResult ['count']));
        $this->Viewer_Assign('aMatching', $aResult ['matches']);
    }

    // ---

    public function EventBlogs()
    {
        $this->SetTemplateAction('results');
        if (!Config::Get('plugin.simplesearch.Enable_Blogs_Description_Search')) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Blogs_Search_Is_Disabled'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }
        if (!$this->PrepareSearchQuery()) return false;

        // make sql query
        $aResult = $this->PluginSimplesearch_Simplesearch_GetBlogsByQuery(
            $this->SQL_Search_Query,
            $this->iCurrentPage,
            $this->iPerPage,
            $this->SQL_Not_Strict_Sub_Query,
            $this->aClosedBlogsIdsForCurrentUser,
            $this->iSearchInOneBlogSelected
        );

        $aBlogs = $this->Blog_GetBlogsAdditionalData($aResult ['result']);

        // highlight searched words in texts
        $RegexpForHighlight = $this->BuildPhpRegExpFromSQLExp();

        foreach ($aBlogs as $oBlog) {
            $sText = $oBlog->getDescription();
            $oBlog->setDescription($this->ReplaceSearchedItems($RegexpForHighlight, $sText));
        }

        $this->Viewer_Assign('aBlogs', $aBlogs);
        $this->Viewer_Assign('aPaging', $this->MakePaging($aResult ['count']));
        $this->Viewer_Assign('aMatching', $aResult ['matches']);
    }

    // --- Dictionaries ---

    public function EventTags()
    {
        $this->SetTemplateAction('results');
        if (!Config::Get('plugin.simplesearch.Enable_Tags_Search')) {
            $this->Message_AddError(
                $this->Lang_Get('plugin.simplesearch.Tags_Search_Is_Disabled'),
                $this->Lang_Get('plugin.simplesearch.Error')
            );
            return false;
        }
        if (!$this->PrepareSearchQuery()) return false;

        // make sql query
        $aResult = $this->PluginSimplesearch_Simplesearch_GetTopicsByTagLikeByQuery(
            $this->OriginalQuerySafe,
            $this->iCurrentPage,
            $this->iPerPage,
            $this->aClosedBlogsIdsForCurrentUser,
            $this->iSearchInOneBlogSelected,
            $this->iFavoriteUserId
        );

        $aTopics = $this->Topic_GetTopicsAdditionalData($aResult ['result']);

        // nothing to highlight

        $this->Viewer_Assign('aTopics', $aTopics);
        $this->Viewer_Assign('aPaging', $this->MakePaging($aResult ['count']));
    }

    // ---

    public function EventShutdown()
    {
        $aReq = array();
        $aReq ['q'] = $this->OriginalQuerySafe;
        $aReq ['sType'] = strtolower(Router::GetActionEvent());

        $aRes = array();
        $aRes ['aCounts'] = array();
        // content types
        if (Config::Get('plugin.simplesearch.Enable_Topics_Search')) {
            $aRes ['aCounts']['topics'] = '';
        }
        if (Config::Get('plugin.simplesearch.Enable_Comments_Search')) {
            $aRes ['aCounts']['comments'] = '';
        }
        if (Config::Get('plugin.simplesearch.Enable_People_Search')) {
            $aRes ['aCounts']['people'] = '';
        }
        if (Config::Get('plugin.simplesearch.Enable_Blogs_Description_Search')) {
            $aRes ['aCounts']['blogsdescriptions'] = '';
        }
        if (Config::Get('plugin.simplesearch.Enable_Tags_Search')) {
            $aRes ['aCounts']['tags'] = '';
        }
        // setup count of searched query
        $aRes ['aCounts'][$aReq ['sType']] = $this->iResultCount;

        $this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.simplesearch.Search_On') . $this->OriginalQuerySafe);

        $this->Viewer_Assign('aReq', $aReq);
        $this->Viewer_Assign('aRes', $aRes);
        $this->Viewer_Assign('bIsResults', $this->iResultCount);
        $this->Viewer_Assign('aOpenCloseBlogsForCurrentUserObj', $this->aAccessibleOpenCloseBlogsForCurrentUserObj);
        $this->Viewer_Assign('iSearchInOneBlogSelected', $this->iSearchInOneBlogSelected);
        $this->Viewer_Assign('iFavoriteUserId', (bool)$this->iFavoriteUserId);
    }

    // ---

    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEvent('topics', 'EventTopics');
        $this->AddEvent('comments', 'EventComments');
        $this->AddEvent('people', 'EventPeople');
        $this->AddEvent('blogsdescriptions', 'EventBlogs');
        $this->AddEvent('tags', 'EventTags');
        $this->AddEvent('opensearch', 'EventOpenSearch');
    }

}
