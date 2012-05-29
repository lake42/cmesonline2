// new window function
	function loadwindow(url){
	window.open(url,"","width=500,height=510,resizable=yes, status=1");
	}	


		if(document.images) {
		var bishopimage = new Array;
		bishopimage[0] = new Image;
		bishopimage[0].src ="i/graves.jpg";
		bishopimage[1] = new Image;
		bishopimage[1].src ="i/brown.jpg";
		bishopimage[2] = new Image;
		bishopimage[2].src ="i/stewart2.jpg";
		bishopimage[3] = new Image;
		bishopimage[3].src ="i/tbrown2.jpg";
		bishopimage[4] = new Image;
		bishopimage[4].src ="i/reddick2.jpg";
		bishopimage[5] = new Image;
		bishopimage[5].src ="i/lakey.jpg";
		bishopimage[6] = new Image;
		bishopimage[6].src ="i/hoyt2.jpg";
		bishopimage[7] = new Image;
		bishopimage[7].src ="i/cunningham4.jpg";
		bishopimage[8] = new Image;
		bishopimage[8].src ="i/williamson3.jpg";
		bishopimage[9] = new Image;
		bishopimage[9].src ="i/carter2.jpg";
		bishopimage[10] = new Image;
		bishopimage[10].src ="i/collegeofbishops2006.jpg";
        }

		var bishoptext = new Array;
		bishoptext[0] = 'Bishop Graves';
		bishoptext[1] = 'Bishop E. L. Brown';
		bishoptext[2] = 'Bishop Stewart';
		bishoptext[3] = 'Bishop T. Brown';
		bishoptext[4] = 'Bishop Reddick';
		bishoptext[5] = 'Bishop Lakey';
		bishoptext[6] = 'Bishop Hoyt';
		bishoptext[7] = 'Bishop Cunningham';
		bishoptext[8] = 'Bishop Williamson';
		bishoptext[9] = 'Bishop Carter';
		bishoptext[10] = 'CME';

		var bishoptext2 = new Array;
		bishoptext2[0] = 'First District';
		bishoptext2[1] = 'Second District';
		bishoptext2[2] = 'Third District';
		bishoptext2[3] = 'Fourth District';
		bishoptext2[4] = 'Fifth District';
		bishoptext2[5] = 'Sixth District';
		bishoptext2[6] = 'Seventh District';
		bishoptext2[7] = 'Eighth District';
		bishoptext2[8] = 'Ninth District';
		bishoptext2[9] = 'Tenth District';
		bishoptext2[10] = 'College of Bishops';
	
	function switcher(district){
		seed = district - 1;
		document['img1'].src =eval("bishopimage[" + seed + "].src");
		cutline = eval("bishoptext[" + seed + "]");
		cutline2 = eval("bishoptext2[" + seed + "]"); 
		textspace=document.getElementById("textSpot");
		textspace.firstChild.nodeValue=cutline;
		textspace2=document.getElementById("textSpot2");
		textspace2.firstChild.nodeValue=cutline2;
	}
	
	function defaultimage(){
		document['img1'].src ='i/cmelogo_42002c.gif';
		cutline = 'CMEs Online';
		cutline2 = '';
		textspace=document.getElementById("textSpot");
		textspace.firstChild.nodeValue=cutline;
		textspace2=document.getElementById("textSpot2");
		textspace2.firstChild.nodeValue=cutline2;
		
	}
