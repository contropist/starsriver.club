<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_nest_data.php 27827 2012-02-15 07:03:43Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_nest_data extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_nest_data';
		$this->_pk    = '';

		parent::__construct();
	}

	public function fetch($targettplname, $tpldirectory) {
		return !empty($targettplname) ? DB::fetch_first('SELECT * FROM '.DB::table($this->_table).' WHERE '.DB::field('targettplname', $targettplname).' AND '.DB::field('tpldirectory', $tpldirectory)) : [];
	}

	public function delete($targettplname, $tpldirectory = null) {

	    global $_G;

        $tpl_suffix = $_G['config']['output']['tpl_suffix'];

		foreach($this->fetch_all($targettplname, $tpldirectory) as $value) {
			$file = ($value['tpldirectory'] ? $value['tpldirectory'].'/' : '').$value['targettplname'];
			@unlink(DISCUZ_ROOT.'./data/nest/'.$file.$tpl_suffix);
			@unlink(DISCUZ_ROOT.'./data/nest/'.$file.$tpl_suffix.'.bak');
			@unlink(DISCUZ_ROOT.'./data/nest/'.$file.'_nest_preview'.$tpl_suffix);
		}
		return DB::delete($this->_table, DB::field('targettplname', $targettplname).($tpldirectory !== null ? ' AND '.DB::field('tpldirectory', $tpldirectory) : ''));
	}

	public function update($targettplname, $tpldirectory, $data) {
		if(!empty($targettplname) && !empty($data) && is_array($data)) {
			return DB::update($this->_table, $data, DB::field('targettplname', $targettplname).' AND '.DB::field('tpldirectory', $tpldirectory));
		}
		return false;
	}

	public function fetch_all($targettplname, $tpldirectory = null) {
		return !empty($targettplname) ? DB::fetch_all('SELECT * FROM '.DB::table($this->_table).' WHERE '.DB::field('targettplname', $targettplname).($tpldirectory !== null ? ' AND '.DB::field('tpldirectory', $tpldirectory) : '')) : [];
	}

	public function count_by_where($wheresql) {
		$wheresql = $wheresql ? ' WHERE '.$wheresql : '';
		return DB::result_first('SELECT COUNT(*) FROM '.DB::table($this->_table).$wheresql);
	}

	public function fetch_all_by_where($wheresql, $ordersql, $start, $limit) {
		$wheresql = $wheresql ? ' WHERE '.$wheresql : '';
		return DB::fetch_all('SELECT * FROM '.DB::table($this->_table).$wheresql.' '.$ordersql.DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
}

?>