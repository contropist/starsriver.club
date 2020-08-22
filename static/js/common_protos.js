Object.defineProperty(HTMLElement.prototype, 'Css', {
    get: function () {
        var self = this;

        function calcstyle(sty) {
            if (self.currentStyle) {
                return self.currentStyle[sty];
            } else {
                return window.getComputedStyle(self, null)[sty];
            }
        }

        return {
            top: self.getBoundingClientRect().top + window.scrollY,
            top2win: self.getBoundingClientRect().top,
            bottom: self.getBoundingClientRect().bottom,
            left: self.getBoundingClientRect().left + window.scrollX,
            left2win: self.getBoundingClientRect().left,
            right: self.getBoundingClientRect().right,
            width: self.clientWidth || self.offsetWidth,
            height: self.clientHeight || self.offsetHeight,
            scrollTop: self.pageYOffset || self.scrollTop,
            scrollLeft: self.pageXOffset || self.scrollLeft,
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

            background: parseFloat(calcstyle('background')),

        };
    },
});

Object.defineProperty(HTMLElement.prototype, 'hasClass', {
    get: function () {
        return function (arg) {
            return this && this.nodeType === 1 && this.className.split(/\s+/).indexOf(arg) !== -1;
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'addClass', {
    get: function () {
        return function (arg) {
            var cls = arg.split(' ');
            for (var i = 0; i < cls.length; i++) {
                !this.hasClass(cls[i]) ? this.classList.add(cls[i]) : 0;
            }
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'delClass', {
    get: function () {
        return function (arg) {
            var cls = arg.split(' ');
            for (var i = 0; i < cls.length; i++) {
                this.hasClass(cls[i]) ? this.classList.remove(cls[i]) : 0;
            }
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'trgClass', {
    get: function () {
        return function (arg) {
            var cls = arg.split(' ');
            for (var i = 0; i < cls.length; i++) {
                this.hasClass(cls[i]) ? this.classList.remove(cls[i]) : this.classList.add(cls[i]);
            }
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'link', {
    get: function () {
        var self = this;
        return function (arg) {
            self.addEventListener('click',function () {
                window.open(arg)
            },false)
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'data', {
    get: function () {
        var self = this;
        return function (arg) {
            return self.getAttribute('data-' + arg);
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'target', {
    get: function () {
        var tgarg = this.data('target');
        return tgarg ? document.querySelector(tgarg) : null;
    },
});

Object.defineProperty(HTMLElement.prototype, 'loadJS', {
    get: function () {
        var that = this;
        return function (arg) {
            var e = document.createElement('script');
            e.type = 'text/javascript';
            e.src = arg;
            that.appendChild(e);
            return e;
        }
    },
});

Object.defineProperty(HTMLElement.prototype, 'modal', {
    get: function () {
        var that = this;
        return function (show) {
            switch (show) {
                case 'hide':
                    that.delClass('active');
                    break;
                case 'show':
                    that.addClass('active');
                    break;
            }
        }
    },
});