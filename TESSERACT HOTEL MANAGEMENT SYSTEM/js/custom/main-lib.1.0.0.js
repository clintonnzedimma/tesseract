/**! tesseract Hotel Management System v1.0.0 | 
*Author: Clinton Nzedimma
*Description: This is the central library of Javascript functions.
(c) Novacom Webs Nigeria 2018
 */

function regulateFormInputDate(before, after) {
	// the variables 'before' and 'after' are selectors
	var dayBefore, monthBefore, yearBefore;
	var dayAfter, monthAfter, yearAfter;
	var minimumDateAttribute;
	$("#checkIn_date, #checkOut_date").on('change', function(){
		var dateBEFORE= $(before).val().split("-"); // exploding string
		//default server format is yyyy/mm/dd, so thats why the array is in the below format
		dayBefore=dateBEFORE[2];
		monthBefore=dateBEFORE[1];
		yearBefore=dateBEFORE[0];

		var dateAFTER= $(after).val().split("-"); 
		dayAfter=dateAFTER[2];
		monthAfter=dateAFTER[1];
		yearAfter=dateAFTER[0];


		if ($(before).val().length==0) {
			//if the string length of 'before' is 0
			minimumDateAttributeForAfter=$("[PHPtoJS=minimumDateText]").text(); //collect PHP data from page
			$(after).attr("min",  minimumDateAttributeForAfter); //set minimum date as collected PHP data
		}else {
			//if the string length of 'before' is NOT 0 and date is set
			minimumDateAttributeForAfter=yearBefore+"-"+monthBefore+"-"+dayBefore; // minimum date attribute for 'after' gotten from 'before' values
			$(after).attr('min',  minimumDateAttributeForAfter); //setting min attribite of 'after' field
}

		
	});
}


function daysBetween(date1, date2) {
	var oneDay=24*60*60*1000; // hours * minutes * seconds * milliseconds
	var dateArr1=date1.split("-");
	var dateArr2=date2.split("-");

	dateObject1= new Date(dateArr1[0],dateArr1[1],dateArr1[2]);
	dateObject2= new Date(dateArr2[0],dateArr2[1],dateArr2[2]);

	var diffDays=Math.round( Math.abs ( (dateObject1.getTime()- dateObject2.getTime())/ (oneDay) ) );

	return diffDays;
}


 function validateEmail(email) {
  var emailReg = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
  return emailReg.test(email);
}



function checkDateRange (input_date, start_date, end_date) {

	var input_date_arr = input_date.split("-");
	var start_date_arr = start_date.split("-");
	var end_date_arr = end_date.split("-");
	/* Date objects below follow yyyy-mm-dd pattern*/
	input_obj = new  Date(input_date_arr[0], input_date_arr[1], input_date_arr[2]); 
	start_obj = new Date(start_date_arr[0], start_date_arr[1], start_date_arr[2]);
	end_obj = new Date(end_date_arr[0], end_date_arr[1], end_date_arr[2]);

	if (input_obj>=start_obj && input_obj<=end_obj) {
		return true;
	}
}


function checkForWhiteSpaceOf(string) {
	stringReg = new RegExp(/[\s]/);
	return stringReg.test(string);
}

function encodeHTML(str) {
	var buf = [];
	for (var i= str.length-1;  i>=0;  i-- ) {
		buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));	
	}
	return buf.join('');
}

