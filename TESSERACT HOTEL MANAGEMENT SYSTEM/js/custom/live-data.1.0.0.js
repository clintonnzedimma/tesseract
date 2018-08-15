/*! tesseact Hotel Management System v1.0.0 | 
*Author: Clinton Nzedimma
*Description: This file helps live data as html from PHP databases and pages 
(c) Novacom Webs Nigeria 2018
 */

$("#vacant_room_search_RESERVATION").on('change',function() {
	var search_query= $(this).val();

	$.post('widgets/live.data.php', {Room_type_and_price: search_query }, function (data) {
		$('#liveRoomType_AND_Price').html(data);

	});
});


$("#vacant_room_search_GUEST_REGISTERATION").on('change',function() {
	var search_query= $(this).val();

	$.post('widgets/live.data.php', {Room_type_and_price: search_query }, function (data) {
		$('#liveRoomType_AND_Price').html(data);

	});
});

$("#chart_GET").on('change',function() {
	var search_query= $(this).val();

	$.post('widgets/live.data.php', {Room_type_and_price: search_query }, function (data) {
		$('#liveRoomType_AND_Price').html(data);

	});
});




