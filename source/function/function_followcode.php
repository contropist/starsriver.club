<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/
    
    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    $_G['post_attach'] = [];
    
    function fcodedisp($html, $type = 'codehtml') {
        global $_G;
        $_G['forum_discuzcode']['pcodecount']++;
        $_G['forum_discuzcode'][$type][$_G['forum_discuzcode']['pcodecount']] = $html;
        $_G['forum_discuzcode']['codecount']++;
        return "[\tattach:" . $_G['forum_discuzcode']['pcodecount'] . "\t]";
    }
    
    function followcode($message, $tid = 0, $pid = 0, $length = 0, $allowimg = true) {
        
        global $_G;
        
        include_once libfile('function/post');
        
        $message = messagesafeclear(strip_tags($message));
        
        if ((strpos($message, '[/code]') || strpos($message, '[/CODE]')) !== false) {
            $message = preg_replace("/\s?\[code\](.+?)\[\/code\]\s?/is", "", $message);
        }
        
        $msglower = strtolower($message);
        
        $htmlon = 0;
        
        $message = dhtmlspecialchars($message);
        
        if ($_G['setting']['plugins']['func'][HOOKTYPE]['discuzcode']) {
            $_G['discuzcodemessage'] = &$message;
            $param = func_get_args();
            hookscript('discuzcode', 'global', 'funcs', [
                'param'  => $param,
                'caller' => 'followcode',
            ], 'discuzcode');
        }
        
        $_G['delattach'] = [];
        
        $message = fparsesmiles($message);
        
        if (strpos($msglower, 'attach://') !== false) {
            $message = preg_replace("/attach:\/\/(\d+)\.?(\w*)/i", '', $message);
        }
        
        if (strpos($msglower, 'ed2k://') !== false) {
            $message = preg_replace("/ed2k:\/\/(.+?)\//", '', $message);
        }
        
        if (strpos($msglower, '[/i]') !== false) {
            $message = preg_replace("/\s*\[i=s\][\n\r]*(.+?)[\n\r]*\[\/i\]\s*/is", '', $message);
        }
        
        $message = str_replace('[/p]', "\n", $message);
        
        $message = str_replace([
            '[/color]',
            '[/backcolor]',
            '[/size]', '[/font]', '[/align]',
            '[b]', '[/b]',
            '[s]', '[/s]',
            '[hr]',
            '[i=s]',
            '[i]', '[/i]',
            '[u]', '[/u]',
            '[list]', '[list=1]', '[list=a]', '[list=A]', "\r\n[*]", '[*]', '[/list]',
            '[indent]', '[/indent]',
            '[/float]',
        ], '', preg_replace([
            "/\[color=([#\w]+?)\]/i",
            "/\[color=((rgb|rgba)\([\d\s,]+?\))\]/i",
            "/\[backcolor=([#\w]+?)\]/i",
            "/\[backcolor=((rgb|rgba)\([\d\s,]+?\))\]/i",
            "/\[size=(\d{1,2}?)\]/i",
            "/\[size=(\d{1,2}(\.\d{1,2}+)?(px|pt)+?)\]/i",
            "/\[font=([^\[\<]+?)\]/i",
            "/\[align=(left|center|right)\]/i",
            "/\[float=left\]/i",
            "/\[float=right\]/i",
        ], '', $message));
        
        if (strpos($msglower, '[/p]') !== false) {
            $message = preg_replace("/\[p=(\d{1,2}|null), (\d{1,2}|null), (left|center|right)\]/i", "<p style=\"line-height:\\1px;text-indent:\\2em;text-align:left;\">", $message);
            $message = str_replace('[/p]', '</p>', $message);
        }
        
        if (strpos($msglower, '[/quote]') !== false) {
            $message = preg_replace("/\s?\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s?/is", '', $message);
        }
        if (strpos($msglower, '[/free]') !== false) {
            $message = preg_replace("/\s*\[free\][\n\r]*(.+?)[\n\r]*\[\/free\]\s*/is", '', $message);
        }
        
        if (isset($_G['cache']['bbcodes'][-$allowbbcode])) {
            $message = preg_replace($_G['cache']['bbcodes'][-$allowbbcode]['searcharray'], '', $message);
        }
        if (strpos($msglower, '[/hide]') !== false) {
            preg_replace_callback("/\[hide.*?\]\s*(.*?)\s*\[\/hide\]/is", 'followcode_callback_hideattach_1', $message);
            if (strpos($msglower, '[hide]') !== false) {
                $message = preg_replace("/\[hide\]\s*(.*?)\s*\[\/hide\]/is", '', $message);
            }
            if (strpos($msglower, '[hide=') !== false) {
                $message = preg_replace("/\[hide=(d\d+)?[,]?(\d+)?\]\s*(.*?)\s*\[\/hide\]/is", '', $message);
            }
        }
        
        if (strpos($msglower, '[/url]') !== false) {
            $message = preg_replace_callback("/\[url(=((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|thunder|qqdl|synacast){1}:\/\/|www\.|mailto:)?([^\r\n\[\"']+?))?\](.+?)\[\/url\]/is", 'followcode_callback_fparseurl_152', $message);
        }
        if (strpos($msglower, '[/email]') !== false) {
            $message = preg_replace_callback("/\[email(=([a-z0-9\-_.+]+)@([a-z0-9\-_]+[.][a-z0-9\-_.]+))?\](.+?)\[\/email\]/is", 'followcode_callback_fparseemail_14', $message);
        }
        
        $nest = 0;
        while (strpos($msglower, '[table') !== false && strpos($msglower, '[/table]') !== false) {
            $message = preg_replace_callback("/\[table(?:=(\d{1,4}%?)(?:,([\(\)%,#\w ]+))?)?\]\s*(.+?)\s*\[\/table\]/is", 'followcode_callback_fparsetable_123', $message);
            if (++$nest > 4)
                break;
        }
        
        if (strpos($msglower, '[/media]') !== false) {
            $message = preg_replace_callback("/\[media=([\w,]+)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/is", 'followcode_callback_fparsemedia_12', $message);
        }
        if (strpos($msglower, '[/audio]') !== false) {
            $message = preg_replace_callback("/\[audio(=1)*\]\s*([^\[\<\r\n]+?)\s*\[\/audio\]/is", 'followcode_callback_fparseaudio_2', $message);
        }
        
        
        // Clean illegal attachment mark [\n]
        $message = clearnl($message);
        
        // Make attachment tags( [attach]\n[/attach] ) to attach mark [\n]
        if ($tid && $pid) {
            $_G['post_attach'] = C::t('forum_attachment_n')->fetch_all_by_id(getattachtableid($tid), 'pid', $pid);
            foreach ($_G['post_attach'] as $aid => $attach) {
                if ((!empty($_G['delattach']) && in_array($aid, $_G['delattach']))) {
                    continue;
                } else {
                    $message = preg_replace("/\[attach\]$attach[aid]\[\/attach\]/i", fparseattach($attach['aid'], $length), $message);
                }
            }
        }
        
        // Clean empty attachment tags
        if (strpos($msglower, '[/attach]') !== false) {
            $message = preg_replace("/\[attach\]\s*([^\[\<\r\n]+?)\s*\[\/attach\]/is", '', $message);
        }
        
        // Cut string
    
        if ($tid) {
            $extra = 'onclick="changefeed('.$tid.', '.$pid.', '.$length.', this)"';
        }
        
        if ($length) {
            $sppos = strpos($message, chr(0) . chr(0) . chr(0));
            if ($sppos !== false) {
                $message = substr($message, 0, $sppos);
            }
            $checkstr = cutstr($message, $length, '');
            if (strpos($checkstr, '[') && strpos(strrchr($checkstr, "["), ']') === false) {
                $length = strpos($message, ']', strrpos($checkstr, strrchr($checkstr, "[")));
            }
            $message = cutstr($message, $length + 1, '...');
            
            $expender = '<a class="folder unfold"' . $extra . '>' . lang('space', 'follow_view_fulltext') . '</a>';
        } elseif ($allowimg && !empty($extra)) {
            $expender = '<a class="folder fold"' . $extra . '>' . lang('space', 'follow_retract') . '</a>';
        }
        
        // Make attachment tags to html entity and Samplize
        $counter = [
            'img'    => 0,
            'audio'  => 0,
            'video'  => 0,
            'media'  => 0,
            'attach' => 0,
        ];
        
        $html = $imageHtml = $mediaHtml = $videoHtml = $audioHtml = $attachHtml = '';
        
        for ($i = 0; $i <= $_G['forum_discuzcode']['pcodecount']; $i++) {
            if (!empty($_G['forum_discuzcode']['audio'][$i])) {
                
                $counter['audio'] += 1;
                $audioHtml .= '<li class="nth-of-' . $counter['audio'] . '">' . $_G['forum_discuzcode']['audio'][$i] . '</li>';
                $html = $_G['forum_discuzcode']['audio'][$i];
                
            } elseif (!empty($_G['forum_discuzcode']['video'][$i])) {
                
                $counter['video'] += 1;
                $videoHtml .= '<li class="nth-of-' . $counter['video'] . '">' . $_G['forum_discuzcode']['video'][$i] . '</li>';
                $html = $_G['forum_discuzcode']['video'][$i];
                
            } elseif (!empty($_G['forum_discuzcode']['media'][$i])) {
                
                $counter['media'] += 1;
                $mediaHtml .= '<li class="nth-of-' . $counter['media'] . '">' . $_G['forum_discuzcode']['media'][$i] . '</li>';
                $html = $_G['forum_discuzcode']['media'][$i];
                
            } elseif (!empty($_G['forum_discuzcode']['image'][$i]) && $counter['img'] < 9) {
                
                $counter['img'] += 1;
                $imageHtml .= '<a class="image rec-img nth-of-'.$counter['img'].'" style="background-image: url(' . $_G['forum_discuzcode']['image'][$i] . ')"><img src="' . LIBURL . '/img/row-e-col/1.1.png"/></a>';
                $html = '<div class="thread-element-img"><img src="'.$_G['forum_discuzcode']['image'][$i].'" /></div>';
                
            } elseif (!empty($_G['forum_discuzcode']['attach'][$i])) {
                
                $counter['attach'] += 1;
                $attachHtml .= '<li class="nth-of-' . $counter['attach'] . '">' . $_G['forum_discuzcode']['attach'][$i] . '</li>';
                $html = $_G['forum_discuzcode']['attach'][$i];
            }
            
            if (!empty($_G['forum_discuzcode']['codehtml'][$i])) {
                $html = $_G['forum_discuzcode']['codehtml'][$i];
            } elseif ($length) {
                $html = '';
            }
            
            $message = str_replace("[\tattach:$i\t]", $html, $message);
        }
        
        $message = '<div class="thread-element-content">' . $message .'</div>';
        
        if ($length) {
            
            if (!empty($audioHtml)) {
                $message .= '<div class="thread-element-audio audioGrid grid-'.$counter['audio'].'" ><ul>' . $audioHtml . '</ul></div>';
            }
            if (!empty($videoHtml)) {
                $message .= '<div class="thread-element-video mediaGrid grid-4-3 grid-'.$counter['video'].'"><ul>' . $videoHtml . '</ul></div>';
            }
            if (!empty($mediaHtml)) {
                $message .= '<div class="thread-element-media mediaGrid grid-4-3 grid-'.$counter['media'].'"><ul>' . $mediaHtml . '</ul></div>';
            }
            if (!empty($attachHtml)) {
                $message .= '<div class="thread-element-attachs"><span class="title">' . lang('feed', 'feed_attach') . '</span><ul>' . $attachHtml . '</ul></div>';
            }
            if (!empty($imageHtml)) {
                $message .= '<div class="thread-element-imgs imageGrid grid-'.$counter['img'].'">' . $imageHtml . '</div>';
            }
        }
    
        $message = '<div class="thread-elements-container">' . $message .'</div>';
    
        // Clean empty attachment mark
        $message = clearnl($message);
        
        // Highlight extra contents
        if (!empty($_GET['highlight'])) {
            $highlightarray = explode('+', $_GET['highlight']);
            $sppos = strrpos($message, chr(0) . chr(0) . chr(0));
            if ($sppos !== false) {
                $specialextra = substr($message, $sppos + 3);
                $message = substr($message, 0, $sppos);
            }
            followcode_callback_highlightword_21($highlightarray, 1);
            $message = preg_replace_callback("/(^|>)([^<]+)(?=<|$)/sU", 'followcode_callback_highlightword_21', $message);
            $message = preg_replace("/<highlight>(.*)<\/highlight>/siU", "<s class='thread-inner-element-text-highlight'>\\1</s>", $message);
            if ($sppos !== false) {
                $message = $message . chr(0) . chr(0) . chr(0) . $specialextra;
            }
        }
        
        // Clean memory
        unset($msglower);
        
        // Add [expend] button
        $message .= $expender;
        
        return $htmlon ? $message : nl2br(str_replace(["\t", '   ', '  '], ' ', $message));
    }
    
    function followcode_callback_hideattach_1($matches) {
        return hideattach($matches[1]);
    }
    
    function followcode_callback_fparseurl_152($matches) {
        return fparseurl($matches[1], $matches[5], $matches[2]);
    }
    
    function followcode_callback_fparseemail_14($matches) {
        return fparseemail($matches[1], $matches[4]);
    }
    
    function followcode_callback_fparsetable_123($matches) {
        return fparsetable($matches[1], $matches[2], $matches[3]);
    }
    
    function followcode_callback_fparsemedia_12($matches) {
        return fparsemedia($matches[1], $matches[2]);
    }
    
    function followcode_callback_fparseaudio_2($matches) {
        return fparseaudio($matches[2]);
    }
    
    function fparsetable_callback_parsetrtd_12($matches) {
        return parsetrtd($matches[1], 0, 0, $matches[2]);
    }
    
    function fparsetable_callback_parsetrtd_1($matches) {
        return parsetrtd('td', 0, 0, $matches[1]);
    }
    
    function fparsetable_callback_parsetrtd_1234($matches) {
        return parsetrtd($matches[1], $matches[2], $matches[3], $matches[4]);
    }
    
    function fparsetable_callback_parsetrtd_123($matches) {
        return parsetrtd('td', $matches[1], $matches[2], $matches[3]);
    }
    
    function followcode_callback_highlightword_21($matches, $action = 0) {
        static $highlightarray = [];
        
        if ($action == 1) {
            $highlightarray = $matches;
        } else {
            return highlightword($matches[2], $highlightarray, $matches[1]);
        }
    }
    
    function clearnl($message) {
        
        $message = preg_replace("/[\r\n|\n|\r]\s*[\r\n|\n|\r]/i", "\n", $message);
        $message = preg_replace("/^[\r\n|\n|\r]{1,}/i", "", $message);
        $message = preg_replace("/[\r\n|\n|\r]{2,}/i", "", $message);
        
        return $message;
    }
    
    function hideattach($hidestr) {
        global $_G;
        
        preg_match_all("/\[attach\]\s*(.*?)\s*\[\/attach\]/is", $hidestr, $del);
        foreach ($del[1] as $aid) {
            $_G['delattach'][$aid] = $aid;
        }
    }
    
    function fparseurl($url, $text, $scheme) {
        global $_G;
        
        $html = '';
        if (!$url && preg_match("/((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|thunder|qqdl|synacast){1}:\/\/|www\.)[^\[\"']+/i", trim($text), $matches)) {
            $url = $matches[0];
            $length = 65;
            if (strlen($url) > $length) {
                $text = substr($url, 0, intval($length * 0.5)) . ' ... ' . substr($url, -intval($length * 0.3));
            }
            $html = '<a href="' . (substr(strtolower($url), 0, 4) == 'www.' ? 'http://' . $url : $url) . '" target="_blank">' . $text . '</a>';
        } else {
            $url = substr($url, 1);
            if (substr(strtolower($url), 0, 4) == 'www.') {
                $url = 'http://' . $url;
            }
            $url = !$scheme ? $_G['siteurl'] . $url : $url;
            $atclass = substr(strtolower($text), 0, 1) == '@' ? ' ' : '';
            $html = '<a href="' . $url . '" target="_blank" ' . $atclass . '>' . $text . '</a>';
        }
        return fcodedisp($html);
    }
    
    function fparseattach($aid, $length = 0) {
        global $_G;
        
        $html = '';
        if (!empty($_G['post_attach']) && !empty($_G['post_attach'][$aid])) {
            $attach = $_G['post_attach'][$aid];
            unset($_G['post_attach'][$attach['aid']]);
            $attach['url'] = ($attach['remote'] ? $_G['setting']['ftp']['attachurl'] : $_G['setting']['attachurl']) . 'forum/';
            $attach['isimage'] = $attach['isimage'] && !$attach['price'] ? $attach['isimage'] : 0;
            $attach['refcheck'] = (!$attach['remote'] && $_G['setting']['attachrefcheck']) || ($attach['remote'] && ($_G['setting']['ftp']['hideurl'] || ($attach['isimage'] && $_G['setting']['attachimgpost'] && strtolower(substr($_G['setting']['ftp']['attachurl'], 0, 3)) == 'ftp')));
            $rimg_id = random(5) . $attach['aid'];
            if ($attach['isimage'] && !$attach['price'] && !$attach['readperm']) {
                $nothumb = $length ? 0 : 1;
                $src = $attach['url'] . (!$attach['thumb'] ? $attach['attachment'] : getimgthumbname($attach['attachment']));
                $html = $src;
                return fcodedisp($html, 'image');
            } else {
                if ($attach['price'] || $attach['readperm']) {
                    $html = '<a href="forum.php?mod=viewthread&tid=' . $attach['tid'] . '" id="attach_' . $rimg_id . '" target="_blank" class="flw_attach_price"><strong>' . $attach['filename'] . '</strong><span>' . sizecount($attach['filesize']) . '</span></a>';
                } else {
                    require_once libfile('function/attachment');
                    $aidencode = packaids($attach);
                    $attachurl = "forum.php?mod=attachment&aid=$aidencode";
                    $html = '<a href="' . $attachurl . '" id="attach_' . $rimg_id . '"><strong>' . $attach['filename'] . '</strong><span>' . sizecount($attach['filesize']) . '</span></a>';
                }
                return fcodedisp($html, 'attach');
            }
        }
        return '';
    }
    
    function fparseemail($email, $text) {
        global $_G;
        
        $text = str_replace('\"', '"', $text);
        $html = '';
        if (!$email && preg_match("/\s*([a-z0-9\-_.+]+)@([a-z0-9\-_]+[.][a-z0-9\-_.]+)\s*/i", $text, $matches)) {
            $email = trim($matches[0]);
            $html = '<a href="mailto:' . $email . '">' . $email . '</a>';
        } else {
            $html = '<a href="mailto:' . substr($email, 1) . '">' . $text . '</a>';
        }
        return fcodedisp($html);
    }
    
    function fparsetable($width, $bgcolor, $message) {
        global $_G;
        $html = '';
        if (strpos($message, '[/tr]') === false && strpos($message, '[/td]') === false) {
            $rows = explode("\n", $message);
            $html = '<table cellspacing="0" class="t_table" ' . ($width == '' ? null : 'style="width:' . $width . '"') . ($bgcolor ? ' bgcolor="' . $bgcolor . '">' : '>');
            foreach ($rows as $row) {
                $html .= '<tr><td>' . str_replace(['\|', '|', '\n',], ['&#124;', '</td><td>', "\n",], $row) . '</td></tr>';
            }
            $html .= '</table>';
        } else {
            if (!preg_match("/^\[tr(?:=([\(\)\s%,#\w]+))?\]\s*\[td([=\d,%]+)?\]/", $message) && !preg_match("/^<tr[^>]*?>\s*<td[^>]*?>/", $message)) {
                return str_replace('\\"', '"', preg_replace("/\[tr(?:=([\(\)\s%,#\w]+))?\]|\[td([=\d,%]+)?\]|\[\/td\]|\[\/tr\]/", '', $message));
            }
            if (substr($width, -1) == '%') {
                $width = substr($width, 0, -1) <= 98 ? intval($width) . '%' : '98%';
            } else {
                $width = intval($width);
                $width = $width ? ($width <= 560 ? $width . 'px' : '98%') : '';
            }
            $message = preg_replace_callback("/\[tr(?:=([\(\)\s%,#\w]+))?\]\s*\[td(?:=(\d{1,4}%?))?\]/i", 'fparsetable_callback_parsetrtd_12', $message);
            $message = preg_replace_callback("/\[\/td\]\s*\[td(?:=(\d{1,4}%?))?\]/i", 'fparsetable_callback_parsetrtd_1', $message);
            $message = preg_replace_callback("/\[tr(?:=([\(\)\s%,#\w]+))?\]\s*\[td(?:=(\d{1,2}),(\d{1,2})(?:,(\d{1,4}%?))?)?\]/i", 'fparsetable_callback_parsetrtd_1234', $message);
            $message = preg_replace_callback("/\[\/td\]\s*\[td(?:=(\d{1,2}),(\d{1,2})(?:,(\d{1,4}%?))?)?\]/i", 'fparsetable_callback_parsetrtd_123', $message);
            $html = '<table cellspacing="0" class="t_table" ' . ($width == '' ? null : 'style="width:' . $width . '"') . ($bgcolor ? ' bgcolor="' . $bgcolor . '">' : '>') . str_replace('\\"', '"', preg_replace("/\[\/td\]\s*\[\/tr\]\s*/i", '</td></tr>', $message)) . '</table>';
        }
        return fcodedisp($html);
        
    }
    
    function fparseaudio($url) {
        $url = addslashes($url);
        if (!in_array(strtolower(substr($url, 0, 6)), [
                'http:/',
                'https:',
                'ftp://',
                'rtsp:/',
                'mms://',
            ]) && !preg_match('/^static\//', $url) && !preg_match('/^data\//', $url)) {
            return dhtmlspecialchars($url);
        }
        if (fileext($url) == 'mp3') {
            $randomid = 'music_' . random(3);
            $html = '<img src="' . IMGDIR . '/music.gif" alt="' . lang('space', 'follow_click_play') . '" onclick="javascript:showFlash(\'music\', \'' . $url . '\', this, \'' . $randomid . '\');" class="tn" style="cursor: pointer;" />';
            return fcodedisp($html, 'audio');
        } else {
            $html = '<a href="' . $url . '" target="_blank">' . $url . '</a>';
            return $html;
        }
    }
    
    function fparsemedia($params, $url) {
        $params = explode(',', $params);
        
        $url = addslashes($url);
        $html = '';
        
        if (in_array(count($params), [3, 4])) {
            $type = $params[0];
            $url = str_replace(['<', '>',], '', str_replace('\\"', '\"', $url));
            switch ($type) {
                case 'mp3':return fparseaudio($url);
                default:$html = '<a href="' . $url . '" target="_blank">' . $url . '</a>';break;
            }
        }
        return fcodedisp($html, 'media');
    }
    
    function fparsesmiles(&$message) {
        global $_G;
        static $enablesmiles;
        if ($enablesmiles === null) {
            $enablesmiles = false;
            if (!empty($_G['cache']['smilies']) && is_array($_G['cache']['smilies'])) {
                foreach ($_G['cache']['smilies']['replacearray'] as $key => $smiley) {
                    if (substr($_G['cache']['smilies']['replacearray'][$key], 0, 1) == '<') {
                        break;
                    }
                    $_G['cache']['smilies']['replacearray'][$key] = '<img class="smilie" src="' . STATICURL . 'image/smiley/' . $_G['cache']['smileytypes'][$_G['cache']['smilies']['typearray'][$key]]['directory'] . '/' . $smiley . '" smilieid="' . $key . '"/>';
                }
                $enablesmiles = true;
            }
        }
        $enablesmiles && $message = preg_replace($_G['cache']['smilies']['searcharray'], $_G['cache']['smilies']['replacearray'], $message, $_G['setting']['maxsmilies']);
        return $message;
    }