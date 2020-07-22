var defaultsize = 150,
    minenlarge = 20,
    maxenlarge = 200,

    form = jQuery('#avatarform'),
    avatarcreator = jQuery('#avatarcreator'),
    fileselector = jQuery('#fileselector'),
    filereselector = jQuery('#filereselector'),
    avataradjuster = jQuery('#avataradjuster'),
    avatarfile = jQuery('#avatarfile'),
    avatarimage = jQuery('#avatarimage'),
    canvas = jQuery('#avatarcanvas'),
    selector = jQuery('#selector'),
    slider = jQuery('#slider'),
    saver = jQuery('#saver');

var selectareaw = avatarcreator.width(),
    selectareah = avatarcreator.height();

avatarfile.width(selectareaw);
avatarfile.height(selectareah);
avataradjuster.width(selectareaw);
avataradjuster.height(selectareah);
canvas.attr('width', selectareaw);
canvas.attr('height', selectareah);

selector.width(defaultsize);
selector.height(defaultsize);

form.attr('target', 'uploadframe');
avatarfile.attr('onchange', uploadAvatarDone);

$('avatarform').target = 'uploadframe';
$('avatarfile').onchange = uploadAvatarDone;

window.addEventListener('message', receiveMessage, false);

sliderverticle = 1;
if (form.hasClass('horizon')) {
    sliderverticle = 0;
}

jQuery(document).ready(function () {
    selector
        .draggable({
            containment: "parent",
            drag: function (event, ui) {
                refreshAvatarCanvas(ui.position);
            },
            stop: function () {
                forceSelectorInsideAvatar();
            },
        })
        .resizable({
            containment: "parent",
            minHeight: 50,
            minWidth: 50,
            aspectRatio: 1,
            resize: function (event, ui) {
                refreshAvatarCanvas(ui.position);
            },
            stop: function () {
                forceSelectorInsideAvatar();

            },
        });
    slider.slider({
        min: minenlarge,
        max: maxenlarge,
        orientation: sliderverticle ? "vertical" : '',
        value: 50,
        step: 3,
        slide: function (event, ui) {
            forceSelectorInsideAvatar();
        },
        stop: function () {
            forceSelectorInsideAvatar();

        },
    });
    var ruller = '';
    var rrate = 3;
    for (var i = 0; i < (maxenlarge - minenlarge) / rrate + 1; i++) {
        var content = '';
        if (0 === i % 12) content = '<i>' + (sliderverticle ? (maxenlarge - i * rrate) : i * rrate + minenlarge) + '</i>';
        ruller += '<li>' + content + '</li>'
    }
    slider.append('<ul class="ui-slider-pointers">' + ruller + '</ul>');
});


function uploadAvatarDone() {
    if (this.files && this.files[0]) {
        var fr = new FileReader();
        fr.onload = function (e) {
            fileselector.hide();
            filereselector.show();
            avataradjuster.show();
            saver.attr('disabled', false);
            selector.css('left', (selectareaw - defaultsize) / 2);
            selector.css('top', (selectareah - defaultsize) / 2);
            selector.width(defaultsize);
            selector.height(defaultsize);
            $('avatarimage').src = e.target.result;
            $('selectedarea').src = e.target.result;
            slider.slider('value', 50);
        };
        fr.readAsDataURL(this.files[0]);
    }
}

function getAvatarDimension() {
    var factor = slider.slider('option', 'value');
    var cw = avataradjuster.width();
    var ch = avataradjuster.height();
    var iw = avatarimage.width();
    var ih = avatarimage.height();
    var minw = 96;
    var minh = 96;
    var midw = Math.min(Math.max(iw, 96), cw);
    var midh = Math.min(Math.max(ih, 96), ch);
    var maxw = Math.max(Math.max(iw, 96), cw);
    var maxh = Math.max(Math.max(ih, 96), ch);
    var minr = Math.max(minw / iw, minh / ih);
    var midr = Math.max(midw / iw, midh / ih);
    var maxr = Math.max(maxw / iw, maxh / ih);
    if (factor <= 50) {
        r = (minr * (50 - factor) + midr * factor) / 50;
    } else {
        r = (midr * (100 - factor) + maxr * (factor - 50)) / 50;
    }
    var aw = r * iw;
    var ah = r * ih;
    var al = (cw - aw) / 2;
    var at = (ch - ah) / 2;
    var selectorDiv = getSelectorDimention();
    if (aw > cw) al = (cw - aw) / (cw - selectorDiv.width) * selectorDiv.left;
    if (ah > ch) at = (ch - ah) / (ch - selectorDiv.height) * selectorDiv.top;
    return {left: Math.floor(al), top: Math.floor(at), width: Math.floor(aw), height: Math.floor(ah)};
}

function getSelectorDimention() {
    return {
        left: selector.position().left,
        top: selector.position().top,
        width: selector.width(),
        height: selector.height(),
    };
}

