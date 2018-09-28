
$(".preview").change(function(){
 
    var file = this.files[0];
    var str = getObjectUrl(file);
    $(this).prev('.img_preview').remove();
    $(this).before("<div class='img_preview'><img src='"+str+"' width='120'></div>");

})

    function getObjectUrl(file) {
        var url = null;
        if (window.createObjectURL != undefined) {
            url = window.createObjectURL(file)
        } else if (window.URL != undefined) {
            url = window.URL.createObjectURL(file)
        } else if (window.webkitURL != undefined) {
            url = window.webkitURL.createObjectURL(file)
        }
        return url
    }
