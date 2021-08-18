/**
 *  Custom Javascript File for Valasys Media Program Management
 *  Copyright 2021
 */

$(document).ready(function () {
    callFun();
});

function callFun() {
    var pathname = window.location.pathname;
    var urlPath = pathname.split('/');
    var pathName = urlPath[3];
    //alert(pathName);
    switch (pathName) {
        case 'v1': getdashboarddataV1(1); break;
        case 'v2': getdashboarddataV2(1); break;
        default: ;
    }
}


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

function getdashboarddataV1(filterVal) {
    if (filterVal == 0) {
        var targetData = {
            'campaignmonth': '0',
            'campaigstartdate': '0',
            'campaigenddate': '0'
        };
    } else {
        var campaignmonth = $('#campaign-status-month-select').val();
        if (campaignmonth != '') {
            $('#campaign-status-start-date').prop('disabled', true);
            $('#campaign-status-end-date').prop('disabled', true);
            $('#campaign-status-start-date').val('');
            $('#campaign-status-end-date').val('');
        } else {
            $('#campaign-status-start-date').prop('disabled', false);
            $('#campaign-status-end-date').prop('disabled', false);
        }
        var targetData = {
            'campaignmonth': campaignmonth,
            'campaigstartdate': $('#campaign-status-start-date').val(),
            'campaigenddate': $('#campaign-status-end-date').val()
        };
    }
    $.ajax({
        type: 'post',
        url: BASE_PATH + "/dashboard/get-data-v1",
        data: targetData,
        dataType: 'json',
        success: function (response) {
            console.log(response);

            $('#liveAll').text(response.Live);
            $('#pauseAll').text(response.Pause);
            $('#cancelledAll').text(response.Cancelled);
            $('#deliveredAll').text(response.Delivered);
            $('#reactivatedAll').text(response.Reactivated);
            $('#shortfallAll').text(response.Shortfall);

            $('#liveTA').text(response.LiveTA);
            $('#pauseTA').text(response.PauseTA);
            $('#cancelledTA').text(response.CancelledTA);
            $('#deliveredTA').text(response.DeliveredTA);
            $('#reactivatedTA').text(response.ReactivatedTA);
            $('#shortfallTA').text(response.ShortfallTA);

            $('#liveINT').text(response.LiveINT);
            $('#pauseINT').text(response.PauseINT);
            $('#cancelledINT').text(response.CancelledINT);
            $('#deliveredINT').text(response.DeliveredINT);
            $('#reactivatedINT').text(response.ReactivatedINT);
            $('#shortfallINT').text(response.ShortfallINT);

            $('#liveLC').text(response.LiveLC);
            $('#pauseLC').text(response.PauseLC);
            $('#cancelledLC').text(response.CancelledLC);
            $('#deliveredLC').text(response.DeliveredLC);
            $('#reactivatedLC').text(response.ReactivatedLC);
            $('#shortfallLC').text(response.ShortfallLC);

            $('#liveNC').text(response.LiveNC);
            $('#pauseNC').text(response.PauseNC);
            $('#cancelledNC').text(response.CancelledNC);
            $('#deliveredNC').text(response.DeliveredNC);
            $('#reactivatedNC').text(response.ReactivatedNC);
            $('#shortfallNC').text(response.ShortfallNC);

            $('#ALLTOTAL').text(response.ALLTOTAL);
            $('#TATOTAL').text(response.TATOTAL);
            $('#INTTOTAL').text(response.INTTOTAL);
            $('#LCTOTAL').text(response.LCTOTAL);
            $('#NCTOTAL').text(response.NCTOTAL);

        }
    });
}

function getdashboarddataV2(filterVal) {
    if (filterVal == 0) {
        var targetData = {
            'campaignmonth': '0',
            'campaigstartdate': '0',
            'campaigenddate': '0'
        };
    } else {
        var campaignmonth = $('#campaign-status-month-select').val();
        if (campaignmonth != '') {
            $('#campaign-status-start-date').prop('disabled', true);
            $('#campaign-status-end-date').prop('disabled', true);
            $('#campaign-status-start-date').val('');
            $('#campaign-status-end-date').val('');
        } else {
            $('#campaign-status-start-date').prop('disabled', false);
            $('#campaign-status-end-date').prop('disabled', false);
        }
        var targetData = {
            'campaignmonth': campaignmonth,
            'campaigstartdate': $('#campaign-status-start-date').val(),
            'campaigenddate': $('#campaign-status-end-date').val()
        };
    }

    $.ajax({
        type: 'post',
        url: BASE_PATH + "/dashboard/get-data-v1",
        data: targetData,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            var dataLive = [response.Live, response.LiveTA, response.LiveINT, response.LiveLC, response.LiveNC];
            var dataPause = [response.Pause, response.PauseTA, response.PauseINT, response.PauseLC, response.PauseNC];
            var dataCancelled = [response.Cancelled, response.CancelledTA, response.CancelledINT, response.CancelledLC, response.CancelledNC];
            var dataDelivered = [response.Delivered, response.DeliveredTA, response.DeliveredINT, response.DeliveredLC, response.DeliveredNC];
            var dataReactivated = [response.Reactivated, response.ReactivatedTA, response.ReactivatedINT, response.ReactivatedLC, response.ReactivatedNC];
            var dataShortfall = [response.Shortfall, response.ShortfallTA, response.ShortfallINT, response.ShortfallLC, response.ShortfallNC];
            Highcharts.chart('chart-highchart-bar1', {
                chart: {
                    type: 'column'
                },
                colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#08ff40', '#6c757d'],
                title: {
                    text: 'Campaign status count'
                },
                // subtitle: {
                //     text: 'Source: WorldClimate.com'
                // },
                xAxis: {
                    categories: [
                        'ALL',
                        'TA',
                        'INT',
                        'LC',
                        'NC'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Live',
                    data: dataLive

                }, {
                    name: 'Pause',
                    data: dataPause

                }, {
                    name: 'Cancelled',
                    data: dataCancelled

                }, {
                    name: 'Delivered',
                    data: dataDelivered

                }, {
                    name: 'Reactivated',
                    data: dataReactivated

                }, {
                    name: 'Shortfall',
                    data: dataShortfall

                }]
            });

        }
    });


}
