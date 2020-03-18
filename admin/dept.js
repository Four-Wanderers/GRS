var CONTROLLER_LINK = "controller.php?action=";

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

function setup(type)
{
    switch(type)
    {
        case 'add_dept':
            dept_name = document.getElementById('dept_name').value;
            url = CONTROLLER_LINK+'addDept&dept_name='+dept_name;
            callback(url,addDept);
            break;
        case 'assign_hod':
            dept_name = document.getElementById('hod_dept_name').value;
            email = document.getElementById('hod_email').value;
            username = document.getElementById('hod_username').value;
            url = CONTROLLER_LINK+`assignhod&username=${username}&email=${email}&dept_name=${dept_name}`;
            callback(url,assignHOD);
            break;
        case 'load':
            callback(CONTROLLER_LINK+"departments",loadTable);
            break;
    }
}

function assignHOD(xhttp)
{
    span = document.getElementById('assignhod_ack');
    if(xhttp.responseText)
    {
        // btn = document.getElementById("add_dept_btn");  
        span.innerHTML = "assigned successfully";
        span.setAttribute('class','text-success');
        // btn.setAttribute('data-dismiss','modal');
        setup('load');
    }
    else
    {
        span.innerHTML = "failed to assign or couldn't send email";
        span.setAttribute('class','text-danger');
    }
}

function addDept(xhttp)
{
    span = document.getElementById('addDept_ack');
    if(xhttp.responseText)
    {
        span.innerHTML = "created successfully";
        span.setAttribute('class','text-success');
        setup('load');
    }
    else
    {
        span.innerHTML = "failed";
        span.setAttribute('class','text-danger');
    }
}



function removeHOD(xhttp){
    if(xhttp.responseText)
        setup('load');
    else
    {
        console.log('failed');
    }
}
function passDeptname(dept_name)
{
    document.getElementById("hod_dept_name").value = dept_name;
};

function loadTable(xhttp)
{
    var table;
    var makeEle = (type,id)=>{
        e = document.createElement(type);
        e.setAttribute('id',id);
    };

    var makeThead = (table)=>{
                
        tr = document.createElement("tr");
        
        th = makeEle('th','Department');
        th.innerHTML = "Department";
        tr.appendChild(th);
        
        th = makeEle('th','HOD');
        th.innerHTML = "HOD";
        tr.appendChild(th);
        
        th = makeEle('th','Action');
        th.innerHTML = "Action";
        tr.appendChild(th);
        
        table.appendChild(tr);
    };
    
    var makeTbody = (dept, table)=>{
        //dept name, should be a link which directs to the page whick would be displaying dept specific details
        
        tr = document.createElement("tr");
        td = document.createElement("td");
        a = document.createElement("a");
        // a.setAttribute("href",DEPT_DETAILS_LINK+dept.dept_name);
        a.innerHTML = dept.dept_name;
        td.appendChild(a);
        tr.appendChild(td);
        //name of the hod or assign button which is supposed to be a form                   
        td = document.createElement("td");
        if(dept.hod_name == null)
        {
            input = document.createElement("input");
            input.setAttribute("type","submit");
            input.setAttribute("value","Assign");
            input.setAttribute("onclick",`passDeptname('${dept.dept_name}')`);
            input.setAttribute("data-toggle","modal");
            input.setAttribute("data-target","#assignhod");
            input.setAttribute("class","btn btn-primary launchConfirm pull-right ");
            td.appendChild(input);
        }
        else
        {
            td.innerHTML = dept.hod_name;
        }   
        tr.appendChild(td);
        //remove HOD ..a button which has onClick event
        td = document.createElement("td");
        button = document.createElement("button");
        button.setAttribute("type","button");
        button.innerHTML = "RemoveHOD";
        url = CONTROLLER_LINK+"removeHOD&uname="+dept.hod_name;
        button.setAttribute("onClick","callback('"+url+"',removeHOD)");
        td.appendChild(button);

        tr.appendChild(td);
        table.appendChild(tr);
    };

    data = JSON.parse(xhttp.responseText); //array of {dept_id: -, dept_name:"",hod_name:""}
    table = document.getElementById("depTable");
    
    while(table.firstChild)
        table.removeChild(table.firstChild);
    
    if(data.length === 0)
    {
        document.getElementById("addcustom").innerHTML = "No Departments";
    }
    else
    {
        document.getElementById("addcustom").innerHTML = "";
        
        makeThead(table);
        
        for(dept of data)
        {
            makeTbody(dept, table);
        }
    }
    
}