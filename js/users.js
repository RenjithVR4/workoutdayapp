var users = {
    init: function() {
        this.listUsers();
        this.pageEvent();
        this.editClickEvent();

    },
    page: 1,
    listUsers: function() {
        _this = this;
        $.ajax({
            url: "api/user.php/listing",
            type: "GET",
            success: function(data) {
                if (!data.error) {
                    _this.renderuserList(data);
                } else {
                    $("#content-body").html("<tr><td colspan='11'>" + data.error + "</td></tr>");
                }

            },
            error: function(data) {
                $("#content-body").html("<tr><td colspan='11'>No users available.</td></tr>");
            }
        });
    },
    renderuserList: function(data) {
        _this = this;
        var obj = data;

        if (obj.length < 15) {
            $(".next").addClass("disabled");
        } else {
            $(".next").removeClass("disabled");
        }

        var userContent = "";
        linkedit = '<a class="btn btn-danger edit-link" href="#">';


        var row = '<tr id={iduser}>' +
            '<td class="p-t-25">{firstname}</a></td>' +
            '<td class="p-t-25">{lastname}</a></td>' +
            '<td class="p-t-25">{email}</a></td>' +
            '<td class="p-t-25">{status}</a></td>' +
            '<td class="p-t-25">{created}</a></td>' +
            '<td>' + linkedit + 'Edit</a></td>' +
            "</tr>";

        for (i in obj) {
            str = row;

            for (j in obj[i]) {
                thisvalue = obj[i][j];

                if (thisvalue === null) {
                    thisvalue = "Not Defined";
                }

                if (thisvalue == 1) {
                    thisvalue = "Active";
                }

                if (thisvalue == 0) {
                    thisvalue = "Inactive";
                }


                str = str.replace("{" + j + "}", thisvalue);

            }

            userContent += str;
        }
        $("#content-body").html(userContent);
    },
    pageEvent: function() {
        _this = this;
        $(".next").off("click").on("click", function() {
            _this.page++;
            _this.renderuserList();

        });
        $(".prev").off("click").on("click", function() {
            _this.page--;
            _this.renderuserList();
        });
    },
    editClickEvent: function() {
        _this = this;
        $("body").on("click", ".edit-link", function() {
            thisid = $(this).parents("tr").attr("id");
            window.location = 'edituser.php?userid=' + thisid;

        });
    },


};

$(document).ready(function() {
    users.init();
});