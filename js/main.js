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





let initializeLogin = ()=>{

    let form = document.getElementById("login-form");
    form.addEventListener('submit',(event)=>{
        event.preventDefault();
        let userName = document.getElementById("userUsername").value;
        let password = document.getElementById("userPassword").value;
        post(userName,password).then(function(response) {
            response = JSON.parse(response);
            let userId = response.message;
            if(response.status == 200){
                get("http://206.189.64.170/php/ScheduleItemRest.php?userId="+userId).then(function(response){
                    response = JSON.parse(response);
                    console.log(response);
                    displayScheduleItems(response.message);
                }, function(error){
                    console.error("GET FAILED", error);
                });
            } else{
                $("#form-error").addClass("hide");
                }
        }, function(error) {
            console.error("POST FAILED", error);
        });
    })
};


let displayScheduleItems = (scheduleItems)=>{
    let keys = Object.keys(scheduleItems).length;
    $('#login-container').toggleClass("hide");
    $('#schedule-container').toggleClass("hide");
    for(let i = 0; i < keys; i++){
        let name = scheduleItems[i].scheduleItemName;
        let description = scheduleItems[i].scheduleItemDescription;
        let startTime = new Date(scheduleItems[i].scheduleItemStartTime);
        let endTime = new Date(scheduleItems[i].scheduleItemEndTime);

        console.log("NAME: " + name);
        console.log("DESCRIPTION: " + description);
        console.log("START TIME" + startTime);
        console.log("END TIME "+ endTime);

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

let initializeLogout = ()=>{
  $("#logout").click((event)=>{
      $("#initial").empty();
      $("#schedule-container").toggleClass("hide");
      $("#login-container").toggleClass("hide");

  });
};



initializeLogout();



let elem = document.querySelector('.collapsible');
let instance = M.Collapsible.init(elem);
initializeLogin();