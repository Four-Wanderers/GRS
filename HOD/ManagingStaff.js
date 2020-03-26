var CONTROLLER_LINK = "controller.php?action=";

var callback = (url,func)=>
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200)
        {
            func(this);
        }
    };
    xhttp.open('GET',url,true);
    xhttp.send();
};
function passStaffDetails()
{
    var email=document.getElementsByName('email')[0].value;
    var year=document.getElementsByName('year')[0].value;
    url=CONTROLLER_LINK+`addStaff&email=${email}&year=${year}`;
    callback(url,addStaff);
}
function addStaff(xhttp)
{
    if(xhttp.responseText === "true")
    callback(CONTROLLER_LINK+"getStaffs",loadTable);
    else
    {
        console.log('failed');
    }
}
function rStaff(xhttp)
{
    if(xhttp.responseText)
        callback(CONTROLLER_LINK+"getStaffs",loadTable);
    else
    {
        console.log('failed');
    }
}
function loadTable(xhttp)
{
    
        var table = document.getElementById("manage-staff");
        var makeTbody = (staff, table)=>{
        
        // console.log(staff);
        tr = document.createElement("tr");
        
        tr.appendChild(createTbody(staff.id)); 
        tr.appendChild(createTbody(staff.username));
        tr.appendChild(createTbody(staff.year));
        td = document.createElement("td");
       
        button = document.createElement("button");
        button.setAttribute("type","button");
        button.setAttribute("class","btn btn-primary btn-nm");
        button.style="width:150px";
        button.innerHTML = "RemoveStaff";
        //console.log(staff.id);
        url = CONTROLLER_LINK+"removeStaff&staffId="+staff.id;
        button.setAttribute("onClick","callback('"+url+"',rStaff)");
        
        td.appendChild(button);

        tr.appendChild(td);
        table.appendChild(tr);
    }
    
    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText); 
    // console.log(data);
    
    

    if(data.length === 0)
    {
        msg=document.getElementById("msg");
        msg.innerHTML = "No staffs assigned ";
        table.style.display="none";
    }
    else
    {
        table.style.display="";
    
        while(table.childElementCount > 1){
            table.removeChild(table.lastChild);
        }
        
        for(staff of data)
        {
            makeTbody(staff, table);
        }
       
    }

};

function createTbody(val)
{
    td = document.createElement("td");
    td.innerHTML=val;
    return td;
    
}