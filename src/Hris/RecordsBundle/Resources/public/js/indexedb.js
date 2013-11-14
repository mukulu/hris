/**
 * Created with JetBrains PhpStorm.
 * User: administrator
 * Date: 7/31/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */
var localDatabase = {};
var dbName = "employeeDb";
localDatabase.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
localDatabase.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange;
localDatabase.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction;
localDatabase.indexedDB.enable = true;

localDatabase.indexedDB.onerror = function (e) {
    console.log("Database error: " + e.target.errorCode);
};

var replaceOptionString = "";


function createDatabase(databaseName, tableName, columnNames, dataValues) {
    /*
     Parsing the names of columns form strin to Json Format
     */
    tableName = JSON.parse(tableName);

    var openRequest = localDatabase.indexedDB.open(databaseName);

    openRequest.onupgradeneeded = function () {
        // The database did not previously exist, so create object stores and indexes.
        var db = openRequest.result;
        //var tables = ["hris_form2", "hris_form3"];

        var created = false;

        for (var key in tableName) {
            var dataStore = db.createObjectStore(tableName[key], {keyPath: "id"});
            console.log("data Store " + tableName[key] + " Created");

            dataStore.createIndex("uid", "uid", { unique: true });
            console.log("data Store Column UID  Added for the datastore " + tableName[key]);

            if (created == false) {
                var dataStore = db.createObjectStore("field_option_association", {keyPath: "id"});
                console.log("data Store 'field_option_association' Created");

                dataStore.createIndex("fieldoption", "fieldoption", { unique: false });
                console.log("data Store Column UID  Added for the datastore 'field_option_Association' ");
                created = true;

                var dataStore = db.createObjectStore("offline_datavalues", {keyPath: "id"});
                console.log("data Store 'offline_datavalues' Created");

                dataStore.createIndex("status", "status", { unique: false });
                console.log("data Store Column status  Added for the datastore 'offline_datavalues' ");
                created = true;
            }
        }

    };

    openRequest.onsuccess = function () {
        db = openRequest.result;
        console.log("database has been generated");
    };

}

function deleteDatabase(databaseName) {
    /*
     Parsing the names of columns form strin to Json Format
     */

    var deleteRequest = localDatabase.indexedDB.deleteDatabase(databaseName);

    console.log("deleting " + databaseName + " database");

    $("#reload").hide();

    alert("All records are cleared from offline database");

    location.reload();

    deleteRequest.onsuccess = function () {

        console.log("database " + databaseName + "deleted");

        alert("All records are cleared from offline database");
        $("#reload").hide();
        location.reload();
        $("#reload").show();

    };

    deleteRequest.onerror = function (e) {
        console.log("Database error: " + e.target.errorCode);
    };

}


function addRecords(databaseName, tableName, dataValues) {
    /*
     Parsing the names of columns form strin to Json Format
     */

    tableName = JSON.parse(tableName);
    dataValues = JSON.parse(dataValues);


    var openRequest = localDatabase.indexedDB.open(databaseName);

    openRequest.onsuccess = function () {
        db = openRequest.result;

        var transaction = db.transaction(tableName, "readwrite");
        var store = transaction.objectStore(tableName);

        var results = '{';

        for (var key in dataValues) {
            Object.getOwnPropertyNames(dataValues[key]).forEach(function (val, idx, array) {

                if (val == "dataType" || val == "inputType") {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]['name']) + '", ';
                } else if (val == "datecreated" || val == "lastupdated") {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]['date']) + '", ';
                } else if (val == "field") {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]['uid']) + '", ';
                } else if (val == "parent") {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]['uid']) + '", ';
                } else {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]) + '", ';
                }
            });

            results = results.slice(0, -2);
            results += '}';
            store.put(JSON.parse(results));
            console.log("Results has been update");
            var results = '{';
        }

        transaction.oncomplete = function () {
            // All requests have succeeded and the transaction has committed.
            console.log("All Records in " + tableName + " has been saved offline");
            $("#reload").show();
        };

    };

}

