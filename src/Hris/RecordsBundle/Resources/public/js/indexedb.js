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

    openRequest.onupgradeneeded = function() {
        // The database did not previously exist, so create object stores and indexes.
        var db = openRequest.result;
        //var tables = ["hris_form2", "hris_form3"];

        for (var key in tableName){
            var dataStore = db.createObjectStore(tableName[key], {keyPath: "id"});
            console.log("data Store " + tableName[key] + " Created");

            dataStore.createIndex("uid", "uid", { unique: true });
            console.log("data Store Column UID  Added for the datastore " + tableName[key] );
        }

        /*
         Creating the Columns for data Storage

        for (var key in columnNames){
            var column_name = columnNames[key];

            dataStore.createIndex(column_name, column_name, { unique: false });
            console.log("data Store Column '" + column_name + "' Added");
        }
         */
    };

    openRequest.onsuccess = function() {
        db = openRequest.result;
        console.log("this is done deal");
/*
        var transaction = db.transaction(tableName, "readwrite");
        var store = transaction.objectStore(tableName);

        var results = '{';

        for (var key in dataValues){
            Object.getOwnPropertyNames(dataValues[key]).forEach(function(val, idx, array) {
                results += '"'+ val + '" : "' + encodeURIComponent(dataValues[key][val]) +'", ';
            });

            console.log("this is the name " + results.name);
            results = results.slice(0,-2);
            results += '}';
            console.log(results);
            store.put( JSON.parse(results) );
            console.log("Results has been update");
            var results = '{';
        }

        transaction.oncomplete = function() {
            // All requests have succeeded and the transaction has committed.
            console.log("All transaction done");
        };
 */
    };

}

