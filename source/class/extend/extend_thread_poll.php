<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: extend_thread_poll.php 36284 2016-12-12 00:47:50Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class extend_thread_poll extends extend_thread_base {

	public $pollarray;
	
	public $pollimgs;

	public function before_newthread($parameters){

		$polloption = $_GET['tpolloption'] == 2 ? explode("\n", $_GET['polloptions']) : $_GET['polloption'];
		foreach($polloption as $key => $value) {
			$polloption[$key] = censor($polloption[$key]);
			if(trim($value) === '') {
				unset($polloption[$key]);
			}
		}

		$maxpolloptions = $this->setting['maxpolloptions'];
		if(count($polloption) > $maxpolloptions) {
			showmessage('post_poll_option_toomany', '', array('maxpolloptions' => $maxpolloptions));
		} elseif(count($polloption) < 2) {
			showmessage('post_poll_inputmore');
		}

		$curpolloption = count($polloption);
		$this->pollarray['maxchoices'] = empty($_GET['maxchoices']) ? 0 : ($_GET['maxchoices'] > $curpolloption ? $curpolloption : $_GET['maxchoices']);
		$this->pollarray['multiple'] = empty($_GET['maxchoices']) || $_GET['maxchoices'] == 1 ? 0 : 1;
		$this->pollarray['options'] = $polloption;
		$this->pollarray['visible'] = empty($_GET['visibilitypoll']);
		$this->pollarray['overt'] = !empty($_GET['overt']);
		$this->pollarray['pollimage'] = $_GET['pollimage'];
		$this->pollarray['isimage'] = 0;

		if(preg_match("/^\d*$/", trim($_GET['expiration']))) {
			if(empty($_GET['expiration'])) {
				$this->pollarray['expiration'] = 0;
			} else {
				$this->pollarray['expiration'] = TIMESTAMP + 86400 * $_GET['expiration'];
			}
		} else {
			showmessage('poll_maxchoices_expiration_invalid');
		}
		if($_GET['polloptions'] || $_GET['polloption']) {
			$this->param['extramessage'] = "\t".implode("\t", $_GET['tpolloption'] == 2 ? explode("\n", $_GET['polloptions']) : $_GET['polloption']);
		}
	}

	public function after_newthread() {
	    
        foreach($this->pollarray['options'] as $ppkey => $polloptvalue) {
			$polloptvalue = dhtmlspecialchars(trim($polloptvalue));
			$polloptionid = C::t('forum_polloption')->insert(array('tid' => $this->tid, 'polloption' => $polloptvalue), true);
			if($this->pollarray['pollimage'][$ppkey]) {
				C::t('forum_polloption_image')->update($this->pollarray['pollimage'][$ppkey], array('poid' => $polloptionid, 'tid' => $this->tid, 'pid' => $this->pid));
				$this->pollarray['isimage'] = 1;
			}
		}
        
        C::t('forum_poll')->insert([
            'tid'         => $this->tid,
            'multiple'    => $this->pollarray['multiple'],
            'visible'     => $this->pollarray['visible'],
            'maxchoices'  => $this->pollarray['maxchoices'],
            'expiration'  => $this->pollarray['expiration'],
            'overt'       => $this->pollarray['overt'],
            'isimage'     => $this->pollarray['isimage'],
        ]);
	}

	public function before_feed() {
	    
	    global $_G;
        
        $poll_options = C::t('forum_polloption')->fetch_all_by_tid($this->tid);
        $poll_images = C::t('forum_polloption_image')->fetch_all_by_tid($this->tid);
        $poll_ipop = [];
        $poll_ul = '';
        
        foreach ($poll_options as $option){
            $poll_ipop[$option['polloptionid']]['str'] = $option['polloption'];
            $poll_ul .= '<li>'.$option['polloption'].'</li>';
        }
        
        foreach ($poll_images as $img){
            $poll_ipop[$img['poid']]['image'] = $img;
        }
        
        $this->feed = [
            'icon'           => 'poll',
            'title_template' => 'thread_poll',
            'title_data'     => [
                'tid'   => $this->tid,
                'tsub'  => $this->param['subject'],
                'tlink' => 'forum.php?mod=viewthread&tid=' . $this->tid,
            ],
            'body_template'  => 'thread_poll',
            'body_data'      => [
                'tid'   => $this->tid,
                'tsub'  => $this->param['subject'],
                'tlink' => 'forum.php?mod=viewthread&tid=' . $this->tid,
                
                'uid'     => $_G['uid'],
                'ulink'   => 'home.php?mod=space&uid=' . $_G['uid'],
                'uname'   => $_G['username'],
                'uavatar' => avatar($_G['uid'], 'small', true),
                
                'message' => messagecutstr(!$this->param['readperm'] ? $this->param['message'] : '', 300),
                'option'  => '<ul class="feed-element-poll-options">' . $poll_ul . '</ul>',
                
                'expend0'  => '',
                'expend1'  => '',
                'expend2'  => '',
                'expend3'  => '',
                'expend4'  => '',
                'expend5'  => '',
                'expend6'  => '',
                'expend7'  => '',
            ],
        ];
        
        if ($this->pollarray['isimage']) {
            require_once libfile('function/home');
            foreach ($poll_ipop as $option) {
                if (!empty($option['image'])) {
                    $img_info = $option['image'];
                    $this->feed['body_data']['imgs'][] = [
                        'img'      => pic_get($img_info['attachment'], 'forum', $img_info['thumb'], $img_info['remote']),
                        'img_id'   => $img_info['pid'],
                        'img_url'  => 'forum.php?mod=viewthread&tid=' . $this->tid,
                        'img_name' => $option['str'] ? $option['str'] : $img_info['filename'],
                    ];
                }
            }
            $this->feed['body_data']['imgnum'] = sizeof($this->feed['body_data']['imgs']);
        }
    }
    
    public function before_editpost($parameters) {
		$isfirstpost = $this->post['first'] ? 1 : 0;
		$isorigauthor = $this->member['uid'] && $this->member['uid'] == $this->post['authorid'];
		if($isfirstpost) {
			if($this->thread['special'] == 1 && ($this->group['alloweditpoll'] || $isorigauthor) && !empty($_GET['polls'])) {
				$pollarray = [];
				foreach($_GET['polloption'] as $key => $val) {
					if(trim($val) === '') {
						unset($_GET['polloption'][$key]);
					}
				}
				$pollarray['options'] = $_GET['polloption'];
				if($pollarray['options']) {
					if(count($pollarray['options']) > $this->setting['maxpolloptions']) {
						showmessage('post_poll_option_toomany', '', array('maxpolloptions' => $this->setting['maxpolloptions']));
					}
					foreach($pollarray['options'] as $key => $value) {
						$pollarray['options'][$key] = censor($pollarray['options'][$key]);
						if(!trim($value)) {
							C::t('forum_polloption')->delete_safe_tid($this->thread['tid'], $key);
							unset($pollarray['options'][$key]);
						}
					}
					$this->param['threadupdatearr']['special'] = 1;
					foreach($_GET['displayorder'] as $key => $value) {
						if(preg_match("/^-?\d*$/", $value)) {
							$pollarray['displayorder'][$key] = $value;
						}
					}
					$curpolloption = count($pollarray['options']);
					$pollarray['maxchoices'] = empty($_GET['maxchoices']) ? 0 : ($_GET['maxchoices'] > $curpolloption ? $curpolloption : $_GET['maxchoices']);
					$pollarray['multiple'] = empty($_GET['maxchoices']) || $_GET['maxchoices'] == 1 ? 0 : 1;
					$pollarray['visible'] = empty($_GET['visibilitypoll']);
					$pollarray['expiration'] = $_GET['expiration'];
					$pollarray['overt'] = !empty($_GET['overt']);
					$pollarray['pollimage'] = $_GET['pollimage'];
					foreach($_GET['polloptionid'] as $key => $value) {
						if(!preg_match("/^\d*$/", $value)) {
							showmessage('submit_invalid');
						}
					}
					$expiration = intval($_GET['expiration']);
					if($close) {
						$pollarray['expiration'] = TIMESTAMP;
					} elseif($expiration) {
						if(empty($pollarray['expiration'])) {
							$pollarray['expiration'] = 0;
						} else {
							$pollarray['expiration'] = TIMESTAMP + 86400 * $expiration;
						}
					}
					$optid = [];
					$query = C::t('forum_polloption')->fetch_all_by_tid($this->thread['tid']);
					foreach($query as $tempoptid) {
						$optid[] = $tempoptid['polloptionid'];
					}
					foreach($pollarray['options'] as $key => $value) {
						$value = dhtmlspecialchars(trim($value));
						if(in_array($_GET['polloptionid'][$key], $optid)) {
							if($this->group['alloweditpoll']) {
								C::t('forum_polloption')->update_safe_tid($_GET['polloptionid'][$key], $this->thread['tid'], $pollarray['displayorder'][$key], $value);
							} else {
								C::t('forum_polloption')->update_safe_tid($_GET['polloptionid'][$key], $this->thread['tid'], $pollarray['displayorder'][$key]);
							}
						} else {
							$polloptionid = C::t('forum_polloption')->insert(array('tid' => $this->thread['tid'], 'displayorder' => $pollarray['displayorder'][$key], 'polloption' => $value), true);
							if($pollarray['pollimage'][$key]) {
								C::t('forum_polloption_image')->update($pollarray['pollimage'][$key], array('poid' => $polloptionid, 'tid' => $this->thread['tid'], 'pid' => $this->post['pid']));
								$pollarray['isimage'] = 1;
							}
						}
					}
					
					C::t('forum_poll')->update($this->thread['tid'], [
                        'multiple'    => $pollarray['multiple'],
                        'visible'     => $pollarray['visible'],
                        'maxchoices'  => $pollarray['maxchoices'],
                        'expiration'  => $pollarray['expiration'],
                        'overt'       => $pollarray['overt'],
                        'isimage'     => $pollarray['isimage'] ? 1 : 0,
                    ]);
					
				} else {
					$this->param['threadupdatearr']['special'] = 0;
					C::t('forum_poll')->delete($this->thread['tid']);
					C::t('forum_polloption')->delete_safe_tid($this->thread['tid']);
				}
			}
		}
	}
}

?>