var index = {
    init: function() {
        this.getplanList();
    },
    getplanList: function(userid) {
        _this = this;

        $.ajax({
            url: "api/plan.php/listing",
            type: "GET",
            success: function(data) {
                console.log(data);

                if (!data.error) {
                    $("#plans").append("");
                    
                    var option = "";
                    for (i in data) {

                        option += '<a href="./editplan.php?planid=' + data[i].idplan + '" class="col-sm-12 col-md-10 col-lg-7 p-2 mb-1"><div class="card"><div class="card-body"><h4 class="card-title">' + data[i].planname + '</h4></div></div></a>'
                    }
                    $("#plans").append(option);
                }
            },
            error: function(data) {
                usermessage(data.responseText, "error");
            }
        });



    },
    getplanList_q: function() {
        $('#signup').on('submit', function(e) {
            var firstName = $("input[name=firstname]").val();
            var lastName = $("input[name=lastname]").val();
            var email = $("input[name=signupuseremail]").val();
            var password = $("input[name=signupuserpassword]").val();


            if (!firstName) {
                $("#firstName").addClass("is-invalid");
                var message = "Please enter firstname";
                usermessage(message, "error");
                return false;
            } else {
                $("#firstName").addClass("is-valid");
                firstname = firstName.trim();
            }

            if (!lastName) {
                $("#lastName").addClass("is-invalid");
                var message = "Please enter second name";
                usermessage(message, "error");
                return false;
            } else {
                $("#lastName").addClass("is-valid");
                lastname = lastName.trim();
            }

            if (!email) {
                $("#signupUserEmail").addClass("is-invalid");

                var message = "please enter email";
                usermessage(message, "error");
                return false;
            } else if (!isEmail(email)) {
                var message = "please enter valid email";
                usermessage(message, "error");
                return false;
            } else {
                $("#signupUserEmail").addClass("is-valid");
                email = email.trim();
            }

            if (!password) {
                $("#signupUserPassword").addClass("is-invalid");
                var message = "please enter password";
                usermessage(message, "error");
                return false;
            } else {
                $("#signupUserPassword").addClass("is-valid");
                password = password.trim();
            }


            if ((firstname != "") && (lastname != "") && (email != "") && (password != "")) {
                var data = {};
                data['firstname'] = firstname;
                data['lastname'] = lastName;
                data['email'] = email;
                data['password'] = password;
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: "api/user.php",
                    data: data,
                    success: function(data) {
                        console.log(data);

                        if (data.success) {
                            var message = "Account created successfully";
                            usermessage(message, "success");
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
    index.init();
});