var defaultsize = 150,
    minenlarge = 20,
    maxenlarge = 200,

    form = $('avatarform'),
    avatarfile = $('avatarfile'),
    avatarimage = $('avatarimage'),
    canvas = $('avatarcanvas'),

    avatarcreator = jQuery('#avatarcreator'),
    fileselector   = jQuery('#fileselector'),
    filereselector = jQuery('#filereselector'),
    avataradjuster = jQuery('#avataradjuster'),
    selector = jQuery('#selector'),
    slidehander = jQuery('#slider'),
    saver = jQuery('#saver');

var selectareaw = avatarcreator.width(),
    selectareah = avatarcreator.height(),
    imgNatureWidth,
    imgNatureHeight;

avataradjuster.width(selectareaw);
avataradjuster.height(selectareah);
canvas.width = selectareaw;
canvas.height = selectareah;

selector.width(defaultsize);
selector.height(defaultsize);

avatarfile.onchange = uploadAvatarDone;
avatarimage.onload = function(){
    imgNatureWidth = avatarimage.naturalWidth;
    imgNatureHeight = avatarimage.naturalHeight;
    forceSelectorInsideAvatar();
};

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
    slidehander.slider({
        min: minenlarge,
        max: maxenlarge,
        orientation: sliderverticle ? "vertical" : '',
        value: 50,
        step: 1,
        slide: function () {
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
    slidehander.append('<ul class="ui-slider-pointers">' + ruller + '</ul>');
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
            avatarimage.src = e.target.result;
            slidehander.slider('value', 50);
        };
        fr.readAsDataURL(this.files[0]);
    }
}

function getAvatarDimension() {
    var factor = slidehander.slider('option', 'value');
    var cw = avataradjuster.width();
    var ch = avataradjuster.height();
    var minw = 96;
    var minh = 96;
    var midw = Math.min(Math.max(imgNatureWidth, 96), cw);
    var midh = Math.min(Math.max(imgNatureHeight, 96), ch);
    var maxw = Math.max(Math.max(imgNatureWidth, 96), cw);
    var maxh = Math.max(Math.max(imgNatureHeight, 96), ch);
    var minr = Math.max(minw / imgNatureWidth, minh / imgNatureHeight);
    var midr = Math.max(midw / imgNatureWidth, midh / imgNatureHeight);
    var maxr = Math.max(maxw / imgNatureWidth, maxh / imgNatureHeight);
    if (factor <= 50) {
        r = (minr * (50 - factor) + midr * factor) / 50;
    } else {
        r = (midr * (100 - factor) + maxr * (factor - 50)) / 50;
    }
    var aw = Math.floor(r * imgNatureWidth);
    var ah = Math.floor(r * imgNatureHeight);
    var al = (cw - aw) / 2;
    var at = (ch - ah) / 2;
    var selectorDiv = getSelectorDimention();
    if (aw > cw) al = Math.floor((cw - aw) / (cw - selectorDiv.width) * selectorDiv.left);
    if (ah > ch) at = Math.floor((ch - ah) / (ch - selectorDiv.height) * selectorDiv.top);
    return {
        left: al,
        top: at,
        width: aw,
        height: ah
    };
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

    var cw = canvas.width;
    var ch = canvas.height;
    var ctx = canvas.getContext('2d');

    if (uiposition) {
        selectorDiv.left = uiposition.left;
        selectorDiv.top = uiposition.top;
    }

    ctx.clearRect(0, 0, cw, ch);
    ctx.drawImage(avatarimage, 0, 0, imgNatureWidth, imgNatureHeight, imageDiv.left, imageDiv.top, imageDiv.width, imageDiv.height);
    ctx.fillStyle = "rgba(0,0,0,0.5)";
    ctx.fillRect(0, 0, cw, ch);

    if (avataradjuster.data('avatartype') === 'round') {

        var tmp = {
            w: imageDiv.width + 'px',
            h: imageDiv.height + 'px',
            t: 'translate(' + (imageDiv.left - selectorDiv.left - 1) + 'px, ' + (imageDiv.top - selectorDiv.top - 1) + 'px)',
        };

        avatarimage.style.width = tmp.w;
        avatarimage.style.height = tmp.h;
        avatarimage.style.transform = tmp.t;

    } else {
        var ctmp = {
            x: (selectorDiv.left - imageDiv.left) * imgNatureWidth / imageDiv.width,
            y: (selectorDiv.top - imageDiv.top) * imgNatureHeight / imageDiv.height,
            w: (selectorDiv.width + 2) * imgNatureWidth / imageDiv.width,
            h: (selectorDiv.height + 2) * imgNatureHeight / imageDiv.height,

            sl: selectorDiv.left,
            st: selectorDiv.top,
            sw: selectorDiv.width + 2,
            sh: selectorDiv.height + 2,
        };
        ctx.drawImage(avatarimage, ctmp.x, ctmp.y, ctmp.w, ctmp.h, ctmp.sl, ctmp.st, ctmp.sw, ctmp.sh);
    }
}

function forceSelectorInsideAvatar() {
    var imageDiv = getAvatarDimension();
    var selectorDiv = getSelectorDimention();
    if (selectorDiv.width !== selectorDiv.height) {
        var size = Math.min(selectorDiv.height, selectorDiv.width);
        selector.width(size);
        selector.height(size);
    }

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
    var tocanvas = document.createElement('canvas');
    var selectorDiv = getSelectorDimention();
    var imageDiv = getAvatarDimension();

    var pct = {
        left: (selectorDiv.left - imageDiv.left) / imageDiv.width,
        top: (selectorDiv.top - imageDiv.top) / imageDiv.height,
        width: selectorDiv.width / imageDiv.width,
        height: selectorDiv.height / imageDiv.height,
    };

    var sx = Math.floor(pct.left * imgNatureWidth);
    var sy = Math.floor(pct.top * imgNatureHeight);
    var sw = Math.floor(pct.width * imgNatureWidth);
    var sh = Math.floor(pct.height * imgNatureHeight);

    var size = [256, 144, 96];
    for (var i = 0; i < size.length; i++) {
        var r = 1;
        if (sw > size[i] || sh > size[i]) {
            r = Math.max(sw / size[i], sh / size[i])
        }
        tocanvas.width = Math.floor(sw / r);
        tocanvas.height = Math.floor(sh / r);
        tocanvas.getContext("2d").drawImage(avatarimage, sx, sy, sw, sh, 0, 0, Math.floor(sw / r), Math.floor(sh / r));
        var dataURL = tocanvas.toDataURL("image/png");
        jQuery('#avatar' + (i + 1)).val(dataURL.substr(dataURL.indexOf(",") + 1));
    }

    form.action = avatarUploadData[avatarUploadData.indexOf('src') + 1].replace('images/camera.swf?inajax=1', 'index.php?m=user&a=rectavatar&base64=yes');
    form.target = 'rectframe';
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

