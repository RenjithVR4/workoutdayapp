var addplan = {
    init: function() {
        this.getUsers();
        this.addDayInputEvents();
        this.addworkoutPlan();
    },
    getUsers: function(userid) {
        _this = this;

        $.ajax({
            url: "api/user.php/search",
            type: "GET",
            success: function(data) {
                console.log(data);

                if (!data.error) {
                    $("#users").append("");
                    var option = "";
                    for (i in data) {
                        option += '<div class="custom-control custom-checkbox m-l-20 d-block my-2"><input type="checkbox" class="custom-control-input" id="' + data[i].iduser + '"><label class="custom-control-label" for="' + data[i].iduser + '">' + data[i].firstname + ' ' + data[i].lastname + '</label></div>';
                    }

                    $("#users").append(option);
                }
            },
            error: function(data) {
                usermessage(data.responseText, "error");
            }
        });



    },
    addDayInputEvents: function() {

        var _this = this;

        $("#excerciseDays").html('');

        for (var i = 1; i <= 4; i++) {
            $("#excerciseDays").append('<div class="col-lg-5 clear-fix mb-4 excerciseday card" id="excerciseDay' + i + '" ><span class="m-10 dayActions" ><i class="fa fa-close pull-right closeday" id="closeDay' + i + '" aria-hidden="true"></i></span><div id="dayData' + i + '"><div class="col-md-12 col-sm-12"><div class="form-group"><label class="labeltitle" for="dayName' + i + '" >Name of the Day ' + i + '</label><input type="text" name="dayname' + i + '" class="form-control" id="dayName' + i + '" placeholder="Enter name of the day"></div></div></div><div class="m-t-10"><div class="col-md-12 col-sm-12"><div class="form-group  p-b-10"><label class="labeltitle" for="excerciseSet' + i + '">Excercises</label><div class="row m-l-20" id="excerciseSet' + i + '"> </div></div></div></div></div>');
        }

        $.getJSON("js/excercises.json", function(result) {

            for (var j = 1; j <= 4; j++) {
                for (e in result) {
                    $("#excerciseSet" + j + "").append('<div class="custom-control m-r-20 custom-checkbox d-block my-2"><input type="checkbox" class="custom-control-input" id="' + result[e] + j + '"><label class="custom-control-label" for="' + result[e] + j + '">' + result[e] + '</label></div>');
                }
            }
        });


        hidemultipleDivData("excerciseDay");

        var dayCounter = 0;
        var daymaxCount = 4;

        $('#addDay').click(function() {

            if (dayCounter < daymaxCount) {
                dayCounter = dayCounter + 1;
                addDivData("excerciseDay", daymaxCount);
            }

            if (dayCounter == daymaxCount) {
                $("#addDay").attr('disabled', 'disabled');
            }


        });


        $(".closeday").click(function() {
            var idNumber = this.id.match(/\d+/)[0];
            hideSingleDivData('excerciseDay', idNumber);
            dayCounter = dayCounter - 1;
            console.log("close day -->" + dayCounter);

            if (dayCounter < 4) {
                $("#addDay").prop("disabled", false);
            }
        });




    },
    addworkoutPlan: function() {
        $('#addplanForm').on('submit', function(e) {


            var planname = $("input[name=planname]").val();

            var users = [];
            $('#users input:checked').each(function() {
                users.push(parseInt($(this).attr('id')));
            });


            var daynames = [];
            for (var i = 1; i <= 4; i++) {
                if ($("input[name=dayname" + i + "]").val()) {
                    daynames.push($("input[name=dayname" + i + "]").val());
                }
            }

            var excerciseSet = {};
            excerciseSet['excercises1'] = [];
            excerciseSet['excercises2'] = [];
            excerciseSet['excercises3'] = [];
            excerciseSet['excercises4'] = [];

            $("#excerciseSet1 input:checked").each(function() {
                excerciseSet['excercises1'].push($(this).attr("id").slice(0,-1));
            });

            $("#excerciseSet2 input:checked").each(function() {
                excerciseSet['excercises2'].push($(this).attr("id").slice(0,-1));
            });

            $("#excerciseSet3 input:checked").each(function() {
                excerciseSet['excercises4'].push($(this).attr("id").slice(0,-1));
            });

            $("#excerciseSet4 input:checked").each(function() {
                excerciseSet['excercises4'].push($(this).attr("id").slice(0,-1));
            });


            //validations not included

            var data = {};
            data['planname'] = planname;
            data['userids'] = users;
            data['daynames'] = daynames;
            data['excerciseset'] = excerciseSet;


            if ((data['daydata'] != {}) && (data['excerciseset'] != {})) {

                console.log(data);
                $.ajax({
                    type: "POST",
                    url: "api/plan.php",
                    data: data,
                    success: function(data) {
                        console.log(data);

                        if (data.success) {
                            var message = "Plan created successfully";
                            usermessage(message, "success");
                            setTimeout(function(){ window.location = './'; }, 1500);
                        } else {
                            var message = data.error
                            usermessage(message, "error");
                            return false;
                        }
                    },
                    error: function(xhr, status, errorThrown) {
                        console.log(xhr);
                        var message = "Something is not working from backend!";
                        usermessage(message, "error");
                        return false;
                    }
                });
            }
            e.preventDefault();

        });
    },

}


$(document).ready(function() {
    addplan.init();
});