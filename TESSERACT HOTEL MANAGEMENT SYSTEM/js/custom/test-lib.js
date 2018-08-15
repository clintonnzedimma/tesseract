function testSomething() {
	var type;
	$('select[name=payment_type]').on('change', function (){
		type=$(this).children(':selected').text();

		$('#shit').text(type);
//getting selected form value
	});
}


function testSomething() {
	var fullName;
	$("input[name=full_name]").on('keyup', function(){
		fullName=$("input[name=full_name]").val();
		console.log(fullName);

		$('#shit').text(fullName);


	});
}