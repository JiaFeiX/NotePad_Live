function get_contents(){

 return $("#contents").get(0).value;
}


function get_save_data(contents_value){

    var caret_position = get_caret_position('#contents');
    var scroll_position = get_scroll_position('#contents');
    
    var param = [
					 { name : 'contents', value : contents_value }					
					,{ name : 'caret_position', value : caret_position}
					,{ name : 'scroll_position', value : scroll_position }
				];
    return param;	
}

function get_caret_position(el) {
    if (! $(el)) return false;
    
    el = $(el).get(0);
    
    if (el.selectionStart) {
        return el.selectionStart;
    } else if (document.selection) {
        el.focus();
        
        var r = document.selection.createRange();
        if (r == null) {
            return 0;
        }
        
        var re = el.createTextRange(),
        rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);
        
        return rc.text.length;
    }
    return 0;
}


function set_caret_position(el, pos){
    if (! $(el)) return false;
    
    el = $(el).get(0);
    
    if (el.setSelectionRange) {
        el.focus();
        el.setSelectionRange(pos, pos);
    } else if (el.createTextRange) {
        var range = el.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}
$(window).load(function(){
    if ($('#contents')) {
        if (caret_position) {
            set_caret_position('#contents', caret_position);
        } else {
            $('#contents').focus();
        }
    }
    if ($('#contents') && scroll_position) set_scroll_position('#contents', scroll_position);
    
    if (pad_name && ! disable_autosave && $('#contents')) {
       var interval=setInterval(function() {
                
                _determine_update_contents(get_contents(),false);
            },2500);
            
      // Save contents when mousemove
        $(window).mousemove(
          function(e) {
            //if (unsaved_changes) 
            {
                _determine_update_contents(get_contents(),true);
            }
          }
        );
        var contents=get_contents();
      if( contents && contents != '')
      {
         content_text_pre = contents;
        chars_on_last_save = contents.length;
      }
      $('#printable_contents').innerHTML = htmlspecialchars(contents);
    }
    
})
  