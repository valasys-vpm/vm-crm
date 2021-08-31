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

var testConnectionSpeed = {
    imageAddr : "https://upload.wikimedia.org/wikipedia/commons/a/a6/Brandenburger_Tor_abends.jpg", // this is just an example, you rather want an image hosted on your server
    downloadSize : 2707459, // this must match with the image above
    run:function(mbps_max,cb_gt,cb_lt){
      testConnectionSpeed.mbps_max = parseFloat(mbps_max) ? parseFloat(mbps_max) : 0;
      testConnectionSpeed.cb_gt = cb_gt;
      testConnectionSpeed.cb_lt = cb_lt;
      testConnectionSpeed.InitiateSpeedDetection();
    },
    InitiateSpeedDetection: function() {
      window.setTimeout(testConnectionSpeed.MeasureConnectionSpeed, 1);
    },
    result:function(){
      var duration = (endTime - startTime) / 1000;
      var bitsLoaded = testConnectionSpeed.downloadSize * 8;
      var speedBps = (bitsLoaded / duration).toFixed(2);
      var speedKbps = (speedBps / 1024).toFixed(2);
      var speedMbps = (speedKbps / 1024).toFixed(2);
      if(speedMbps >= (testConnectionSpeed.max_mbps ? testConnectionSpeed.max_mbps : 1) ){
        testConnectionSpeed.cb_gt ? testConnectionSpeed.cb_gt(speedMbps) : false;
      }else {
        testConnectionSpeed.cb_lt ? testConnectionSpeed.cb_lt(speedMbps) : false;
      }
    },
    MeasureConnectionSpeed:function() {
      var download = new Image();
      download.onload = function () {
          endTime = (new Date()).getTime();
          testConnectionSpeed.result();
      }
      startTime = (new Date()).getTime();
      var cacheBuster = "?nnn=" + startTime;
      download.src = testConnectionSpeed.imageAddr + cacheBuster;
    }
  }
  
  
  
  
  // start test immediatly, you could also call this on any event or whenever you want
//   testConnectionSpeed.run(1.5, function(mbps){
     
//       $('#internetspeed').text(mbps+"Mbps");

//     } )

    setInterval(function() {
        testConnectionSpeed.run(1.5, function(mbps){
     
            $('#internetspeed').text(mbps+" Mbps");
      
          } )
      }, 5000);

