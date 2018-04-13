/**
 * Checks if the user is already logged in, and if they are, to display their schedule items
 */
$(document).ready((event)=>{
   if(!(localStorage.getItem('userId') === null)){
       let userId = localStorage.getItem("userId");
       let userName = localStorage.getItem("userName");
       $("#name").text("Welcome "+userName+"!");
       get("http://206.189.64.170/php/ScheduleItemRest.php?userId="+userId).then(function(response){
           response = JSON.parse(response);
           console.log(response);
           displayScheduleItems(response.message);
       }, function(error){
           console.log(error);
       });
   }
});

/**
 * Generic get method which allows me to use Promises
 *
 * @param url The url I am sending a GET request to
 * @returns {Promise}
 */
function get(url) {
    // Return a new promise.
    return new Promise(function(resolve, reject) {
        // Do the usual XHR stuff
        var req = new XMLHttpRequest();
        req.open('GET', url);

        req.onload = function() {
            // This is called even on 404 etc
            // so check the status
            if (req.status == 200) {
                // Resolve the promise with the response text
                resolve(req.response);
            }
            else {
                // Otherwise reject with the status text
                // which will hopefully be a meaningful error
                reject(Error(req.statusText));
            }
        };

        // Handle network errors
        req.onerror = function() {
            reject(Error("Network Error"));
        };

        // Make the request
        req.send();
    });
}

/**
 * A generic post method which checks user credentials and allows me to use Promises
 * @param userName The user's username
 * @param password The user's password
 * @returns {Promise}
 */
function post(userName, password) {
    // Return a new promise.

    return new Promise(function(resolve, reject) {
        // Do the usual XHR stuff
        let req = new XMLHttpRequest();
        req.open("POST", "http://206.189.64.170/php/UserRest.php", true);
        req.setRequestHeader('Content-Type', 'application/json');

        req.onload = function() {
            // This is called even on 404 etc
            // so check the status
            if (req.status == 200) {
                // Resolve the promise with the response text
                resolve(req.response);
            }
            else {
                // Otherwise reject with the status text
                // which will hopefully be a meaningful error
                reject(Error(req.statusText));
            }
        };

        // Handle network errors
        req.onerror = function() {
            reject(Error("Network Error"));
        };

        // Make the request
        req.send(JSON.stringify({
            userUsername: userName,
            userPassword: password
        }));
    });
}


/**
 * Initializes an event listener in the form which prevents classic form POST submittal in favor of
 * using Promises to access data from the REST Endpoints. Also handles logic for displaying scheduleItems
 * as well as showing errors if the user entered in faulty credentials
 *
 */
let initializeLogin = ()=>{
    let form = document.getElementById("login-form");
    form.addEventListener('submit',(event)=>{
        event.preventDefault();
        let userName = document.getElementById("userUsername").value;
        console.log(userName);
        let password = document.getElementById("userPassword").value;
        console.log(password);
        post(userName,password).then(function(response) {
            response = JSON.parse(response);
            //store userId and userName in local storage
            let userId = response.message.userId;
            let userName = response.message.userUsername;
            localStorage.setItem("userId",userId);
            localStorage.setItem("userName",userName);
            $("#name").text("Welcome "+userName+"!");
            if(response.status === 200){
                //if the credentials were authorized, display the user's corresponding schedule
                $("#form-error").addClass("hide");
                get("http://206.189.64.170/php/ScheduleItemRest.php?userId="+userId).then(function(response){
                    response = JSON.parse(response);
                    console.log(response);
                    displayScheduleItems(response.message);
                }, function(error){
                    console.log(error);
                });
            }else{
                //if the credentials were faulty, show an error message
                $("#form-error").removeClass("hide");
                }
        }, function(error) {
            console.error("POST FAILED", error);
        });

    });
};

/**
 * A method used to display schedule items from a get request to the scheduleItem endpoint
 *
 * @param scheduleItems A list of Javascript objects in JSON format
 */
let displayScheduleItems = (scheduleItems)=>{
    $('#initial').empty();
    let keys = Object.keys(scheduleItems).length;
    $('#login-container').addClass("hide");
    $('#schedule-container').removeClass("hide");
    for(let i = 0; i < keys; i++){
        //populate the collapsible section with a row for every schedule item
        let name = scheduleItems[i].scheduleItemName;
        let description = scheduleItems[i].scheduleItemDescription;
        let startTime = new Date(scheduleItems[i].scheduleItemStartTime);
        let endTime = new Date(scheduleItems[i].scheduleItemEndTime);

        let displayString = "<li>\n" +
            "                        <div class=\"collapsible-header\"><i class=\"material-icons\">access_time\n" +
            "                            </i>"+name+"</div>\n" +
            "                        <div class=\"collapsible-body\">\n" +
            "                            <div>Start Time: "+startTime+"</div>\n" +
            "                            <div>End Time: "+endTime+"</div>\n" +
            "                            <div>Description: "+description+"</div>\n" +
            "                        </div>\n" +
            "                    </li>";

        $('#initial').append(displayString);
    }

};
//add a click event listener to the logout button to clear local storage and display the login screen again
let initializeLogout = ()=>{
  $("#logout").click((event)=>{
      $("#initial").empty();
      $("#schedule-container").addClass("hide");
      $("#login-container").removeClass("hide");
        localStorage.clear();
  });
};


//initialize MaterializeCSS's collapsible component
let elem = document.querySelector('.collapsible');
let instance = M.Collapsible.init(elem);

//initialize login form and logout button
initializeLogin();
initializeLogout();

