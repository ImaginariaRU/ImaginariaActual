<?php

/********************************************
 * Author: Vladimir Linkevich
 * e-mail: Vladimir.Linkevich@gmail.com
 * since 2011-02-25
 ********************************************/


class PluginAutocut_ModuleAutocut extends Module
{
    /**
     * @var array Массив тегов, внутри которых не должен происходить разрыв
     */
    protected $unbreakable_tags_list;

    /**
     * Length before cut
     * @var
     */
    protected $max_text_length_before_cut;


    /**
     * Настройки, задающие длину необрезанного текста
     */
    protected $uncuttable_text_length_absolute;
    protected $uncuttable_text_length_relative;

    /**
     * Две "непрозрачных" настройки, задающих "второй барьер"
     * @var
     */
    protected $cut_second_barrier_charcount;
    protected $cut_second_barrier_enabled;

    public function Init()
    {
        $this->unbreakable_tags_list = Config::Get('plugin.autocut.unbreakable_tags');
        $this->max_text_length_before_cut = Config::Get('plugin.autocut.length_before_cut');

        $this->cut_second_barrier_enabled = Config::Get('plugin.autocut.LightModeOn');
        $this->cut_second_barrier_charcount = Config::Get('plugin.autocut.SecondBarrier');

        $this->uncuttable_text_length_absolute = Config::Get('plugin.autocut.length_of_uncuttable_text_absolute');
        $this->uncuttable_text_length_relative = Config::Get('plugin.autocut.length_of_uncuttable_text_relative');
    }

    /**
     * Add cut tag
     *
     * @param $str
     * @return null|string|string[]
     */
    public function CutAdd($str)
    {
        $max_text_length_before_cut = $this->max_text_length_before_cut;
        $unbreakable_tags_list = $this->unbreakable_tags_list;
        $cut_second_barrier_charcount = $this->cut_second_barrier_charcount;
        $input_text_length = mb_strlen($str, 'UTF-8');
        $cutpos = null;

        // exclude video link from counting position
        $sPrestripKey = '/<?video>[^>]*<?\/video>/i';
        $sPrestripped = preg_replace($sPrestripKey, '', $str);
        $sStripped = preg_replace('/<[^>]*>/', '', $sPrestripped);

        // check stripped text length;
        if (strlen($sStripped) <= $max_text_length_before_cut) {
            return $str;
        }

        // get current CUT position if exists
        $cutpos = mb_strpos(preg_replace('/<(?!cut)[^>]*>/', '', $sPrestripped), '<cut>', 0, "UTF-8");

        if ($cutpos !== false && $this->cut_second_barrier_enabled) {
            if ($cutpos <= $cut_second_barrier_charcount || $cut_second_barrier_charcount == 0) {
                return $str;
            } else {
                $max_text_length_before_cut = $cut_second_barrier_charcount;
            }
        }

        if ($cutpos !== false && $cutpos <= $max_text_length_before_cut) {
            return $str;
        } else {

            // remove CUT
            $str = preg_replace('/<cut>/', '', $str);
            $cutpos = 0; // calculated CUT position
            $i = 0; //char counter
            $countchar = 0; // visible chars counter

            $bInTag = false; // if we are <inside of a tag>
            $bRecTag = false; // start recording tag name
            $bCount = true; // #<don't>DO COUNT<don't>

            // current tag name
            $sCurrentTag = '';

            //if we are waiting for tag closure
            $sWaitTag = '';

            //moving through text
            while ($countchar <= $max_text_length_before_cut && $i < $input_text_length) {
                $current = mb_substr($str, $i, 1, 'UTF-8');

                //#Find where the tag begins and start recording it
                if ($current == '<') {

                    //#set Cut position before tag;
                    if ($i != 0 && $sWaitTag == '') {
                        $cutpos = $i - 1;
                    }

                    //#if it's a second open tag then it's not a tag;
                    if ($bInTag) {
                        $bInTag = false;
                        $bRecTag = false;
                        $bCount = true;
                        $countchar += strlen($sCurrentTag) + 2;
                    } else {
                        $sCurrentTag = '';
                        $bInTag = true;
                        $bRecTag = true;
                        $bCount = false;

                    }
                } //#close tag
                elseif ($current == '>') {
                    if ($bInTag) {
                        $bInTag = false;
                        $bRecTag = false;
                    } else {
                        $countchar++;
                    }
                    if (in_array($sCurrentTag, $unbreakable_tags_list)) {
                        $sWaitTag = $sWaitTag == '' ? $sCurrentTag : '';
                    }
                    if ($sWaitTag != 'video') {
                        $bCount = true;
                    }
                    //#Space character
                } elseif ($current == ' ') {
                    if ($bCount) {
                        $countchar++;
                    }
                    if ($bInTag && !$sCurrentTag == '') {
                        $bRecTag = false;
                    }
                    //#stop recording tag if it's length is over 11 chars
                    if ($bRecTag && (strlen($sCurrentTag) > 11)) {
                        $bRecTag = false;
                        $bInTag = false;
                        $bCount = true;
                        $countchar += strlen($sCurrentTag) + 2;
                    }
                    //#if we are not in tag set cut position at this space symbol place
                    if (!$bInTag && $sWaitTag == '') {
                        $cutpos = $i;
                    }
                } else {
                    if ($bCount) {
                        $countchar++;
                    }
                    //#stop recording tag if it's length is over 11 chars
                    if ($bRecTag && (strlen($sCurrentTag) > 11)) {
                        $bRecTag = false;
                        $bInTag = false;
                        $bCount = true;
                        $countchar += strlen($sCurrentTag) + 2;
                    }
                    //#record current	character as one of the tag
                    if ($bRecTag && $current != '<' && $current != '/' && $current != ' ') {
                        $sCurrentTag .= $current;
                    }
                }
                //#char position enumerator
                $i++;
            }

            $left_text_length = $input_text_length - $cutpos;

            // Если длина остатка текста меньше необрезаемой длины - возвращаем неурезанный текст
            if ($left_text_length <= $this->uncuttable_text_length_absolute) {
                return $str;
            }

            // Если необрезаемый остаток текста составляет долю, меньшую порогового значения в % - возвращаем неурезанный текст
            if ($left_text_length <= ($input_text_length * $this->uncuttable_text_length_relative / 100)) {
                return $str;
            }

            //#insert a cut tag;
            $str = mb_substr($str, 0, $cutpos, 'UTF-8') . '<cut>' . mb_substr($str, $cutpos, $input_text_length, 'UTF-8');
        }
        return $str;
        //#END OF CUT ADD FUNCTION
    }

//#End of class
}
