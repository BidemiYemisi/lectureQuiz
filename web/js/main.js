
$(function() {
    var addDiv = $('#quizbox');
    var max_fields      = 4;
    var i = $('#quizbox p').size() + 1;

    $('#addNew').live('click', function() {
        if (i<max_fields){
            $(
                '<p id= "addNew" name="p_new_' + i +'"><label for="question">Answer</label><input rows="4" cols="50" class="form-control" id="question" name="question" placeholder="Enter Question"><a href="#" id= "addNew">Add </a><a href="#" id="remNew"> Remove</a></p> ').appendTo(addDiv);
            i++;
        }
        return false;
    });

    $('#remNew').live('click', function() {
        if( i > 2 ) {
            $(this).parents('p').remove();
            i--;
        }
        return false;
    });
});

