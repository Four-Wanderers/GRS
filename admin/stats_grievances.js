var CONTROLLER_LINK = "controller.php?action=";

function renderStats(url)
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function()
    {
        if(this.readyState == 4 && this.status == 200) 
        {
            
            console.log(xhttp.responseText);
            data = JSON.parse(xhttp.responseText); 
            console.log(data);
        //   statistics= document.getElementById("stat");
        //   statistics.removeFirstChild;
           var tot=0; 
           var i=1;
           for(s of data)
           {
            tot+=parseInt(s.count);
           }

           console.log(tot);
           for(s of data)
           {
            p=document.getElementById("count"+i);
            console.log(s.count);
            p.innerHTML=s.count;
            pb=document.getElementById("stats"+i);
            var percentage=(s.count/tot)*100;
            pb.style="width:"+percentage+"%";
            console.log(percentage);
            pb.innerHTML=percentage+"%";
           i=i+1; 
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
function createThead(id,value)
{
    th = document.createElement("th");
    th.setAttribute("id",value);
    th.innerHTML = value;
    return th;
}
function createTbody(val)
{
    td = document.createElement("td");
    td.innerHTML=val;
    return td;
    
}
function loadTable(xhttp)
{
    var table;
    // var makeThead = (table)=>{
                
    //     tr = document.createElement("tr");
       
    //     tr.appendChild(createThead("id","ticket_id"));
    //     tr.appendChild(createThead("id","title"));
    //     tr.appendChild(createThead("id","dept_name"));
    //     tr.appendChild(createThead("id","status"));
    //     tr.appendChild(createThead("id","year"));
    //     table.appendChild(tr);

    //     //console.log(table.innerHTML);
    // };

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
    console.log(xhttp.responseText);
    data = JSON.parse(xhttp.responseText); 
    console.log(data);
    table = document.getElementById("AdminGrievances");
    // AdminGrievances = document.getElementById("AdminGrievances");
    //AdminGrievances.removeFirstChild; 
    table.removeFirstChild;
    if(data.length === 0)
    {
        table.innerHTML = "No AdminGrievances ";
    }
    else
    {
        
        //table.border=1;
        //makeThead(table);
        
        for(grievances of data)
        {
            makeTbody(grievances, table);
        }
        // AdminGrievances.appendChild(table);
    }
}