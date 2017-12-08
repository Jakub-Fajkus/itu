export let getJSON = (url, data = {}) => new Promise((resolve, reject) =>   {
    $.ajax({
        type: "GET",
        url: url,
        success: (data) => resolve(data),
        data: data,
        error: (XMLHttpRequest, textStatus, errorThrown) => reject({XMLHttpRequest, textStatus, errorThrown})
    });
});
export let postJSON = (url, data = {}) => new Promise((resolve, reject) => {
    $.ajax({
        type: "POST",
        url: url,
        success: (data) => resolve(data),
        data: JSON.stringify(data),
        processData: false,
        contentType: 'application/json',
        error: (XMLHttpRequest, textStatus, errorThrown) => reject({
            responseText: XMLHttpRequest.responseText,
            responseJSON: XMLHttpRequest.responseJSON,
            status: XMLHttpRequest.status,
            textStatus,
            errorThrown
        })
    });
});