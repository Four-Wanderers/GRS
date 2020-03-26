function callback()
{    
    getAllGrievances("controller.php?action=allgrievaces",loadtable);
}

function getAllGrievances(url,func)
{
    var xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200) //that is we received a positive response
        {
            func(this);
        }
    };
    xhttp.open('GET',url);
    xhttp.send();
}

function loadtable(xhttp)
{
    var table;
    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText); 
    console.log(data);
    table = document.getElementById("grievancesTable");

    while (table.hasChildNodes()) {
        table.removeChild(table.firstChild);
      }
    if(data.length === 0)
    {
        var hiddendiv=document.getElementById("nogriev");
        hiddendiv.style.visibility="visible";
        hiddendiv.innerHTML = "No Grievances Available! ";
    }
    else
    {
    
        tr = document.createElement("tr");
        tr.appendChild(createThead("Ticket Id"));
        tr.appendChild(createThead("Grievance"));
        tr.appendChild(createThead("Department"));
        tr.appendChild(createThead("Username"));
        tr.appendChild(createThead("Year"));
        tr.appendChild(createThead("Status"));
        table.appendChild(tr);

        for(grievance of data)
        {
            makeTbody(grievance, table);
        }
    }
   
}
function createTbody(val)
{
    td = document.createElement("td");
    td.innerHTML=val;
    return td;
    
}
function createThead(value)
{
    th = document.createElement("th");
    th.innerHTML = value;
    return th;
}

function makeTbody(grievance,table)
{
    tr = document.createElement("tr");
        
    tr.appendChild(createTbody(grievance.ticket_id)); 
    tr.appendChild(createTbody(grievance.title)); 
    tr.appendChild(createTbody(grievance.dept_name)); 
    tr.appendChild(createTbody(grievance.username)); 
    tr.appendChild(createTbody(grievance.year)); 
    tr.appendChild(createTbody(grievance.status)); 
    $(document).ready(function(){
        $("tr:nth-child(even)").css("background-color", "#ebebe0");
      });
    table.appendChild(tr);
}



