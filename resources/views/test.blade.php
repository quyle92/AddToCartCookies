<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Document</title>
</head>
<body>
	<button type="button" class="btn btn-info">button1</button>
	<button type="button" class="btn btn-danger">button2</button>
</body>

<script type="text/javascript">
	// $.ajaxSetup({
 //      headers: {
 //          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 //      }
 //  	});
	$('.btn-info').click( function(){
		$.ajax({
			type: 'POST',
			url:'/authentication',
			success: function(data) {
               console.log(e)
            },
            data:{guest: 'guest'},
            error: function(e) {
            	console.log(e)
            }
		})
	});

	$('.btn-danger').click( function(){
		$.ajax({
			type: 'POST',
			url:'/checkAuthentication',
			data:{message: 'message'},
			success: function(data) {
               console.log(e)
            },
            error: function(e) {
            	console.log(e)
            }
		})
	});
</script>
</html>