function toggleEye(tagId, imgId, basePath) {
    const tag = document.getElementById(tagId);
    const icon = document.getElementById(imgId);
    
    if (tag.getAttribute("type") == "password") {
        tag.setAttribute("type", "text");
    } else {
        tag.setAttribute("type", "password");
    }

    const visibleNotPath = basePath + "visible-not.svg";
    const visiblePath = basePath + "visible.svg";
    
    if (icon.src == visibleNotPath) {
        icon.src = visiblePath;
    } else {
        icon.src = visibleNotPath;
    }
}