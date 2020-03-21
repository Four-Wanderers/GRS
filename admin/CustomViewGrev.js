var CONTROLLER_LINK = "controller.php?action=";

var callback = (url,func)=>{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200) //that is we received a positive response
        {
            func(this);
        }
    };
    xhttp.open('GET',url,true);
    xhttp.send();
}

function getDepartments(xhttp){
    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText); //array of {dept_id:"",dept_name:"",hod_name:""}
    form = document.getElementById("c_view_radio");
    if(data.length === 0)
    {
        form.innerHTML = "No departments ";
    }
    else
    {
        while(form.firstChild)
            form.removeChild(form.firstChild);
        for(dept of data){
            var radio = document.createElement("input");
            radio.setAttribute("type", "radio");
            radio.setAttribute("name", "dept");
            radio.setAttribute("id", dept.dept_id);
            radio.value = dept.dept_id;
            var label = document.createElement("label");
            label.setAttribute("for", dept.dept_id);
            label.innerHTML = dept.dept_name;
            form.appendChild(radio);
            form.appendChild(label);
            form.appendChild(document.createElement("br"));
        }
        var radios = document.getElementsByName("dept");
        radios[0].checked = true;
        getCheckedBox();
    }
}

function getCheckedBox(){
    var checkboxes = document.querySelectorAll('input[name="status"]:checked');
    var radio = document.querySelectorAll('input[name="dept"]:checked')[0];
    var dept_id = radio.value;
    var checks = [];

    for(ch of checkboxes)
        checks.push(ch.value);
    
    status = checks.join("_");
    
    var url = CONTROLLER_LINK + "grievance&dept_id="+dept_id+"&status="+status;
    console.log(url);
    callback(url, getGrievances);
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

function getGrievances(xhttp)
{
    var table = document.getElementById("customtable");
    
    var makeTbody = (grievance, table)=>{
        tr = document.createElement("tr");
        tr.appendChild(createTableBody(grievance.ticket_id));
        tr.appendChild(createTableBody(grievance.title));
        tr.appendChild(createTableBody(grievance.dept_name));
        tr.appendChild(createTableBody(grievance.handler_name));
        tr.appendChild(createTableBody(grievance.year));
        tr.appendChild(createTableBody(grievance.status));
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
        tbody.setAttribute("align", "center");
        for(grievance of data)
        {
            makeTbody(grievance, tbody);
        }
        table.append(tbody);
    }
}