var CONTROLLER_LINK = "controller.php";

function sendCsv(files)
{
    form_data = new FormData();
    csv = files[0];
    form_data.append('csv_file',csv,"studentcsv.csv");
    form_data.append('action','upload_std_csv');

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200) //that is we received a positive response
        {
            info = JSON.parse(this.responseText);
            console.log(info);
        }
    };
    xhttp.open('POST',CONTROLLER_LINK,true);
    xhttp.send(form_data);
}