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
            }
        }

    };

    openRequest.onsuccess = function () {
        db = openRequest.result;
        console.log("this is done deal");
    };

}

function deleteDatabase(databaseName) {
    /*
     Parsing the names of columns form strin to Json Format
     */

    var deleteRequest = localDatabase.indexedDB.deleteDatabase(databaseName);
    console.log("deleting " + databaseName + " database");

    deleteRequest.onsuccess = function () {

        console.log("database " + databaseName + "deleted");

        var transaction = db.transaction(tableName, "readwrite");
        var store = transaction.objectStore(tableName);

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
        console.log("this is done deal");

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
                } else {
                    results += '"' + val + '" : "' + encodeURIComponent(dataValues[key][val]) + '", ';
                }
            });

            results = results.slice(0, -2);
            results += '}';
            console.log(results);
            store.put(JSON.parse(results));
            console.log("Results has been update");
            var results = '{';
        }

        transaction.oncomplete = function () {
            // All requests have succeeded and the transaction has committed.
            console.log("All transaction done");
        };

    };

}

function getSingleRecord(databaseName, uid, tableName) {

    tableName = JSON.parse(tableName);

    console.log(uid);

    var result = document.getElementById("result");
    result.innerHTML = "";

    var openRequest = localDatabase.indexedDB.open(databaseName);

    openRequest.onsuccess = function () {
        db = openRequest.result;
        console.log("this is done deal");


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
                console.log(decodeURIComponent(matching.hypertext));

            } else {
                // No match was found.
                report(null);
            }
        };

    };

}

function getDataEntryForm(databaseName, uid, tableName) {

    tableName = JSON.parse(tableName);

    console.log(uid);

    var result = document.getElementById("result");
    result.innerHTML = "";

    var openRequest = localDatabase.indexedDB.open(databaseName);

    function getForm(callback) {

        openRequest.onsuccess = function () {
            db = openRequest.result;
            console.log("this is done deal");


            var transaction = db.transaction(tableName, "readonly");
            var store = transaction.objectStore(tableName);
            var index = store.index("uid");

            var request = index.get(uid);
            request.onsuccess = function () {
                var matching = request.result;
                if (matching !== undefined) {
                    // A match was found.

                    var jsonStr = JSON.stringify(decodeURIComponent(matching.hypertext));

                    var hypertext = decodeURIComponent(matching.hypertext);

                    result.innerHTML = hypertext;

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

    console.log(fieldUid);

    $.each(fieldUid, function (key, value) {
        console.log(key + ": " + value);

        var field_uid = value;


        //getting all the field Combos

        var openRequest = localDatabase.indexedDB.open(databaseName);

        openRequest.onsuccess = function () {

            var db = openRequest.result;

            var fieldOptionTransaction = db.transaction("hris_fieldoption", "readonly");
            var fieldOptionStore = fieldOptionTransaction.objectStore("hris_fieldoption");

            var fielOptiondRequest = fieldOptionStore.openCursor();

            fielOptiondRequest.onsuccess = function () {

                var cursorOption = fielOptiondRequest.result;


                if (cursorOption) {

                    if (field_uid == cursorOption.value.field) {
                        console.log(decodeURIComponent(cursorOption.value.value) + " uid " + field_uid + " field_id " + cursorOption.value.uid);

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
                    removeElement = true;
                }

                console.log(decodeURIComponent(cursorOption.value.fieldoptionref) + " uid " + field_uid + " fieldoptionUID " + cursorOption.value.fieldoptionrefuid);

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
