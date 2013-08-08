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

function createDatabase(databaseName, tableName, columnNames, dataValues) {
    /*
    Parsing the names of columns form strin to Json Format
     */
    columnNames = JSON.parse(columnNames);
    tableName = JSON.parse(tableName);
    dataValues = JSON.parse(dataValues);

    var openRequest = indexedDB.open(databaseName);

    openRequest.onupgradeneeded = function() {
        // The database did not previously exist, so create object stores and indexes.
        var db = openRequest.result;
        var dataStore = db.createObjectStore(tableName, {keyPath: "uid"});
        console.log("data Store " + tableName + " Created");

        /*
         Creating the Columns for data Storage
         */
        for (var key in columnNames){
            var column_name = columnNames[key];

            dataStore.createIndex(column_name, column_name, { unique: false });
            console.log("data Store Column '" + column_name + "' Added");
        }
    };

    openRequest.onsuccess = function() {
        db = openRequest.result;
        console.log("this is done deal");

        var transaction = db.transaction("hris_form", "readwrite");
        var store = transaction.objectStore("hris_form");

        var results = '{';

        for (var key in dataValues){
            Object.getOwnPropertyNames(dataValues[key]).forEach(function(val, idx, array) {
                results += '"'+ val + '" : "' + dataValues[key][val] +'", ';
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

    };

}