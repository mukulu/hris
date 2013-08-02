/**
 * Created with JetBrains PhpStorm.
 * User: administrator
 * Date: 7/31/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */
var localDatabase = {};
//var dbName = "employeeDb";
localDatabase.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
localDatabase.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange;
localDatabase.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction;

localDatabase.indexedDB.onerror = function (e) {
    console.log("Database error: " + e.target.errorCode);
};

function openDatabase(dbName) {
    var openRequest = localDatabase.indexedDB.open(dbName);
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

function addRecords(tableName, dataValues) {
    /*
    Parsing the object to Json format for further use.
     */
    tableName = JSON.parse(tableName);
    dataValues = JSON.parse(dataValues);


    for(var i = 0; i < dataValues.length; i++)
    {
        console.log( dataValues[i] );

        Object.getOwnPropertyNames(dataValues[i]).forEach(function(val, idx, array) {
            console.log('"' + val + '":"' + dataValues[i][val] + '"');
        });
    }

    try {
        alert(tableName);

        var result = document.getElementById("result");
        result.innerHTML = "";


        var transaction = localDatabase.db.transaction(tableName, "readwrite");
        var store = transaction.objectStore(tableName);

        if (localDatabase != null && localDatabase.db != null) {
            var request = store.add({
                "id": "E5",
                "first_name" : "Jane",
                "last_name" : "Doh",
                "email" : "jane.doh@somedomain.com",
                "street" : "123 Pennsylvania Avenue",
                "city" : "Washington D.C.",
                "state" : "DC",
                "zip_code" : "20500",
            });
            request.onsuccess = function(e) {
                result.innerHTML = "Employee record was added successfully.";;
            };

            request.onerror = function(e) {
                console.log(e.value);
                result.innerHTML = "Employee record was not added.";
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function updateEmployee() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        var transaction = localDatabase.db.transaction("employees", "readwrite");
        var store = transaction.objectStore("employees");
        var jsonStr;
        var employee;

        if (localDatabase != null && localDatabase.db != null) {

            store.get("E3").onsuccess = function(event) {
                employee = event.target.result;
                // save old value
                jsonStr = "OLD: " + JSON.stringify(employee);
                result.innerHTML = jsonStr;

                // update record
                employee.email = "john.adams@anotherdomain.com";

                var request = store.put(employee);

                request.onsuccess = function(e) {
                };

                request.onerror = function(e) {
                    console.log(e.value);
                };

                // fetch record again
                store.get("E3").onsuccess = function(event) {
                    employee = event.target.result;
                    jsonStr = "<br/>NEW: " + JSON.stringify(employee);
                    result.innerHTML = result.innerHTML  + jsonStr;
                };
            }; // fetch employee first time
        }
    }
    catch(e){
        console.log(e);
    }
}

function clearAllEmployees() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees", "readwrite").objectStore("employees");

            store.clear().onsuccess = function(event) {
                result.innerHTML = "'Employees' object store cleared";
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchEmployee() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";
        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees").objectStore("employees");
            store.get("E3").onsuccess = function(event) {
                var employee = event.target.result;
                if (employee == null) {
                    result.innerHTML = "employee not found";
                }
                else {
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = jsonStr;
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchAllEmployees() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var request = store.openCursor();
            request.onsuccess = function(evt) {
                var cursor = evt.target.result;
                if (cursor) {
                    var employee = cursor.value;
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = result.innerHTML + "<br/>" + jsonStr;
                    cursor.continue();
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchNewYorkEmployees() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var range = IDBKeyRange.only("New York");

            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var index = store.index("stateIndex");

            index.openCursor(range).onsuccess = function(evt) {
                var cursor = evt.target.result;
                if (cursor) {
                    var employee = cursor.value;
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = result.innerHTML + "<br/>" + jsonStr;
                    cursor.continue();
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchEmployeeByEmail() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var range = IDBKeyRange.only("john.adams@somedomain.com");

            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var index = store.index("emailIndex");

            index.get(range).onsuccess = function(evt) {
                var employee = evt.target.result;
                var jsonStr = JSON.stringify(employee);
                result.innerHTML = jsonStr;
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchEmployeeByZipCode1() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var index = store.index("zipIndex");

            var range = IDBKeyRange.lowerBound("92000");

            index.openCursor(range).onsuccess = function(evt) {
                var cursor = evt.target.result;
                if (cursor) {
                    var employee = cursor.value;
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = result.innerHTML + "<br/>" + jsonStr;
                    cursor.continue();
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchEmployeeByZipCode2() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var index = store.index("zipIndex");

            var range = IDBKeyRange.upperBound("93000");

            index.openCursor(range).onsuccess = function(evt) {
                var cursor = evt.target.result;
                if (cursor) {
                    var employee = cursor.value;
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = result.innerHTML + "<br/>" + jsonStr;
                    cursor.continue();
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}

function fetchEmployeeByZipCode3() {
    try {
        var result = document.getElementById("result");
        result.innerHTML = "";

        if (localDatabase != null && localDatabase.db != null) {
            var store = localDatabase.db.transaction("employees").objectStore("employees");
            var index = store.index("zipIndex");

            var range = IDBKeyRange.bound("92000", "92999", true, true);

            index.openCursor(range).onsuccess = function(evt) {
                var cursor = evt.target.result;
                if (cursor) {
                    var employee = cursor.value;
                    var jsonStr = JSON.stringify(employee);
                    result.innerHTML = result.innerHTML + "<br/>" + jsonStr;
                    cursor.continue();
                }
            };
        }
    }
    catch(e){
        console.log(e);
    }
}