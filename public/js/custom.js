/**
 *  Custom Javascript File for Valasys Media Program Management
 *  Copyright 2021
 */


function getModal(url)
{
    $.ajax({
        url: './../'+url,
        type: 'get',
        success: function(response) {
            $("#div-modal").append(response);
            $("#"+url).modal('show');
            return true;
        }
    });
}

function trigger_pnofify(type = 'default', title = '', message = '')
{

    new PNotify({
        title: (title === '') ? false : title,
        text: (message === '') ? false : message,
        type: type,
        icon: (title === '') ? 'none' : true,
        buttons: {
            sticker: false
        },
        delay: 5000
    });

}
