jQuery(function () {

    let defaultsize = 150,
        minenlarge = 20,
        maxenlarge = 200,
        sliderverticle = 1,

        form = SR('#avatarform')[0],
        saver = SR('#saver')[0],
        canvas = SR('#avatarcanvas')[0],
        avatarfile = SR('#avatarfile')[0],
        avatarimage = SR('#avatarimage')[0],
        avatarcreator = SR('#avatarcreator')[0],
        avataradjuster = SR('#avataradjuster')[0],
        fileselector = SR('#fileselector')[0],
        filereselector = SR('#filereselector')[0],

        selector = jQuery('#selector'),
        slidehander = jQuery('#slider'),

        cvsWidth = avatarcreator.Css.width,
        cvsHeight = avatarcreator.Css.height,
        imgNatureWidth,
        imgNatureHeight;

    canvas.width = cvsWidth;
    canvas.height = cvsHeight;

    saver.onclick = saveAvatar;
    avatarfile.onchange = uploadAvatarDone;
    avatarimage.onload = function () {
        imgNatureWidth = avatarimage.naturalWidth;
        imgNatureHeight = avatarimage.naturalHeight;
        forceSelectorInsideAvatar();
    };

    if (form.hasClass('horizon')) {
        sliderverticle = 0;
    }

    selector
        .width(defaultsize)
        .height(defaultsize)
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
    slidehander
        .slider({
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

    let ruller = '',
        rrate = 3;

    for (let i = 0; i < (maxenlarge - minenlarge) / rrate + 1; i++) {
        let content = '';
        if (0 === i % 12) content = '<i>' + (sliderverticle ? (maxenlarge - i * rrate) : i * rrate + minenlarge) + '</i>';
        ruller += '<li>' + content + '</li>'
    }
    slidehander.append('<ul class="ui-slider-pointers">' + ruller + '</ul>');


    function uploadAvatarDone() {
        if (this.files && this.files[0]) {
            let fr = new FileReader();
            fr.onload = function (e) {
                saver.disabled = false;
                fileselector.style.display = 'none';
                filereselector.style.display = '';
                avataradjuster.style.display = '';

                selector.css('left', (cvsWidth - defaultsize) / 2);
                selector.css('top', (cvsHeight - defaultsize) / 2);
                selector.width(defaultsize);
                selector.height(defaultsize);
                avatarimage.src = e.target.result;
                slidehander.slider('value', 50);
            };
            fr.readAsDataURL(this.files[0]);
        }
    }

    function getAvatarDimension() {
        let r,
            factor = slidehander.slider('option', 'value'),
            minw = 96,
            minh = 96,
            midw = Math.min(Math.max(imgNatureWidth, 96), cvsWidth),
            midh = Math.min(Math.max(imgNatureHeight, 96), cvsHeight),
            maxw = Math.max(Math.max(imgNatureWidth, 96), cvsWidth),
            maxh = Math.max(Math.max(imgNatureHeight, 96), cvsHeight),
            minr = Math.max(minw / imgNatureWidth, minh / imgNatureHeight),
            midr = Math.max(midw / imgNatureWidth, midh / imgNatureHeight),
            maxr = Math.max(maxw / imgNatureWidth, maxh / imgNatureHeight);

        if (factor <= 50) {
            r = (minr * (50 - factor) + midr * factor) / 50;
        } else {
            r = (midr * (100 - factor) + maxr * (factor - 50)) / 50;
        }

        let aw = Math.floor(r * imgNatureWidth),
            ah = Math.floor(r * imgNatureHeight),
            al = (cvsWidth - aw) / 2,
            at = (cvsHeight - ah) / 2,
            selectorDiv = getSelectorDimention();

        if (aw > cvsWidth) {
            al = Math.floor((cvsWidth - aw) / (cvsWidth - selectorDiv.width) * selectorDiv.left);
        }

        if (ah > cvsHeight) {
            at = Math.floor((cvsHeight - ah) / (cvsHeight - selectorDiv.height) * selectorDiv.top);
        }

        return {
            left: al,
            top: at,
            width: aw,
            height: ah,
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

        let imageDiv = getAvatarDimension(),
            selectorDiv = getSelectorDimention(),
            ctx = canvas.getContext('2d');

        if (uiposition) {
            selectorDiv.left = uiposition.left;
            selectorDiv.top = uiposition.top;
        }

        ctx.clearRect(0, 0, cvsWidth, cvsHeight);
        ctx.drawImage(avatarimage, 0, 0, imgNatureWidth, imgNatureHeight, imageDiv.left, imageDiv.top, imageDiv.width, imageDiv.height);
        ctx.fillStyle = "rgba(0,0,0,0.5)";
        ctx.fillRect(0, 0, cvsWidth, cvsHeight);

        if (avataradjuster.data('avatartype') === 'round') {

            let tmp = {
                w: imageDiv.width + 'px',
                h: imageDiv.height + 'px',
                t: 'translate(' + (imageDiv.left - selectorDiv.left - 1) + 'px, ' + (imageDiv.top - selectorDiv.top - 1) + 'px)',
            };

            avatarimage.style.width = tmp.w;
            avatarimage.style.height = tmp.h;
            avatarimage.style.transform = tmp.t;

        } else {
            let ctmp = {
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
        let imageDiv = getAvatarDimension(),
            selectorDiv = getSelectorDimention();

        if (selectorDiv.width !== selectorDiv.height) {
            let size = Math.min(selectorDiv.height, selectorDiv.width);
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
        let tocanvas = document.createElement('canvas'),
            selectorDiv = getSelectorDimention(),
            imageDiv = getAvatarDimension(),

            size = [256, 144, 96],
            pct = {
                left: (selectorDiv.left - imageDiv.left) / imageDiv.width,
                top: (selectorDiv.top - imageDiv.top) / imageDiv.height,
                width: selectorDiv.width / imageDiv.width,
                height: selectorDiv.height / imageDiv.height,
            },

            sx = Math.floor(pct.left * imgNatureWidth),
            sy = Math.floor(pct.top * imgNatureHeight),
            sw = Math.floor(pct.width * imgNatureWidth),
            sh = Math.floor(pct.height * imgNatureHeight);

        for (let i = 0; i < size.length; i++) {
            let r = 1;
            if (sw > size[i] || sh > size[i]) {
                r = Math.max(sw / size[i], sh / size[i])
            }
            tocanvas.width = Math.floor(sw / r);
            tocanvas.height = Math.floor(sh / r);
            tocanvas.getContext("2d").drawImage(avatarimage, sx, sy, sw, sh, 0, 0, Math.floor(sw / r), Math.floor(sh / r));

            let dataURL = tocanvas.toDataURL("image/png");

            jQuery('#avatar' + (i + 1)).val(dataURL.substr(dataURL.indexOf(",") + 1));
        }

        form.action = avatarUploadData[avatarUploadData.indexOf('src') + 1].replace('images/camera.swf?inajax=1', 'index.php?m=user&a=rectavatar&base64=yes');
        form.target = 'rectframe';
    }


    window.addEventListener('message', receiveMessage, false);

    function receiveMessage(event) {
        let msgdata = event.data;
        if (!msgdata) {
            alert('网络似乎似乎出差了( ᖛ ̫ ᖛ )，如果多次出现该消息，请联系管理员寻求帮助');
        } else if (typeof (msgdata) !== 'string') {
            alert('服务器顺着网线扔过来一个错误(。≖ˇェˇ≖。)，如果多次出现该消息，请联系管理员寻求帮助');
        } else if (msgdata === 'success') {
            alert('上传成功');
            location.reload();
        } else if (msgdata === 'failure') {
            alert('上传失败');
        } else {
            alert('上传被终止，请检查你的网络。当多次出现该消息，请联系管理员寻求帮助\'');
        }
    }
});
