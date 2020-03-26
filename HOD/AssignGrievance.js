var global_dept_id = null;
var CONTROLLER_LINK = "controller.php?action=";

var callback = (url,func)=>{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200)
        {
            func(this);
        }
    };
    xhttp.open('GET',url,true);
    xhttp.send();
}

function setup(dept_id){
    global_dept_id = dept_id;
    callback('controller.php?action=getStaffs&dept_id='+dept_id, getStaffs);
    callback('controller.php?action=unassignedGriev&dept_id='+dept_id, getUnassignedGriev);
}

function createStaffDiv(id, uname, year){
    var div = document.createElement("div");
    div.setAttribute("class", "row");
    
    var col4 = document.createElement("div");
    col4.setAttribute("class", "col-sm-4");
    col4.innerHTML = id;
    div.appendChild(col4);
    
    var col4 = document.createElement("div");
    col4.setAttribute("class", "col-sm-4");
    col4.innerHTML = uname;
    div.appendChild(col4);
    
    var col4 = document.createElement("div");
    col4.setAttribute("class", "col-sm-4");
    col4.innerHTML = year;
    div.appendChild(col4);
    return div;
}

function createTableHeader(id, value){
    th = document.createElement("th");
    th.setAttribute("id",id);
    th.innerHTML = value;
    return th;
}

function createTableBody(value){
    td = document.createElement("td");
    td.innerHTML = value;
    return td;
}

function getStaffs(xhttp){
    console.log(xhttp.responseText)
    data = JSON.parse(xhttp.responseText); //{"id", "username", "year"}
    form = document.getElementById("staffs");
    if(data.length === 0)
    {
        form.innerHTML = "No Staffs";
    }
    else
    {
        while(form.firstChild){
            form.removeChild(form.firstChild);
        }
        
        for(staff of data){
            var radio = document.createElement("input");
            radio.setAttribute("type", "radio");
            radio.setAttribute("name", "staff");
            radio.value = staff.id;
            
            var label = document.createElement("label");
            label.setAttribute("class", "btn btn-outline-dark");
            label.setAttribute("for", staff.id);
            label.style.width = "265px";
            
            var div = document.createElement("div");            
            div.appendChild(radio);
            div.appendChild(createStaffDiv(staff.id, staff.username, staff.year));
            label.appendChild(div);
            
            form.appendChild(label);
        }
    }
}

function setSuccess(xhttp){
    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText);
    var p = document.getElementById("success");
    if(data == true){
        p.style.display = "block";
        p.innerHTML = "Successfully Assigned";
        p.setAttribute("class", "text-success");
    }
    else{
        p.style.display = "block";
        p.innerHTML = "Error Occured";
        p.setAttribute("class", "text-danger");
    }
    callback('controller.php?action=unassignedGriev&dept_id='+global_dept_id, getUnassignedGriev);
}

function selfAssign(id){
    var ticket_id = document.getElementById("ticket_id").value;
    url = CONTROLLER_LINK + `assignGriev&handler_id=${id}&ticket_id=${ticket_id}`;
    callback(url, setSuccess);
}

function assignToStaff(){
    var id = document.querySelectorAll('input[name="staff"]:checked');
    if(id.length == 0)return;
    id = id[0].value;
    selfAssign(id);
}

function setTicketID(ticket_id){
    var input = document.getElementById('ticket_id');
    input.value = ticket_id;
}

function closeReset(){
    setTicketID("");
    var p = document.getElementById("success");
    p.style.display = "none";
    p.innerHTML = "";
    var radio = document.querySelectorAll('input[name="staff"]:checked');
    for(i of radio){
        i.checked = false;
    }
}

function getUnassignedGriev(xhttp)
{
    var table = document.getElementById("customtable");
    var makeTbody = (grievance, table)=>{
        var tr = document.createElement("tr");
        tr.appendChild(createTableBody(grievance.ticket_id));
        tr.appendChild(createTableBody(grievance.title));
        tr.appendChild(createTableBody(grievance.year));
        
        var button = document.createElement("button");
        button.innerHTML = "Assign";
        button.setAttribute("type", "button");
        button.setAttribute("data-toggle", "modal");
        button.setAttribute("class", "btn btn-outline-primary");
        button.setAttribute("data-target", "#exampleModalCenter");
        button.setAttribute("onclick", 'setTicketID("'+grievance.ticket_id+'")');

        tr.appendChild(button);
        table.appendChild(tr);
    };

    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText);
    grievance = document.getElementById("nogrievance");

    if(data.length === 0)
    {
        table.style.display = "none";
        grievance.style.display = "";
    }
    else
    {
        table.style.display = "";
        grievance.style.display = "none";

        while(table.childElementCount > 1){
            table.removeChild(table.lastChild);
        }

        var tbody = document.createElement("tbody");
        tbody.setAttribute("align","center");
        for(grievance of data)
        {
            makeTbody(grievance, tbody);
        }
        table.append(tbody);
    }
}