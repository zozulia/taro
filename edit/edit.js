var wayToVipDir='';
var edition= 0;
var cur_img_fn='';
/* ------------- Gallery Photo Upload ------------ */
var galleryFiles;
var galleryFotoChangedId;

// Grab the files and set them to our variable
function prepareGalleryUpload(event)
{
	galleryFiles = event.target.files;
	uploadGalleryFiles(event);
}

// Catch the form submit and upload the files
function uploadGalleryFiles(event)
{
	event.stopPropagation(); // Stop stuff happening
	event.preventDefault(); // Totally stop stuff happening
    // Create a formdata object and add the files
    var data = new FormData();
    $.each(galleryFiles, function(key, value)
    {
        data.append(key, value);
    });
	data.append('fn', $('#fnimg').val());
	
    $.ajax({
        url: wayToVipDir + '/edit/getimg.php?files',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'text',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
			var randomSuffix = '?' + Math.random();
			$('img#gallery-photo').attr('src', data + randomSuffix);
			var selector = 'img[src="' + cur_img_fn + '"]'
			console.log( selector + ' = ' + data + randomSuffix );
			$(selector).attr('src', data + randomSuffix);
			cur_img_fn = data + randomSuffix;
			$('#imgform').css('display', 'none');
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

var pressed = false;

function prepare2edit(){
	if (pressed){ console.log('allredy prepared'); return;}
	pressed=true;
	console.log('prepare');
	$.get('/edit/edition.txt', function(data){
		edition=data;
	});
	var link_span_pressed = false;
	$('span.edited').css('border','solid 1px red').click(function(event){
		event.preventDefault();
		link_span_pressed = true;
		var txt = prompt('Введіть нове значення поля замість нинішнього', $(this).html());
		if (txt){
			if (txt.length < 1)
				alert('Не можна залишати запис пустим. Спрробуйте ще раз.')	;
			else
				$(this).load('/edit/update.php',{val:txt,block_name:$(this).data('block'),block_order:$(this).data('order'),field_name:$(this).data('field'), edition:1});
		}
	})

	$('.wide').css('z-index','-100');
	$('img.edited').css('border','solid 1px red').css('border-left','0px').css('border-right','0px').click(function(event){
		$('#imgform').css('display', 'block');
		$('#gallery-photo').attr('src','/edit/fotoapparat.gif');
		if ($(this).hasClass('gallery-img')) $('#image-size').html('Ширина 633, высота: 627 пикселей');
		else if ($(this).hasClass('hotel')) $('#image-size').html('Ширина 265, высота: 267 пикселей')
		cur_img_fn = $(this).attr('src');
		var arr_img_fn = cur_img_fn.split('/');
		var lastindex = -1 + arr_img_fn.length;
		var fn_length = arr_img_fn[lastindex].indexOf('?');
		$('#fnimg').val((fn_length > 0)?arr_img_fn[lastindex].substr(0,fn_length):arr_img_fn[lastindex]);
		$('#gallery-photo-file').on('change', prepareGalleryUpload);
	})
	
	$('a.edited').css('border','solid 3px blue').click(function(event){
		event.preventDefault();
		if (link_span_pressed){
			link_span_pressed = false;
			return;
		}
		var txt = prompt('Введіть нове значення поля замість нинішнього', $(this).attr('href'));
		if (txt){
			if (txt.length < 1)
				alert('Не можна залишати лінк пустим. Спрробуйте ще раз.')	;
			else{
				$.post('/edit/update.php',{val:txt,block_name:$(this).data('block'),block_order:$(this).data('order'),field_name:$(this).data('field'), edition:edition},
				function(data){
					$(this).attr('href',data);
				});
			}
		}
	});

	return false;
}
