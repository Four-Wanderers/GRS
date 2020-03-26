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

function getStaff(xhttp){
    data = JSON.parse(xhttp.responseText); //array of {id: , uname:""}
    form = document.getElementById("c_view_radio");

    var createRbtn = (id,label_val,form)=>
    {
        var radio = document.createElement("input");
        radio.setAttribute("type", "radio");
        radio.setAttribute("name", "staff");
        radio.setAttribute("id", `'${id}'`);
        radio.value = id;
        var label = document.createElement("label");
        label.setAttribute("for", `'${id}'`);
        label.innerHTML = label_val;
        form.appendChild(radio);
        form.appendChild(label);
    }

    if(data.length === 0)
    {
        form.innerHTML = "No staffs";
    }
    else
    {
        while(form.firstChild)
            form.removeChild(form.firstChild);
        
        createRbtn('all','All',form);
        form.appendChild(document.createElement("br"));
        
        for(staff of data){
            createRbtn(staff.id,staff.username,form);
            form.appendChild(document.createElement("br"));
        }
        var radios = document.getElementsByName("staff");
        radios[0].checked = true;
        getCheckedBox();
    }
}

function getCheckedBox(){
    var checkboxes = document.querySelectorAll('input[name="status"]:checked');
    var radio = document.querySelectorAll('input[name="staff"]:checked')[0];
    var staff_id = radio.value;
    var checks = [];

    for(ch of checkboxes)
        checks.push(ch.value);
    
    status = checks.join("_");
    if(status)
    {
        var url = CONTROLLER_LINK + "grievance&staff_id="+staff_id+"&status="+status;
        callback(url, getGrievances);
    }
    else
    {
        $('#customtable').hide(500);
        $('#nogrievance').show(500).text("Select atleast one status type");
        // grievance = document.getElementById("nogrievance");
        // document.getElementById("customtable").style.display = "none";
        // grievance.innerHTML = "Select atleast one status type";
        // grievance.style.display = "block";
    }
}



function getGrievances(xhttp)
{
    var table = document.getElementById("customtable");
    
    var capitalize=(str)=>
    {
        var cap_str = "";
        for(s of str.split(" "))
        {
            if(!isNaN(s)) //if it is a number then no capitalizing
                cap_str += s+" ";
            else
                cap_str += s[0].toUpperCase()+s.slice(1).toLowerCase()+" ";
        }
        return cap_str;
    }

    var createTableHeader = (id, value)=>{
        th = document.createElement("th");
        th.setAttribute("id",id);
        th.innerHTML = capitalize(value);
        return th;
    }
    
    var createTableBody = (value)=>{
        td = document.createElement("td");
        td.innerHTML = value;
        return td;
    }
    var makeThead = (table)=>{    
        var thead = document.createElement("thead");
        thead.setAttribute("class", "thead-dark");
        thead.setAttribute("align", "center");
        tr = document.createElement("tr");
        tr.appendChild(createTableHeader("ticket_id","Ticket ID"));
        tr.appendChild(createTableHeader("title","Title"));
        tr.appendChild(createTableHeader("handler_name","Handler Name"));
        tr.appendChild(createTableHeader("year","Year"));
        tr.appendChild(createTableHeader("status","Status"));
        thead.appendChild(tr);
        table.appendChild(thead);
    };
    
    var makeTbody = (grievance, tbody)=>{
        tr = document.createElement("tr");
        tr.appendChild(createTableBody(grievance.ticket_id));
        tr.appendChild(createTableBody(grievance.title));
        tr.appendChild(createTableBody(capitalize(grievance.handler_name)));
        tr.appendChild(createTableBody(grievance.year));
        tr.appendChild(createTableBody(capitalize(grievance.status)));
        tbody.appendChild(tr);
    };

    data = JSON.parse(xhttp.responseText);
    grievance = document.getElementById("nogrievance");

    if(data.length === 0)
    {
        $('#customtable').hide(500);
        $('#nogrievance').show(500).text("No Grievances");
        // table.style.display = "none";
        // grievance.innerHTML = "No Grievances";
        // grievance.style.display = "block";
    }
    else
    {
        while(table.firstChild){
            table.removeChild(table.firstChild);
        }
        makeThead(table);
        var tbody = document.createElement("tbody");
        tbody.setAttribute("align", "center");
        for(grievance of data)
        {
            makeTbody(grievance, tbody);
        }
        table.appendChild(tbody);
        $('#customtable').show(500);
        $('#nogrievance').hide(500);
        // table.style.display = "block";
        // grievance.style.display = "none";
    }
}