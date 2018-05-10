var addUser = {
    init: function() {
        this.adduser();
        this.formkeyUp();
    },
    adduser: function() {

        $('#adduserForm').on('submit', function(e) {

            var firstName = $("input[name=firstname]").val();
            var lastName = $("input[name=lastname]").val();
            var email = $("input[name=email]").val();


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
                var message = "Please enter last name";
                usermessage(message, "error");
                return false;
            } else {
                $("#lastName").addClass("is-valid");
                lastname = lastName.trim();
            }

            if (!email) {
                $("#email").addClass("is-invalid");

                var message = "please enter email";
                usermessage(message, "error");
                return false;
            } else if (!isEmail(email)) {
                var message = "please enter valid email";
                usermessage(message, "error");
                return false;
            } else {
                $("#email").addClass("is-valid");
                email = email.trim();
            }

            if ((firstname != "") && (lastname != "") && (email != "")) {
                var data = {};
                data['firstname'] = firstname;
                data['lastname'] = lastName;
                data['email'] = email;
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: "api/user.php",
                    data: data,
                    success: function(data) {
                        console.log(data);

                        if (data.success) {
                            var message = "User created successfully";
                            usermessage(message, "success");
                            setTimeout(function(){ window.location = './users.php'; }, 1500);
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
    formkeyUp: function() {
        var _this = this;

        $("#email").bind("keyup change", function(e) {
            var email = $("input[name=email]").val();

            if (!isEmail(email)) {
                $("#email").addClass("is-invalid");
                return false;
            } else {
                $("#email").removeClass("is-invalid");
                $("#email").addClass("is-valid");
            }

        });


        $("#firstName").bind("keyup change", function(e) {
            var firstname = $("input[name=firstname]").val();

            if (firstname) {
                $("#firstName").removeClass("is-invalid");
                $("#firstName").addClass("is-valid");
            }

        });


        $("#lastName").bind("keyup change", function(e) {
            var lastname = $("input[name=lastname]").val();

            if (lastname) {
                $("#lastName").removeClass("is-invalid");
                $("#lastName").addClass("is-valid");
            }

        });


    },

}


$(document).ready(function() {
    addUser.init();
});