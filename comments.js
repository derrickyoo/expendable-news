var $ = function (e) {
	return document.querySelector(e);
};

var	name = $('#name'), 	
	email = $('#email'),
	comment = $('#comment'),
    form = $('#comment-fields'),
	output = $('#commentjs'),
	date = $('#date'),
	sendcomment = new XMLHttpRequest(),
	pattern = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	
	// function htmlspecialchars() source:
	// http://www.toao.net/32-my-htmlspecialchars-function-for-javascript
	function htmlspecialchars(str) {
		if (typeof(str) == "string") {
	  		str = str.replace(/&/g, "&amp;"); /* must do &amp; first */
	  		str = str.replace(/"/g, "&quot;");
	  		str = str.replace(/'/g, "&#039;");
	  		str = str.replace(/</g, "&lt;");
	  		str = str.replace(/>/g, "&gt;");
	  	}
	 	return str;
	};
	
	
	form.onsubmit = function () {
    	if (pattern.test(email.value) && comment.value != '') {
        	var formdata = new FormData(this);
           	sendcomment.open('POST', 'processcommentjs.php', true);
           	sendcomment.send(formdata);
			
			var div = document.createElement('div');
			div.id = 'open-comments';
			div.innerHTML = "<b>" + name.value + "</b>" + " says: " + "<br> " + "<br>" + htmlspecialchars(comment.value) + "<br>" + "<br>" + "<img src='images/calendar_2_icon&amp;16.png' width='10' height='10' alt='Calendar 2 Icon&amp;16'> " + date.value + " <img src='images/delete_icon&amp;16.png' width='10' height='10' alt='Delete Icon&amp;16'>" + "<a href='deletecomment.php?com=$comment_id'>" + " Delete" + "</a>";			
			output.appendChild(div);

		   	email.value = '';
		   	comment.value = '';
		
		}
		else {
			alert("Complete all form fields with a VALID Email address.")
		}

       return false;
   };


