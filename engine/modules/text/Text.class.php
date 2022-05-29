<?php

//@todo: KW: load with composer
/*
https://github.com/AgelxNash/Jevix/pull/1/commits/179e940e839764fff648302828d8df68bd7e7981
https://github.com/ur001/Jevix/network
 */

use Imaginaria\Jevix\Jevix;

/**
 * Модуль обработки текста на основе типографа Jevix
 * Позволяет вырезать из текста лишние HTML теги и предотвращает различные попытки внедрить в текст JavaScript
 * <pre>
 * $sText=$this->Text_Parser($sTestSource);
 * </pre>
 * Настройки парсинга находятся в конфиге /config/jevix.php
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleText extends Module
{
    /**
     * Объект типографа
     *
     * @var Jevix
     */
    protected $oJevix;

    /**
     * Инициализация модуля
     *
     */
    public function Init()
    {
        /**
         * Создаем объект типографа и запускаем его конфигурацию
         */
        $this->oJevix = new Jevix();
        $this->JevixConfig();
    }

    /**
     * Конфигурирует типограф
     *
     */
    protected function JevixConfig()
    {
        // загружаем конфиг
        $this->LoadJevixConfig();
    }

    /**
     * Загружает конфиг Jevix'а
     *
     * @param string $sType Тип конфига
     * @param bool $bClear Очищать предыдущий конфиг или нет
     */
    public function LoadJevixConfig($sType = 'default', $bClear = true)
    {
        if ($bClear) {
            $this->oJevix->tagsRules = array();
        }
        $aConfig = Config::Get('jevix.' . $sType);
        if (is_array($aConfig)) {
            foreach ($aConfig as $sMethod => $aExec) {
                foreach ($aExec as $aParams) {
                    if (in_array(strtolower($sMethod), array_map("strtolower", array('cfgSetTagCallbackFull', 'cfgSetTagCallback')))) {
                        if (isset($aParams[1][0]) and $aParams[1][0] == '_this_') {
                            $aParams[1][0] = $this;
                        }
                    }
                    call_user_func_array(array($this->oJevix, $sMethod), $aParams);
                }
            }
            /**
             * Хардкодим некоторые параметры
             */
            unset($this->oJevix->entities1['&']); // разрешаем в параметрах символ &
            if (Config::Get('view.noindex') and isset($this->oJevix->tagsRules['a'])) {
                $this->oJevix->cfgSetTagParamDefault('a', 'rel', 'nofollow', true);
            }
        }
    }

    /**
     * Возвращает объект Jevix
     *
     * @return Jevix
     */
    public function GetJevix()
    {
        return $this->oJevix;
    }

    /**
     * Парсинг текста с помощью Jevix
     *
     * @param string $sText Исходный текст
     * @param array $aError Возвращает список возникших ошибок
     * @return string
     */
    public function JevixParser($sText, &$aError = null)
    {
        // Если конфиг пустой, то загружаем его
        if (!count($this->oJevix->tagsRules)) {
            $this->LoadJevixConfig();
        }
        $sResult = $this->oJevix->parse($sText, $aError);
        return $sResult;
    }

    /**
     * Парсинг текста на предмет видео
     * Находит теги <pre><video></video></pre> и преобразовывает их в видео
     *
     * @param string $sText Исходный текст
     * @return string
     */
    public function VideoParser($sText)
    {
        /**
         * youtube.com
         * http://livestreet.ru/blog/tips_and_tricks/18041.html#comment295519
         * http://livestreet.ru/blog/tips_and_tricks/18041.html
         *
         */
        $pattern_youtube = "/<video>(?:https?:\/\/)?(?:(?:www\.))?(?:youtube\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})(?:\?(?:t|start)=((?:[0-9]{1,10}[hms]?){1,4}))?<\/video>/i";
        preg_match($pattern_youtube, $sText, $output_array);

        if (count($output_array) == 2) {
            $template_youtube = '<iframe width="560" height="310" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';
            $sText = preg_replace($pattern_youtube, $template_youtube, $sText);
        } elseif (count($output_array) == 3 ) {
            $template_youtube = '<iframe width="560" height="310" src="//www.youtube.com/embed/$1?start=$2" frameborder="0" allowfullscreen></iframe>';
            $sText = preg_replace($pattern_youtube, $template_youtube, $sText);
        }

        /**
         * vimeo.com
         */
        $pattern_vimeo = '/<video>(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+).*<\/video>/i';
        $template_vimeo = '<iframe src="//player.vimeo.com/video/$1" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        $sText = preg_replace($pattern_vimeo, $template_vimeo, $sText);

        /**
         * dailymotion.com
         */
        /*$pattern_dailymotion = "/(<video>)(?:https?:\/\/)?(?:www\.)?dailymotion.com\/video\/(\w+)(?:_.*?)?(<\/video>)/ui";
        $pattern_dailymotion_short = "/(<video>)(?:https?:\/\/)?(?:www\.)?dai.ly\/(\w+)(?:_.*?)?(<\/video>)/ui";
        $template_dailymotion = '<iframe frameborder="0" width="560" height="315" src="//www.dailymotion.com/embed/video/$2" allowfullscreen></iframe>';
        $sText = preg_replace($pattern_dailymotion, $template_dailymotion, $sText);
        $sText = preg_replace($pattern_dailymotion_short, $template_dailymotion, $sText);*/

        /**
         * coub.com
         */
        /*$pattern_coub = "/(<video>)(?:https?:\/\/)?(?:www\.)?coub.com\/view\/(\w+)(<\/video>)/ui";
        $template_coub = '<iframe src="//coub.com/embed/$2?muted=false&autostart=false&originalSize=false&hideTopBar=true&noSiteButtons=true&startWithHD=false" allowfullscreen="true" frameborder="0" width="400" height="400"></iframe>';
        $sText = preg_replace($pattern_coub, $template_coub, $sText);*/

        /**
         * rutube.ru
         */
        $pattern_rutube = "/(<video>)(?:https?:\/\/)?(?:www\.)?rutube.ru\/video\/(\w+)\/?(<\/video>)/ui";
        $template_rutube = '<iframe src="//rutube.ru/video/embed/$2" allowfullscreen="true" frameborder="0" width="560" height="315"></iframe>';
        $sText = preg_replace($pattern_rutube, $template_rutube, $sText);

        /**
         * gfycat.com
         */
        /*$pattern_gfycat = "/(<video>)(?:https?:\/\/)?(?:www\.)?gfycat.com\/(?:[\w\d-_\/]+\/)?([\w\d-_]+)(\?[\w\d-_\/=%&]*)?(<\/video>)/ui";
        $template_gfycat = '<iframe src="//gfycat.com/ifr/$2$3" allowfullscreen="true" frameborder="0" width="560" height="315"></iframe>';
        $sText = preg_replace($pattern_gfycat, $template_gfycat, $sText);*/

        /**
         * vault.mle.party (PeerTube)
         */
        /*$pattern_PeerTube = "/(<video>)(?:https?:\/\/)?(?:www\.)?vault.mle.party\/videos\/\w+\/([\w\d-_]+)\/?(\?[\w\d-_\/=%&]*)?(<\/video>)/ui";
        $template_PeerTube = '<iframe src="//vault.mle.party/videos/embed/$2" allowfullscreen="true" frameborder="0" width="560" height="315"></iframe>';
        $sText = preg_replace($pattern_PeerTube, $template_PeerTube, $sText);*/

        /**
         * video.yandex.ru
         */
        $pattern_yandex_video = '/<video>https?:\/\/video\.yandex\.ru\/users\/([a-zA-Z0-9_\-]+)\/view\/(\d+).*<\/video>/i';
        $template_yandex_video = '<object width="467" height="345">' .
                            '<param name="video" value="http://video.yandex.ru/users/$1/view/$2/get-object-by-url/redirect"></param>' .
                            '<param name="allowFullScreen" value="true"></param>' .
                            '<param name="scale" value="noscale"></param>' .
                            '<embed src="http://video.yandex.ru/users/$1/view/$2/get-object-by-url/redirect" type="application/x-shockwave-flash" width="467" height="345" allowFullScreen="true" scale="noscale" ></embed>' .
                            '</object>';
        $sText = preg_replace($pattern_yandex_video, $template_yandex_video, $sText);

        /**
         * vk.com video
         */
        $pattern_vk_video = '/<video>http(?:s|):\/\/(?:www\.|)vk\.com\/video([\d]+)_([\d]+)<\/video>/Ui';

        if (preg_match($pattern_vk_video, $sText)) {
            preg_match_all($pattern_vk_video, $sText, $sTextMatches);

            for ($i = 0; $i < count($sTextMatches[1]); $i++) {
                $html = file_get_contents('http://vk.com/video' . $sTextMatches[1][$i] . '_' . $sTextMatches[2][$i]);
                preg_match('/\\\"hash2\\\":\\\"([a-f0-9]+)\\\"/Ui', $html, $matches);
                $sText = preg_replace(
                    '/<video>(?:http(?:s|):|)(?:\/\/|)(?:www\.|)vk\.com\/video' . $sTextMatches[1][$i] . '_' . $sTextMatches[2][$i] . '(?:\?[\s\S]+|)<\/video>/Ui',
                    '<iframe src="http://vk.com/video_ext.php?oid=' . $sTextMatches[1][$i] . '&id=' . $sTextMatches[2][$i] . '&hash=' . $matches[1] . '" width="560" height="315" frameborder="0"></iframe>',
                    $sText);
            }
        }

        return $sText;
    }

    /**
     * Парсинг дайсов в тексте
     *
     * TODO: Переписать, взято из табуна
     *
     * @param string $sText
     * @return string
     */
    private function DiceParser($sText)
    {
        $border = Config::Get('plugin.dice.border');
        $delim = Config::Get('plugin.dice.delim');
        $max_x = Config::Get('plugin.dice.max_x');
        $max_y = Config::Get('plugin.dice.max_y');
        if (stristr($sText, $border)) {
            preg_match_all('/' . $border . '[0-9]{1,3}' . $delim . '[0-9]{1,3}' . $border . '/', $sText, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                preg_match_all('/[0-9]{1,3}/', $match[0], $array, PREG_SET_ORDER);
                if ($array[0][0] > 0 && $array[0][0] <= $max_x && $array[1][0] > 0 && $array[1][0] <= $max_y) {
                    $dices = null;

                    for ($i = 1; $i <= $array[0][0]; $i++, $dices[] = rand(1, $array[1][0])) {}

                    $str[] = '<span class="dice"><span class="blue">' . $array[0][0] . $delim . $array[1][0] . '</span>: <span class="green">[' . implode(' + ', $dices) . ']</span> | <span class="red">[' . array_sum($dices) . ']</span></span>';
                }
            }
            foreach ($str as $s) $sText = preg_replace('/' . $border . '[0-9]{1,3}' . $delim . '[0-9]{1,3}' . $border . '/', $s, $sText, 1);
        }
        return $sText;
    }

    /**
     * Парсит текст, применяя все парсеры
     *
     * @param string $sText Исходный текст
     * @param int $actionType Тип релевантного парсера
     * @return string
     */
    const ACT_CREATE = 1;
    const ACT_FIX    = 2;
    const ACT_UPDATE = 3;

    public function Parser($sText, $actionType = -1)
    {
        if (!is_string($sText)) {
            return '';
        }
        $sResult = $this->FlashParamParser($sText);
        $sResult = $this->JevixParser($sResult);
        $sResult = $this->VideoParser($sResult);
        $sResult = $this->CodeSourceParser($sResult);

        $parser_dice_enabled = Config::Get('plugin.dice.border');
        if ($parser_dice_enabled) {
            if($actionType === $this::ACT_CREATE || $actionType === $this::ACT_UPDATE) {
                // Don't parce dices for edited comments
                $sResult=$this->DiceParser($sResult);
            }
        }

        return $sResult;
    }

    /**
     * Заменяет все вхождения короткого тега <param/> на длиную версию <param></param>
     * Заменяет все вхождения короткого тега <embed/> на длиную версию <embed></embed>
     *
     * @param string $sText Исходный текст
     * @return string
     */
    protected function FlashParamParser($sText)
    {
        if (preg_match_all("@(<\s*param\s*name\s*=\s*(?:\"|').*(?:\"|')\s*value\s*=\s*(?:\"|').*(?:\"|'))\s*/?\s*>(?!</param>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $str_new = $str . '></param>';
                $sText = str_replace($aMatch[0][$key], $str_new, $sText);
            }
        }
        if (preg_match_all("@(<\s*embed\s*.*)\s*/?\s*>(?!</embed>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $str_new = $str . '></embed>';
                $sText = str_replace($aMatch[0][$key], $str_new, $sText);
            }
        }
        /**
         * Удаляем все <param name="wmode" value="*"></param>
         */
        if (preg_match_all("@(<param\s.*name=(?:\"|')wmode(?:\"|').*>\s*</param>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $sText = str_replace($aMatch[0][$key], '', $sText);
            }
        }
        /**
         * А теперь после <object> добавляем <param name="wmode" value="opaque"></param>
         * Решение не фантан, но главное работает :)
         */
        if (preg_match_all("@(<object\s.*>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $sText = str_replace($aMatch[0][$key], $aMatch[0][$key] . '<param name="wmode" value="opaque"></param>', $sText);
            }
        }
        return $sText;
    }

    /**
     * Подсветка исходного кода
     *
     * @param string $sText Исходный текст
     * @return mixed
     */
    public function CodeSourceParser($sText)
    {
        $sText = str_replace("<code>", '<pre class="prettyprint"><code>', $sText);
        $sText = str_replace("</code>", '</code></pre>', $sText);
        return $sText;
    }

    /**
     * Производить резрезание текста по тегу cut.
     * Возвращаем массив вида:
     * <pre>
     * array(
     *        $sTextShort - текст до тега <cut>
     *        $sTextNew   - весь текст за исключением удаленного тега
     *        $sTextCut   - именованное значение <cut>
     * )
     * </pre>
     *
     * @param  string $sText Исходный текст
     * @return array
     */
    public function Cut($sText)
    {
        $sTextShort = $sText;
        $sTextNew = $sText;
        $sTextCut = null;

        $sTextTemp = str_replace("\r\n", '[<rn>]', $sText);
        $sTextTemp = str_replace("\n", '[<n>]', $sTextTemp);

        if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui", $sTextTemp, $aMatch)) {
            $aMatch[1] = str_replace('[<rn>]', "\r\n", $aMatch[1]);
            $aMatch[1] = str_replace('[<n>]', "\r\n", $aMatch[1]);
            $aMatch[3] = str_replace('[<rn>]', "\r\n", $aMatch[3]);
            $aMatch[3] = str_replace('[<n>]', "\r\n", $aMatch[3]);
            $sTextShort = $aMatch[1];
            $sTextNew = $aMatch[1] . ' <a name="cut"></a> ' . $aMatch[3];
            if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui', $aMatch[2], $aMatchCut)) {
                $sTextCut = trim($aMatchCut[1]);
            }
        }

        return array($sTextShort, $sTextNew, $sTextCut ? htmlspecialchars($sTextCut) : null);
    }

    /**
     * Обработка тега ls в тексте
     * <pre>
     * <ls user="admin" />
     * </pre>
     *
     * @param string $sTag Тег на ктором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackTagLs($sTag, $aParams)
    {
        $sText = '';
        if (isset($aParams['user'])) {
            if ($oUser = $this->User_getUserByLogin($aParams['user'])) {
                $sText .= "<a href=\"{$oUser->getUserWebPath()}\" class=\"ls-user\">{$oUser->getLogin()}</a> ";
            }
        }
        return $sText;
    }
}