function refreshAvatarCanvas(uiposition) {
    var imageDiv = getAvatarDimension();
    var selectorDiv = getSelectorDimention();
    var img = $('avatarimage');
    var canvas = $('avatarcanvas');
    var selectedarea = $('selectedarea');
    var cw = canvas.width;
    var ch = canvas.height;
    var ctx = canvas.getContext('2d');
    var iw = avatarimage.width();
    var ih = avatarimage.height();
    if (uiposition) {
        selectorDiv.left = uiposition.left;
        selectorDiv.top = uiposition.top;
    }
    ctx.fillStyle = "rgba(0,0,0,0.5)";
    ctx.clearRect(0, 0, cw, ch);
    ctx.drawImage(img, 0, 0, iw, ih, imageDiv.left, imageDiv.top, imageDiv.width, imageDiv.height);
    ctx.fillRect(0, 0, cw, ch);
    if (avataradjuster.data('avatartype') === 'round') {

        var tmp = {
            w: imageDiv.width + 'px',
            h: imageDiv.height + 'px',
            t: 'translate(' + (imageDiv.left - selectorDiv.left - 1) + 'px, ' + (imageDiv.top - selectorDiv.top - 1) + 'px)',
        };

        selectedarea.style.width = tmp.w;
        selectedarea.style.height = tmp.h;
        selectedarea.style.transform = tmp.t;

    } else {
        var ctmp = {
            x: (selectorDiv.left - imageDiv.left) * iw / imageDiv.width,
            y: (selectorDiv.top - imageDiv.top) * ih / imageDiv.height,
            w: (selectorDiv.width + 2) * iw / imageDiv.width,
            h: (selectorDiv.height + 2) * ih / imageDiv.height,

            sl: selectorDiv.left,
            st: selectorDiv.top,
            sw: selectorDiv.width + 2,
            sh: selectorDiv.height + 2,
        };
        ctx.drawImage(img, ctmp.x, ctmp.y, ctmp.w, ctmp.h, ctmp.sl, ctmp.st, ctmp.sw, ctmp.sh);
    }
}

function forceSelectorInsideAvatar() {
    var imageDiv = getAvatarDimension();
    var selectorDiv = getSelectorDimention();
    if (selectorDiv.width > selectorDiv.height) selector.width(selectorDiv.height);
    if (selectorDiv.height > selectorDiv.width) selector.height(selectorDiv.width);
    /* aspectRatio */
    if (selectorDiv.width > imageDiv.width) selector.width(imageDiv.width - 2);
    if (selectorDiv.height > imageDiv.height) selector.height(imageDiv.height - 2);
    /* aspectRatio End */
    if (selectorDiv.left < imageDiv.left) selector.css('left', imageDiv.left);
    if (selectorDiv.top < imageDiv.top) selector.css('top', imageDiv.top);
    if (selectorDiv.left + selectorDiv.width > imageDiv.left + imageDiv.width - 2) selector.css('left', imageDiv.left + imageDiv.width - selectorDiv.width - 2);
    if (selectorDiv.top + selectorDiv.height > imageDiv.top + imageDiv.height - 2) selector.css('top', imageDiv.top + imageDiv.height - selectorDiv.height - 2);
    refreshAvatarCanvas();
}

function saveAvatar() {
    var img = $('avatarimage');
    var canvas = document.createElement('canvas');
    var selectorDiv = getSelectorDimention();
    var imageDiv = getAvatarDimension();
    var imgwidth = avatarimage.width();
    var imgheight = avatarimage.height();

    var pct = {
        left: (selectorDiv.left - imageDiv.left) / imageDiv.width,
        top: (selectorDiv.top - imageDiv.top) / imageDiv.height,
        width: selectorDiv.width / imageDiv.width,
        height: selectorDiv.height / imageDiv.height,
    };

    var sx = pct.left * imgwidth;
    var sy = pct.top * imgheight;
    var sw = pct.width * imgwidth;
    var sh = pct.height * imgheight;

    var size = [256, 144, 96];
    for (var i = 0; i < size.length; i++) {
        var r = 1;
        if (sw > size[i] || sh > size[i]) {
            r = Math.max(sw / size[i], sh / size[i])
        }
        canvas.width = sw / r;
        canvas.height = sh / r;
        canvas.getContext("2d").drawImage(img, sx, sy, sw, sh, 0, 0, sw / r, sh / r);
        var dataURL = canvas.toDataURL("image/png");
        jQuery('#avatar' + (i + 1)).val(dataURL.substr(dataURL.indexOf(",") + 1));
    }

    form.attr('action', avatarUploadData[avatarUploadData.indexOf('src') + 1].replace('images/camera.swf?inajax=1', 'index.php?m=user&a=rectavatar&base64=yes'));
    form.attr('target', 'rectframe');
}


function receiveMessage(event) {
    var msgdata = event.data;
    if (!msgdata) {
        alert('网络似乎似乎出差了( ᖛ ̫ ᖛ )，如果多次出现该消息，请联系管理员寻求帮助');
    } else if (typeof (msgdata) !== 'string') {
        alert('服务器顺着网线扔过来一个错误(。≖ˇェˇ≖。)，如果多次出现该消息，请联系管理员寻求帮助');
    } else if (msgdata === 'success') {
        alert('上传成功');
        location.reload();
    } else if (res === 'failure') {
        alert('上传失败');
    } else {
        alert('上传被终止，请检查你的网络。当多次出现该消息，请联系管理员寻求帮助\'');
    }
}

