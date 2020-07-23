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

        loader = {
            pare : SR('render[role="loader"]'),
            hook : SR('#loader_hook')[0]
        };

        body = SR('render[role="body"]')[0];
        nav = SR('[html-header] #nav')[0];
        banner = SR('[html-header] .banner')[0];
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

            function inni() {

                while (1){

                    if(loader.hook.complete){
                        setTimeout(function () {
                            /* 页面尺寸初始化 */
                            DocAction.resize();

                            /* tooltip初始化 */
                            tooltip_init();

                            /* nav样式初始化 */
                            if(banner){
                                (document.documentElement.scrollTop || document.body.scrollTop || 0) >= (banner.Css.height - nav.Css.height) ? body.addClass('scroll-overhaed') : '';
                            }

                            /* 清除loader遮罩 */
                            for(let self of loader.pare){
                                self.parentElement.removeChild(self);
                            }

                        },1);

                        break;
                    }
                }
            }

            loader.hook.onload = inni();
            loader.hook.src = loader.hook.data('src');
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

            let MasGuideWidth = isUndefined(MasElements.guide) ? 0 : MasElements.guide.Css.width,

                windowSize = 'WL-0';

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

            if(banner){
                SRGlobal.Window.Scroll.Top >= (banner.Css.height - nav.Css.height) ? body.addClass('scroll-overhaed') : body.delClass('scroll-overhaed');
            }

            //nav 滚动效果
            (function () {
                if(nav){
                    nav.addClass('trans-ease');

                    if(SRGlobal.Window.Scroll.Top > banner.clientHeight && SRGlobal.Window.Scroll.DirY === 'down'){
                        nav.addClass('sleep');
                    } else {
                        nav.delClass('sleep');
                    }
                    if(SRGlobal.Window.Scroll.ToBottom && !uconsole){
                        nav.delClass('sleep');
                    }
                }
            })();

            // editor nav滚动效果
            (function () {
                let editor_nav = document.querySelector('#e_controls');

                if(editor_nav){
                    editor_nav.addClass('trans-ease');
                    if(SRGlobal.Window.Scroll.Top > banner.clientHeight && SRGlobal.Window.Scroll.DirY === 'down'){
                            editor_nav.style.transform ='translateY(-' + nav.Css.height +'px)';
                    } else {
                        editor_nav.style.transform ='translateY(0)';
                    }

                    if(SRGlobal.Window.Scroll.ToBottom){
                        editor_nav.style.transform ='translateY(0)';
                    }
                }
            })();

            // console 翻滚效果
            (function () {
                if(uconsole){
                    let scrollrate = 1.3,
                        menu = uconsole.querySelector('.menu'),
                        menu_list = uconsole.querySelector('.menu > ul'),
                        console_title = uconsole.querySelector('.panel > .title'),
                        console_ctl = uconsole.querySelector('.panel > .panel-container');

                    function topaction () {
                        menu.style.transform = 'translate3d(0,0,0)';
                        menu.style.position = '';
                        menu.style.top = '';
                        console_title.style.position = '';
                        console_title.style.top = '';
                        console_title.style.transform = 'translate3d(0,0,0)';
                        console_ctl.style.paddingTop = '0';
                    }

                    if(uconsole.scrollHeight >= SRGlobal.Window.Height*scrollrate && SRGlobal.Window.Height > 390 ){

                        menu_list.style.height = SRGlobal.Window.Height - console_title.Css.height - menu_list.Css.paddingBottom - ((SRGlobal.Window.Height - uconsole.Css.bottom) > 0 ? (SRGlobal.Window.Height - uconsole.Css.bottom) : 0) + 'px';


                        if(SRGlobal.Window.Scroll.Top > uconsole.Css.top){
                            menu.style.position = 'fixed';
                            menu.style.top = '0';
                            console_title.style.position = 'fixed';
                            console_title.style.top = '0';
                            console_ctl.style.paddingTop = '48px';

                            if(SRGlobal.Window.Scroll.DirY === 'down'){
                                menu.style.transform = 'translate3d(0,0,0)';
                                console_title.style.transform = 'translate3d(0,0,0)';
                            }

                            if(SRGlobal.Window.Scroll.DirY === 'up'){
                                nav.addClass('sleep');
                            }

                        } else {
                            topaction ()
                        }

                    }
                    if(SRGlobal.Window.Scroll.Top <= uconsole.Css.top) {
                        topaction ()
                    }
                }
            })();
        },

        wheel: function (evt) {
            let e = evt || window.event;
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
                    let trspct = (50 - MasElements.viewer.scrollTop / MasElements.viewerBannerImg.Css.height * 50);
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