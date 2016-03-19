function htmlspecialchars(string, quote_style, charset, double_encode) {
    // http://kevin.vanzonneveld.net
    // +   original by: Mirek Slugen
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Nathan
    // +   bugfixed by: Arno
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // +      input by: Mailfaker (http://www.weedem.fr/)
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +      input by: felix
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: charset argument not supported
    // *     example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
    // *     returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
    // *     example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
    // *     returns 2: 'ab"c&#039;d'
    // *     example 3: htmlspecialchars("my "&entity;" is still here", null, null, false);
    // *     returns 3: 'my &quot;&entity;&quot; is still here'

    var optTemp = 0, i = 0, noquotes= false;
    if (typeof quote_style === 'undefined' || quote_style === null) {
        quote_style = 2;
    }
    string = string.toString();
    if (double_encode !== false) { // Put this first to avoid double-encoding
        string = string.replace(/&/g, '&amp;');
    }
    string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');

    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE' : 1,
        'ENT_HTML_QUOTE_DOUBLE' : 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE' : 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i=0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            }
            else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/'/g, '&#039;');
    }
    if (!noquotes) {
        string = string.replace(/"/g, '&quot;');
    }

    return string;
}

function auto_resize(el) {
    // http://googlecode.blogspot.com/2009/07/gmail-for-mobile-html5-series.html
    
    var TEXTAREA_LINE_HEIGHT = 13;
    
    var textarea = $(el).get(0);
    var newHeight = textarea.scrollHeight;
    var currentHeight = textarea.clientHeight;
    
    if (newHeight > currentHeight) {
        textarea.height(newHeight + (2 * TEXTAREA_LINE_HEIGHT));// + 'px';
    }
}

function unix_timestamp() {
    return Math.round(new Date().getTime() / 1000);
}



function get_scroll_position(el) {
    if (! $(el)) return false;
    
    offset = $(el).get(0).scrollTop;//cumulativeScrollOffset();
    return offset;//.top;
}

function set_scroll_position(el, pos) {
    if (! $(el)) return false;
    
    $(el).get(0).scrollTop = pos;
}

var checking_if_name_exists = false;


function validate_name_exists() {
    just_clicked_bubble = true;
    
    if (! $('#change_url_input')) return false;
    el = $('#change_url_input');
    
    
    el.val( el.val().toLowerCase().replace("'", '').replace(/[^-a-z0-9]/g, '-').replace(/--+/g, '-').replace(/^-+|-+$/g, '').substr(0,50));
    if( el.val().length<4 || el.val().length>32 )
    {
      alert('请输入4-32个字符或数字组成的字符串');
      $('#change_url_input').select();
      return false;
    }
    if (el.val() == '' || el.val() == pad_name) {
        $('#bubble_for_change_url').hide();
        $('#message_for_change_url_unavailable').hide();
        
        el.val(pad_name);
    } else {
        if (! checking_if_name_exists) {
            el.addClass('loading');
            $.ajax({
              type: "POST",
              url: baseurltype+'/ajax/checkname/' + el.val(),
              data: null,
              success: function(data){ 
                checking_if_name_exists = false;
                    
                if (data == 'false') {
                    $('#message_for_change_url_unavailable').hide();
                    $('#form_for_set_name').get(0).submit();
                    //return true;
                } else {
                    $('#bubble_for_change_url').show();
                    $('#message_for_change_url_unavailable').show();
                    el.removeClass('loading');
                    $('#change_url_input').select();
                    //return false;
                }
              },
              error: function(data) { 
                
              }
            });
            
        }
    }
   return false; 
    
}

function _update_contents(contents_value) {
    // Immediately save contents
        
    if (saving_pad || ((last_saved_on + seconds_before_save) > unix_timestamp()) ) {
        return true;
    }
    
    window.clearTimeout(update_contents_timeout);
    
    saving_pad = true;
    unsaved_changes = false;
    content_text_pre = contents_value;
    last_saved_on = unix_timestamp();
    
    $('#unsaved').hide();
    $('#loading').show();
    
   
    var param = get_save_data(contents_value);				 
    if( !(param[0].value) )
    {
      var a=1;
      a++;
    
    }
    $.ajax({
      type: "POST",
      url: saveurl,//"ajax/update_contents/"+ pad_name,
      data: param,
      success: function(data){ 
        chars_on_last_save = contents_value.length;
        
        saving_pad = false;
        $('#loading').hide();
      },
      error: function(data) { 
        $('#unsaved').show();
        $('#loading').hide();
        
        if (403 == data.status) {
            alert("Sorry, you seem to be logged out. Refresh the page and log in again.");
            
            disable_autosave = true;
            if (contents_observer) contents_observer = false;
            if (update_contents_timeout) update_contents_timeout = false;
        } else {
            alert("Sorry, couldn't save contents. Try again in a few seconds.");
        }
      }
    });
}

