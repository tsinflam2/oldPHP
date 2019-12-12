
 function check_reg_blank() {
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
	        return msg;
			if(flag==0) {
				return false;
			}
}
