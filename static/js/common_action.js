/***********\
     申明    |
\***********/
    addEvent(document,'DOMContentLoaded',function () {

        SRGlobal = {

            Debugmod : false,

            Window:{
                Width: document.documentElement.clientHeight || document.body.clientHeight,
                Height: document.documentElement.clientHeight || document.body.clientHeight,
                Scroll:{
                    DirX:'',
                    DirY:'',
                    Top:0,
                    Left:0,
                    BeforeTop:0,
                    BeforeLeft:0,
                    ToBottom:0,
                },
            },

            Cursor:{
                type:'', //游标类型
                Top:0,
                Left:0,
                BeforeTop:0,
                BeforeLeft:0,
            },

            Wheel:{
                Dir:'',
            },

            Temp: {
                windowSize:'WL-0'
            }
        };

        body = SR('body')[0];
        nav = SR('#nav')[0];
        banner = SR('.banner')[0];
        banner_on = SR('.banner.off')[0] ? 0 : 1;
        uconsole = SR('#console')[0];
        bottommenu = SR('#bottommenu')[0];

        MasElements = {
            pare:            SR('.Mas')[0],
            guide:           SR('.Mas > .mas-guide')[0],
            guideSwitch:     SR('.Mas > .mas-guide > .mas-guide-switch')[0],
            viewer:          SR('.Mas > .mas-viewer')[0],
            viewerHeader:    SR('.Mas > .mas-viewer > .mas-viewer-header')[0],
            viewerBanner:    SR('.Mas > .mas-viewer > .mas-viewer-header > div > .banner')[0],
            viewerBannerImg: SR('.Mas > .mas-viewer > .mas-viewer-header > div > .banner > img')[0],
            BankS:           SR('.Mas > .mas-viewer > .mas-viewer-header  > div > .bank.type-scroll')[0],
        };

        DocAction.initalize();
        MasAction.initalize();

    });

