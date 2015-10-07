// VARS
var $rslts;
var $grid;


// Range SLIDER
/* We need to change slider appearance oninput and onchange */
function showValue(val,slidernum) {
  // console.log(val);
}
/* we often need a function to set the slider values on page load */
function setValue(val,num) {
  $("#slider"+num).value = val;
  showValue(val,num);
}


// clearForm
function clearForm() { 
                $form = $('fieldset#parameters');
                $form.find('input').val('');
                // $form.find('input[type=checkbox]').attr('checked', false);
                $form.find('option:selected').attr('selected', false);

                $form.find('.select').each(function() {
                    var $element = $(this);
                    var $select = $element.find('select');
                    var $value = $element.find('.select-value');

                    $value.text($select.find(':selected').html());
                });

                $('form#theForm').submit();
            }

//(re)triggerMasonry
	function triggerMasonry(){

		// don't proceed if $grid has not been selected
		if ( !$grid.length ){
			return;
		}

		$rslts.masonry('layout');
	}
// inintMsnry
	function inintMsnry(){ 
	
		$rslts.masonry({
	    	// options
	    	itemSelector : '.post',
	    	columnWidth: 300,
	    	gutterWidth: 20
	     });

		imagesLoaded( '#result', function() {
        	$rslts.masonry('layout');
    	});

	}
// killMsnry
	function killMsnry(){ 
			$rslts.masonry('destroy');
	}
// layoutButtonActive
	function layoutButtonActive(currentLayout){
		
		$('.view a').removeClass('active');
		$('#'+currentLayout).addClass('active');
		
	}


// DOCUMENT READY - 2st
	$(document).ready(function() {

		$rslts = $('#results');
		$grid = $('.grid');


		// COOKIE CHECK - 1st
			$(function() {
				var cc = $.cookie('list_grid');
				if (cc == 'g') {
					layoutButtonActive('grid');
					$rslts.removeClass('list').addClass('grid');
					inintMsnry();
				} else {
					layoutButtonActive('list');
					$rslts.removeClass('grid').addClass('list');
				}
			});
		
	//LAYOUT CHANGE BUTTONS
		$('#grid').click(function() {
			$rslts.fadeOut(300, function() {
				$(this).removeClass('list').addClass('grid').fadeIn(300);
				$.cookie('list_grid', 'g');
				inintMsnry();
			});
			layoutButtonActive('grid');
			return false;
		});
		
		$('#list').click(function() {
			$rslts.fadeOut(300, function() {
				$(this).removeClass('grid').addClass('list').fadeIn(300);
				$.cookie('list_grid', null);
				killMsnry();
			});
			layoutButtonActive('list');
			return false;
		});

	//onChange FORM SUBMIT

	// $('fieldset#record-types input[type=radio]').on('change', function() {
 //    	$(this).closest("form").submit();
	// });

	$('form#theForm').on('change', 'input, select', function() {
		$(this).closest("form").submit();
	});

	// Clear Filters
	$('.js-clear-all-filters').on('click',function(e){ 
		e.preventDefault();
		clearForm();
	})

	
	});

// WINDOW LOAD - 3rd
	$(window).load(function(){

   		// inintMsnry();

	});

// Check Submit function ?????????????????????

	// function checkSubmit() {
    	    
 //    	    // Please enter all ID's of your Multi-Select-Fields into this array.
 //    	    multiPicklistFields = new Array("pba__Request__c.pba__PropertyType__c","pba__Request__c.View__c");
    	    
 //    	    // Enter the name of your form here. DEFAULT: web2prospect
 //    	    var formName = "web2prospect";
    	    
 //    	    // PLEASE DONT'T CHANGE THE JAVASCRIPT-CODE AFTER THIS LINE
 //    	    for (var y=0; y < multiPicklistFields.length; y++) {
 //    	        var string = "";
 //    	        var field = multiPicklistFields[y];
 //    	        for (var i=0; i < document.forms[formName].elements[field].length; i++) {
 //    	            if (document.forms[formName].elements[field][i].checked) {
 //    	                string += document.forms[formName].elements[field][i].value + "; ";
 //    	            }
 //    	        }
 //    	        string = string.substr(0, string.length - 2);
 //    	        field = document.createElement("input");
 //    	        field.type = "hidden";
 //    	        field.name = multiPicklistFields[y];
 //    	        field.value = string;
 //    	        document.forms[formName].appendChild(field);
 //    	    }
 //    	    return true;
 //    	}

////// WEB FONTS /////////

WebFont.load({
    google: {
      families: ['Droid Sans', 'Droid Serif']
    },
    active: triggerMasonry,
  	inactive: triggerMasonry
  });

