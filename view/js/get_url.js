const get_url = () => {
    let url = document.location.pathname.split("/");
    url = url[3].split(".php");
    url = url[0]
    return url;
};