/***********\
   页面动作  |页面滚动/点击触发的动作
\***********/
    var DocAction = {

        initalize: function () {

            addEvent(document,'click',     DocAction.click);
            addEvent(window,  'scroll',    DocAction.scroll);
            addEvent(window,  'resize',    DocAction.resize);
            addEvent(window,  'mousewheel',DocAction.wheel);
            addEvent(document,'mousewheel',DocAction.wheel);

            if(document.addEventListener){ //firefox
                document.addEventListener('DOMMouseScroll', DocAction.wheel(), false);
            }

            /* 页面尺寸初始化 */
            this.resize();

            /* tooltip初始化 */
            tooltip_init();

            /* nav样式初始化 */
            if(nav && banner_on){
                body.scrollTop < banner.clientHeight ? nav.delClass('white') : nav.addClass('white');
            }
        },

        click: function (evt) { //传入e事件来兼容火狐

            let e =  evt || window.event;
            let triggle = getEventobj();

            SRGlobal.Cursor.Top = e.clientY;
            SRGlobal.Cursor.Left = e.clientX;

            /* modal 在 body 的委托 */
            if(triggle.data('type') === 'modal'){
                triggle.target.modal(triggle.data('action'));
            }
        },

        resize: function () {

            SRGlobal.Window.Height = document.documentElement.clientHeight || document.body.clientHeight;
            SRGlobal.Window.Width = document.documentElement.clientWidth || document.body.clientWidth;

            var MasGuideWidth = isUndefined(MasElements.guide) ? 0 : MasElements.guide.Css.width;

            var windowSize = 'WL-0';

            if(SRGlobal.Window.Width > 350 + MasGuideWidth){
                windowSize = 'WL-3P';
            }
            if(SRGlobal.Window.Width > 470 + MasGuideWidth){
                windowSize = 'WL-4P';
            }
            if(SRGlobal.Window.Width > 760){
                windowSize = 'WL-7P';
            }
            if(SRGlobal.Window.Width > 1320){
                windowSize = 'WL-13P';
            }
            if(SRGlobal.Window.Width > 1910){
                windowSize = 'WL-1K';
            }
            if(SRGlobal.Window.Width > 2550){
                windowSize = 'WL-2K';
            }
            if(SRGlobal.Window.Width > 4080){
                windowSize = 'WL-4K';
            }

            if(SRGlobal.Temp.windowSize !== windowSize){
                SRGlobal.Temp.windowSize = windowSize;
                body.delClass("WL-0 WL-3P WL-4P WL-7P WL-13P WL-1K WL-2K WL-2K WL-4K");
                body.addClass(windowSize);
            }

            if(nav){
                nav.delClass('trans-ease-slow trans-ease');
            }
            if(document.querySelector('#e_controls')) {
                document.querySelector('#e_controls').delClass('trans-ease-slow trans-ease');
            }
            if(document.querySelector('#console')) {
                document.querySelector('#console').delClass('trans-ease-slow trans-ease');
            }
        },

        scroll: function () {

            SRGlobal.Window.Scroll.Height = document.documentElement.scrollHeight || document.body.scrollHeight;
            SRGlobal.Window.Scroll.Width = document.documentElement.scrollWidth || document.body.scrollWidth;
            SRGlobal.Window.Scroll.Top = document.documentElement.scrollTop || document.body.scrollTop || 0;
            SRGlobal.Window.Scroll.Left = document.documentElement.scrollLeft || document.body.scrollLeft || 0;

            if(SRGlobal.Window.Scroll.Top > SRGlobal.Window.Scroll.BeforeTop){
                SRGlobal.Window.Scroll.DirY = 'down'
            } else if(SRGlobal.Window.Scroll.Top < SRGlobal.Window.Scroll.BeforeTop){
                SRGlobal.Window.Scroll.DirY = 'up'
            }

            if(SRGlobal.Window.Scroll.Left > SRGlobal.Window.Scroll.BeforeLeft){
                SRGlobal.Window.Scroll.DirX = 'right'
            } else if(SRGlobal.Window.Scroll.Left < SRGlobal.Window.Scroll.BeforeLeft){
                SRGlobal.Window.Scroll.DirX = 'left'
            }

            setTimeout(SRGlobal.Window.Scroll.BeforeTop = SRGlobal.Window.Scroll.Top , 0);
            setTimeout(SRGlobal.Window.Scroll.BeforeLeft = SRGlobal.Window.Scroll.Left , 0);

            SRGlobal.Window.Scroll.ToBottom = SRGlobal.Window.Scroll.Top + SRGlobal.Window.Height >= SRGlobal.Window.Scroll.Height ? 1 : 0;

            if(SRGlobal.Window.Scroll.ToBottom){
                body.addClass('scroll-tobottom');
            } else {
                body.delClass('scroll-tobottom');
            }

            if(banner_on){
                if(SRGlobal.Window.Scroll.Top > (banner.Css.height - nav.Css.height)){
                    body.addClass('scroll-overhaed');
                } else {
                    body.delClass('scroll-overhaed');
                }
            } else {
                body.addClass('scroll-overhaed');
            }

            //nav 隐藏
            (function () {
                if(nav){
                    nav.addClass('trans-ease');

                    var editor_nav = document.querySelector('#e_controls');

                    if(SRGlobal.Window.Scroll.Top > banner.clientHeight && SRGlobal.Window.Scroll.DirY === 'down'){
                        if(banner_on){nav.addClass('white');}
                        setTimeout(function(){
                                nav.style.transform ='translateY(-' + (nav.Css.height + 10) +'px)';
                            },0
                        );

                        if(editor_nav && editor_nav.getBoundingClientRect().top <= nav.Css.height){
                            editor_nav.addClass('trans-ease');
                            setTimeout(function(){
                                    editor_nav.style.transform ='translateY(-' + nav.Css.height +'px)';
                                },0
                            );
                        }
                    }

                    if(SRGlobal.Window.Scroll.Top < banner.Css.height || SRGlobal.Window.Scroll.DirY === 'up'){
                        nav.style.transform ='translateY(0)';
                        if(editor_nav){
                            editor_nav.addClass('trans-ease');
                            setTimeout(function(){
                                    editor_nav.style.transform ='translateY(0)';
                                },0
                            );
                        }
                    }

                    if(banner_on){
                        if(body.hasClass('scroll-overhaed')){
                            nav.addClass('white');
                        } else {
                            nav.delClass('white');
                        }
                    }

                    if(SRGlobal.Window.Scroll.ToBottom){
                        if(!editor_nav && !uconsole){
                            setTimeout(function(){
                                nav.delClass('trans-ease');
                                nav.addClass('trans-ease-slow');
                                nav.style.transform ='translateY(0)';
                            },0);
                        }
                    }
                }
            })();

            // console 翻滚
            (function () {
                if(uconsole){
                    var scrollrate = 1.3;
                    var menu = uconsole.getElementsByClassName('menu')[0];
                    var menu_list = uconsole.getElementsByTagName('ul')[0];
                    var console_title = uconsole.getElementsByClassName('title')[1];
                    function topaction () {
                        menu.style.transform = 'translate3d(0,0,0)';
                        menu.style.position = '';
                        menu.style.top = '';
                        console_title.style.position = '';
                        console_title.style.top = '';
                        console_title.style.transform = 'translate3d(0,0,0)';
                        uconsole.style.paddingTop = '';
                    }
                    if(uconsole.scrollHeight >= SRGlobal.Window.Width*scrollrate && SRGlobal.Window.Width > 390 ){
                        uconsole.addClass('trans-ease');
                        menu_list.style.height = 'auto';
                        if(body.scrollTop > uconsole.Css.top){
                            menu.style.position = 'fixed';
                            menu.style.top = '0';
                            console_title.style.position = 'fixed';
                            console_title.style.top = '0';
                            uconsole.style.paddingTop = console_title.clientHeight + 'px';
                            if(SRGlobal.Window.Scroll.DirY === 'down'){
                                menu.style.transform = 'translate3d(0,0,0)';
                                console_title.style.transform = 'translate3d(0,0,0)';
                            }
                            if(SRGlobal.Window.Scroll.DirY === 'up'){
                                setTimeout(
                                    function () {
                                        menu.style.transform = 'translate3d(0,' + nav.offsetHeight +'px, 0)';
                                        console_title.style.transform = 'translate3d(0,' + nav.offsetHeight +'px, 0)';
                                    },1
                                );
                            }
                        } else {
                            topaction ()
                        }
                        if(body.scrollTop + SRGlobal.Window.Width >= SRGlobal.Window.Scroll.Height - 50 ){
                            menu_list.style.height = SRGlobal.Window.Width - nav.offsetHeight - console_title.offsetHeight - document.body.Css.marginBottom + 'px';
                        }
                    }
                    if(body.scrollTop <= uconsole.Css.top) {
                        topaction ()
                    }
                }
            })();
        },

        wheel: function (evt) {
            var e = evt || window.event;
            if (e.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件
                if (e.wheelDelta > 0) {SRGlobal.Wheel.Dir="up"}
                if (e.wheelDelta < 0) {SRGlobal.Wheel.Dir="down"}
            } else if (e.detail) {  //Firefox滑轮事件
                if (e.detail> 0) {SRGlobal.Wheel.Dir="down"}
                if (e.detail< 0) {SRGlobal.Wheel.Dir="up"}
            }
        },

    };

    var MasAction = {

        initalize: function () {

            addEvent(MasElements.viewer,'scroll',MasAction.viewerScroll);
            addEvent(window,'resize',MasAction.bannerImgResize);

            if(MasElements.viewerBannerImg){
                MasElements.viewerBannerImg.onload = function () {
                    setTimeout(MasAction.bannerImgResize(),10);
                };
                setTimeout(MasAction.bannerImgResize(),10);
            }
        },

        viewerScroll: function () {
            if(MasElements.viewer && MasElements.viewerBanner.Css.height !== 0){
                if(MasElements.viewer.scrollTop >= MasElements.viewerHeader.Css.height){
                    body.addClass('scroll-overhaed');
                } else {
                    body.delClass('scroll-overhaed');
                }

                if(!body.hasClass('scroll-overhaed')){
                    var trspct = (50 - MasElements.viewer.scrollTop / MasElements.viewerBannerImg.Css.height * 50);
                    MasElements.viewerBannerImg.style.transform = 'translate(-50%, -' + trspct + '%)';
                }
            }
        },

        bannerImgResize: function () {
            if(MasElements.viewerBannerImg){
                if(MasElements.viewerBannerImg.naturalWidth / MasElements.viewerBannerImg.naturalHeight >= MasElements.viewerBanner.Css.width / MasElements.viewerBanner.Css.height){
                    MasElements.viewerBannerImg.style.width = "auto";
                    MasElements.viewerBannerImg.style.height = "100%";
                } else {
                    MasElements.viewerBannerImg.style.width = "100%";
                    MasElements.viewerBannerImg.style.height = "auto";
                }
            }
        }
    };