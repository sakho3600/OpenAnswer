// JavaScript Document
var xmlhttp = getNewHTTPObject();

function showCall(){
	//xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null){
	  alert ("Your browser does not support XMLHTTP!");
	  return;
	}
	var url="test.php";
	url=url+"?sid="+Math.random();
	xmlhttp.onreadystatechange=stateChanged;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}

function stateChanged(){
	
	if (xmlhttp.readyState==4){
		document.getElementById("cinfo").innerHTML=xmlhttp.responseText;
	}
	if (xmlhttp.responseText.length > 0){
		//alert("String is not empty");
		clearInterval(checkCall);
	}
}


function getNewHTTPObject(){

        var xmlHttp;

        /** Special IE only code ... */
        /*@cc_on
          @if (@_jscript_version >= 5)
              try
              {
                  xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
              }
              catch (e)
              {
                  try
                  {
                      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                  }
                  catch (E)
                  {
                      xmlHttp = false;
                  }
             }
          @else
             xmlHttp = false;
        @end @*/

        /** Every other browser on the planet */
        if (!xmlHttp && typeof XMLHttpRequest != 'undefined')
        {
            try
            {
                xmlHttp = new XMLHttpRequest();
            }
            catch (e)
            {
                xmlHttp = false;
            }
        }

        return xmlHttp;
}