function showinfo(x)
{
	switch(x)
	{
	    case "Reset":
	        //clear all textbox data 
	        var textbox;
	        var inputs = document.getElementsByTagName("input");
	        for (var i = 0; i < inputs.length; i++) {
	            if (inputs[i].name.indexOf('text_') == 0) {
	                textbox = document.getElementById(inputs[i].name);
	                textbox.value = "";
	            }
	        }
	        //clear all span
	        var spans = document.getElementsByTagName('span')
	        for (var j = 0; j < spans.length; j++) {
	            document.getElementsByTagName('span')[j].textContent = "";
	        }
	        break;
	    case "Submit":
	        //check empty slot
	        var flag = 0;
	        var idx;
	        var inputs = document.getElementsByTagName("input");
	        for (var i = 0; i < inputs.length; i++) {
	            if (inputs[i].name.indexOf('text_') == 0) {
	                idx = document.getElementById(inputs[i].name);
	                if (idx.value == "") {
	                    flag = 1;
	                }
	            }
	        }
            //check error message
	        var spans = document.getElementsByTagName('span')
	        for (var j = 0; j < spans.length - 1; j++) {
	            if (document.getElementsByTagName('span')[j].textContent != "") {
	                flag = 2;
	            }
	        }
	        var msg = "";
	        switch (flag) {
	            case 0:
	                msg = "Your form had been submited.";
                    //clear all textbox data 
	                var textbox;
	                var inputs = document.getElementsByTagName("input");
	                for (var i = 0; i < inputs.length; i++) {
	                    if (inputs[i].name.indexOf('text_') == 0) {
	                        textbox = document.getElementById(inputs[i].name);
	                        textbox.value = "";
	                    }
	                }
	                break;
	            case 1:
	                msg = "Please complete the form before submitting.";
	                break;
	            case 2:
	                msg = "Please fill in the form with complete information.";
	                break;
	        }
	        document.getElementById("msgback_Submit").textContent = msg;
			if(flag==0) {
				document.forms["register_form"].submit();
			}
	        break;
	    default:
            opensend(x);
	        break;       
	}
}

//Ajax
function opensend(x) {

    if (window.XMLHttpRequest) {   //code for IE7+,firefox,chrome,opera,safari
        xmlhttp = new XMLHttpRequest();
    }
    else {   //code for IE5,6
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("msgback_"+x).innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "validate.php?textbox=" + x.substr(1) + "__" + document.getElementById('text_' + x).value, true);
    xmlhttp.send(null);

}