

var monthArray = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var dayArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

$(function () {

    $('body').on('change', '#start_date, #end_date', function () {
        $('input[type=radio][name=pacing]').prop('checked', false);
        $("#v-pills-tab").html('');
        $("#div_pacing_details").hide();
        $("#v-pills-tabContent").html('');
        $("#total-sub-allocation").html(0);
    });

    $('body').on('change','input[type=radio][name=pacing]', function() {
        $("#v-pills-tab").html('');
        $("#div_pacing_details").hide();
        $("#v-pills-tabContent").html('');
        $("#total-sub-allocation").html(0);
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if(start_date != '' && end_date != '') {
            var start = new Date(start_date);
            var end = new Date(end_date);
            var month = '';
            var html = '';

            switch ($(this).val()) {
                case 'Daily':
                    var start_loop_date = new Date(start_date);
                    while (start_loop_date <= end) {
                        month = monthArray[start_loop_date.getMonth()]+'-'+start_loop_date.getFullYear();
                        $("#v-pills-tab").append('<li><a class="nav-link text-left" id="v-pills-'+month+'-tab" data-toggle="pill" href="#v-pills-'+month+'" role="tab" aria-controls="v-pills-'+month+'" aria-selected="false">'+month+'</a></li>');
                        html = '<div class="tab-pane fade" id="v-pills-'+month+'" role="tabpanel" aria-labelledby="v-pills-'+month+'-tab">'+
                            '       <div class="row">'+
                            '           <div class="col-md-6 form-group">'+
                            '               <label for="days">Select Day(s)<span class="text-danger">*</span></label>'+
                            '               <select class="form-control btn-square select2-multiple select2-multiple-days" id="'+month+'_days" name="days['+month+'][]" multiple="multiple" data-month="'+start_loop_date.getMonth()+'" data-year="'+start_loop_date.getFullYear()+'" onChange="getHtmlPacingDates(this);">'+
                            '                   <option value="1"> Monday</option>'+
                            '                   <option value="2"> Tuesday</option>'+
                            '                   <option value="3"> Wednesday</option>'+
                            '                   <option value="4"> Thursday</option>'+
                            '                   <option value="5"> Friday</option>'+
                            '                   <option value="6"> Saturday</option>'+
                            '                   <option value="0"> Sunday</option>'+
                            '               </select>'+
                            '           </div>'+
                            '       </div>'+
                            '       <div class="row" id="'+month+'-dates">'+
                            '       </div>'+
                            '    </div>';
                        $("#v-pills-tabContent").append(html);
                        $(".select2-multiple-days").select2({
                            placeholder: " -- Select Day(s) --",
                        });
                        start_loop_date.setDate(1);
                        start_loop_date.setMonth( start_loop_date.getMonth() + 1 );
                    }
                    break;
                case 'Weekly':
                    var start_loop_date = new Date(start_date);
                    while (start_loop_date <= end) {
                        month = monthArray[start_loop_date.getMonth()]+'-'+start_loop_date.getFullYear();
                        $("#v-pills-tab").append('<li><a class="nav-link text-left" id="v-pills-'+month+'-tab" data-toggle="pill" href="#v-pills-'+month+'" role="tab" aria-controls="v-pills-'+month+'" aria-selected="false">'+month+'</a></li>');
                        html = '<div class="tab-pane fade" id="v-pills-'+month+'" role="tabpanel" aria-labelledby="v-pills-'+month+'-tab">'+
                            '       <div class="row">'+
                            '           <div class="col-md-6 form-group">'+
                            '               <label for="days">Select Day<span class="text-danger">*</span></label>'+
                            '               <select class="form-control btn-square form-control-sm" id="'+month+'_day" name="day['+month+']" data-month="'+start_loop_date.getMonth()+'" data-year="'+start_loop_date.getFullYear()+'" onChange="getHtmlPacingDates(this);">'+
                            '                   <option value="">-- Select Day --</option>'+
                            '                   <option value="1"> Monday</option>'+
                            '                   <option value="2"> Tuesday</option>'+
                            '                   <option value="3"> Wednesday</option>'+
                            '                   <option value="4"> Thursday</option>'+
                            '                   <option value="5"> Friday</option>'+
                            '                   <option value="6"> Saturday</option>'+
                            '                   <option value="0"> Sunday</option>'+
                            '               </select>'+
                            '           </div>'+
                            '       </div>'+
                            '       <div class="row" id="'+month+'-dates">'+
                            '       </div>'+
                            '    </div>';
                        $("#v-pills-tabContent").append(html);
                        start_loop_date.setDate(1);
                        start_loop_date.setMonth( start_loop_date.getMonth() + 1 );
                    }
                    break;
                case 'Monthly':
                    while (start <= end || (start.getMonth() === end.getMonth())) {
                        month = monthArray[start.getMonth()]+'-'+start.getFullYear();
                        lastDay = new Date(start.getFullYear(), start.getMonth() + 1, 0);

                        if(lastDay > end) { lastDay = end; }

                        secondLast = new Date(lastDay.getFullYear(), lastDay.getMonth(), lastDay.getDate() - 1);
                        var secondLastDate = secondLast.getFullYear()+'-'+((secondLast.getMonth()+1)<=9?('0'+(secondLast.getMonth()+1)) : (secondLast.getMonth()+1))+'-'+(secondLast.getDate()<=9 ? '0'+secondLast.getDate() : secondLast.getDate());

                        while ($.inArray(secondLastDate, holidays) !== -1) {
                            secondLast = new Date(secondLast.getFullYear(), secondLast.getMonth(), secondLast.getDate() - 1);
                            secondLastDate = secondLast.getFullYear()+'-'+((secondLast.getMonth()+1)<=9?('0'+(secondLast.getMonth()+1)) : (secondLast.getMonth()+1))+'-'+(secondLast.getDate()<=9 ? '0'+secondLast.getDate() : secondLast.getDate());
                        }

                        $("#v-pills-tab").append('<li><a class="nav-link text-left" id="v-pills-'+month+'-tab" data-toggle="pill" href="#v-pills-'+month+'" role="tab" aria-controls="v-pills-'+month+'" aria-selected="false">'+month+'</a></li>');
                        html = '<div class="tab-pane fade" id="v-pills-'+month+'" role="tabpanel" aria-labelledby="v-pills-'+month+'-tab">'+
                            '       <div class="row" id="'+month+'-dates">'+
                            '           <div class="col-md-8">'+
                            '               <div class="input-group mb-3">'+
                            '                   <div class="input-group-prepend"><span class="input-group-text">'+dayArray[secondLast.getDay()]+' '+secondLast.getDate()+'-'+monthArray[secondLast.getMonth()]+'-'+secondLast.getFullYear()+'</span></div>'+
                            '                   <input type="number" class="form-control btn-square only-non-zero-number sub-allocation" name="sub-allocation['+secondLastDate+']" placeholder="Enter Sub-Allocation">'+
                            '               </div>'+
                            '          </div>'+
                            '       </div>';
                        $("#v-pills-tabContent").append(html);
                        start.setMonth( start.getMonth() + 1 );
                    }
                    break;
            }

            $("#div_pacing_details").show();

        } else {
            $(this).prop('checked', false);
            alert('Select Start Date & End Date');
        }
    });

    $("#allocation").on('keyup', function () {
        var allocation = ($(this).val() > 0) ? $(this).val() : '0';
        $("#text-allocation").html(' / '+allocation);
    });

    $('body').on('keyup', ".sub-allocation",function () {
        var total = 0;

        $('body').find('.sub-allocation').each(function(){
            if($(this).val() != '') {
                total = total + parseInt($(this).val());
            }
        });
        total = (total > 0) ? total : 0;
        $("#total-sub-allocation").html(total);

        if(total > parseInt($("#allocation").val())) {
            $(this).val('');
            $(this).keyup();
        }

    });

});

