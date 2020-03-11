var CONTROLLER_LINK = "controller.php?action=";
var DEPT_DETAILS_LINK = "viewDeptDetails.php?dept_name=";
var ASSIGN_HOD_LINK = "assignHOD.php";

/*
actions for
load table = "departments"
removeHOD = "removeHOD"
*/

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
};

function removeHOD(xhttp){
    if(xhttp.responseText === "true")
        callback(CONTROLLER_LINK+"departments",loadTable);
    else
    {
        //failed to send an email to HOD
    }
}

function loadTable(xhttp)
{
    var table;
    var makeThead = (table)=>{
                
        tr = document.createElement("tr");
        th = document.createElement("th");
        th.setAttribute("id","Department");
        th.innerHTML = "Department";
        tr.appendChild(th);
    
        th = document.createElement("th");
        th.setAttribute("id","HOD");
        th.innerHTML = "HOD";
        tr.appendChild(th);
        
        th = document.createElement("th");
        th.setAttribute("id","Action");
        th.innerHTML = "Action";
        tr.appendChild(th);
        
        table.appendChild(tr);
        console.log(table.innerHTML);
    };
    
    var makeTbody = (dept, table)=>{
        //dept name, should be a link which directs to the page whick would be displaying dept specific details
        console.log(dept);
        
        tr = document.createElement("tr");
        td = document.createElement("td");
        a = document.createElement("a");
        a.setAttribute("href",DEPT_DETAILS_LINK+dept.dept_name);
        a.innerHTML = dept.dept_name;
        td.appendChild(a);
        tr.appendChild(td);
        //name of the hod or assign button which is supposed to be a form                   
        td = document.createElement("td");
        if(dept.hod_name == null)
        {
            form = document.createElement("form");
            form.setAttribute("action",ASSIGN_HOD_LINK);
            
            input = document.createElement("input");
            input.setAttribute("type","hidden");
            input.setAttribute("value",dept.dept_name);
            form.appendChild(input);
            
            input = document.createElement("input");
            input.setAttribute("type","submit");
            input.setAttribute("value","Assign");
            form.appendChild(input);
            td.appendChild(form);
        }
        else
        {
            //name of the hod
            td.innerHTML = dept.hod_name;
        }   
        tr.appendChild(td);
        //remove HOD ..a button which has onClick event
        td = document.createElement("td");
        button = document.createElement("button");
        button.setAttribute("type","button");
        button.innerHTML = "RemoveHOD";
        url = CONTROLLER_LINK+"removeHOD&uname="+dept.hod_name;
        button.setAttribute("onClick","controller("+url+",removeHOD)");
        td.appendChild(button);

        tr.appendChild(td);
        table.appendChild(tr);
    };

    data = JSON.parse(xhttp.responseText); //array of {dept_id: -, dept_name:"",hod_name:""}
    departments = document.getElementById("departments");
    departments.removeChild(departments.firstChild); //delete previous table
    if(data.length === 0)
    {
        departments.innerHTML = "No departments ";
    }
    else
    {
        table = document.createElement("table");
        table.setAttribute("id","table");
        makeThead(table);
        
        for(dept of data)
        {
            makeTbody(dept, table);
        }
        departments.appendChild(table);
    }

}