var content_text_pre="";
function _determine_update_contents(contents_value, save_now) {
    if( contents_value == content_text_pre )
      return;
    $('#unsaved').show();
    unsaved_changes = true;
    
    if( save_now )   
    {
      _update_contents(contents_value);
    }
    else
    {
      window.clearTimeout(update_contents_timeout);
      update_contents_timeout = setTimeout(function() {
        return _update_contents.apply(_update_contents, [contents_value]);
      },seconds_before_save*1000);//_update_contents.delay(seconds_before_save, contents_value);
      
      if ((Math.abs(chars_on_last_save - contents_value.length) > new_chars_before_save) 
          )
       {
          window.clearTimeout(update_contents_timeout);
          _update_contents(contents_value);
      }
    }         
    
}
function get_readonc_url(){
  if($('#share_this_readonce_input').val()!="")
  {
    $('#share_this_readonce_input').get(0).select();  
    return;
  }
  $('#share_this_readonce_input').val('正在生成地址...');  
  var el = $('#share_this_readonce_input');
  el.addClass('loading');
  $.ajax({
    type: "POST",
    url: baseurltype+'/ajax/get_readonce_url/' + filehash,
    data: null,
    dataType:'json',
    success: function(data){ 
      checking_if_name_exists = false;
      el.removeClass('loading');     
      if (data.status == 'success') {
        $('#share_this_readonce_input').val(data.contents);
        $('#share_this_readonce_input').get(0).select(); 
        
      } else {
         alert("出错，请稍后再试");
      }
    },
    error: function(data) { 
       el.removeClass('loading'); 
       alert("网络出错，请稍后再试");
    }
  });
  
}


var new_chars_before_save = 50;
var seconds_before_save = 2;

var chars_on_last_save = 0;
var last_saved_on = 0;

var contents_observer = false;
var update_contents_timeout = false;

var saving_pad = false;
var unsaved_changes = false;
var just_clicked_bubble = false;
var bubble_show=false;

$(window).load(function(){
    $('#share_this_readonce_input').val("");
    if ($('#controls .bubble')) {
        $(document).click(function(e){
 
            if (just_clicked_bubble) {
                $('#controls .bubble').hide();
                $('#bubble_for_' + just_clicked_bubble).show();
                
                if (just_clicked_bubble == 'change_url' && $('#change_url_input')) $('#change_url_input').get(0).focus();//select();
                if (just_clicked_bubble == 'set_password' && $('#set_password_input')) $('#set_password_input').get(0).focus();//select();
                if (just_clicked_bubble == 'share_this' && $('#share_this_input')) 
                {
                  bubble_show = "#share_this_input";
                  $('#share_this_input').get(0).select();

                }
                if (just_clicked_bubble == 'share_this_read' && $('#share_this_read_input')) 
                {
                  //$("body").trigger("click");
                  bubble_show = "#share_this_read_input";
                  $('#share_this_read_input').get(0).select();

                }
                if (just_clicked_bubble == 'share_this_readonce' && $('#share_this_readonce_input'))
                {
                  //$("body").trigger("click");
                  bubble_show = "#share_this_readonce_input";
                  get_readonc_url();

                } 
                
                just_clicked_bubble = false;
            } else {
                $('#controls .bubble').hide();
                bubble_show = false;
            }
        });       
    }
        
    if (pad_name && ! disable_autosave && $('#contents')) {
        // Save contents when the page loses focus
        $(window).blur(
          function(e) {
            if (typeof(get_contents) == 'function')//unsaved_changes)
             {
                _determine_update_contents(get_contents(),true);
            }
          }
        );
        
        // Save contents before unload. Prototype mucks with onBeforeUnload
        window.onbeforeunload = function() {
            if (typeof(get_contents) == 'function')//unsaved_changes)
            {
                _determine_update_contents(get_contents(),true);
                return "You have unsaved content.\n\nPlease wait a few seconds before leaving the page. The content will save automatically.";
            }
        };
        
        if (is_iphone_os) {
            auto_resize('#contents');
            
            $('#contents').keyup(
              function(e) {
                auto_resize('#contents');
              }
            );
            
        }
        
        $('#contents').keydown(
          function(e) {
            if (e.keyCode == 9 ) {                     //Event.KEY_TAB
                // Catch and support tabs
                var tab = '	';
                
                var t = e.target;//Event.element(e);
                var s_start = t.selectionStart;
                var s_end = t.selectionEnd;
                var tvalue=t.val();
                t.val(tvalue.slice(0, s_start).concat(tab).concat(tvalue.slice(s_end, tvalue.length)));
                t.selectionStart = t.selectionEnd = s_start + 1;
                
                e.preventDefault();
                e.stopPropagation();
            } else if ((e.ctrlKey || e.metaKey) && e.keyCode == 83) {
                // Save on ?S / ?S
                if (typeof(get_contents) == 'function')//unsaved_changes)
                {
                    _determine_update_contents(get_contents(),true);
                }
                
                e.preventDefault();
                e.stopPropagation();
            }
           
            // } else if (e.ctrlKey || e.altKey || e.metaKey) {
            //     // Save on ? and ?
            //     
            //     if (unsaved_changes) {
            //         _update_contents($('#contents').get(0).value);
            //     }
            // }
            
            //$('#printable_contents').innerHTML = htmlspecialchars(get_contents());
            
        
          }
        );
        
    }

});

var winWidth = 0;var winHeight = 0;
       
function findDimensions() 
//函数：获取尺寸
{
  //获取窗口宽度
  if (window.innerWidth)
    winWidth = window.innerWidth;
  else if ((document.body) && (document.body.clientWidth))
    winWidth = document.body.clientWidth;
  //获取窗口高度
  if (window.innerHeight)
    winHeight = window.innerHeight;
  else if ((document.body) && (document.body.clientHeight))
    winHeight = document.body.clientHeight;
  //通过深入Document内部对body进行检测，获取窗口大小
  if (document.documentElement  && document.documentElement.clientHeight && document.documentElement.clientWidth)
  {
    winHeight = document.documentElement.clientHeight;
    winWidth = document.documentElement.clientWidth;
  }

}
findDimensions();