function deleteDatabase(databaseName) {
    /*
     Parsing the names of columns form strin to Json Format
     */

    var deleteRequest = localDatabase.indexedDB.deleteDatabase(databaseName);
    console.log("deleting " + databaseName + " database");

    deleteRequest.onsuccess = function() {

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

    openRequest.onsuccess = function() {
        db = openRequest.result;
        console.log("this is done deal");

        var transaction = db.transaction(tableName, "readwrite");
        var store = transaction.objectStore(tableName);

        var results = '{';

        for (var key in dataValues){
            Object.getOwnPropertyNames(dataValues[key]).forEach(function(val, idx, array) {

                if( val == "dataType" || val == "inputType" ){
                    results += '"'+ val + '" : "' + encodeURIComponent(dataValues[key][val]['name']) +'", ';
                }else if( val == "datecreated" || val == "lastupdated" ){
                    results += '"'+ val + '" : "' + encodeURIComponent(dataValues[key][val]['date']) +'", ';
                }else if( val == "field" ){
                    results += '"'+ val + '" : "' + encodeURIComponent(dataValues[key][val]['uid']) +'", ';
                }else{
                    results += '"'+ val + '" : "' + encodeURIComponent(dataValues[key][val]) +'", ';
                }
            });

            results = results.slice(0,-2);
            results += '}';
            console.log(results);
            store.put( JSON.parse(results) );
            console.log("Results has been update");
            var results = '{';
        }

        transaction.oncomplete = function() {
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

    openRequest.onsuccess = function() {
        db = openRequest.result;
        console.log("this is done deal");

        //var transaction = db.transaction(tableName, "readwrite");
        //var store = transaction.objectStore(tableName);

        var transaction = db.transaction(tableName, "readonly");
        var store = transaction.objectStore(tableName);
        var index = store.index("uid");

        var request = index.get(uid);
        request.onsuccess = function() {
            var matching = request.result;
            if (matching !== undefined) {
                // A match was found.
                //report(matching.hypertext);

                var jsonStr = JSON.stringify(decodeURIComponent(matching.hypertext));
                result.innerHTML = decodeURIComponent(matching.hypertext);
                console.log(decodeURIComponent(matching.hypertext));

                //return matching.hypertext;
/*
                $.ajax({
                    type: "POST",
                    url: "http://localhost/hris/web/app_dev.php/record/new/3",
                    data: { variable: matching }
                }).success( console.log ("the event completed successful") );
*/

            } else {
                // No match was found.
                report(null);
            }
        };

    };

}

var testString = "";

function getDataEntryForm(databaseName, uid, tableName) {

    tableName = JSON.parse(tableName);

    console.log(uid);

    var result = document.getElementById("result");
    result.innerHTML = "";

    var openRequest = localDatabase.indexedDB.open(databaseName);

    openRequest.onsuccess = function() {
        db = openRequest.result;
        console.log("this is done deal");


        var transaction = db.transaction(tableName, "readonly");
        var store = transaction.objectStore(tableName);
        var index = store.index("uid");

        var request = index.get(uid);
        request.onsuccess = function() {
            var matching = request.result;
            if (matching !== undefined) {
                // A match was found.

                var jsonStr = JSON.stringify(decodeURIComponent(matching.hypertext));

                var hypertext = decodeURIComponent(matching.hypertext);
                //Formating the forms to be sent out as a complete form

                /*
                 /*
                 Getting Field Data
                 */

                var fieldTransaction = db.transaction("hris_field", "readonly");
                var storeObject = fieldTransaction.objectStore("hris_field");

                var fieldRequest = storeObject.openCursor();

                fieldRequest.onsuccess = function(evt) {


                    var cursor = fieldRequest.result;
                    if (cursor) {
                        // Called for each matching record.

                        var inputType = cursor.value.inputType;
                        var fieldName = cursor.value.name;

                        console.log(decodeURIComponent(inputType));

                        function foo(fn){


                        if( inputType == "combo" ){
                            //getting all the field Combos



                            var fieldOptionTransaction = db.transaction("hris_fieldoption", "readonly");
                            var fieldOptionStore = fieldOptionTransaction.objectStore("hris_fieldoption");

                            var fielOptiondRequest = fieldOptionStore.openCursor();
                            //replacing the field with Combo Box

                            var replaceOptionString = "<select name='" + fieldName + "'>";



                            fielOptiondRequest.onsuccess = function(callback) {

                                var cursorOption = fielOptiondRequest.result;

                                if (cursorOption) {

                                    if ( cursorOption.value.field == cursor.value.uid ){
                                        replaceOptionString += "<option value='" + cursorOption.value.id + "'>" + cursorOption.value.value + "</option>";

                                        //console.log(" Inside Field UID from Option = "+ cursorOption.value.field + " and UID from Fields " + cursor.value.uid +" and option value " + cursorOption.value.value);
                                    }
                                    //console.log("Field UID from Option = "+ cursorOption.value.field + " and UID from Fields " + cursor.value.uid +" and option value " + cursorOption.value.value);
                                    cursorOption.continue();
                                }
                                else {
                                    // No more matching records.
                                    console.log('No more Matching Fields Options');
                                   replaceOptionString += "</select >";
                                   fn(replaceOptionString);

                                    //hypertext = hypertext.replace("~" + fieldName + "~", replaceOptionString);
                                    //console.log(hypertext);

                                }

                            }

                        }
                        else if( inputType == "text_box" ){
                            //replacing the field with Text Box
                            var replaceString = "<input type='text' name='" + fieldName + "'>";
                            hypertext = hypertext.replace("~" + fieldName + "~", replaceString);
                        }
                        else if( inputType == "textArea" ){
                            //replacing the field with Text Area
                        }

                        cursor.continue();
                    }

                        foo(function(location){
                            alert(location); // this is where you get the return value
                            console.log("replacement " + location);
                            hypertext = hypertext.replace("~" + fieldName + "~", location);
                            console.log(hypertext);

                            result.innerHTML = hypertext;
                        });
                }else {
                        // No more matching records.
                        //console.log('No more Matching Fields');
                        //console.log(hypertext);
                        //result.innerHTML = hypertext;
                        // console.log(hypertext);
                    }
                }



            } else {
                // No match was found.
                report(null);
            }
        };

    };

}
