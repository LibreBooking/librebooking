/* exported mopen, mclosetimer */
// javascript for enabling drop down sub menus in main navigation

var timeout = 500;
var closetimer = 500;
var submenuitem = 0;

// open hidden layer
function mopen(id) {
  // prevent close timer operation
  mcancelclosetimer();

  // close previous shown submenu
  if (submenuitem) submenuitem.style.visibility = 'hidden';

  // prepare a new submenu and show it
  submenuitem = document.getElementById(id);
  submenuitem.style.visibility = 'visible';
}
// close visible submenu
function mclose() {
  if (submenuitem) submenuitem.style.visibility = 'hidden';
}

// close submenu with a timer
function mclosetimer() {
  closetimer = window.setTimeout(mclose, timeout);
}

// cancel submenu closing timer
function mcancelclosetimer() {
  if (closetimer) {
    window.clearTimeout(closetimer);
    closetimer = null;
  }
}

// close submenu on click
document.onclick = mclose;
