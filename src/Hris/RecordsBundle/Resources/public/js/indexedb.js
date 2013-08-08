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

function openDatabase(databaseName) {
    var openRequest = localDatabase.indexedDB.open(databaseName);
    openRequest.onerror = function(e) {
        console.log("Database error: " + e.target.errorCode);
    };
    openRequest.onsuccess = function(event) {
        localDatabase.db = openRequest.result;
    };
}

function createDatabase(databaseName, tableName, columnNames) {
    /*
    Parsing the names of columns form strin to Json Format
     */
    columnNames = JSON.parse(columnNames);
    tableName = JSON.parse(tableName);

    console.log('Deleting local database');
    var deleteDbRequest = localDatabase.indexedDB.deleteDatabase(databaseName);
    deleteDbRequest.onsuccess = function (event) {
        console.log('Database deleted');
        var openRequest = localDatabase.indexedDB.open(databaseName,1);

        openRequest.onerror = function(e) {
            console.log("Database error: " + e.target.errorCode);
        };
        openRequest.onsuccess = function(event) {
            console.log("Database created");
            localDatabase.db = openRequest.result;
        };
        openRequest.onupgradeneeded = function (evt) {
            console.log('Creating object stores');
            var dataStore = evt.currentTarget.result.createObjectStore
            (tableName, {keyPath: "id"});

            /*
            Creating the Columns for data Storage
             */
            for (var key in columnNames){
                var column_name = columnNames[key];

                dataStore.createIndex(column_name, "state", { unique: false });
            }
        };
        deleteDbRequest.onerror = function (e) {
            console.log("Database error: " + e.target.errorCode);
        };

    };
}

function addRecords(tableName, dataValues) {
    console.log("populating database");

    var tx = localDatabase.db.transaction("hris_form", "readwrite");
    var store = tx.objectStore("hris_form");

    if (localDatabase != null && localDatabase.db != null) {
        /*
        Start of store function to send data to database
         */
        console.log("populating database with unknown data for testing");
        var request = store.put({
            "id": "E1",
            "first_name" : "Joe",
            "last_name" : "Smith",
            "email" : "joe.smith@somedomain.com",
            "street" : "123 Main Street",
            "city" : "New York",
            "state" : "New York",
            "zip_code" : "10016",
        });
        request.onsuccess = function(e) {
            console.log("Added E1");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };

        tx.oncomplete = function() {
            console.log("All data is entered into the system");
        };
        // end of transactions

    }
}

function populateDatabase() {
    console.log("populating database");
    var transaction = localDatabase.db.transaction("employees", "readwrite");
    var store = transaction.objectStore("employees");

    if (localDatabase != null && localDatabase.db != null) {
        var request = store.put({
            "id": "E1",
            "first_name" : "Joe",
            "last_name" : "Smith",
            "email" : "joe.smith@somedomain.com",
            "street" : "123 Main Street",
            "city" : "New York",
            "state" : "New York",
            "zip_code" : "10016",
        });
        request.onsuccess = function(e) {
            console.log("Added E1");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };

        request = store.put({
            "id": "E2",
            "first_name" : "John",
            "last_name" : "Jones",
            "email" : "john.jones@somedomain.com",
            "street" : "999 Main Street",
            "city" : "New York",
            "state" : "New York",
            "zip_code" : "10016",
        });
        request.onsuccess = function(e) {
            console.log("Added E2");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };
        request = store.put({
            "id": "E3",
            "first_name" : "John",
            "last_name" : "Adams",
            "email" : "john.adams@somedomain.com",
            "street" : "1 First Street",
            "city" : "San Franciso",
            "state" : "California",
            "zip_code" : "94118",
        });
        request.onsuccess = function(e) {
            console.log("Added E3");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };
        request = store.put({
            "id": "E4",
            "first_name" : "Jane",
            "last_name" : "Adams",
            "email" : "jane.adams@somedomain.com",
            "street" : "2 Second Street",
            "city" : "San Franciso",
            "state" : "California",
            "zip_code" : "92101",
        });

        request.onsuccess = function(e) {
            console.log("Added E4");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };

        request = store.put({
            "id": "E5",
            "first_name" : "Jane",
            "last_name" : "Davis",
            "email" : "jane.davis@somedomain.com",
            "street" : "3 Third Street",
            "city" : "San Franciso",
            "state" : "California",
            "zip_code" : "92100",
        });

        request.onsuccess = function(e) {
            console.log("Added E5");
        };

        request.onerror = function(e) {
            console.log(e.value);
        };
    }
}