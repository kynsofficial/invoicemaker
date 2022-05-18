jQuery(document).ready(function($) {
    
    function createCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
  
    $(document).on('click', '#cookie-btn', function() {
        
        createCookie('data', '', -1);
        
        var id = $(this).data('id');
        
        $('.cookie-bar').fadeOut('slow');
        
        createCookie('data', id, 365);
        
        return false;
        
    });
    
    $(document).on('click', '.cookie-bar-open', function() {
        
        $('.cookie-bar').fadeIn('slow');
        
        return false;
        
    });
    
});