function getSingleRecord(databaseName, uid, tableName) {

    tableName = JSON.parse(tableName);

    var result = document.getElementById("result");
    result.innerHTML = "";

    var openRequest = localDatabase.indexedDB.open(databaseName);

    openRequest.onsuccess = function () {
        db = openRequest.result;

        var transaction = db.transaction(tableName, "readonly");
        var store = transaction.objectStore(tableName);
        var index = store.index("uid");

        var request = index.get(uid);
        request.onsuccess = function () {
            var matching = request.result;
            if (matching !== undefined) {
                // A match was found.

                var jsonStr = JSON.stringify(decodeURIComponent(matching.hypertext));
                result.innerHTML = decodeURIComponent(matching.hypertext);

            } else {
                // No match was found.
                report(null);
            }
        };

    };

}

function getDataEntryForm(databaseName, formUid, tableName) {

    tableName = JSON.parse(tableName);

    var result = document.getElementById("result");
    result.innerHTML = "";

    var openRequest = localDatabase.indexedDB.open(databaseName);

    function getForm(callback) {

        openRequest.onsuccess = function () {
            db = openRequest.result;

            var transaction = db.transaction(tableName, "readonly");
            var store = transaction.objectStore(tableName);
            var index = store.index("uid");

            var request = index.get(formUid);
            request.onsuccess = function () {
                var matching = request.result;
                if (matching !== undefined) {
                    // A match was found.

                    var jsonStr = JSON.stringify(decodeURIComponent(matching.hypertext));

                    var hypertext = decodeURIComponent(matching.hypertext);

                    result.innerHTML = hypertext;

                    //formatting dates

                    $( ".date" ).datepicker( {
                        changeMonth: true,
                        changeYear: true,
                        showOn: "both",
                        buttonImageOnly: true,
                        dateFormat: "dd/mm/yy",
                        buttonImage: "../../../commons/images/calendar.gif",
                        showAnim: "clip",
                        yearRange:'c-60:c+60'
                    });

                    $(".date").keypress(function(event) {

                        if (event.keyCode === 8) {
                            return true;
                        };
                        event.preventDefault();
                    });

                } else {
                    // No match was found.

                    report(null);
                }
            };

        };
        callback();
    }

    getForm(function () {
        //alert('Finished eating my sandwich.');
    });

}

function loadFieldOptions(fieldUIDS, databaseName) {

    var fieldUid = JSON.parse(fieldUIDS);

    $.each(fieldUid, function (key, value) {

        var field_uid = value;

        //getting all the field Combos

        var openRequest = localDatabase.indexedDB.open(databaseName);

        openRequest.onsuccess = function () {

            var db = openRequest.result;

            var fieldOptionTransaction = db.transaction("hris_fieldoption", "readonly");
            var fieldOptionStore = fieldOptionTransaction.objectStore("hris_fieldoption");

            var fielOptiondRequest = fieldOptionStore.openCursor();

            var emptyElement = false

            fielOptiondRequest.onsuccess = function () {

                var cursorOption = fielOptiondRequest.result;


                if (cursorOption) {

                    //Setting the first empty Option

                    if ( emptyElement == false ){

                        $("#" + field_uid).append($('<option>', {
                            value: '',
                            text: '--'
                        }));

                        emptyElement = true;
                    }

                    if (field_uid == cursorOption.value.field) {

                        $("#" + field_uid).append($('<option>', {
                            value: cursorOption.value.uid,
                            text: decodeURIComponent(cursorOption.value.value)
                        }));
                    }

                    cursorOption.continue();
                }
                else {
                    console.log('No more Matching Fields Options');
                }

            }
        }
    });

}

