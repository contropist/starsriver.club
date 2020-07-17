/***********\
    申明表   |
\***********/
    var SRGlobal = {

        Debugmod : false,

        Window:{
            Width:'',
            Height:'',
            Scroll:{
                Dir:'',
                Pos:{
                    Top:0,
                    Left:0,
                }
            },
        },

        Cursor:{
            type:'', //游标类型
            Pos:{    //游标位置 Unit[px]
                Top:0,
                Left:0,
            },
        },

        Wheel:{
            Dir:'',
        },

        Temp: {
            Base_Style:'WL-0'
        }
    };

    doc_code_height = 390;   //代码容器默认高度
    doc_cor_scrollTop = 0;   //即时滚动高度
    doc_pre_scrollTop = 0;   //延时滚动高度
    doc_cor_scrollLeft = 0;  //即时滚动宽度
    doc_pre_scrollLeft = 0;  //延时滚动宽度

    addEvent(document,'DOMContentLoaded',function () {

        nav =  document.querySelector('#nav');

        banner = document.querySelector('.banner');
        banner_on = document.querySelector('.banner.off') ? 0 : 1 ;
        uconsole = document.getElementById('console');
        bottommenu = document.getElementById('bottommenu');

        addEvent(document,'click',doc_onclick);
        addEvent(window,'scroll',doc_onscroll);
        window.onresize = function () {doc_resize();};
        if(document.addEventListener){ //firefox
            document.addEventListener('DOMMouseScroll', doc_onwheel, false);
        }
        window.onMousewheel = document.onMousewheel = doc_onwheel; // IE ,Chrome

        doc_resize();
        doc_initalize();

    });