function getHtmlPacingDates(_this) {
    var month = $(_this).data('month');
    var year = $(_this).data('year');
    var selectedDays =  $(_this).val();

    var dayArr = [];
    var allDates = [];

    if(Array.isArray(selectedDays)) {
        dayArr = selectedDays;
    } else {
        dayArr.push(selectedDays);
    }

    $.each(dayArr, function () {
        $.merge(allDates, getDaysInMonthYear(parseInt(month), parseInt(year), parseInt(this)));
    });

    var html = '';

    $('body').find('#'+monthArray[month]+'-'+year+'-dates').html(html);

    $.each(allDates, function () {

        var currentDate = this.getFullYear()+'-'+((this.getMonth()+1)<=9?('0'+(this.getMonth()+1)) : (this.getMonth()+1))+'-'+(this.getDate()<=9 ? '0'+this.getDate() : this.getDate());
        var disabled = '';
        var place_holder = 'Sub-Allocation';
        var text_color = '';

        if($.inArray(currentDate, holidays) !== -1) {
            disabled = ' disabled ';
            place_holder = 'Holiday';
            text_color = 'text-danger';
        }

        html = '<div class="col-md-6">'+
            '               <div class="input-group mb-3">'+
            '                   <div class="input-group-prepend"><span class="input-group-text '+text_color+'">'+dayArray[this.getDay()]+' '+this.getDate()+'-'+monthArray[this.getMonth()]+'-'+this.getFullYear()+'</span></div>'+
            '                   <input type="number" class="form-control btn-square only-non-zero-number sub-allocation" name="sub-allocation['+currentDate+']" placeholder="'+place_holder+'" '+disabled+'>'+
            '               </div>'+
            '          </div>';

        $('body').find('#'+monthArray[month]+'-'+year+'-dates').append(html);
    });
}

function getDaysInMonthYear(month, year, weekday) {
    var date = new Date(year, month, 1);
    var days = [];
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var start = new Date(start_date);
    var end = new Date(end_date);
    while (date.getMonth() === month) {
        if(date.getDay() == weekday && (start <= date) && (end >= date)) {
            days.push(new Date(date));
        }
        date.setDate(date.getDate() + 1);
    }
    return days;
}