function changeRelatedFieldOptions(field_uid) {

    var optionUid = $("#" + field_uid).val();

    //getting all the field Combos

    var openRequest = localDatabase.indexedDB.open("hrhis");

    openRequest.onsuccess = function () {

        var db = openRequest.result;

        var fieldOptionTransaction = db.transaction("field_option_association", "readonly");
        var fieldOptionStore = fieldOptionTransaction.objectStore("field_option_association");

        var index = fieldOptionStore.index("fieldoption");

        var request = index.openCursor(IDBKeyRange.only(optionUid));

        var removeElement = false

        request.onsuccess = function () {

            var cursorOption = request.result;

            if (cursorOption) {

                if ( removeElement == false ){
                    $("#" + cursorOption.value.fieldref).find('option').remove();

                    $("#" + cursorOption.value.fieldref).append($('<option>', {
                        value: '',
                        text: '--'
                    }));

                    removeElement = true;
                }

                $("#" + cursorOption.value.fieldref).append($('<option>', {
                    value: cursorOption.value.fieldoptionrefuid,
                    text: decodeURIComponent(cursorOption.value.fieldoptionref)
                }));
                cursorOption.continue();
            }
            else {
                console.log('No more Matching Fields Options');
            }

        }
    }

}

function isUnique(field_uid) {

    var optionUid = $("#" + field_uid).val();

    //getting all the field Combos

    var openRequest = localDatabase.indexedDB.open("hrhis");

    openRequest.onsuccess = function () {

        var db = openRequest.result;

        var fieldOptionTransaction = db.transaction("field_option_association", "readonly");
        var fieldOptionStore = fieldOptionTransaction.objectStore("field_option_association");

        var index = fieldOptionStore.index("fieldoption");

        var request = index.openCursor(IDBKeyRange.only(optionUid));

        var removeElement = false

        request.onsuccess = function () {

            var cursorOption = request.result;

            if (cursorOption) {

                if ( removeElement == false ){
                    $("#" + cursorOption.value.fieldref).find('option').remove();
                    removeElement = true;
                }

                $("#" + cursorOption.value.fieldref).append($('<option>', {
                    value: cursorOption.value.fieldoptionrefuid,
                    text: decodeURIComponent(cursorOption.value.fieldoptionref)
                }));
                cursorOption.continue();
            }
            else {
                console.log('No more Matching Fields Options');
            }

        }
    }

}

function getunits(parent){

    var parentUid = $(parent).val();

    //getting all the field Combos

    var openRequest = localDatabase.indexedDB.open('hrhis');

    openRequest.onsuccess = function () {

        var db = openRequest.result;

        var orgunitTransaction = db.transaction("hris_organisationunit", "readonly");
        var orgunitStore = orgunitTransaction.objectStore("hris_organisationunit");

        var orgunitRequest = orgunitStore.openCursor();

        $("#units").find('option').remove();

        $("#units").append($('<option>', {
            value: '',
            text: '--'
        }));

        orgunitRequest.onsuccess = function () {

            var cursorOption = orgunitRequest.result;


            if (cursorOption) {

                if (parentUid == cursorOption.value.parent) {

                    $("#units").append($('<option>', {
                        value: cursorOption.value.uid,
                        text: decodeURIComponent(cursorOption.value.longname)
                    }));
                }

                cursorOption.continue();
            }
            else {
                console.log('No more Matching Fields Options');
            }

        }
    }
}

