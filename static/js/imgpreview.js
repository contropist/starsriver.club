function previewImage(img,tg,w,h) {
    var ipv = {
        source : img,
        maxwidth : w,
        maxheight : h,
        previewer : document.querySelector('#' + tg),
        reader : new FileReader()
    };
    ipv.org = ipv.previewer.src;
    ipv.run = function () {
        if (ipv.source.files && ipv.source.files[0]) {
            ipv.reader.onload = function (evt) {
                ipv.previewer.src =  evt.target.result ? evt.target.result : ipv.org;
            };
            ipv.reader.readAsDataURL(ipv.source.files[0]);
        }
        else {
            ipv.previewer.src = ipv.source.value ? ipv.source.value : ipv.org
        }
    };
    ipv.run();
}