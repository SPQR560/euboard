$(document).ready(function() { 
    $(function() {
        $(".board-message").tooltip();
    });
   
    $('.board-message').hover(function(e){
        e.preventDefault();
        let id = $(this).attr("href");
        let message = $(id).children('p').text().trim();
        $(this).attr('data-original-title', message);    
    });
        
    $('.answerLink').click(function (e){
        e.preventDefault();
        let messageDiv = $(this).parent().parent();
        let messageDivId = $(messageDiv).attr('id');
        let messageFormText = $('#message_form_text');

        messageFormText.val(`>>>>>${messageDivId}\n${messageFormText.val()}`);
    });

    $('#strike_button').click(function (e){
        e.preventDefault();
        addOpenTagBeforeSelectionStartAndCloseTagAfterSelectionEndPosition('s');
    });

    $('#bold_button').click(function (e){
        e.preventDefault();
        addOpenTagBeforeSelectionStartAndCloseTagAfterSelectionEndPosition('b');
    });

    function addOpenTagBeforeSelectionStartAndCloseTagAfterSelectionEndPosition(tag) {
        let messageFormText = $('#message_form_text');
        let cursorStartPos = messageFormText.prop('selectionStart');
        let cursorEndPos = messageFormText.prop('selectionEnd');
        let textValue = messageFormText.val();

        let selectedText = textValue.substring(cursorStartPos,  cursorEndPos);
        let textBefore = `${textValue.substring(0,  cursorStartPos)}<${tag}>`;
        let textAfter  = `</${tag}>${textValue.substring(cursorEndPos, textValue.length)}`;

        messageFormText.val(textBefore + selectedText + textAfter);
    }
});