function populateForm(fieldUIDS, databaseName, dataValues, otherFields, orgunit) {

    dataValues = JSON.parse(dataValues);
    otherFields = JSON.parse(otherFields);
    orgunit = JSON.parse(orgunit);

    var fieldUid = JSON.parse(fieldUIDS);

    //Setting Organizationunit
    $.each(orgunit, function (key, value) {
        $("#units").append($('<option>', {
            value: value["uid"],
            text: value["longname"],
            selected: "selected"
        }));

    });

    $.each(fieldUid, function (key, value) {

        var field_uid = value;

        //getting all the field Combos

        var openRequest = localDatabase.indexedDB.open(databaseName);

        openRequest.onsuccess = function () {

            var db = openRequest.result;

            var fieldOptionTransaction = db.transaction("hris_fieldoption", "readonly");
            var fieldOptionStore = fieldOptionTransaction.objectStore("hris_fieldoption");

            var fielOptiondRequest = fieldOptionStore.openCursor();

            var emptyElement = false

            fielOptiondRequest.onsuccess = function () {

                var cursorOption = fielOptiondRequest.result;


                if (cursorOption) {

                    //Setting the first empty Option



                    if (field_uid == cursorOption.value.field) {

                        $("#" + field_uid).append($('<option>', {
                            value: cursorOption.value.uid,
                            text: decodeURIComponent(cursorOption.value.value)
                        }));

                        if ( emptyElement == false ){

                            for (var keys in dataValues){

                                if (field_uid == keys && cursorOption.value.uid == dataValues[keys]){

                                $("#" + field_uid).append($('<option>', {
                                    value: cursorOption.value.uid,
                                    text: decodeURIComponent(cursorOption.value.value),
                                    selected: "selected"
                                }))

                                emptyElement = true;
                                }
                            }
                        }
                    }

                    cursorOption.continue();
                }
                else {
                    console.log('No more Matching Fields Options');

                    $.each(otherFields, function (key, value) {
                        for ( var keyValue in dataValues){
                            if(value == keyValue){

                                if (Object.prototype.toString.call(dataValues[keyValue]) == "[object Object]"){

                                    var date = dataValues[keyValue]["date"].split(" ");
                                    $('#' + value).val(date[0]);
                                    //alert(dataValues[keyValue]["date"]);
                                }else{
                                    $('#' + value).val(dataValues[keyValue]);
                                }

                            }
                        }
                    });
                }

            }
        }
    });

}

function offLineDataStorage(databaseName){
    $(function () {
        $('form').on('submit', function (e) {

            e.preventDefault();

            tableName = "offline_datavalues";
            dataValues = $('form').serialize();


            var openRequest = localDatabase.indexedDB.open(databaseName);

            openRequest.onsuccess = function () {
                db = openRequest.result;

                var transaction = db.transaction(tableName, "readwrite");
                var store = transaction.objectStore(tableName);

                var uuid = Math.floor((Math.random()*10000)+1);

                store.put({id: uuid, status: 'false', data: dataValues});

                transaction.oncomplete = function () {
                    // All requests have succeeded and the transaction has committed.
                    console.log("All Records in " + tableName + " has been saved offline");
                    $('form').trigger("reset");
                    window.scrollTo(0, 0);
                    alert("Data Has Been Saved Successfully");
                };

            };

        });

    });
}


function sendDataToServer(databaseName){

    var timer = $.timer(function() {

        var openRequest = localDatabase.indexedDB.open(databaseName);

        openRequest.onsuccess = function () {

            var db = openRequest.result;

            var transaction = db.transaction("offline_datavalues", "readonly");

            var store = transaction.objectStore("offline_datavalues");

            var index = store.index("status");

            var dataRequest = index.openCursor(IDBKeyRange.only("false"));

            var state = false;


            dataRequest.onsuccess = function () {

                var cursorOption = dataRequest.result;


                if (cursorOption) {

                    var dataValues = JSON.stringify(cursorOption.value.data);

                    var uuid = cursorOption.value.id;
                    var value = cursorOption.value.data;

                    $.ajax({
                        type: 'POST',
                        url: '../',
                        data: decodeURIComponent(cursorOption.value.data),
                        success: function () {
                            $('form').trigger("reset");
                            console.log('data has been submitted to the server');

                            var transactionUpdate = db.transaction("offline_datavalues", "readwrite");

                            var storeUpdate = transactionUpdate.objectStore("offline_datavalues");

                            storeUpdate.put({id: uuid, status: 'true', data: value});
                        },
                        error: function(){
                            console.log('form was not submitted, no internet connection');
                        }
                    });

                    cursorOption.continue();
                }
                else {
                    console.log('No more Matching Fields Options');
                }

            }

        }
    });

    timer.set({ time : 5000, autostart : true });
}