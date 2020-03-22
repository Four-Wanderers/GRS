var CONTROLLER_LINK = "controller.php?action=";

function renderStats(url)
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function()
    {
        if(this.readyState == 4 && this.status == 200) 
        {
            
            data = JSON.parse(xhttp.responseText); 
           var tot=0; 
           for(s of data)
           {
            tot+=parseInt(s.count);
           }

           for(s of data)
           {
            var status = s.status.toLowerCase();
            p=document.getElementById(status);
            p.innerHTML=s.count;
            pb=document.getElementById(status+"_stats");
            var percentage=((s.count/tot)*100).toFixed(2);
            pb.style="width:"+percentage+"%";
            pb.innerHTML=percentage+"%";
           }
        }
    };
    xhttp.open('GET',url,true);
    xhttp.send();
}
function callback()
{
    renderStats(CONTROLLER_LINK+"getStatis");
    getGrievances(CONTROLLER_LINK+"myGrievances",loadTable);
    
}

function getGrievances(url,func)
{
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
// function createThead(id,value)
// {
//     th = document.createElement("th");
//     th.setAttribute("id",value);
//     th.innerHTML = value;
//     return th;
// }
function createTbody(val)
{
    td = document.createElement("td");
    td.innerHTML=val;
    return td;
    
}
function loadTable(xhttp)
{
    var table;

     var makeTbody=(greivances,table)=>
     {
        tr = document.createElement("tr");
        
        tr.appendChild(createTbody(greivances.ticket_id)); 
        tr.appendChild(createTbody(greivances.title)); 
        tr.appendChild(createTbody(greivances.dept_name)); 
        tr.appendChild(createTbody(greivances.status)); 
        tr.appendChild(createTbody(greivances.year)); 

        table.appendChild(tr);
     };
    
    data = JSON.parse(xhttp.responseText); 
    
    table = document.getElementById("AdminGrievances");
    
    if(data.length === 0)
    {
        table.innerHTML = "No AdminGrievances ";
    }
    else
    {

        for(grievances of data)
        {
            makeTbody(grievances, table);
        }
    }
}