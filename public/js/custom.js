/**
 *  Custom Javascript File for Valasys Media Program Management
 *  Copyright 2021
 */

$(function () {
    $(".select2-multiple").select2();

    $("body").on("change",".import-action",function(){"import_campaigns"===$(this).val()?$("#div_specification_file").show():"update_campaigns"===$(this).val()&&$("#div_specification_file").hide()});
});

$('body').on("input", ".only-non-zero-number", function (){
    if(this.value < 1) {
        $(this).val('');
    } else {
        $(this).val(parseInt(this.value));
    }
});

function getModal(url)
{
    $.ajax({
        url: './../'+url,
        type: 'get',
        beforeSend: function() {
            $("#div-modal").html('');
        },
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


