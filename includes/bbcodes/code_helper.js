/* 
| Code from http://www.phpbb.com/
*/
function selectCode(a) {
   'use strict';

   // Get ID of code block
   var e = a.parentNode.parentNode.getElementsByTagName('CODE')[0];
   var s, r;

   // Not IE and IE9+
   if (window.getSelection) {
      s = window.getSelection();
      // Safari and Chrome
      if (s.setBaseAndExtent) {
         var l = (e.innerText.length > 1) ? e.innerText.length - 1 : 1;
         try {
            s.setBaseAndExtent(e, 0, e, l);
         } catch (error) {
            r = document.createRange();
            r.selectNodeContents(e);
            s.removeAllRanges();
            s.addRange(r);
         }
      }
      // Firefox and Opera
      else {
         // workaround for bug # 42885
         if (window.opera && e.innerHTML.substring(e.innerHTML.length - 4) === '<BR>') {
            e.innerHTML = e.innerHTML + '&nbsp;';
         }

         r = document.createRange();
         r.selectNodeContents(e);
         s.removeAllRanges();
         s.addRange(r);
      }
   }
   // Some older browsers
   else if (document.getSelection) {
      s = document.getSelection();
      r = document.createRange();
      r.selectNodeContents(e);
      s.removeAllRanges();
      s.addRange(r);
   }
   // IE
   else if (document.selection) {
      r = document.body.createTextRange();
      r.moveToElementText(e);
      r.select();
   }
}

jQuery(document).ready(function() {
	jQuery(".sel_code").click(function() {
	  selectCode(this);return false;
	});
});