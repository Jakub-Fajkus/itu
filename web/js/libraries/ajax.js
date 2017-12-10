import {flashMessage} from "./helpers";

export let getJSON = (url, data = {}) => new Promise((resolve, reject) => {
    $.ajax({
        type: "GET",
        url: url,
        success: (data) => {
            if (data.flashMessage) flashMessage(data.flashMessage, data.status || 'success');
            resolve(data)
        },
        data: data,
        error: (XMLHttpRequest, textStatus, errorThrown) => reject({XMLHttpRequest, textStatus, errorThrown})
    });
});
export let postJSON = (url, data = {}) => new Promise((resolve, reject) => {
    $.ajax({
        type: "POST",
        url: url,
        success: (data) => {
            if (data.flashMessage) flashMessage(data.flashMessage, data.status || 'success');
            resolve(data)
        },
        data: JSON.stringify(data),
        processData: false,
        contentType: 'application/json',
        error: (XMLHttpRequest, textStatus, errorThrown) => reject({XMLHttpRequest, textStatus, errorThrown})
    });
});