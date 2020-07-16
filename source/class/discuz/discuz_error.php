<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: discuz_error.php 33361 2013-05-31 08:59:06Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class discuz_error
{

	public static function system_error($message, $show = true, $save = true, $halt = true) {
		if(!empty($message)) {
			$message = lang('error', $message);
		} else {
			$message = lang('error', 'error_unknow');
		}

		list($showtrace, $logtrace) = discuz_error::debug_backtrace();

		if($save) {
			$messagesave = '<b>'.$message.'</b><br><b>PHP:</b>'.$logtrace;
			discuz_error::write_error_log($messagesave);
		}

		if($show) {
			if(!defined('IN_MOBILE')) {
				discuz_error::show_error('system', "<li>$message</li>", $showtrace, 0);
			} else {
				discuz_error::mobile_show_error('system', "<li>$message</li>", $showtrace, 0);
			}
		}

		if($halt) {
			exit();
		} else {
			return $message;
		}
	}

	public static function template_error($message, $tplname) {
		$message = lang('error', $message).'<ul>';
		$tplname = explode(",",str_replace([DISCUZ_ROOT,'./',' '], '', $tplname));
		foreach($tplname as $k){
            $message .= '<li>'.$k.'</li>';
        }
        $message .= '</ul>';
		discuz_error::system_error($message);
	}

	public static function debug_backtrace() {
		$skipfunc[] = 'discuz_error->debug_backtrace';
		$skipfunc[] = 'discuz_error->db_error';
		$skipfunc[] = 'discuz_error->template_error';
		$skipfunc[] = 'discuz_error->system_error';
		$skipfunc[] = 'db_mysql->halt';
		$skipfunc[] = 'db_mysql->query';
		$skipfunc[] = 'DB::_execute';

		$show = $log = '';
		$debug_backtrace = debug_backtrace();
		krsort($debug_backtrace);
		foreach ($debug_backtrace as $k => $error) {
			$file = str_replace(DISCUZ_ROOT, '', $error['file']);
			$func = isset($error['class']) ? $error['class'] : '';
			$func .= isset($error['type']) ? $error['type'] : '';
			$func .= isset($error['function']) ? $error['function'] : '';
			if(in_array($func, $skipfunc)) {
				break;
			}
			$error['line'] = sprintf('%04d', $error['line']);

			$show .= '<li><span class="line">[Line:'. $error['line'].']</span><span class="file">'.$file.'</span><span class="fun">-> '.$func.'</span></li>';
			$log .= !empty($log) ? ' -> ' : '';$file.':'.$error['line'];
			$log .= $file.':'.$error['line'];
		}
		return array($show, $log);
	}

	public static function db_error($message, $sql) {
		global $_G;

		list($showtrace, $logtrace) = discuz_error::debug_backtrace();

		$title = lang('error', 'db_'.$message);
		$title_msg = lang('error', 'db_error_message');
		$title_sql = lang('error', 'db_query_sql');
		$title_backtrace = lang('error', 'backtrace');
		$title_help = lang('error', 'db_help_link');

		$db = &DB::object();
		$dberrno = $db->errno();
		$dberror = str_replace($db->tablepre,  '', $db->error());
		$sql = dhtmlspecialchars(str_replace($db->tablepre,  '', $sql));

		$msg = '<li>[Type] '.$title.'</li>';
		$msg .= $dberrno ? '<li>['.$dberrno.'] '.$dberror.'</li>' : '';
		$msg .= $sql ? '<li>[Query] '.$sql.'</li>' : '';

		discuz_error::show_error('db', $msg, $showtrace, false);
		unset($msg, $phperror);

		$errormsg = '<b>'.$title.'</b>';
		$errormsg .= "[$dberrno]<br /><b>ERR:</b> $dberror<br />";
		if($sql) {
			$errormsg .= '<b>SQL:</b> '.$sql;
		}
		$errormsg .= "<br />";
		$errormsg .= '<b>PHP:</b> '.$logtrace;

		discuz_error::write_error_log($errormsg);
		exit();

	}

	public static function exception_error($exception) {

		if($exception instanceof DbException) {
			$type = 'db';
		} else {
			$type = 'system';
		}

		if($type == 'db') {
			$errormsg = '('.$exception->getCode().') ';
			$errormsg .= self::sql_clear($exception->getMessage());
			if($exception->getSql()) {
				$errormsg .= '<div class="sql">';
				$errormsg .= self::sql_clear($exception->getSql());
				$errormsg .= '</div>';
			}
		} else {
			$errormsg = $exception->getMessage();
		}

		$trace = $exception->getTrace();
		krsort($trace);

		$trace[] = array('file'=>$exception->getFile(), 'line'=>$exception->getLine(), 'function'=> 'break');
		$phpmsg = [];
		foreach ($trace as $error) {
			if(!empty($error['function'])) {
				$fun = '';
				if(!empty($error['class'])) {
					$fun .= $error['class'].$error['type'];
				}
				$fun .= $error['function'].'(';
				if(!empty($error['args'])) {
					$mark = '';
					foreach($error['args'] as $arg) {
						$fun .= $mark;
						if(is_array($arg)) {
							$fun .= 'Array';
						} elseif(is_bool($arg)) {
							$fun .= $arg ? 'true' : 'false';
						} elseif(is_int($arg)) {
							$fun .= (defined('DISCUZ_DEBUG') && DISCUZ_DEBUG) ? $arg : '%d';
						} elseif(is_float($arg)) {
							$fun .= (defined('DISCUZ_DEBUG') && DISCUZ_DEBUG) ? $arg : '%f';
						} else {
							$fun .= (defined('DISCUZ_DEBUG') && DISCUZ_DEBUG) ? '\''.dhtmlspecialchars(substr(self::clear($arg), 0, 10)).(strlen($arg) > 10 ? ' ...' : '').'\'' : '%s';
						}
						$mark = ', ';
					}
				}

				$fun .= ')';
				$error['function'] = $fun;
			}
			$phpmsg[] = array(
			    'file' => str_replace(array(DISCUZ_ROOT, '\\'), array('', '/'), $error['file']),
			    'line' => $error['line'],
			    'function' => $error['function'],
			);
		}

		self::show_error($type, '<li>'.$errormsg.'</li>', $phpmsg);
		exit();

	}

	public static function show_error($type, $errormsg, $phpmsg = '', $typemsg = '') {
		global $_G;

		ob_end_clean();
		$gzip = getglobal('gzipcompress');
		ob_start($gzip ? 'ob_gzhandler' : null);

		$host = $_SERVER['HTTP_HOST'];
		$title = $type == 'db' ? 'Database' : 'System';
		echo <<<EOT
<!DOCTYPE html>
<html>
<head>
	<title>$title Error</title>
    <meta charset="{$_G['config']['output']['charset']}" />
	<meta name="renderer" content="webkit" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
	<style type="text/css">
	    * { 
	        font-family: sans-serif; 
	        margin: 0;
	        padding: 0;
	    }
        body {
            background-color: #EEEEEE; 
            font-size: 14px
        }
        #container { 
            width: 960px; 
            margin: 39px auto;
            background: #fff;
            border: 1px solid #e6e6e6;
            border-radius: 10px;
            overflow: hidden;
        }
        #message   { 
            width: 960px; 
            color: black; 
        }
        h1 {
            display: block;
            margin: 0;
            width: 100%;
            color: #FFf;
            font-size: 22px;
            text-align: center;
            text-transform: uppercase;
            line-height: 60px;
            background: #ff5151;
        }
        ul{
            padding: 0 30px;
        }
        li {
            list-style: circle;
            color: #666;
        }
        li .line {
            display: inline-block;
            color: #ce507a;
            font-weight: 700;
            font-size: 13px;
        }
        li .file {
            display: inline-block;
            margin: 0 5px;
            color: #a5a5a5;
        }
        li .fun {
            display: inline-block;
            color: #4d93bb;
            font-weight: 700;
        }
        li ul {
            padding: 0 10px;
        }
        li ul li {
            list-style: none;
            font-size: 13px;
            color: #999;
        }
        li li:before {
            content: '► ';
            display: inline-block;
            width: 14px;
        }
        .info {
            color: #000000;
            line-height: 160%;
            padding: 15px 39px;
            margin: 0;
            word-break: break-all;
        }
        .help {
            border-top: 1px solid #e6e6e6;
            color:#999;
            text-align: center;
            padding: 18px;
            word-break: break-all;
        }
        a { 
            color: #aaa; 
            text-decoration: none;
            border-bottom: 1px dashed #ccc;
            margin: 0 5px;
        }
        .title { 
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .sql {
            background: none repeat scroll 0 0 #FFFFCC;
            border: 1px solid #aaaaaa;
            color: #000000;
            font-size: 9pt;
            line-height: 160%;
            margin-top: 10px;
            padding: 4px;
        }
	</style>
</head>
<body>
<div id="container">
<h1>$title Error</h1>
<div class='info'>
    <p class="title"><strong>System Debug</strong></p>
    <ul>$errormsg</ul>
</div>
EOT;
        if (!empty($phpmsg)) {
            echo '<div class="info">';
            echo '<p class="title"><strong>PHP Debug</strong></p><ul>';
            if (is_array($phpmsg)) {
                foreach ($phpmsg as $k => $msg) {
                    echo '<li>';
                    echo '<span class="line">[Line: '.sprintf('%04d', $msg['line']).']</span>';
                    echo '<span class="file"> '.$msg['file'].'</span>';
                    echo '<span class="fun">-> '.$msg['function'].'</span>';
                    echo '</li>';
                }
            } else {
                echo $phpmsg;
            }
            echo '</ul></div>';
        }
        $helplink = '';
        $endmsg = lang('error', 'error_end_message', array('host' => $host));
        echo <<<EOT
<div class="help">$endmsg. $helplink</div>
</div>
</body>
</html>
EOT;
        $exit && exit();

    }

    public static function mobile_show_error($type, $errormsg, $phpmsg) {
        global $_G;

        ob_end_clean();
        ob_start();

        $host = $_SERVER['HTTP_HOST'];
        $phpmsg = trim($phpmsg);
        $title = 'Mobile ' . ($type == 'db' ? 'Database' : 'System');
        echo <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html>
<head>
	<title>$host - $title Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
	<style type="text/css">
	<!--
	body { background-color: white; color: black; }
	UL, LI { margin: 0; padding: 2px; list-style: none; }
	#message   { color: black; background-color: #FFFFCC; }
	#bodytitle { font: 11pt/13pt verdana, arial, sans-serif; height: 20px; vertical-align: top; }
	.bodytext  { font: 8pt/11pt verdana, arial, sans-serif; }
	.help  { font: 12px verdana, arial, sans-serif; color: red;}
	.red  {color: red;}
	a:link     { font: 8pt/11pt verdana, arial, sans-serif; color: red; }
	a:visited  { font: 8pt/11pt verdana, arial, sans-serif; color: #4e4e4e; }
	-->
	</style>
</head>
<body>
<table cellpadding="1" cellspacing="1" id="container">
<tr>
	<td id="bodytitle" width="100%">Discuz! $title Error </td>
</tr>
EOT;

        echo <<<EOT
<tr><td><hr size="1"/></td></tr>
<tr><td class="bodytext">Error messages: </td></tr>
<tr>
	<td class="bodytext" id="message">
		<ul> $errormsg</ul>
	</td>
</tr>
EOT;
        if (!empty($phpmsg) && $type == 'db') {
            echo <<<EOT
<tr><td class="bodytext">&nbsp;</td></tr>
<tr><td class="bodytext">Program messages: </td></tr>
<tr>
	<td class="bodytext">
		<ul> $phpmsg </ul>
	</td>
</tr>
EOT;
        }
        $endmsg = lang('error', 'mobile_error_end_message', array('host' => $host));
        echo <<<EOT
<tr>
	<td class="help"><br />$endmsg</td>
</tr>
</table>
</body>
</html>
EOT;
        $exit && exit();
    }

    public static function clear($message) {
        return str_replace(array("\t", "\r", "\n"), " ", $message);
    }

    public static function sql_clear($message) {
        $message = self::clear($message);
        $message = str_replace(DB::object()->tablepre, '', $message);
        $message = dhtmlspecialchars($message);
        return $message;
    }

    public static function write_error_log($message) {

        $message = discuz_error::clear($message);
        $time = time();
        $file = DISCUZ_ROOT . './data/log/' . date("Ym") . '_errorlog.php';
        $hash = md5($message);

        $uid = getglobal('uid');
        $ip = getglobal('clientip');

        $user = '<b>User:</b> uid=' . intval($uid) . '; IP=' . $ip . '; RIP:' . $_SERVER['REMOTE_ADDR'];
        $uri = 'Request: ' . dhtmlspecialchars(discuz_error::clear($_SERVER['REQUEST_URI']));
        $message = "<?PHP exit;?>\t{$time}\t$message\t$hash\t$user $uri\n";
        if ($fp = @fopen($file, 'rb')) {
            $lastlen = 50000;
            $maxtime = 60 * 10;
            $offset = filesize($file) - $lastlen;
            if ($offset > 0) {
                fseek($fp, $offset);
            }
            if ($data = fread($fp, $lastlen)) {
                $array = explode("\n", $data);
                if (is_array($array))
                    foreach ($array as $key => $val) {
                        $row = explode("\t", $val);
                        if ($row[0] != '<?PHP exit;?>')
                            continue;
                        if ($row[3] == $hash && ($row[1] > $time - $maxtime)) {
                            return;
                        }
                    }
            }
        }
        error_log($message, 3, $file);
    }

}