/***********\
    Expand   |
\***********/

    Object.defineProperty(HTMLElement.prototype, 'Css', {
        get: function() {
            var self = this;
            function calcstyle(sty) {
                if (self.currentStyle) {
                    return self.currentStyle[sty];
                }
                else {
                    return window.getComputedStyle(self, null)[sty];
                }
            }
            return {
                width: self.scrollWidth || self.offsetWidth,
                height: self.scrollHeight || self.offsetHeight,
                boxSizing: parseFloat(calcstyle('boxSizing')),
                paddingTop: parseFloat(calcstyle('paddingTop')),
                paddingLeft: parseFloat(calcstyle('paddingLeft')),
                paddingBottom: parseFloat(calcstyle('paddingBottom')),
                paddingRight: parseFloat(calcstyle('paddingRight')),
                marginTop: parseFloat(calcstyle('marginTop')),
                marginLeft: parseFloat(calcstyle('marginLeft')),
                marginBottom: parseFloat(calcstyle('marginBottom')),
                marginRight: parseFloat(calcstyle('marginRight')),
                borderTop: parseFloat(calcstyle('borderTopWidth')),
                borderLeft: parseFloat(calcstyle('borderLeftWidth')),
                borderBottom: parseFloat(calcstyle('borderBottomWidth')),
                borderRight: parseFloat(calcstyle('borderRightWidth')),

                color: parseFloat(calcstyle('color')),
                fontSize: parseFloat(calcstyle('fontSize')),

                background: parseFloat(calcstyle('background'))

            };
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'hasClass', {
        get : function () {
            return function (arg) {
                return this && this.nodeType === 1 && this.className.split(/\s+/).indexOf(arg) !== -1;
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'addClass', {
        get : function () {
            return function (arg) {
                var cls = arg.split(' ');
                for (var i = 0; i < cls.length; i++) {
                    !this.hasClass(cls[i]) ? this.classList.add(cls[i]) : 0;
                }
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'delClass', {
        get : function () {
            return function (arg) {
                var cls = arg.split(' ');
                for (var i = 0; i < cls.length; i++) {
                    this.hasClass(cls[i]) ? this.classList.remove(cls[i]) : 0;
                }
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'trgClass', {
        get : function () {
            return function (arg) {
                var cls = arg.split(' ');
                for (var i = 0; i < cls.length; i++) {
                    this.hasClass(cls[i]) ? this.classList.remove(cls[i]) : this.classList.add(cls[i]);
                }
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'data', {
        get : function () {
            var self = this;
            return function (arg) {
                return self.getAttribute('data-'+arg);
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'target', {
        get : function () {
            var tgarg = this.data('target');
            return tgarg ? document.querySelector(tgarg) : null;
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'loadJS', {
        get : function () {
            var that = this;
            return function(arg) {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = arg;
                that.appendChild(e);
                return e;
            }
        }
    });

    Object.defineProperty(HTMLElement.prototype, 'modal', {
        get : function () {
            var that = this;
            return function(show) {
                switch (show) {
                    case 'hide':that.delClass('active'); break;
                    case 'show':that.addClass('active'); break;
                }
            }
        }
    });

    function stopBubble() {
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.cancelBubble = true;
    }

    function inArray(cn,array){
        for(var i = 0; i < array.length; i++){
            if(cn === array[i]) return 1;
        }
    }


/***********\
    Tool    |
\***********/
    function SR(obj) {
        return document.querySelectorAll(obj)
    }
    function addEvent(obj, evt, func) {
        if(obj.addEventListener) {
            obj.addEventListener(evt, func, false);
        } else if(obj.attachEvent) {
            obj.attachEvent('on' + evt, func);
        }
    }
    function delEvent(obj, evt, func) {
        if(obj.removeEventListener) {
            obj.removeEventListener(evt, func, false);
        } else if(obj.detachEvent) {
            obj.detachEvent('on' + evt, func);
        }
    }

    function getEvent() {
        if(document.all) return window.event;
        func = getEvent.caller;
        while(func !== null) {
            var arg = func.arguments[0];
            if (arg) {
                if((arg.constructor  === Event || arg.constructor === MouseEvent) || (typeof(arg) === "object" && arg.preventDefault && arg.stopPropagation)) {
                    return arg;
                }
            }
            func=func.caller;
        }
        return null;
    }
    function getEventobj() {
        return getEvent().srcElement || getEvent().target;
    }
    function addLink(e,link) {
        e.onclick = function () {
            window.open(link);
        }
    }

/***********\
  功能模块   | 解决跨域图像后期处理问题
\***********/
  /*远程图像转换 */ // 解决跨域图像后期处理问题
    function Base64Image(img) {
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage( img, 0, 0 );
        localStorage.setItem( "savedImageData", canvas.toDataURL("image/png") );
    }
  /* 生成随机字符串 */ //（是否仅大写字母+数字，是否随机长度，固定长度/最短，最长）
    function Rand_str(onlym, randomFlag, min, max){
        var arr , range = min;
        if(onlym){
            arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
        } else {
            arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']}
        if(randomFlag){
            range = Math.round(Math.random() * (max-min)) + min;
        }
        var str = '';
        for(var i=0; i<range; i++){
            str += arr[Math.round(Math.random() * (arr.length-1))];
        }
        return str;
    }

/***********\
   节点属性   |获取指定节点的一些属性并返回值
\***********/

    function _Top(e) {return e.getBoundingClientRect().top + window.scrollY;}    //绝对顶高
    function _Left(e) {return  e.getBoundingClientRect().left + window.scrollX;} //绝对左高
    function _Scroll_top(obj) { //滚动高度
        e = obj;
        if(e){return  e.pageYOffset || e.documentElement.scrollTop;}
        else {return  window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;}
    }
    function _Scroll_left(obj) { //左端滚动高度
        e = obj;
        if(e){return  e.pageXOffset || e.documentElement.scrollLeft;}
        else {return  window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft;}
    }


    function _Cursor_pos(e) { // 获取事件发生时的鼠标坐标
        e =  e || window.event;
        return {
            x : e.clientX,
            y : e.clientY
        }
    }

    function _Scroll_dir() { // 获取页面滚动方向
        var scroll_dir;
        doc_cor_scrollTop = _Scroll_top();
        doc_cor_scrollLeft = _Scroll_left();
        if(doc_cor_scrollTop > doc_pre_scrollTop){scroll_dir = 'b'}
        if(doc_cor_scrollTop < doc_pre_scrollTop){scroll_dir = 't'}

        if(doc_cor_scrollLeft > doc_pre_scrollLeft){scroll_dir = 'r'}
        if(doc_cor_scrollLeft < doc_pre_scrollLeft){scroll_dir = 'l'}
        setTimeout(doc_pre_scrollTop = doc_cor_scrollTop , 0); //存储当前滚动高度，当下次滚动时进行比较
        setTimeout(doc_pre_scrollLeft = doc_cor_scrollLeft , 0);

        return scroll_dir;
    }
    function _Wheel_dir(event) { // 获取滚轮滚动方向
        var e = event || window.event;
        var dir;
        if (e.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件
            if (e.wheelDelta > 0) {dir="up"}
            if (e.wheelDelta < 0) {dir="down"}
        } else if (e.detail) {  //Firefox滑轮事件
            if (e.detail> 0) {dir="down"}
            if (e.detail< 0) {dir="up"}
        }
        return dir;
    }
    function _Data(obj,datname) { //返回 data-v 的值
        data = isUndefined(obj.getAttribute(datname)) ? '' : obj.getAttribute(datname);
        return data;
    }

/***********\
    tools   |
\***********/ /*触发器 和 被折叠内容位于同一父级节点 */
  /* slider幻灯组件 2017-10-17 zhangyu 2692284716@qq.com
  *    这里使用了图片幻灯组件,可以同时加载多个播放组件
  *    可以配置播放速度，thumb 颜色
  *     ; var slideSpeed = 6000
  *     ; var slideSwitchbgColor = '#fff'
  *     ; var slideSwitchHiColor = 'rgba(0,205,225,1)'
  *    需要预先载入图片资源 利用框架循环如下
  *     ; var slideImgs = []
  *     ; var slideImgLinks = []
  *     ; var slideImgTexts = []
  *     ; //循环开始
  *     ;   slideImgs[<!--{echo $k}-->] = '$svalue[image]'
  *     ;   slideImgLinks[<!--{echo $k}-->] = '{$svalue[url]}'
  *     ;   slideImgTexts[<!--{echo $k}-->] = '$svalue[subject]'
  *     ; //循环结束
  */
    function imgslider() {
        if (isUndefined(sliderun)) {var sliderun = 1;}
        var s = {
            rid :Rand_str(true, false, 7),
            imgLoad : [],
            imgs : isUndefined(slideImgs) ? null : slideImgs,
            imgnum : isUndefined(slideImgs) ? 0 : slideImgs.length,
            imgLinks : isUndefined(slideImgLinks) ? null : slideImgLinks,
            imgTexts : isUndefined(slideImgTexts) ? null : slideImgTexts,
            slideSpeed : isUndefined(slideSpeed) ? 6000 : slideSpeed,
            slideSwitchbgColor : isUndefined(slideSwitchbgColor) ? '#ffffff' : slideSwitchbgColor,
            slideSwitchHiColor : isUndefined(slideSwitchHiColor) ? '#00CDE1' : slideSwitchHiColor,
            currentImg : 0,
            prevImg : 0,
            imgLoaded : 0,
            st : null,
            pos : 0,
        };
        if (!s.imgnum) {return;}

        document.write('<div id="slider_' + s.rid + '">');
        document.write('<ul class="img-panel trans-ease-slow">');
        document.write(typeof IMGDIR !== 'undefined' ? '' : '<div class="pacman"><p></p><p></p><p></p><p></p><p></p></div>' + '<span class="pct">0%</span>');
        document.write('</ul>');
        document.write('<i class="shadow"></i>');
        document.write('<i class="threadtitle trans-ease"></i>');
        document.write('<i class="thumbs trans-ease-quick pxa-radius-a"></i>');
        document.write('</div>');

        var slider = document.getElementById('slider_' + s.rid);
        var loader = slider.getElementsByClassName('pct')[0];
        var current_title = slider.getElementsByClassName('threadtitle')[0];
        var img_panel = slider.getElementsByClassName('img-panel')[0];
        var thumb_list = slider.getElementsByClassName('thumbs')[0];

        s.initalize = function () {
            img_panel.innerHTML = '';
            img_panel.style.width = s.imgs.length * 100 + '%';
            img_panel.onmousedown = function () {

            };
            for (i = 1; i < s.imgnum; i++) {
                thumb = document.createElement('a');
                thumb.i = i;
                thumb.id = 'thumb_' + i + s.rid;
                thumb.style.background = s.slideSwitchbgColor;
                thumb.onclick = function () {s.switchImg(this);};
                thumb_list.appendChild(thumb);
                s.imgLoad[i] = new Image();
                s.imgLoad[i].src = s.imgs[i];
                s.imgLoad[i].title = s.imgTexts[i];
                s.imgLoaded++;
                link = document.createElement('li');
                link.title = s.imgTexts[i];
                link.style.width = 100 / s.imgnum + '%';
                addLink(link, s.imgLinks[i]);
                link.innerHTML = '<img src="' + s.imgs[i] + '">';
                img_panel.appendChild(link);
            }
            if (s.imgLoaded < s.imgnum - 1) {
                loader.innerHTML = (parseInt(s.imgLoad.length / s.imgnum * 100)) + '%';
                setTimeout(function () {s.loadCheck();}, 100);
            } else {
                s.switchImg();
            }
        };

        s.moveArea = function () {
            s.pos = - (s.currentImg - 1) * 100 / s.imgnum;
            img_panel.style.transform ='translate3d(' + s.pos + '%, 0, 0)';
            document.getElementById('thumb_' + s.currentImg + s.rid).style.backgroundColor = s.slideSwitchHiColor;
            current_title.style.opacity = 0;
            setTimeout(function () {current_title.innerHTML = s.imgLoad[s.currentImg].title;}, 100);
            setTimeout(function () {current_title.style.opacity = 1;}, 200);
        };
        s.switchImg = function (obj) {
            var i = obj ? obj.i : '';
            if (!i) {
                s.currentImg++;
                s.currentImg = s.currentImg < s.imgnum ? s.currentImg : 1;
            } else {
                s.currentImg = i;
            }
            if (s.prevImg) {
                document.getElementById('thumb_' + s.prevImg + s.rid).style.backgroundColor = s.slideSwitchbgColor;
            }
            s.prevImg = s.currentImg;
            s.moveArea();
            s.interval();
        };
        s.interval = function () {
            clearInterval(s.st);
            s.st = setInterval(function () {
                s.switchImg();
            }, s.slideSpeed);
        };
        s.initalize();
    }

  /* tooltip组件 2017-9-27 zhangyu 2692284716@qq.com
  *  通过 tooltip_init(obj); 来初始化，obj如果为空则代表对全局元素进行初始化
  *     使用的是嵌套组合而非全局定位，tooltip节点位于 hover 节点之内,利用 data-*传值 ，时间以毫秒为单位
  *     data-pos:    默认auto,            可填方向缩写 如tr = top-right; 对于内部元素tip，有 i1，i2,i3,i4 四个位置
  *     data-animal: 默认ease-in-out效果 ，可填 jumpout ,outback ,popout ,past ,zline ,pasts ,zlines ,pmX ,pmY ,emX ,emY;
  *     data-delay:  默认延迟0;
  */
    tooltip = {
        addaction: function (e) {
            if(e.innerHTML === "" || e.innerHTML.length === 0){
                e.parentNode.removeChild(e);
            } else {
                addEvent(e.parentNode,'mouseenter',this.show);
                addEvent(e.parentNode,'mouseleave',this.hide);
                if(_Data(e,'data-pos')){
                    e.addClass(_Data(e,'data-pos'));
                }
                this.styleinit(e,'1');
            }
        },

        show: function () {
            var cut = getEventobj();
            var tg = cut.getElementsByClassName('tooltip')[0];
            tooltip.styleinit(tg,'1');
            setTimeout(function () {
                tg.style.visibility = 'visible';
                tg.style.transform = 'scale(1,1)';
                tg.style.opacity = '1';
                tg.style.margin = 0;
            },0);
        },
        hide: function () {
            var cut = getEventobj();
            var tg = cut.getElementsByClassName('tooltip')[0];
            var lly = tooltip.styleget(tg)['du'];
            tooltip.styleinit(tg,'2');
            setTimeout(function () {
                tg.style.visibility = 'hidden';
                tg.style.opacity = '0';
            },0);
            setTimeout(function () {tooltip.styleinit(tg,'1');},lly);
        },
        styleinit: function (target,p) {
            var sty = tooltip.styleget(target);
            var skew = 10;
            lock_size(target,'','auto');
            if(sty['pos'] === 'auto'){
                target.className = 'tooltip ' + this.pos(target);
                sty['pos'] = this.pos(target);
            }
            switch(sty['animal']){
                case '':rate = 1;target.style.transitionTimingFunction = 'ease-in-out';break;
                case 'ease':rate = 1.5;target.style.transitionTimingFunction = 'ease';break;
                case 'linear':rate = 1.5;target.style.transitionTimingFunction = 'linear';break;

                /*以下的动画位于 动画初始化 区域*/
                case 'jumpout':Hover.Animation.init.jumpout(target);rate = 2;break;
                case 'outback':Hover.Animation.init.outback(target);rate = 2;break;
                case 'popout':Hover.Animation.init.popout(target);rate = 0;break;
                case 'past':Hover.Animation.init.past(target);rate = 0;break;
                case 'zline':Hover.Animation.init.zline(target);rate = 0;break;
                case 'pasts':Hover.Animation.init.pasts(target,p);rate = 0;break;
                case 'zlines':Hover.Animation.init.zlines(target,p);rate = 0;break;
                case 'pmX':Hover.Animation.init.pmX(target);rate = 0;break;
                case 'pmY':Hover.Animation.init.pmY(target);rate = 0;break;
                case 'emX':Hover.Animation.init.emX(target);rate = 0;break;
                case 'emY':Hover.Animation.init.emY(target);rate = 0;break;
            }
            switch (sty['pos']){
                case 'tl':case 'tr':target.style.marginBottom = skew*rate +'px'; break;
                case 'bl':case 'br':target.style.marginTop = skew*rate +'px'; break;
                case 'lt':case 'lb':target.style.marginRight = skew*rate +'px'; break;
                case 'rt':case 'rb':target.style.marginLeft = skew*rate +'px'; break;
            }
            target.style.transitionDelay = sty['delay'] + 'ms';
            target.style.transitionDuration = sty['du'] > 500 ? 500 + 'ms' : sty['du'] +'ms';
        },
        styleget: function (e) {
            var pos    = _Data(e,'data-pos') ? _Data(e,'data-pos') : 'auto';
            var animal = _Data(e,'data-animal') ? _Data(e,'data-animal') : '';
            var dly    = _Data(e,'data-delay') ? _Data(e,'data-delay') : '0';
            var du     = _Data(e,'data-du') ? _Data(e,'data-du') : '100';
            var ignore = _Data(e,'data-ignore') ? 1 : null;
            return {'pos':pos, 'animal': animal, 'delay': dly, 'du': du}
        },
        pos: function (e) {
            e = e.parentNode;
            var h = document.documentElement.clientHeight;
            var w = document.documentElement.clientWidth;
            var pos = e.getBoundingClientRect();
            var top = (pos.top + pos.bottom)/2;
            var left = (pos.right + pos.left)/2;
            var l = left/w*2, t = top/h*2 ,r = 2-l, b = 2-t;
            return t >= 1 ? (l >= 1 ? (t >= l ? 'tr' : 'lb') : (t >= r ? 'tl' : 'rt')) : (l >= 1 ? (b > l ? 'br' : 'lt') : (b >= r ? 'bl' : 'rt'));
        }
    };
    function tooltip_init(obj) {
        var tooltips = obj ? obj.querySelectorAll('.tooltip') : document.querySelectorAll('.tooltip');
        for(var i =0 ; i<tooltips.length; i++){
            if (!_Data(tooltips[i],'data-ignore')){
                tooltip.addaction(tooltips[i]);
            }
        }
    }

  /* folder折叠触发器组件 2017-8-31 zhangyu 2692284716@qq.com
  * folder:
  *     trigger     : 触发器元素（触发按钮）
  *     tag         : 被折叠/展开的元素的 tag/class/id
  *     fh          : 被折叠/展开元素的折叠高度
  *     disposable  : 是否只能折叠/展开一次
  * fold:
  *     tag   : 元素的识别关键池
  *     fh    : 折叠后高度
  */
    function fold(tag,h,onetime){
        var fh = h ? h : '0' ;
        var tg = document.querySelectorAll(tag);
        if(tg){
            for (var i = 0; i< tg.length; i++){
                if( tg[i].scrollHeight > fh ){
                    var fd = document.createElement('a');
                    fd.className = 'unfold';
                    fd.setAttribute('data-foldbtn','');
                    fd.onclick = function () {folder( tag ,fh ,onetime);};
                    tg[i].style.height = fh+'px';
                    tg[i].style.boxSizing = 'border-box';
                    tg[i].parentNode.appendChild(fd);
                }
            }
        }
    }
    function folder(tag,h,onetime) {
        var  trigger = getEventobj(),
             tg = trigger.parentNode.querySelector(tag),
             rh = tg.scrollHeight + tg.offsetHeight - tg.clientHeight, //真实高度
             sh = tg.offsetHeight, //可视高度
             fh = h ? h : '0';
        if(rh > fh){
            if(!onetime){
                if (sh <= fh) {
                    tg.style.maxHeight = '100%';
                    tg.style.height = sh + 'px';
                    setTimeout(function () {
                        tg.style.height = rh + 'px';
                        trigger.delClass('unfold');
                        trigger.addClass('fold');
                    }, 0);
                } else {
                    tg.style.height = sh + 'px';
                    setTimeout(function () {
                        tg.style.height = fh + 'px';
                        trigger.delClass('fold');
                        trigger.addClass('unfold');
                    }, 0);
                }
            } else {
                tg.style.maxHeight='100%';
                tg.style.height = rh + 'px';
                trigger.parentNode.removeChild(trigger);
                tg.style.overflow = 'auto';
            }
        }
    }

  /* 瀑布流组件 2017-8-31 zhangyu 2692284716@qq.com
  * 瀑布流组件要有 plate 布局的支持 layout-2-type4,5,6,分别表示 2列，3列，4列
  *     plateid: 主体容器（plate），当该容器为waterfall主体时，不能下设新的布局块
  *     source : 子元素来源 id ,以 ul 作为存储形式，li 中不能包含 section
  *     iclass : 子列元素的class
  *     iid    : 子列元素的id
  *     io     : 子列元素的 data-order 属性
  */
    function swaterfall(plateid, source, iclass, col) {
        var plate = document.querySelector(plateid);
        plate.style.width = '100%';
        var p = document.querySelector(source);
        var col = col ? col : 3 ;
        if (col === 2) {
            plate.addClass('plate layout-2-type4')
        } else if (col === 3) {
            plate.addClass('plate layout-3-type1')
        } else if (col === 4) {
            plate.addClass('plate layout-4-type1')
        }
        for (var n = 0; n < col; n++) {
            document.write('<section class="col-' + (n + 1) + '"></section>');
        }
        wf_minh = function () {
            var rh = plate.getElementsByTagName('section');
            var ci = rh[0];
            for (var m = 0; m < col; m++) {
                ci = rh[m].parentNode === plate ? (rh[m].clientHeight < ci.clientHeight ? rh[m] : ci) : ci;
            }
            return ci;
        };
        wf_load = function () {

            var s = p.getElementsByTagName('li');
            for (var i = 0; i < s.length; i++) {
                if(s[i].parentNode === p){
                    var id = s[i].id ?  s[i].id : plateid + '_' + (i + 1) ;
                    wf_minh().innerHTML += '<div class="' + iclass + '" id="'+ id + '" data-order="' + (i + 1) + '">' + s[i].innerHTML + '</div>'
                }
            }
            p.innerHTML = '';
        };
        p.onchange = wf_load();
        wf_load();
    }

  /* alert组件 */
    function alert_hide(e) {
        var alerter = e.parentNode;
        fade_out(alerter,'left','400','0');
    }

  /* hover-Tab组件
    * 开始时需要隐藏 tab-body 部分 以避免短时间的显示错乱问题
    * 在 element 内 放置 .tab-controller, .tab-body
    * tab-controller 是 ul，内部 li[data-tabthumb]为按钮
    * tab-body 内用 section[data-tabbox] 作为 切换模块
    *
    * <div>
    *     <ul class="tab-controller">
    *         <li data-tabthumb>控制1</li>
    *         <li data-tabthumb>控制2</li>
    *     </ul>
    *     <div class="tab-body">
    *         <section data-tabbox></section>
    *         <section data-tabbox></section>
    *     </div>
    * </div>
    * */
    function tab_initialize(tab,act) {
        var t = {
            active: 'active',
            obj : document.querySelector('#' + tab),
            pos:0,
        };
        t.nav = t.obj.querySelector('.tab-controller');
        t.body = t.obj.querySelector('.tab-body');
        t.navitems = t.obj.querySelectorAll('.tab-controller > [data-tabthumb]');
        t.bodyitems = t.obj.querySelectorAll('.tab-body > [data-tabbox]');

        t.wp = t.navitems.length;

        t.body.style.width = 100 * t.wp +'%';

        t.clear = function () {
            for(var i = 0; i <t.wp ; i++){
                t.navitems[i].delClass(t.active)
            }
        };

        t.scroll = function (i) {
            t.pos = -i*100/t.wp;
            t.body.style.transform = 'translate3d(' + t.pos +'%, 0, 0';
            t.body.style.height = t.bodyitems[i].offsetHeight + 'px';
        };

        t.trigger = function () {
            t.clear();
            var self = getEventobj();
            t.scroll(self.getAttribute('data-tabthumb'));
            self.addClass(t.active);
        };

        t.init = function () {
            var firstactive;
            for(var i=0; i<t.wp; i++){
                addEvent(t.navitems[i], act ? act : 'mouseenter',t.trigger);
                t.navitems[i].setAttribute('data-tabthumb',i);
                t.bodyitems[i].style.width = 100/t.wp + '%';
                t.bodyitems[i].classList.add('on');
                if(t.navitems[i].classList.contains(t.active)){
                    firstactive = i;
                    t.scroll(i);
                }
            }
            if(!firstactive){
                t.navitems[0].addClass(t.active);
                t.body.style.height = t.bodyitems[0].offsetHeight + 'px';
            }
            t.body.style.display = '';
        };
        t.init();
    }

  /* nav美化组件 nav为 ul,触发动作的元素为第一级li
     *
     * 将自动为nav组件添加 span.foo作为滑动线，样式通过使用.foo来修改
     * */
    function nav_initialize(e) {
        var n = {
            pos: 0,

            activename: 'active',

            foo: document.createElement('span'),

            nav: document.querySelector('#' + e),

            navitems: document.querySelectorAll('#' + e + '> li'),

            move: function (e) {
                this.pos = e.offsetLeft + 5;
                this.foo.style.transform = 'translate3d(' + this.pos + 'px, 0, 0)';
                this.foo.style.width = e.offsetWidth - 10 + 'px';
            },

            active: function () {
                n.move(getEventobj());
            },

            backbone: function () {
                var that = n;
                for (var i = 0; i < that.navitems.length; i++) {
                    if (that.navitems[i].classList.contains(that.activename)) {
                        that.move(that.navitems[i]);
                    }
                }
            },

            init: function () {
                this.nav.appendChild(this.foo);
                this.backbone();
                this.foo.addClass('foo trans-rush');

                addEvent(this.nav, 'mouseleave', this.backbone);
                for (var i = 0; i < this.navitems.length; i++) {
                    addEvent(this.navitems[i], 'mouseenter', this.active);
                }
            }

        };

        n.init();
    }

  /* menu美化组件，open作为开启类, 具备data-protect属性的对象将作为保护对象，点击后不会关闭按钮
    *   tg:触发器，
    *   obj菜单主体，
    *   closer[array]用于关闭菜单的对象，
    *   fuc触发操作[默认为click]
    * */
    function menu_initialize(tg,obj,closer,fuc) {
        var m = {
            fuc: fuc ? fuc : 'click',
            io: document.querySelector(tg),
            obj: document.querySelector(obj),
            solid: document.querySelectorAll(obj + '> [data-protect]'),

            pt: function () {
                stopBubble();
            },

            open: function () {
                m.obj.addClass('open');
                stopBubble();
            },

            close: function () {
                m.obj.delClass('open');
            },

            bind: function (e, func) {
                if (Object.prototype.toString.call(e) === '[object String]') {
                    addEvent(document.querySelector(e), this.fuc, func)
                } else {
                    addEvent(e, this.fuc, func)
                }
            },

            init: function () {
                if (!this.io || !this.obj || !closer) return 0;
                addEvent(this.io, this.fuc, this.open);
                if (this.obj.hasAttribute('data-protect')) {
                    this.bind(this.obj, this.pt)
                }
                if (this.solid) {
                    for (var j = 0; j < this.solid.length; j++) {
                        this.bind(this.solid[j], this.pt)
                    }
                }
                if (Object.prototype.toString.call(closer) === '[object Array]') {
                    for (var i = 0; i < closer.length; i++) {
                        this.bind(closer[i], this.close)
                    }
                } else {
                    this.bind(closer, this.close)
                }
            }
        };
        m.init();
    }

    // 显示相关的inputbox
    function showInputBox(obj) {
        var inputs = obj.children;
        for (var i = 0; i < inputs.length; i++) {
            var bx = document.querySelector('#' + inputs[i].id + '_inputbox');
            if (bx) {
                if (inputs[i].checked) {
                    bx.style.display = '';
                } else {
                    bx.style.display = 'none';
                }
            }
        }
    }

  // 初始化页面特定内容的dislpay方式
    function item_display(tag,stat) {
        var tg = document.querySelectorAll(tag);
        for (var i = 0; i< tg.length; i++){
            tg[i].style.display = stat;
        }
    }
  // 初始化元素尺寸
    function lock_size(e,w,h) {
        if(w === 'auto'){
            e.style.width = 'auto';
        } else {
            e.style.width = w ? w + 'px' : (e.Css.width ? e.Css.width : e.clientWidth - e.Css.paddingLeft - e.Css.paddingRight  + 'px') ;
        }
        if(h === 'auto'){
            e.style.height = 'auto';
        } else {
            e.style.height = h ? h + 'px' : (e.Css.height ? e.Css.height : e.clientHeight - e.Css.paddingTop - e.Css.paddingBottom + 'px');
        }
    }

  // 强制居中
    function _Center(parent,obj,stopw) {
        var dyw= parent.clientWidth;
        var ow = obj.scrollWidth;
        if(dyw > stopw){
            obj.style.marginLeft = (dyw - ow)/2 + 'px';
        } else {
            obj.style.marginLeft = stopw ? (stopw - ow)/2 + 'px' : (dyw - ow)/2 + 'px';
        }
    }

/***********\
   预设动画   |一些动画效果
\***********/
    function fade_out(obj,dir,du,dly) {
        if(!obj) {return 0}
        var st = obj.Css;
        obj.style.opacity = '0';
        obj.style.overflow = 'hidden';
        obj.style.transition = (dly ? dly : 0) + 'ms';
        obj.style.transitionDuration = (du ? du : 390) + 'ms';
        obj.style.transitionTimingFunction = 'ease-in-out';
        switch (dir){
            case 'left' :
                obj.style.transform = 'translate3d(-390px,0,0)';
                setTimeout(function (){obj.style.marginTop = '-' + (st.marginBottom + obj.offsetHeight) + 'px'}, du);
                break;
            case 'right' :
                obj.style.transform = 'translate3d(390px,0,0)';
                setTimeout(function (){obj.style.marginTop = '-' + (st.marginBottom + obj.offsetHeight) + 'px'}, du);
                break;
            case 'top' :
                obj.style.transform = 'translate3d(0, -' + (obj.offsetHeight + st.marginTop + st.marginBottom) + 'px,0)';
                setTimeout(function (){obj.style.marginLeft = '-' + (st.marginRight + obj.offsetWidth) + 'px'}, du);
                break;
            case 'bottom' :
                obj.style.transform = 'translate3d(0, '+ (obj.offsetHeight + st.marginTop + st.marginBottom) +'px,0)';
                setTimeout(function (){obj.style.marginLeft = '-' + (st.marginRight + obj.offsetWidth) + 'px'}, du);
                break;
            case '' : break;
        }
        setTimeout(function () {obj.style.display = 'none';},du*2);
    }

    function fade_in(obj,dir,du,dly) {
        var st = obj.Css;
        obj.style.height = 0;
        obj.style.minHeight = '0';
        obj.style.opacity = '0';
        obj.style.overflow = 'hidden';
        obj.style.transition = dly + 'ms';
        obj.style.transitionDuration = du + 'ms';
        obj.style.transitionTimingFunction = 'ease-in-out';
        switch (dir){
            case 'top' :obj.style.transform = 'translate3d(0, -'+ (obj.scrollHeight + st.borderBottom) +'px,0)'; break;
            case 'left' :obj.style.transform = 'translate3d(-390px,0,0)'; break;
            case 'right' :obj.style.transform = 'translate3d(390px,0,0)'; break;
            case 'bottom' :obj.style.transform = 'translate3d(0, '+ (obj.scrollHeight + st.borderBottom) +'px,0)'; break;
            case '' : break;
        }
        setTimeout(function () {
            obj.style.margin = '0';
            obj.style.opacity = '1';
            obj.style.height = obj.scrollHeight + st.borderTop + st.borderBottom +'px';
        },du);
    }

/***********\
  动画初始化  |一些简单动画效果播放前的样式
\***********//* p参数表示步骤，在鼠标动作中 1=显示前, 2=隐藏后 */
/*鼠标以及简单动画初始化*/

    Hover = {
        Animation: {
            init : {
                outback: function (obj) {
                    obj.style.transform = 'scale(0)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,-0.54, 0, 1.66)';
                },
                jumpout: function(obj) {
                    obj.style.transform = 'scale(0)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(0.2, 0.5, 0, 1.3)';
                },
                popout: function(obj) {
                    obj.style.transform = 'scale(0,2)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,-0.54, 0, 1.66)';
                },
                past: function(obj) {
                    obj.style.transform = 'scale(1.2)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0, 0, 1)';
                },
                zline: function(obj) {
                    obj.style.transform = 'scale(0)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0.18, 0.2, 1)';
                },
                pasts: function(obj,p) {
                    switch (p){
                        case '1': obj.style.transform = 'scale(1.2)'; break;
                        case '2': obj.style.transform = 'scale(0.5)'; break;
                    }
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0, 0, 1)';
                },
                zlines: function(obj,p) {
                    switch (p){
                        case '1': obj.style.transform = 'scale(0.5)'; break;
                        case '2': obj.style.transform = 'scale(1.2)'; break;
                    }
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0.18, 0.2, 1)';
                },
                pmX: function(obj) {
                    obj.style.transform = 'scale(1.5,1)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0.18, 0.2, 1)';
                },
                pmY: function(obj) {
                    obj.style.transform = 'scale(1,1.5)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0, 0, 1)';
                },
                emX: function(obj) {
                    obj.style.transform = 'scale(0.2,1)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0, 0, 1)';
                },
                emY: function(obj) {
                    obj.style.transform = 'scale(1,0.5)';
                    obj.style.transitionTimingFunction = 'cubic-bezier(1,0, 0, 1)';
                }
            }
        }
    };

/***********\
   页面动作  |页面滚动/点击触发的动作
\***********/
    function doc_onscroll() {
        BodyHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
        WinHeight = document.documentElement.clientHeight || document.body.clientHeight;
        var ScrollToBottom = _Scroll_top() + WinHeight >= BodyHeight ? 1 : 0;
        var dr = _Scroll_dir();

        //nav 隐藏
        (function () {
            if(nav){
                nav.addClass('trans-ease');

                var nav_height = nav.clientHeight;
                var banner_height = banner.clientHeight;
                var nav_editor = document.querySelector('#e_controls');

                if(_Scroll_top() > banner.clientHeight && dr === 'b'){
                    if(banner_on){nav.addClass('white');}
                    setTimeout(function(){nav.style.transform ='translateY(-' + (nav_height + 10) +'px)';},0);
                    if(nav_editor && nav_editor.getBoundingClientRect().top <= nav_height){
                        nav_editor.addClass('trans-ease');
                        setTimeout(function(){nav_editor.style.transform ='translateY(-' + nav_height +'px)';},0);
                    }
                }
                if(_Scroll_top() < banner_height || dr === 't'){
                    nav.style.transform ='translateY(0)';
                    if(nav_editor){
                        nav_editor.addClass('trans-ease');
                        setTimeout(function(){nav_editor.style.transform ='translateY(0)';},0);
                    }
                }
                if(banner_on){
                    if(_Scroll_top() < banner_height - nav_height){
                        nav.delClass('white');
                    } else {
                        nav.addClass('white');
                    }
                }
                if(ScrollToBottom){
                    if(!nav_editor && !uconsole){
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
                if(uconsole.scrollHeight >= WinHeight*scrollrate && WinHeight > 390 ){
                    uconsole.addClass('trans-ease');
                    menu_list.style.height = 'auto';
                    if(_Scroll_top() > _Top(uconsole)){
                        menu.style.position = 'fixed';
                        menu.style.top = '0';
                        console_title.style.position = 'fixed';
                        console_title.style.top = '0';
                        uconsole.style.paddingTop = console_title.clientHeight + 'px';
                        if(dr === 'b'){
                            menu.style.transform = 'translate3d(0,0,0)';
                            console_title.style.transform = 'translate3d(0,0,0)';
                        }
                        if(dr === 't'){
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
                    if(_Scroll_top() + WinHeight >= BodyHeight - 50 ){
                        menu_list.style.height = WinHeight - nav.offsetHeight - console_title.offsetHeight - document.body.Css.marginBottom + 'px';
                    }
                }
                if(_Scroll_top() <= _Top(uconsole)) {
                    topaction ()
                }
            }
        })();

        //返回顶部
        var btn_ScrollToTop = document.querySelector('#ScrollToTop');
        if(btn_ScrollToTop){
            if (_Scroll_top() > banner.clientHeight - nav.offsetHeight) {
                btn_ScrollToTop.addClass('active');
            } else {
                btn_ScrollToTop.delClass('active');
            }
        }
    }
    function doc_onclick(e) { //传入e事件来兼容火狐
        var x = _Cursor_pos(e).x;
        var y = _Cursor_pos(e).y;
        var h = document.documentElement.clientHeight;

        var triggle = getEventobj();


        /* modal 在 body 的委托 */
        if(triggle.data('type') === 'modal'){
            triggle.target.modal(triggle.data('action'));
        }

        console.log(triggle);

    }

    function doc_onwheel(e) {
        var wdir = _Wheel_dir(e);
    }
    function doc_resize() {

        SRGlobal.Window.Width = document.getElementsByTagName('body')[0].clientWidth;
        SRGlobal.Window.Height = document.getElementsByTagName('body')[0].clientHeight;

        var Global_Style_Tag = 'WL-0';

        if(SRGlobal.Window.Width > 360){
            Global_Style_Tag = 'WL-3P';
        }
        if(SRGlobal.Window.Width > 480){
            Global_Style_Tag = 'WL-4P';
        }
        if(SRGlobal.Window.Width > 780){
            Global_Style_Tag = 'WL-7P';
        }
        if(SRGlobal.Window.Width > 1330){
            Global_Style_Tag = 'WL-13P';
        }
        if(SRGlobal.Window.Width > 1920){
            Global_Style_Tag = 'WL-1K';
        }
        if(SRGlobal.Window.Width > 2560){
            Global_Style_Tag = 'WL-2K';
        }
        if(SRGlobal.Window.Width > 4096){
            Global_Style_Tag = 'WL-4K';
        }

        if(SRGlobal.Temp.Base_Style !== Global_Style_Tag){
            SRGlobal.Temp.Base_Style = Global_Style_Tag;
            document.querySelector('body').delClass("WL-0 WL-3P WL-4P WL-7P WL-13P WL-1K WL-2K WL-2K WL-4K");
            document.querySelector('body').addClass(Global_Style_Tag);
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
    }
    function doc_initalize() {

        /* tooltip初始化 */
        tooltip_init();

        /* nav样式初始化 */
        if(nav && banner_on){
            _Scroll_top() < banner.clientHeight ? nav.delClass('white') : nav.addClass('white');
        }
    }