//Importing fields
function importFields( fields ){

    //Initialize field Import Message
    $("#status").append($('<option>', {
        value: "",
        text: "Starting Field Import"
    }));

    $.ajax({//Make the Ajax Request
        type: "POST",
        url: "./importFields",
        data: "fields="+ fields,
        success: function(html){

            $("#status").append($('<option>', {
                value: "",
                text: "Field Import Completed"
            }));
        }
    });

}

//Importing Field Options
function importFieldOptions( fieldOptions ){

    //Initialize field Import Message
    $("#status").append($('<option>', {
        value: "",
        text: "Starting Field Options Import"
    }));

    $.ajax({//Make the Ajax Request
        type: "POST",
        url: "./importFieldOptions",
        data: "fieldOptions="+ fieldOptions,
        success: function(html){

            $("#status").append($('<option>', {
                value: "",
                text: "Field Options Import Completed"
            }));
        }
    });

}

//Importing Organisationunits
function importOrganisationUnit( organisationUnits ){

    //Initialize field Import Message
    $("#status").append($('<option>', {
        value: "",
        text: "Starting organisationUnits Import"
    }));

    $.ajax({//Make the Ajax Request
        type: "POST",
        url: "./importOrganisationUnits",
        data: "organisationUnits="+ organisationUnits,
        success: function(html){

            $("#status").append($('<option>', {
                value: "",
                text: "organisationUnits Import Completed"
            }));
        }
    });

}

//Importing records
function importRecords( records ){

    //Initialize field Import Message
    $("#status").append($('<option>', {
        value: "",
        text: "Starting records Import"
    }));

    $.ajax({//Make the Ajax Request
        type: "POST",
        url: "./importRecords",
        data: "records="+ records,
        success: function(html){

            $("#status").append($('<option>', {
                value: "",
                text: "records Import Completed"
            }));
        }
    });

}