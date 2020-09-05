<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/
    
    /**
     *      [Discuz!] (C)2001-2099 Comsenz Inc.
     *      This is NOT a freeware, use is subject to license terms
     *
     *      $Id: discuz_extend.php 30690 2012-06-12 05:57:59Z zhangguosheng $
     */
    
    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    class discuz_extend extends discuz_container {
        
        public $setting;
        public $member;
        public $group;
        public $param;
        
        public function __construct($obj) {
            parent::__construct($obj);
        }
        
        public function __call($name, $p) {
            if (method_exists($this->_obj, $name)) {
                switch (count($p)) {
                    case 0:return $this->_obj->{$name}();
                    case 1:return $this->_obj->{$name}($p[0], $p[1]);
                    case 2:return $this->_obj->{$name}($p[0], $p[1], $p[2]);
                    case 3:return $this->_obj->{$name}($p[0], $p[1], $p[2], $p[3]);
                    case 4:return $this->_obj->{$name}($p[0], $p[1], $p[2], $p[3], $p[4]);
                    case 5:return $this->_obj->{$name}($p[0], $p[1], $p[2], $p[3], $p[4], $p[5]);
                    default:return call_user_func_array([$this->_obj, $name,], $p);
                }
            } else {
                return parent::__call($name, $p);
            }
        }
        
        public function init_base_var() {
            $this->setting = &$this->_obj->setting;
            $this->member = &$this->_obj->member;
            $this->group = &$this->_obj->group;
            $this->param = &$this->_obj->param;
        }
    }