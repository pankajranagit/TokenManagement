$(document).ready(function () {
    $('.refreshCaptcha').on('click', function () {
        var base_url = $('#base_url_captcha').val();
        $.get(base_url, function (data) {
            $('#captImg').html(data);
        });
    });

    $("#locationstate").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/Center/getcitylocation",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#citydiv').html('<input type="text" class="form-control" readonly="" required="" name="city">');
                } else {
                    $('#citydiv').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });



    $("#getcenterinfo").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/ManageOperator/getcenterinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });


    $("#getmappinginfo").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/CounterMapping/getcenterinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#getmappinginfo").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/CounterMapping/getcenterinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#getcenterdashinfo").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/CenterDashboard/getcenterinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });


    $("#getpropertyinfo").change(function () {
        var catg_id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "Admin/ManagePriority/getpropertyinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $(".set_priority").change(function () {

        var status_id = $(this).val();
        var ord_id = $(this).attr("slider-id");
        var center_id = $(this).attr("center_id");
        $.ajax({
            type: "POST",
            url: base_url + "Admin/ManagePriority/change_priority",
            data: {
                'status_id': status_id,
                'ord_id': ord_id,
                'center_id': center_id
            },
            success: function (response) {

                console.log(response);

                if (response == "success") {
                    alert("Priority Changed");
                } else {
                    alert("Same Priority Assigned to Another Type");
                    location.reload(true);
                }

            },
            error: function (error) {
                console.log(error);
            }
        });

    });

    $("#getservicesinfo").change(function () {
        var catg_id = $(this).val();
        // alert(catg_id);
        $.ajax({
            type: "POST",
            url: base_url + "Admin/ManageServices/getservicesinfo",
            data: {
                'catg_id': catg_id
            },
            success: function (response) {
                if (response == "NORECORD") {
                    $('#centerinfo').html('No Data Found');
                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

});