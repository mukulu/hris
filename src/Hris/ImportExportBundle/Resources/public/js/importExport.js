//Importing fields
function importData( fields, fieldOptions, organisationUnits, records ){

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

            importFieldOptions( fieldOptions,  organisationUnits, records )
        }
    });

}

//Importing Field Options
function importFieldOptions( fieldOptions,  organisationUnits, records ){

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

            importOrganisationUnit( organisationUnits, records )
        }
    });

}

//Importing Organisationunits
function importOrganisationUnit( organisationUnits, records ){

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

            importRecords( records )
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