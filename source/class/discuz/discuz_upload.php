<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


Class discuz_upload{

	public $attach = [];
    public $type = '';
    public $extid = 0;
    public $errorcode = 0;
    public $forcename = '';

	public function __construct() {

	}

	/*
	 * 初始化类，创建存储对象
	 *
	 * $param $attach | [array] -> 文件对象 -> $_FILES['post']
	 * $param $type | [string] -> 文件所在的功能模块 -> forum,group,album 等
	 * $param $extid | [int] -> 文件作用的对象ID，如 groupid, forumid等。
	 * $param $forcename | [string] -> 文件作用类型后缀，如 banner, icon 等。
	 *
	 * */
	function init($attach, $type = 'temp', $extid = 0, $forcename = '') {

	    /* 判断文件是存在 */
		if(!is_array($attach) || empty($attach) || !$this->is_upload_file($attach['tmp_name']) || trim($attach['name']) == '' || $attach['size'] == 0) {
            $this->attach = [];
            $this->errorcode = -1;
            return false;
        }

        $this->type = discuz_upload::check_dir_type($type);
        $this->extid = intval($extid);
        $this->forcename = $forcename;

        $attach['size'] = intval($attach['size']);
        $attach['name'] =  trim($attach['name']);
        $attach['thumb'] = '';
        $attach['ext'] = $this->fileext($attach['name']);

        $attach['name'] =  dhtmlspecialchars($attach['name'], ENT_QUOTES);

        if(strlen($attach['name']) > 90) {
            $attach['name'] = cutstr($attach['name'], 80, '').'.'.$attach['ext'];
        }

        $attach['isimage'] = $this->is_image_ext($attach['ext']);
        $attach['extension'] = $this->get_target_extension($attach['ext']);
        $attach['attachdir'] = $this->get_target_dir($this->type, $extid);
        $attach['attachment'] = $attach['attachdir'].$this->get_target_filename($this->type, $this->extid, $this->forcename).'.'.$attach['extension'];
        $attach['target'] = getglobal('setting/attachdir').'./'.$this->type.'/'.$attach['attachment'];
        $this->attach = & $attach;
        $this->errorcode = 0;
        return true;

	}

    /*
     * 保存文件
     *
     * $param $ignore | [int] -> 是否不进行验证直接存储文件
     * */
	function save($ignore = 0) {

	    /* 直接存储文件 */
		if($ignore) {
			if(!$this->save_to_local($this->attach['tmp_name'], $this->attach['target'])) {
				$this->errorcode = -103;
				return false;
			} else {
				$this->errorcode = 0;
				return true;
			}
		}

        /* 文件格式是否完整 */
		if(empty($this->attach) || empty($this->attach['tmp_name']) || empty($this->attach['target'])) {
			$this->errorcode = -101;

        /* 规定['group', 'album', 'category', 'collection']相关上传的必须为图像 */
		} elseif(in_array($this->type, array('group', 'album', 'category', 'collection')) && !$this->attach['isimage']) {
			$this->errorcode = -102;

		} elseif(in_array($this->type, array('common')) && (!$this->attach['isimage'] && $this->attach['ext'] != 'ext')) {
			$this->errorcode = -102;
		} elseif(!$this->save_to_local($this->attach['tmp_name'], $this->attach['target'])) {
			$this->errorcode = -103;
		} elseif(($this->attach['isimage'] || $this->attach['ext'] == 'swf') && (!$this->attach['imageinfo'] = $this->get_image_info($this->attach['target'], true))) {
			$this->errorcode = -104;
			@unlink($this->attach['target']);
		} else {
			$this->errorcode = 0;
			return true;
		}

		return false;
	}

	function error() {
		return $this->errorcode;
	}

	function errormessage() {
		return lang('error', 'file_upload_error_'.$this->errorcode);
	}

    /*
    * 检查文件存在性
    *
    * $param $filename | [string] -> 文件名称
    * */
	function fileext($filename) {
		return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
	}

    /*
    * 检查图像文件
    *
    * $param $ext | [string] -> 图像允许的后缀
    * */
	function is_image_ext($ext) {
		static $imgext  = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		return in_array($ext, $imgext) ? 1 : 0;
	}

    /*
    * 获取图像信息
    *
    * $param $target | [string] -> 图像地址
    * $param $allowswf | [int] -> 是否允许flash
    * */
	function get_image_info($target, $allowswf = false) {
		$ext = $this->fileext($target);
		$isimage = $this->is_image_ext($ext);
		if(!$isimage && ($ext != 'swf' || !$allowswf)) {
			return false;
		} elseif(!is_readable($target)) {
			return false;
		} elseif($imageinfo = @getimagesize($target)) {
			list($width, $height, $type) = !empty($imageinfo) ? $imageinfo : array('', '', '');
			$size = $width * $height;
			if($size > 16777216 || $size < 16 ) {
				return false;
			} elseif($ext == 'swf' && $type != 4 && $type != 13) {
				return false;
			} elseif($isimage && !in_array($type, array(1,2,3,6,13))) {
				return false;
			} elseif(!$allowswf && ($ext == 'swf' || $type == 4 || $type == 13)) {
				return false;
			}
			return $imageinfo;
		} else {
			return false;
		}
	}

	function is_upload_file($source) {
		return $source && ($source != 'none') && (is_uploaded_file($source) || is_uploaded_file(str_replace('\\\\', '\\', $source)));
	}

	function get_target_filename($type, $extid = 0, $forcename = '') {
		if($type == 'group' || ($type == 'common' && $forcename != '')) {
			$filename = $type.'_'.intval($extid).($forcename != '' ? "_$forcename" : '');
		} else {
			$filename = date('His').strtolower(random(16));
		}
		return $filename;
	}

	function get_target_extension($ext) {
		static $safeext  = array('attach', 'jpg', 'jpeg', 'gif', 'png', 'swf', 'bmp', 'txt', 'zip', 'rar', 'mp3');
		return strtolower(!in_array(strtolower($ext), $safeext) ? 'attach' : $ext);
	}

	function get_target_dir($type, $extid = '', $check_exists = true) {

		$subdir = $subdir1 = $subdir2 = '';
		if(in_array($type,['album','forum','portal','category','profile','collection'])) {
			$subdir1 = date('Ym');
			$subdir2 = date('d');
			$subdir = $subdir1.'/'.$subdir2.'/';
		} elseif($type == 'group' || $type == 'common') {
			$subdir = $subdir1 = substr(md5($extid), 0, 2).'/';
		}

		$check_exists && $this->check_dir_exists($type, $subdir1, $subdir2);

		return $subdir;
	}

	function check_dir_type($type) {
		return !in_array($type, array('forum', 'group', 'album', 'portal', 'common', 'temp', 'category', 'profile', 'collection')) ? 'temp' : $type;
	}

	function check_dir_exists($type = '', $sub1 = '', $sub2 = '') {

		$type = $this->check_dir_type($type);

		$basedir = !getglobal('setting/attachdir') ? (DISCUZ_ROOT.'./data/attachment') : getglobal('setting/attachdir');

		$typedir = $type ? ($basedir.'/'.$type) : '';
		$subdir1  = $type && $sub1 !== '' ?  ($typedir.'/'.$sub1) : '';
		$subdir2  = $sub1 && $sub2 !== '' ?  ($subdir1.'/'.$sub2) : '';

		$res = $subdir2 ? is_dir($subdir2) : ($subdir1 ? is_dir($subdir1) : is_dir($typedir));
		if(!$res) {
			$res = $typedir && $this->make_dir($typedir);
			$res && $subdir1 && ($res = $this->make_dir($subdir1));
			$res && $subdir1 && $subdir2 && ($res = $this->make_dir($subdir2));
		}

		return $res;
	}

	function save_to_local($source, $target) {
		if(!$this->is_upload_file($source)) {
			$succeed = false;
		}elseif(@copy($source, $target)) {
			$succeed = true;
		}elseif(function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)) {
			$succeed = true;
		}elseif (@is_readable($source) && (@$fp_s = fopen($source, 'rb')) && (@$fp_t = fopen($target, 'wb'))) {
			while (!feof($fp_s)) {
				$s = @fread($fp_s, 1024 * 512);
				@fwrite($fp_t, $s);
			}
			fclose($fp_s); fclose($fp_t);
			$succeed = true;
		}
		if($succeed)  {
			$this->errorcode = 0;
			@chmod($target, 0644); @unlink($source);
		} else {
			$this->errorcode = 0;
		}

		return $succeed;
	}

	function make_dir($dir, $index = true) {
		$res = true;
		if(!is_dir($dir)) {
			$res = @mkdir($dir, 0777);
			$index && @touch($dir.'/index.html');
		}
		return $res;
	}
}

?>