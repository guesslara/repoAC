<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../recursos/calendario/css/datepicker/datepicker.css" />
<script type="text/javascript" src="../recursos/calendario/js/datepicker/datepicker.js"></script>
<script type="text/javascript">

// <![CDATA[     
	
	// A quick test of the setGlobalVars method - remember, the "lang" attribute will NOT work when passed to this method
	datePickerController.setGlobalVars({"split":["-dd","-mm"]});
	
	/* 
			The following function dynamically calculates Easter Monday's date.
			It is used as the "redraw" callback function for the second last calendar on the page
			and returns an empty object.
	   
			It dynamically calculates Easter Monday for the year in question and uses
			the "adddisabledDates" method of the datePickercontroller Object to
			disable the date in question.
	   
			NOTE: This function is not needed, it is only present to show you how you
			might use this callback function to disable dates dynamically!   
	*/            
	function disableEasterMonday(argObj) { 
			// Dynamically calculate Easter Monday - I've forgotten where this code 
			// was originally found and I don't even know if it returns a valid
			// result so don't use it in a prod environment...
			var y = argObj.yyyy,
				a=y%4,
				b=y%7,
				c=y%19,
				d=(19*c+15)%30,
				e=(2*a+4*b-d+34)%7,
				m=Math.floor((d+e+114)/31),
				g=(d+e+114)%31+1,            
				yyyymmdd = y + "0" + m + String(g < 10 ? "0" + g : g);         
			
			datePickerController.addDisabledDates(argObj.id, yyyymmdd); 
			
			// The redraw callback expects an Object as a return value
			// so we just give it an empty Object... 
			return {};
	};
	
	/* 
	
			The following functions updates a span with an "English-ised" version of the
			currently selected date for the last datePicker on the page. 
	   
			NOTE: These functions are not needed, they are only present to show you how you
			might use callback functions to use the selected date in other ways!
	   
	*/
	function createSpanElement(argObj) {
			// Make sure the span doesn't exist already
			if(document.getElementById("EnglishDate-" + argObj.id)) return;
	
			// create the span node dynamically...
			var spn = document.createElement('span');
				p   = document.getElementById(argObj.id).parentNode;
				
			spn.id = "EnglishDate-" + argObj.id;
			p.parentNode.appendChild(spn);
			
			// Remove the bottom margin on the input's wrapper paragraph
			p.style.marginBottom = "0";
			
			// Add a whitespace character to the span
			spn.appendChild(document.createTextNode(String.fromCharCode(160)));
	};
	
	function showEnglishDate(argObj) {
			// Grab the span & get a more English-ised version of the selected date
			var spn = document.getElementById("EnglishDate-" + argObj.id),
				formattedDate = datePickerController.printFormattedDate(argObj.date, "l-cc-sp-d-S-sp-F-sp-Y", false);
			
			// Make sure the span exists before attempting to use it!
			if(!spn) {
					createSpanElement(argObj); 
					spn = document.getElementById("EnglishDate-" + argObj.id);
			};
			
			// Note: The 3rd argument to printFormattedDate is a Boolean value that 
			// instructs the script to use the imported locale (true) or not (false)
			// when creating the dates. In this case, I'm not using the imported locale
			// as I've used the "S" format mask, which returns the English ordinal
			// suffix for a date e.g. "st", "nd", "rd" or "th" and using an
			// imported locale would look strange if an English suffix was included
			
			// Remove the current contents of the span
			while(spn.firstChild) spn.removeChild(spn.firstChild);
			
			// Add a new text node containing our formatted date
			spn.appendChild(document.createTextNode(formattedDate));
	};
	
		  
	/* 
	 
			Create a datepicker using Javascript and not classNames
			-------------------------------------------------------
		  
			datePickerController.createDatePicker has to be called onload as we need 
			the locale file to have loaded before we can create a datepicker.
		  
			The only way to get around using an onload event is to 
			explicitly set the language by adding it before the datepicker script e.g:
		  
			<script type="text/javascript" src="/the/path/to/the/language/file.js"></ script>
			<script type="text/javascript" src="/the/path/to/the/datepicker/file.js"></ script>
		 
	*/
				
	datePickerController.addEvent(window, "load", function() {
		  var opts = {
			// The ID of the associated form element
			id:"dp-js1",
			// The date format to use
			format:"d-sl-m-sl-Y",
			// Days to highlight (starts on Monday)
			highlightDays:[0,0,0,0,0,1,1],
			// Days of the week to disable (starts on Monday)
			disabledDays:[0,0,0,0,0,0,0],
			// Dates to disable (YYYYMMDD format, "*" wildcards excepted)
			disabledDates:{
					"20090601":"20090612", // Range of dates
					"20090622":"1",        // Single date
					"****1225":"1"         // Wildcard example 
					},
			// Date to always enable
			enabledDates:{},
			// Don't fade in the datepicker
			// NOTE: Only relevant if "staticPos" is set to false
			noFadeEffect:false,
			// Is it inline or popup
			staticPos:false,
			// Do we hide the associated form element on create
			hideInput:false,
			// Do we hide the today button
			noToday:true,
			// Do we show weeks along the left hand side
			showWeeks:true,
			// Is it drag disabled
			// NOTE: Only relevant if "staticPos" is set to false
			dragDisabled:true,
			// Positioned the datepicker within a wrapper div of your choice (requires the ID of the wrapper element)
			// NOTE: Only relevant if "staticPos" is set to true
			positioned:"",
			// Do we fill the entire grid with dates
			fillGrid:true,
			// Do we constrain dates not within the current month so that they cannot be selected
			constrainSelection:true,
			// Callback Object
			callbacks:{"create":[createSpanElement], "dateselect":[showEnglishDate]},
			// Do we create the button within a wrapper element of your choice (requires the ID of the wrapper element)
			// NOTE: Only relevant if staticPos is set to false
			buttonWrapper:"",
			// Do we start the cursor on a specific date (YYYYMMDD format string)
			cursorDate:""      
		  };
		  datePickerController.createDatePicker(opts);
	});

// ]]>		
</script>
</head>
<body>
