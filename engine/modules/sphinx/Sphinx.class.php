<?php

// require_once(Config::Get('path.root.engine') . '/lib/external/Sphinx/sphinxapi.php');

use Arris\Toolkit\SphinxToolkit;
use Foolz\SphinxQL\SphinxQL;
use NilPortugues\Sphinx\SphinxClient;

/**
 * Модуль для работы с машиной полнотекстового поиска Sphinx
 *
 * @package modules.sphinx
 * @since 1.0
 */
class ModuleSphinx extends Module
{
    /**
     * Объект сфинкса
     *
     * @var SphinxClient|null
     */
    protected $oSphinx = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->InitSphinx();
    }

    /**
     * Инициализация сфинкса
     */
    protected function InitSphinx()
    {
        /**
         * Получаем объект Сфинкса(из Сфинкс АПИ)
         */
        $this->oSphinx = new SphinxClient();
        $this->oSphinx->SetServer(Config::Get('module.search.sphinx.host'), intval(Config::Get('module.search.sphinx.port')));
        /**
         * Устанавливаем тип сортировки
         */
        $this->oSphinx->SetSortMode(SPH_SORT_EXTENDED, "@weight DESC, @id DESc");
    }

    /**
     * Возвращает число найденых элементов в зависимости от их типа
     *
     * @param string $sTerms Поисковый запрос
     * @param string $sObjType Тип поиска
     * @param array $aExtraFilters Список фильтров
     * @return int
     */
    public function GetNumResultsByType($sTerms, $sObjType = 'topics', $aExtraFilters = [])
    {
        $query_expression = SphinxQL::expr("count(*) AS count");
        $search_index = $sObjType . "Index";

        $searchd_query = SphinxToolkit::createInstance()
            ->select($query_expression)
            ->from($search_index);

        if ($sObjType == 'topics') {
            $match = ['topic_title', 'topic_text'];

            $searchd_query = $searchd_query
                ->match($match, $sTerms)
                ->where('topic_publish', 1);
            ;
        } else {
            $match = ['comment_text'];

            $searchd_query = $searchd_query
                ->match($match, $sTerms)
                ->where('comment_delete', 0);
        }

        $result_data = $searchd_query->execute();

        $result = $result_data->fetchAssoc();

        return empty($result) ? 0 : $result['count'];
    }

    public function FindContent($sTerms, $sObjType, $iOffset = 0, $iLimit = 1, $aExtraFilters = [])
    {
        if (Config::Get('module.search.mode') == 'legacy') {
            $result = $this->FindContent_legacy($sTerms, $sObjType, $iOffset, $iLimit, $aExtraFilters);
        } else {
            $result = $this->FindContent_new($sTerms, $sObjType, $iOffset, $iLimit, $aExtraFilters);
        }

        return $result;
    }

    public function FindContent_new($sTerms, $sObjType, $iOffset = 0, $iLimit = 1, $aExtraFilters = [])
    {
        /*switch ($sObjType) {
            case 'topics': {
                break;
            }
        }*/

        if ($sObjType == 'topics') {
            $query_expression = SphinxQL::expr(implode(', ', [
                'id',
                'topic_date_add',
                'topic_publish',
                'tag',
                'nice_url',
                "highlight({before_match='<em>', after_match='</em>', around=8}, 'topic_title') AS topic_title",
                "highlight({before_match='<em>', after_match='</em>', around=8}, 'topic_text') AS topic_text",
                "user_login",
                "yearmonthday(topic_date_add) AS topic_date_ymd"
            ]));

            $orderBy = 'topic_date_add';
            $match = ['topic_title', 'topic_text'];

        } else {
            $query_expression = SphinxQL::expr(implode(', ', [
                'id',
                'comment_date',
                "highlight({before_match='<em>', after_match='</em>', around=8}, 'comment_text') AS comment_text",
                "yearmonthday(comment_date) AS comment_date_ymd"
            ]));

            $orderBy = 'comment_date';
            $match = ['comment_text'];
        }

        $search_index = $sObjType . "Index";

        $query_dataset = SphinxToolkit::createInstance()
            ->select($query_expression)
            ->from($search_index)
            ->offset($iOffset)
            ->orderBy($orderBy, 'DESC')
            ->match($match, $sTerms)
            ->limit($iLimit);

        // array('topics' => array('topic_publish' => 1), 'comments' => array('comment_delete' => 0));
        if (!is_null($aExtraFilters)) {
            foreach ($aExtraFilters AS $sAttribName => $sAttribValue) {
                $query_dataset = $query_dataset->where(
                    $sAttribName,
                    $sAttribValue
                );
            }
        }

        $result_data = $query_dataset->execute();

        $dataset = [];
        while ($row = $result_data->fetchAssoc()) {
            if ($sObjType == 'topics') {
                $row['cdate'] = date('H:i / d.m.Y', $row['topic_date_add']);
                $row['cdate_time'] = date('H:i', $row['topic_date_add']);
                $row['cdate_date'] = date('d.m.Y', $row['topic_date_add']);
            }

            $dataset[ $row['id'] ] = $row;
        }

        return [
            'total_found'   =>  count($dataset),
            'matches'       =>  $dataset
        ];
    }

    /**
     * Непосредственно сам поиск
     *
     * @param string $sTerms Поисковый запрос
     * @param string $sObjType Тип поиска
     * @param int $iOffset Сдвиг элементов
     * @param int $iLimit Количество элементов
     * @param array $aExtraFilters Список фильтров
     * @return array|bool
     */
    public function FindContent_legacy($sTerms, $sObjType, $iOffset, $iLimit, $aExtraFilters)
    {
        /**
         * используем кеширование при поиске
         */
        $sExtraFilters = serialize($aExtraFilters);
        $cacheKey = Config::Get('module.search.entity_prefix') . "searchResult_{$sObjType}_{$sTerms}_{$iOffset}_{$iLimit}_{$sExtraFilters}";
        if (false === ($data = $this->Cache_Get($cacheKey))) {
            /**
             * Параметры поиска
             */
            $this->oSphinx->SetMatchMode(SPH_MATCH_ALL);
            $this->oSphinx->SetLimits($iOffset, $iLimit);
            /**
             * Устанавливаем атрибуты поиска
             */
            $this->oSphinx->ResetFilters();
            if (!is_null($aExtraFilters)) {
                foreach ($aExtraFilters AS $sAttribName => $sAttribValue) {
                    $this->oSphinx->SetFilter(
                        $sAttribName,
                        (is_array($sAttribValue)) ? $sAttribValue : array($sAttribValue)
                    );
                }
            }
            /**
             * Ищем
             */
            if (!is_array($data = $this->oSphinx->Query($sTerms, Config::Get('module.search.entity_prefix') . $sObjType . 'Index'))) {
                return false; // Скорее всего недоступен демон searchd
            }
            /**
             * Если результатов нет, то и в кеш писать не стоит...
             * хотя тут момент спорный
             */
            if ($data['total'] > 0) {
                $this->Cache_Set($data, $cacheKey, array(), 60 * 15);
            }
        }
        return $data;
    }

    /**
     * Получить ошибку при последнем обращении к поиску
     *
     * @return string
     */
    public function GetLastError()
    {
        return $this->oSphinx->GetLastError();
    }

    /**
     * Получаем сниппеты(превью найденых элементов)
     *
     * @param string $sText Текст
     * @param string $sIndex Название индекса
     * @param string $sTerms Поисковый запрос
     * @param string $before_match Добавляемый текст перед ключом
     * @param string $after_match Добавляемый текст после ключа
     * @return array
     */
    public function GetSnippet($sText, $sIndex, $sTerms, $before_match, $after_match)
    {
        $aReturn = $this->oSphinx->BuildExcerpts(array($sText), Config::Get('module.search.entity_prefix') . $sIndex . 'Index', $sTerms, array(
                'before_match' => $before_match,
                'after_match' => $after_match,
            )
        );
        return $aReturn[0];
    }
}
