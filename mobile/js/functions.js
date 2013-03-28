// function for the bishops pages
$("#bb").live('click', function()
  {
  $.ajax({
//  url:'http://cmesonline.org/cmeREST/api/bishops/' + $(this).attr("bid"),
url:'http://cmesonline.org/cmeREST/api/bishops/43',
  //url:'http://192.168.1.171/cmeREST/api/bishops/' + $(this).attr("bid"),
  data:'',
  dataType:'json',
  success: function(data)
    {
alert ('success:' + $(this).attr("bid"));
     var d = data;
     $('h1#bhead').empty();
     $('h1#bhead').append(d['bishop_name']);
     $('#bishopdata').empty();
     $('#bishopdata').append('<p><img src="' + d['thumbnail'] + '" class="thumb" /><h4>The ' + d['dstrct_title'] + ' District</h4><h2>Bishop<br/>' + d['bishop_name'] + '</h2><p>' + d['vitae'] + '</p></p>');
    },
    error: function()
    {
alert ('fail:' + $(this).attr("bid"));

    //alert('failed');
    }
  })
});

// function for the church locator
$("#lct").live('vclick', function(){
  $.ajax({
  //url:'http://localhost/cmeREST/api/churches',
  //url:'http://192.168.1.171/cmeREST/api/bishops/' + $(this).attr("bid"),
  url:'locator_j.php',
  data:'',
  dataType:'json',
  success: function(rows)
    {
      $('ul#locatelist').empty();
      for (var i in rows)
        {
          var row = rows[i];          
          var church = row['church'];
          var address = row['address'];
          var city = row['city'];
          var state = row['state'];
          var zip = row['zip'];
          var district = row['e_dist'];
          var number = row['number'];
          $('ul#locatelist').append('<li><a href="#map" class="lmap" onclick="javascript:findAddressViaGoogle(\'' + address + '\',\'' + city + '\',\'' + state + '\',\'' + number + '\',\'' + church + '\');" rel="' + address + ', ' + city + ' ' + state + '" title="' + row[0] + '" number="' + number + '">' + church + '<br/><span>' + city + ', ' + state + ' | ' + district + ' district</span></a></li>');
	      $('ul#locatelist').listview('refresh');
        }
    },
    error: function()
    {
    alert('failed');
    }
  })
});

$("#levents").live('vclick', function(){
  $.ajax({
  url:'events_m.php',
  data:'',
  dataType:'',
  success: function(data)
  {
//  alert('fine');
  	$('ul#eventsdiv').empty();
  	$('ul#eventsdiv').append(data);
  	$('ul#eventsdiv').listview('refresh');

  },
  error: function()
  {
  alert('failed');
  }
  })
  
  });


$('.lmap').click(function(){
	$("#map_canvas").empty();
});

$("#bibsubmit2").live('vclick',function(){
  $.ajax({
    url:'bible_search_m.php',
	  	data:{
	  		book: $("#book").val(),
	  		chapter: $("#chapter").val(),
	  		startverse: $("#startverse").val(),
	  		endverse: $("#endverse").val()
	  	},
  		dataType:'',
  		success: function(data)
			{
			$("#bibresult").empty();
		//	alert('far data');
			$("#bibresult").append(data);
			},
		error: function()
		{
		alert('search failed');
		}
	})	
	
});

function showAddress(address) {
     //   map = new GMap2(document.getElementById("map_canvas"));
    //    var geocoder = new GClientGeocoder();
   // alert(address);
        var geocoder = new google.maps.Geocoder();
      //  newPointClicked(results[0].geometry.location);
        geocoder.geocode( { 'address': address, 'region': 'us' }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        var myLatlng = new google.maps.LatLng(results[0].geometry.location);
        alert (myLatlng);
        var myOptions = {
          zoom: 16,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    var map = new google.maps.Map($("#map_canvas").get(0), myOptions);
//        google.maps.event.trigger(map, 'resize'); 
//    map.setZoom( map.getZoom() );   
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title:"Hello World!"
   });

        }
        });
  }
  
  
function findAddressViaGoogle(a,city,state,b,c) {
    var number = b;
    var church = c;
    var comma = ", ";
    var br = "<br/>";
    var ss = "<strong>";
    var se = "</strong>";
    var info = ss + c + se + br + a + br + city + comma + state + br + ss + number + se;
    $('#infox').fadeOut('slow');
    $('#infox').html(info);
    var address = a;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address, 'region': 'us' }, function(results, status) {
    	if (status == google.maps.GeocoderStatus.OK) {
    		m = results[0].geometry.location;
    		var myLatlng = m;
    		var myOptions = {
      			zoom: 15,
      			center: myLatlng,
      			mapTypeId: google.maps.MapTypeId.ROADMAP
    		}
         	var map = new google.maps.Map($("#map_canvas").get(0), myOptions);  
        	} else {
            alert("Unable to find address: " + status);
        }        
		var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title:""
		});
	});
}


  
