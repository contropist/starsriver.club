<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_myrepeats {

	var $value = [];

    function __construct() {
		global $_G;
		if(!$_G['uid']) {
			return 0;
		}

		$myrepeatsusergroups = (array)dunserialize($_G['cache']['plugin']['myrepeats']['usergroups']);
		if(in_array('', $myrepeatsusergroups)) {
			$myrepeatsusergroups = [];
		}
		$userlist = [];
		if(!in_array($_G['groupid'], $myrepeatsusergroups)) {
			if(!isset($_G['cookie']['myrepeat_rr'])) {
				$users = count(C::t('#myrepeats#myrepeats')->fetch_all_by_username($_G['username']));
				dsetcookie('myrepeat_rr', 'R'.$users, 86400);
			} else {
				$users = substr($_G['cookie']['myrepeat_rr'], 1);
			}
			if(!$users) {
				return '';
			}
		}

        $this->value['global_usernav_extra1'] = '
            <a id="myrepeats" href="home.php?mod=spacecp&ac=plugin&id=myrepeats:memcp" class="r icon-make-group icon_small"></a>';

/*
		$this->value['global_usernav_extra1'] =
			'<a class="r icon-make-group icon_small" id="myrepeats"><span class="tooltip tr"></span></a>'.
            '<script>
                var myrepeatsmenu=document.createElement("span");
                myrepeatsmenu.id="menu_myrepeats";
                myrepeatsmenu.className="tooltip tr";
                $("myrepeats").appendChild(myrepeatsmenu);
                ajaxget("plugin.php?id=myrepeats:switch&list=yes","menu_myrepeats","ajaxwaitid");
            </script>';*/
	}

	function global_usernav_extra1() {
		return $this->value['global_usernav_extra1'];
	}

}

?>