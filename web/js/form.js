

//Code gotten from
//http://www.sanwebe.com/2013/03/addremove-input-fields-dynamically-with-jquery
//By Sanwebe
//Add/Remove Input Fields Dynamically with jQuery

$(document).ready(function(){
    var maxField = 4; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var x = 1; //Initial field counter is 1
    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter

            var fieldHTML = '<div><input type="text" class="form-control" id="answer" name="answer[]" required="required" title="Please enter an answer option" placeholder="Enter Answer"><label><input type="radio" value="answer' + x +'" name="iscorrect" required="required"> Correct </label><a href="javascript:void(0);" class="remove_button" title="Remove field"><br><span class="glyphicon glyphicon-minus"></span></a></div>'; //New input field html

            $(wrapper).append(fieldHTML); // Add field html
        }
    });


    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});

