/**
 * http://www.openjs.com/scripts/events/keyboard_shortcuts/
 * Version : 2.01.B
 * By Binny V A
 * License : BSD
 */
shortcut = {
	'all_shortcuts':{},//All the shortcuts are stored in this array
	'add': function(shortcut_combination,callback,opt) {
		//Provide a set of default options
		var default_options = {
			'type':'keydown',
			'propagate':false,
			'disable_in_input':false,
			'target':document,
			'keycode':false
		}
		if(!opt) opt = default_options;
		else {
			for(var dfo in default_options) {
				if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
			}
		}

		var ele = opt.target;
		if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
		var ths = this;
		shortcut_combination = shortcut_combination.toLowerCase();

		//The function to be called at keypress
		var func = function(e) {
			e = e || window.event;
			
			if(opt['disable_in_input']) { //Don't enable shortcut keys in Input, Textarea fields
				var element;
				if(e.target) element=e.target;
				else if(e.srcElement) element=e.srcElement;
				if(element.nodeType==3) element=element.parentNode;

				if(element.tagName == 'INPUT' || element.tagName == 'TEXTAREA') return;
			}
	
			//Find Which key is pressed
			if (e.keyCode) code = e.keyCode;
			else if (e.which) code = e.which;
			var character = String.fromCharCode(code).toLowerCase();
			
			if(code == 188) character=","; //If the user presses , when the type is onkeydown
			if(code == 190) character="."; //If the user presses , when the type is onkeydown

			var keys = shortcut_combination.split("+");
			//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
			var kp = 0;
			
			//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
			var shift_nums = {
				"`":"~",
				"1":"!",
				"2":"@",
				"3":"#",
				"4":"$",
				"5":"%",
				"6":"^",
				"7":"&",
				"8":"*",
				"9":"(",
				"0":")",
				"-":"_",
				"=":"+",
				";":":",
				"'":"\"",
				",":"<",
				".":">",
				"/":"?",
				"\\":"|"
			}
			//Special Keys - and their codes
			var special_keys = {
				'esc':27,
				'escape':27,
				'tab':9,
				'space':32,
				'return':13,
				'enter':13,
				'backspace':8,
	
				'scrolllock':145,
				'scroll_lock':145,
				'scroll':145,
				'capslock':20,
				'caps_lock':20,
				'caps':20,
				'numlock':144,
				'num_lock':144,
				'num':144,
				
				'pause':19,
				'break':19,
				
				'insert':45,
				'home':36,
				'delete':46,
				'end':35,
				
				'pageup':33,
				'page_up':33,
				'pu':33,
	
				'pagedown':34,
				'page_down':34,
				'pd':34,
	
				'left':37,
				'up':38,
				'right':39,
				'down':40,
	
				'f1':112,
				'f2':113,
				'f3':114,
				'f4':115,
				'f5':116,
				'f6':117,
				'f7':118,
				'f8':119,
				'f9':120,
				'f10':121,
				'f11':122,
				'f12':123
			}
	
			var modifiers = { 
				shift: { wanted:false, pressed:false},
				ctrl : { wanted:false, pressed:false},
				alt  : { wanted:false, pressed:false},
				meta : { wanted:false, pressed:false}	//Meta is Mac specific
			};
                        
			if(e.ctrlKey)	modifiers.ctrl.pressed = true;
			if(e.shiftKey)	modifiers.shift.pressed = true;
			if(e.altKey)	modifiers.alt.pressed = true;
			if(e.metaKey)   modifiers.meta.pressed = true;
                        
			for(var i=0; k=keys[i],i<keys.length; i++) {
				//Modifiers
				if(k == 'ctrl' || k == 'control') {
					kp++;
					modifiers.ctrl.wanted = true;

				} else if(k == 'shift') {
					kp++;
					modifiers.shift.wanted = true;

				} else if(k == 'alt') {
					kp++;
					modifiers.alt.wanted = true;
				} else if(k == 'meta') {
					kp++;
					modifiers.meta.wanted = true;
				} else if(k.length > 1) { //If it is a special key
					if(special_keys[k] == code) kp++;
					
				} else if(opt['keycode']) {
					if(opt['keycode'] == code) kp++;

				} else { //The special keys did not match
					if(character == k) kp++;
					else {
						if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
							character = shift_nums[character]; 
							if(character == k) kp++;
						}
					}
				}
			}
			
			if(kp == keys.length && 
						modifiers.ctrl.pressed == modifiers.ctrl.wanted &&
						modifiers.shift.pressed == modifiers.shift.wanted &&
						modifiers.alt.pressed == modifiers.alt.wanted &&
						modifiers.meta.pressed == modifiers.meta.wanted) {
				callback(e);
	
				if(!opt['propagate']) { //Stop the event
					//e.cancelBubble is supported by IE - this will kill the bubbling process.
					e.cancelBubble = true;
					e.returnValue = false;
	
					//e.stopPropagation works in Firefox.
					if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
					}
					return false;
				}
			}
		}
		this.all_shortcuts[shortcut_combination] = {
			'callback':func, 
			'target':ele, 
			'event': opt['type']
		};
		//Attach the function with the event
		if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
		else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
		else ele['on'+opt['type']] = func;
	},

	//Remove the shortcut - just specify the shortcut and I will remove the binding
	'remove':function(shortcut_combination) {
		shortcut_combination = shortcut_combination.toLowerCase();
		var binding = this.all_shortcuts[shortcut_combination];
		delete(this.all_shortcuts[shortcut_combination])
		if(!binding) return;
		var type = binding['event'];
		var ele = binding['target'];
		var callback = binding['callback'];

		if(ele.detachEvent) ele.detachEvent('on'+type, callback);
		else if(ele.removeEventListener) ele.removeEventListener(type, callback, false);
		else ele['on'+type] = false;
	}
}

shortcut.add("esc",function() {
  $(".row_over").removeClass("row_over_edit");
});
shortcut.add("F1",function() {
  window.location = "warehouses-take-in";
});
shortcut.add("F2",function() {
  window.location = "transfer-index";
});
shortcut.add("F3",function() {
  var note_box_position = $("#notes_box").css("right");
  if(note_box_position == "0px") {
    // close the box
    var notes_box_width = $("#notes_box").css("width");
    var whole_notes_box_width =  parseInt(notes_box_width)+30;
    $("#notes_box").animate({right:'-'+whole_notes_box_width+'px'}, 500);
  }
  else {
    // open the box
    $("#notes_box").animate({right:'0px'}, 500);
  }
});
shortcut.add("F4",function() {
  window.location = "administration-index";
});

function fixedEncodeURIComponent (str) {
  return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}

// Handling Cookies

function createCookie(name,value,hours) {
  var expires = "";
  if (hours) {
    var date = new Date();
    date.setTime(date.getTime()+(hours*60*60*1000));
    expires = "; expires="+date.toGMTString();
  }
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-3);
}

// Disable Right Click Script
 
function IE(e) {
  if (navigator.appName == "Microsoft Internet Explorer" && (event.button == "2" || event.button == "3")) {
    return false;
  }
}
function NS(e) {
  if (document.layers || (document.getElementById && !document.all)) {
    if (e.which == "2" || e.which == "3") {
      return false;
    }
  }
}
//document.onmousedown=IE;document.onmouseup=NS;document.oncontextmenu=new Function("return false");

function Checkbox(checkbox) {
  if($(checkbox).parent().hasClass("checkbox_checked")) {
    $(checkbox).parent().removeClass("checkbox_checked");
  }
  else {
    $(checkbox).parent().addClass("checkbox_checked");
  }
}

function NoRightsToEdit() {
  alert("You have no rights to insert or edit information!");
  $(".row_over").removeClass("row_over_edit");
}

function NoRightsToDelete() {
  alert("You have no rights to delete information!");
  $(".row_over").removeClass("row_over_edit");
}

function CheckEditRights() {

  var user_id = $("#user_id").val();
  if(user_id == "" || user_id == undefined) {
    location.reload();
    return false;
  }
  
  var user_access_edit = $(".second_menu .active .active .third_menu_link").attr("user-access-edit");
  if(user_access_edit == undefined) {
    user_access_edit = $(".second_menu .active .second_menu_link").attr("user-access-edit");
  }
  if(user_access_edit == undefined) {
    user_access_edit = $("#menu .selected .active_first_level").attr("users-rights-access");
  }
  if(user_access_edit == 0) {
    alert("You have no rights to insert or edit information!");
    $(".row_over").removeClass("row_over_edit");
    return false;
  }
  else return true;
  
}
  
function CheckDeleteRights() {
  
  var user_id = $("#user_id").val();
  if(user_id == "" || user_id == undefined) {
    location.reload();
    return false;
  }
  var user_access_delete = $(".second_menu .active .active .third_menu_link").attr("user-access-delete");
  if(user_access_delete == undefined) {
    user_access_delete = $(".second_menu .active .second_menu_link").attr("user-access-delete");
  }
  if(user_access_delete == undefined) {
    user_access_delete = $("#menu .selected .active_first_level").attr("user_access_delete");
  }
  if(user_access_delete == 0) {
    alert("You have no rights to delete information!");
    $(".row_over").removeClass("row_over_edit");
    return false;
  }
  else return true;
  
}

function unloadAllJS() {
  var jsArray = new Array();
  jsArray = document.getElementsByTagName('script');
  for (i = 0; i < jsArray.length; i++){
    if (jsArray[i].id){
      unloadJS(jsArray[i].id)
    }else{
      jsArray[i].parentNode.removeChild(jsArray[i]);
    }
  }      
}
  
function ShowAjaxLoader() {
  $("#ajax_loader_backgr, #ajax_loader").show();
  setTimeout(function () { $("#ajax_loader_backgr, #ajax_loader").hide(); }, 5000);
}

function HideAjaxLoader() {
  $("#ajax_loader_backgr, #ajax_loader").hide();
//  setTimeout(function () { $("#ajax_loader_backgr, #ajax_loader").hide(); }, 250);
}

function CalculateModalWindowSize() {
  var html_width = $(window).width();
  var html_height = $(window).height();
  var modal_window_width = $("#modal_window").width();
  var modal_window_height = $("#modal_window").height();
  //alert(modal_window_width);alert(modal_window_height);
  var modal_window_left = parseInt(html_width-modal_window_width-10)/2.1;
  var modal_window_top = parseInt(html_height-modal_window_height-10)/2.1;
  //alert(modal_window_top);alert(modal_window_left);
  $("#modal_window").css({top: modal_window_top+"px",left: modal_window_left+"px"})
}

function EditRestrictedUser(user_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_div = "#user"+user_id;
  var user_username = $(user_div+" .user_username").val();
  var user_password = $(user_div+" .user_password").val();
  //alert(task_group_name);
  $.ajax({
  url:"administration/ajax/edit/edit-restricted-user.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id,
    user_username:user_username,
    user_password:user_password
    }
  }).done(function(data){
    $(".row_over").removeClass("row_over_edit");
    
    if(data == "") {
      $(user_div+" td").effect("highlight", {}, 3000);
    }
    else {
      alert(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddEditUserLanguage(user_id) {
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var language_id = $("#language_id").val();
  //alert(task_group_name);
  $.ajax({
  url:"administration/ajax/edit/add-edit-user-default-language.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id,
    language_id:language_id
    }
  }).done(function(data){
    $(".row_over").removeClass("row_over_edit");
    
    if(data == "") {
      $("#user_lang"+user_id+" td").effect("highlight", {}, 3000);
    }
    else {
      alert(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetUsersForType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_type_id = $(".selected_user_type a").attr("data-id");
  var user_type = $(".selected_user_type a").html();
  //alert(friendly_url);return;
  $.ajax({
  url:"administration/ajax/get/get-users-for-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_type_id:user_type_id,
    user_type:user_type
    }
  }).done(function(data){
    
    $("#users_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetUserDetails() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_id = $(".selected_user a").attr("data-id");
  var user_name = $(".selected_user a").html();
  //alert(friendly_url);return;
  $.ajax({
  url:"administration/ajax/get/get-user-details.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id,
    user_name:user_name
    }
  }).done(function(data){
    
    $("#user_details").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddUser() {
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_type_id = $(".selected_user_type a").attr("data-id");
  var warehouse_id = $("#add_user_warehouse_id").val();
  var user_username = $("#add_user_username").val();
  var user_password = $("#add_user_password").val();
  var user_firstname = $("#add_user_firstname").val();
  var user_lastname = $("#add_user_lastname").val();
  var user_address = $("#add_user_address").val();
  var user_phone = $("#add_user_phone").val();
  var user_email = $("#add_user_email").val();
  var user_info = $("#add_user_info").val();
  var user_is_ip_in_use = ($("#add_user_is_ip_in_use").is(":checked"))? "1" : "0";
  var user_is_active = ($("#add_user_is_active").is(":checked"))? "1" : "0";
  var create_user_account = ($("#add_create_user_account").is(":checked"))? "1" : "0";
  //alert(task_group_name);
  $.ajax({
  url:"administration/ajax/add/add-user.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_type_id:user_type_id,
    warehouse_id:warehouse_id,
    user_username:user_username,
    user_password:user_password,
    user_firstname:user_firstname,
    user_lastname:user_lastname,
    user_address:user_address,
    user_phone:user_phone,
    user_email:user_email,
    user_info:user_info,
    user_is_ip_in_use:user_is_ip_in_use,
    user_is_active:user_is_active,
    create_user_account:create_user_account
    }
  }).done(function(data){
    $(".row_over").removeClass("row_over_edit");
    
    if(data == "") {
      GetUsersForType();
    }
    else {
      alert(data);
    }
    
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditUserFullDetails(user_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_type_id = $(".selected_user_type a").attr("data-id");
  var warehouse_id = $("#user_warehouse_id").val();
  var user_username = $("#user_username").val();
  var user_password = $("#user_password").val();
  var user_firstname = $("#user_firstname").val();
  var user_lastname = $("#user_lastname").val();
  var user_address = $("#user_address").val();
  var user_phone = $("#user_phone").val();
  var user_email = $("#user_email").val();
  var user_info = $("#user_info").val();
  var user_is_ip_in_use = ($("#user_is_ip_in_use").is(":checked"))? "1" : "0";
  var user_is_active = ($("#user_is_active").is(":checked"))? "1" : "0";
  var create_user_account = ($("#create_user_account").is(":checked"))? "1" : "0";
  if($("#user_has_account").length) create_user_account = "2";
  //alert(task_group_name);
  $.ajax({
  url:"administration/ajax/edit/edit-user-details.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_type_id:user_type_id,
    user_id:user_id,
    warehouse_id:warehouse_id,
    user_username:user_username,
    user_password:user_password,
    user_firstname:user_firstname,
    user_lastname:user_lastname,
    user_address:user_address,
    user_phone:user_phone,
    user_email:user_email,
    user_info:user_info,
    user_is_ip_in_use:user_is_ip_in_use,
    user_is_active:user_is_active,
    create_user_account:create_user_account
    }
  }).done(function(data){
    $(".row_over").removeClass("row_over_edit");
    
    if(data == "") {
      $("#choose_user #tr"+user_id+" td a").html(user_firstname+" "+user_lastname);
      $("#user_details"+user_id+" td").effect("highlight", {}, 3000);
    }
    else {
      alert(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditUserJQ(user_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var areChecked = [];
  var i = 0;
  $.each($( ".details"+user_id+" input:checkbox[name=access]:checked" ), function(){     
      var tempArray = [];
      var j = 0;
      tempArray[j] = $(this).val();
      $.each($( ".details"+user_id+" .page"+$(this).val()+" input:checkbox[name=rights]:checked" ), function(){
          j++;
          tempArray[j] = $(this).val();
      });
      areChecked[i] = tempArray;
      i++;
  });

  $.post("administration/ajax/edit/edit-user.php",{
      user_access:user_access,
      user_id:user_id,
      user_name:$("#user_username"+user_id).val(),
      user_password:$("#user_password"+user_id).val(),
      warehouse_id:$("#warehouse_id"+user_id).val(),
      user_is_ip_in_use:($("#ip_in_use"+user_id).is(':checked') ? 1 : 0),
      user_is_active:($("#active"+user_id).is(':checked') ? 1 : 0),
      user_rights:areChecked
  }).done(function(data) {
      $(".row_over").removeClass("row_over_edit");
      $(".users_details").removeClass("access_rights_edit");
      if(data == "") {
        $("#user"+user_id+" td").effect("highlight", {}, 3000);
      }
      else {
        alert(data);
      }
      HideAjaxLoader();
  }).fail(function(data) {
        console.log("Error: "+data);
        alert("Error: "+data);
  });
    
}

function DeleteUser(user_id) {
  if(user_id == "1") {
    alert("You can't delete admin!");
    return
  }
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this user?');
  if(answer) {
    $.ajax({
    url:"administration/ajax/delete/delete-user.php",
    type:"POST",
    data:{
      user_access:user_access,
      user_id:user_id
      }
    }).done(function(data){
      //alert(data);
      $("#choose_user #tr"+user_id).remove();
      $("#user_details").html("");
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function EditUserForReset(user_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  $.post("administration/ajax/edit/edit-user-for-reset.php",{
      user_access:user_access,
      user_id:user_id,
      user_name:$("#user_username"+user_id).val(),
      user_password:$("#user_password"+user_id).val()
  }).done(function(data) {
      $(".row_over").removeClass("row_over_edit");
      $(".users_detail").removeClass("access_rights_edit");
      if(data == "") {
        $("#user"+user_id+" td").effect("highlight", {}, 3000);
      }
      else {
        alert(data);
      }
      HideAjaxLoader();
  }).fail(function(data) {
        console.log("Error: "+data);
        alert("Error: "+data);
  });
    
}

function SearchUsers(company_type_id) {
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_username = $("#search_user_username").val();
  var user_first_name = $("#search_user_first_name").val();
  var user_last_name = $("#search_user_last_name").val();
  var user_company_name = $("#search_user_company_name").val();
  var user_department_id = $("#user_group_department_id").val();
  //alert(user_company_type+' | '+user_department);return;
  $.ajax({
  url:"administration/ajax/get-queries/search-users.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_username:user_username,
    user_first_name:user_first_name,
    user_last_name:user_last_name,
    company_type_id:company_type_id,
    user_company_name:user_company_name,
    user_department_id:user_department_id
    }
  }).done(function(data){
      $("#users_list").html(data);
      HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  });
}

function SearchUsersForReset(company_type_id,all) {
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_username = $("#search_user_username").val();
  var user_first_name = $("#search_user_first_name").val();
  var user_last_name = $("#search_user_last_name").val();
  var user_company_name = $("#search_user_company_name").val();
  var user_department_id = $("#user_group_department_id").val();
  //alert(user_company_type+' | '+user_department);return;
  $.ajax({
  url:"administration/ajax/get-queries/search-users-for-reset.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_username:user_username,
    user_first_name:user_first_name,
    user_last_name:user_last_name,
    company_type_id:company_type_id,
    user_company_name:user_company_name,
    user_department_id:user_department_id,
    all:all
    }
  }).done(function(data){
      $("#users_list").html(data);
      HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  });
}

function GetUserLog(user_id) {
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var url = "administration/ajax/get/get-user-log.php?user_id="+user_id+"&user_access="+user_access;
  window.open(url,'mywindow','status=no,location=yes,resizable=yes,scrollbars=yes,width=800,height=800,left=100,top=0,screenX=0,screenY=0');
}

function ResetIP(user_id) {
  ShowAjaxLoader();
  
  $.ajax({
  url:"administration/ajax/edit/edit-user-ip.php",
  type:"POST",
  data:{
    user_id:user_id
    }
  }).done(function(data){
    $(".row_over").removeClass("row_over_edit");
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddMenuLink() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var friendly_url = $("#friendly_url").val();
  var menu_parent_id = $("#add_menu #add_menu_parent_id").val();
  var menu_parent_level = $("#add_menu #add_menu_parent_id :selected").attr("level");
  var menu_has_children = ($("#add_menu #add_menu_has_children").is(":checked") ? "1" : "0");
  var menu_name = $("#add_menu #add_menu_name").val();
  var menu_url = $("#add_menu #add_menu_url").val();
  var menu_friendly_url = $("#add_menu #add_menu_friendly_url").val();
  var menu_path_name = $("#add_menu #add_menu_path_name").val();
  var menu_image_url = $("#add_menu #add_menu_image_url").val();
  var menu_sort = $("#add_menu #add_menu_sort").val();
  var menu_active = ($("#add_menu #add_menu_active").is(":checked") ? "1" : "0");
  if(menu_name == "") {
    alert("Please enter menu name!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"administration/ajax/add/add-menu-link.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_parent_id:menu_parent_id,
    menu_parent_level:menu_parent_level,
    menu_has_children:menu_has_children,
    menu_name:menu_name,
    menu_url:menu_url,
    menu_friendly_url:menu_friendly_url,
    menu_path_name:menu_path_name,
    menu_image_url:menu_image_url,
    menu_sort:menu_sort,
    menu_active:menu_active
    }
  }).done(function(){
    
    window.location = friendly_url;
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditMenuLink(menu_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var friendly_url = $("#friendly_url").val();
  var menu_div = "#menu"+menu_id;
  var menu_parent_id = $(menu_div+" .menu_parent_id").val();
  var menu_parent_level = $(menu_div+" .menu_parent_id :selected").attr("level");
  var menu_has_children = ($(menu_div+" .menu_has_children").is(":checked") ? "1" : "0");
  var menu_name = $(menu_div+" .menu_name").val();
  var menu_url = $(menu_div+" .menu_url").val();
  var menu_friendly_url = $(menu_div+" .menu_friendly_url").val();
  var menu_path_name = $(menu_div+" .menu_path_name").val();
  var menu_image_url = $(menu_div+" .menu_image_url").val();
  var menu_sort = $(menu_div+" .menu_sort").val();
  var menu_active = ($(menu_div+" .menu_active").is(":checked") ? "1" : "0");
  //alert(friendly_url);return;
  $.ajax({
  url:"administration/ajax/edit/edit-menu-link.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_id:menu_id,
    menu_parent_id:menu_parent_id,
    menu_parent_level:menu_parent_level,
    menu_has_children:menu_has_children,
    menu_name:menu_name,
    menu_url:menu_url,
    menu_friendly_url:menu_friendly_url,
    menu_path_name:menu_path_name,
    menu_image_url:menu_image_url,
    menu_sort:menu_sort,
    menu_active:menu_active
    }
  }).done(function(data){
    
    window.location = friendly_url;
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteMenuLink(menu_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this menu?');
  var menu_div = "#menu"+menu_id;
  var friendly_url = $("#friendly_url").val();
  var menu_parent_id = $(menu_div+" .menu_parent_id").val();
  if(answer) {
    $.ajax({
    url:"administration/ajax/delete/delete-menu-link.php",
    type:"POST",
    data:{
      user_access:user_access,
      menu_id:menu_id,
      menu_parent_id:menu_parent_id
      }
    }).done(function(data){
      
      if(data == "") {
        window.location = friendly_url;
      }
      else {
        alert(data);
      }
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function GetMenuLinkChildren(level) {
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  var prev_level = parseInt(level)-1;
  var menu_id = $(".selected_menu_link_level_"+prev_level+" a").attr("data");
  var menu_name = $(".selected_menu_link_level_"+prev_level+" a").html();
  //alert(prev_level);return false;
  $.ajax({
  url:"/administration/ajax/get/get-menu-link-children.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_id:menu_id,
    menu_name:menu_name,
    level:level
    }
  }).done(function(data){
    if(data == "") {
      // if there are no children then do nothing
    }
    else {
      $("#menu_links_level_"+level).html(data);
    }
    GetMenuLinkNote(menu_id);
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetMenuLinkNote(menu_id) {
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  var language_id = $(".selected_language a").attr("data");
  if(menu_id == undefined) {
    menu_id = $(".selected_menu_link_level_2 a").attr("data");
  }
  if(menu_id == undefined) {
    menu_id = $(".selected_menu_link_level_1 a").attr("data");
  }
  if(menu_id == undefined) {
    menu_id = $(".selected_menu_link_level_0 a").attr("data");
  }
  //alert(part_id);return false;
  $.ajax({
  url:"/administration/ajax/get/get-menu-link-note.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_id:menu_id,
    language_id:language_id
    }
  }).done(function(data){
    $("#add_new_menu_link_note").html("");
    $("#menu_link_note").html(data);
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddMenuLinkNote() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  var language_id = $(".selected_language a").attr("data");
  var menu_id = $(".selected_menu_link_level_2 a").attr("data");
  var menu_link_note = $("#add_menu_link_note").val();
  if(menu_id == undefined) {
    menu_id = $(".selected_menu_link_level_1 a").attr("data");
  }
  if(menu_id == undefined) {
    menu_id = $(".selected_menu_link_level_0 a").attr("data");
  }
  //alert(menu_link_note);return;
  $.ajax({
  url:"/administration/ajax/add/add-menu-link-note.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_id:menu_id,
    language_id:language_id,
    menu_link_note:menu_link_note
    }
  }).done(function(data){
    //alert(data);
    GetMenuLinkNote(menu_id);
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditMenuLinkNote(menu_id,language_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  var menu_link_note_tr = "#menu_link_note"+menu_id+language_id;
  var menu_link_note = $(menu_link_note_tr+" .menu_link_note").val();
  //alert(news_feed_is_web);return;
  $.ajax({
  url:"/administration/ajax/edit/edit-menu-link-note.php",
  type:"POST",
  data:{
    user_access:user_access,
    menu_id:menu_id,
    language_id:language_id,
    menu_link_note:menu_link_note
    }
  }).done(function(data){
    //alert(data);
    $(".row_over").removeClass("row_over_edit");
    $(menu_link_note_tr+" td").effect("highlight", {}, 3000);
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteMenuLinkNote(menu_id,language_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this news feed?');
  if(answer) {
    $.ajax({
    url:"/administration/ajax/delete/delete-menu-link-note.php",
    type:"POST",
    data:{
      user_access:user_access,
      menu_id:menu_id,
      language_id:language_id
      }
    }).done(function(){
      //alert(data);
      $("div#menu_link_note"+menu_id+language_id).remove();
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddVehicleType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type = $("#add_vehicle_type #add_vehicle_type").val();
  var vehicle_image_id = $("#add_vehicle_type #add_vehicle_image_id").val();
  if(vehicle_type == "" || vehicle_type == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"vehicles/ajax/add/add-vehicle-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type:vehicle_type,
    vehicle_image_id:vehicle_image_id
    }
  }).done(function(data){
    
    $("#add_new_vehicle_type").append(data);
    $("#add_vehicle_type #add_vehicle_type").val("");
    $("#add_vehicle_type #add_vehicle_type").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditVehicleType(vehicle_type_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_div = "#vehicle_type"+vehicle_type_id;
  var vehicle_type = $(vehicle_type_div+" .vehicle_type").val();
  var vehicle_image_id = $(vehicle_type_div+" .vehicle_image_id").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/edit/edit-vehicle-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    vehicle_type:vehicle_type,
    vehicle_image_id:vehicle_image_id
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(vehicle_type_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteVehicleType(vehicle_type_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this vehicle type?');
  var vehicle_type_div = "#vehicle_type"+vehicle_type_id;
  if(answer) {
    $.ajax({
    url:"vehicles/ajax/delete/delete-vehicle-type.php",
    type:"POST",
    data:{
      user_access:user_access,
      vehicle_type_id:vehicle_type_id
      }
    }).done(function(data){
      
      $(vehicle_type_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddVehicleMake() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_make = $("#add_vehicle_make #add_vehicle_make").val();
  if(vehicle_make == "" || vehicle_make == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"vehicles/ajax/add/add-vehicle-make.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_make:vehicle_make
    }
  }).done(function(data){
    
    $("#add_new_vehicle_make").append(data);
    $("#add_vehicle_make #add_vehicle_make").val("");
    $("#add_vehicle_make #add_vehicle_make").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditVehicleMake(vehicle_make_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_make_div = "#vehicle_make"+vehicle_make_id;
  var vehicle_make = $(vehicle_make_div+" .vehicle_make").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/edit/edit-vehicle-make.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_make_id:vehicle_make_id,
    vehicle_make:vehicle_make
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(vehicle_make_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteVehicleMake(vehicle_make_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this vehicle type?');
  var vehicle_make_div = "#vehicle_make"+vehicle_make_id;
  if(answer) {
    $.ajax({
    url:"vehicles/ajax/delete/delete-vehicle-make.php",
    type:"POST",
    data:{
      user_access:user_access,
      vehicle_make_id:vehicle_make_id
      }
    }).done(function(data){
      
      $(vehicle_make_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function GetVehicleMakesForType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/get/get-vehicle-makes-for-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id
    }
  }).done(function(data){
    
    $("#vehicle_makes_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetVehicleMakesListForType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/get/get-vehicle-makes-list-for-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id
    }
  }).done(function(data){
    
    $("#vehicle_makes_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddDeleteMakeToType(vehicle_make_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  var vehicle_make_checkbox = ($("#vehicle_make"+vehicle_make_id+" .vehicle_make_id").is(":checked")) ? "1":"0";
  //alert(vehicle_make_checkbox);return;
  $.ajax({
  url:"vehicles/ajax/add/add-delete-vehicle-make-for-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id,
    vehicle_make_checkbox:vehicle_make_checkbox
    }
  }).done(function(data){
    
    $(".row_over").removeClass("row_over_edit");
    $("#vehicle_make"+vehicle_make_id+" td").effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetVehicleModels() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  var vehicle_make_id = $(".selected_vehicle_make a").attr("data-id");
  if(vehicle_type_id == undefined || vehicle_make_id == undefined) {
    HideAjaxLoader();
    return;
  }
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/get/get-vehicle-models.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id
    }
  }).done(function(data){
    
    $("#vehicle_models_list").html(data);
    $("#add_new_vehicle_model").html("");
    $("#add_vehicle_model_field").show();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddVehicleMоdel() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  var vehicle_make_id = $(".selected_vehicle_make a").attr("data-id");
  var vehicle_model = $("#add_vehicle_model_field #add_vehicle_model").val();
  if(vehicle_model == "" || vehicle_model == undefined) {
    alert("Please enter vehicle model!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"vehicles/ajax/add/add-vehicle-model.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id,
    vehicle_model:vehicle_model
    }
  }).done(function(data){
    
    $("#add_new_vehicle_model").append(data);
    if($("#no_records").length) {
      $("#no_records").remove();
    }
    $("#add_vehicle_model_field #add_vehicle_model").val("");
    $("#add_vehicle_model_field #add_vehicle_model").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditVehicleMоdel(vehicle_model_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_model_div = "#vehicle_model"+vehicle_model_id;
  var vehicle_model = $(vehicle_model_div+" .vehicle_model").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/edit/edit-vehicle-model.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_model_id:vehicle_model_id,
    vehicle_model:vehicle_model
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(vehicle_model_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteVehicleMоdel(vehicle_model_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this vehicle model?');
  var vehicle_model_div = "#vehicle_model"+vehicle_model_id;
  if(answer) {
    $.ajax({
    url:"vehicles/ajax/delete/delete-vehicle-model.php",
    type:"POST",
    data:{
      user_access:user_access,
      vehicle_model_id:vehicle_model_id
      }
    }).done(function(data){
      
      $(vehicle_model_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function ChooseClientFromSelect(client_id) {
  $("#client_id").val(client_id);
  $("#client_name").val("");
  LoadVehiclePlatesForClient(client_id);
}

function GetClientCarPlates() {
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_id = $(".selected_client a").attr("data-id");
  var user_name = $(".selected_client a").html();
  //alert(user_company_type+' | '+user_department);return;
  $.ajax({
  url:"vehicles/ajax/get/get-client-car-plates.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id,
    user_name:user_name
    }
  }).done(function(data){
      $("#add_car_plate_field").show();
      $("#add_new_car_plate").html("");
      $("#clients_car_plates").html(data);
      HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  });
}

function AddVehiclePlate() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var user_id = $(".selected_client a").attr("data-id");
  var car_plate = $("#add_car_plate").val();
  if(car_plate == "" || car_plate == undefined) {
    alert("Please enter vehicle plate!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"vehicles/ajax/add/add-car-plate-to-client.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id,
    car_plate:car_plate
    }
  }).done(function(data){
    
    if($("#no_records").length) {
      $("#no_records").remove();
    }
    $("#add_new_car_plate").append(data);
    $("#add_car_plate").val("");
    $("#add_car_plate").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditVehiclePlate(vptc_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_plate_div = "#vehicle_plate"+vptc_id;
  var vehicle_plate = $(vehicle_plate_div+" .vehicle_plate").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"vehicles/ajax/edit/edit-car-plate-to-client.php",
  type:"POST",
  data:{
    user_access:user_access,
    vptc_id:vptc_id,
    vehicle_plate:vehicle_plate
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(vehicle_plate_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteVehiclePlate(vptc_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre make?');
  var vehicle_plate_div = "#vehicle_plate"+vptc_id;
  if(answer) {
    $.ajax({
    url:"vehicles/ajax/delete/delete-car-plate-to-client.php",
    type:"POST",
    data:{
      user_access:user_access,
      vptc_id:vptc_id
      }
    }).done(function(data){
      
      $(vehicle_plate_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreMake() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_make = $("#add_tyre_make #add_tyre_make").val();
  if(tyre_make == "" || tyre_make == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-make.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_make:tyre_make
    }
  }).done(function(data){
    
    $("#add_new_tyre_make").append(data);
    $("#add_tyre_make #add_tyre_make").val("");
    $("#add_tyre_make #add_tyre_make").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreMake(tyre_make_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_make_div = "#tyre_make"+tyre_make_id;
  var tyre_make = $(tyre_make_div+" .tyre_make").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-make.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_make_id:tyre_make_id,
    tyre_make:tyre_make
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_make_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreMake(tyre_make_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre make?');
  var tyre_make_div = "#tyre_make"+tyre_make_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-make.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_make_id:tyre_make_id
      }
    }).done(function(data){
      
      $(tyre_make_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function GetTyreModelsForMake() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_make_id = $(".selected_tyre_make a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/get/get-tyre-models-for-make.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_make_id:tyre_make_id
    }
  }).done(function(data){
    
    $("#add_new_tyre_model").html("");
    $("#tyre_models_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddTyreModel() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_make_id = $(".selected_tyre_make a").attr("data-id");
  var tyre_model = $("#add_tyre_model #add_tyre_model").val();
  if(tyre_model == "" || tyre_model == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-model.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_make_id:tyre_make_id,
    tyre_model:tyre_model
    }
  }).done(function(data){
    
    if($("#no_records").length) {
      $("#no_records").remove();
    }
    $("#add_new_tyre_model").append(data);
    $("#add_tyre_model #add_tyre_model").val("");
    $("#add_tyre_model #add_tyre_model").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreModel(tyre_model_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_model_div = "#tyre_model"+tyre_model_id;
  var tyre_model = $(tyre_model_div+" .tyre_model").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-model.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_model_id:tyre_model_id,
    tyre_model:tyre_model
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_model_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreModel(tyre_model_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre model?');
  var tyre_model_div = "#tyre_model"+tyre_model_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-model.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_model_id:tyre_model_id
      }
    }).done(function(data){
      
      $(tyre_model_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreWidth() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_width = $("#add_tyre_width #add_tyre_width").val();
  var tyre_width_order = $("#add_tyre_width #add_tyre_width_order").val();
  if(tyre_width == "" || tyre_width == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-width.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_width:tyre_width,
    tyre_width_order:tyre_width_order
    }
  }).done(function(data){
    
    $("#add_new_tyre_width").append(data);
    $("#add_tyre_width #add_tyre_width").val("");
    $("#add_tyre_width #add_tyre_width").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreWidth(tyre_width_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_width_div = "#tyre_width"+tyre_width_id;
  var tyre_width = $(tyre_width_div+" .tyre_width").val();
  var tyre_width_order = $(tyre_width_div+" .tyre_width_order").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-width.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_width_id:tyre_width_id,
    tyre_width:tyre_width,
    tyre_width_order:tyre_width_order
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_width_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreWidth(tyre_width_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre make?');
  var tyre_width_div = "#tyre_width"+tyre_width_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-width.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_width_id:tyre_width_id
      }
    }).done(function(data){
      
      $(tyre_width_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreRatio() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_ratio = $("#add_tyre_ratio #add_tyre_ratio").val();
  var tyre_ratio_order = $("#add_tyre_ratio #add_tyre_ratio_order").val();
  if(tyre_ratio == "" || tyre_ratio == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-ratio.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_ratio:tyre_ratio,
    tyre_ratio_order:tyre_ratio_order
    }
  }).done(function(data){
    
    $("#add_new_tyre_ratio").append(data);
    $("#add_tyre_ratio #add_tyre_ratio").val("");
    $("#add_tyre_ratio #add_tyre_ratio").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreRatio(tyre_ratio_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_ratio_div = "#tyre_ratio"+tyre_ratio_id;
  var tyre_ratio = $(tyre_ratio_div+" .tyre_ratio").val();
  var tyre_ratio_order = $(tyre_ratio_div+" .tyre_ratio_order").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-ratio.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_ratio_id:tyre_ratio_id,
    tyre_ratio:tyre_ratio,
    tyre_ratio_order:tyre_ratio_order
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_ratio_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreRatio(tyre_ratio_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre ratio?');
  var tyre_ratio_div = "#tyre_ratio"+tyre_ratio_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-ratio.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_ratio_id:tyre_ratio_id
      }
    }).done(function(data){
      
      $(tyre_ratio_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreDiameter() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_diameter = $("#add_tyre_diameter #add_tyre_diameter").val();
  var tyre_diameter_order = $("#add_tyre_diameter #add_tyre_diameter_order").val();
  if(tyre_diameter == "" || tyre_diameter == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-diameter.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_diameter:tyre_diameter,
    tyre_diameter_order:tyre_diameter_order
    }
  }).done(function(data){
    
    $("#add_new_tyre_diameter").append(data);
    $("#add_tyre_diameter #add_tyre_diameter").val("");
    $("#add_tyre_diameter #add_tyre_diameter").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreDiameter(tyre_diameter_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_diameter_div = "#tyre_diameter"+tyre_diameter_id;
  var tyre_diameter = $(tyre_diameter_div+" .tyre_diameter").val();
  var tyre_diameter_order = $(tyre_diameter_div+" .tyre_diameter_order").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-diameter.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_diameter_id:tyre_diameter_id,
    tyre_diameter:tyre_diameter,
    tyre_diameter_order:tyre_diameter_order
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_diameter_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreDiameter(tyre_diameter_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre diameter?');
  var tyre_diameter_div = "#tyre_diameter"+tyre_diameter_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-diameter.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_diameter_id:tyre_diameter_id
      }
    }).done(function(data){
      
      $(tyre_diameter_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreLoadIndex() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_load_index = $("#add_tyre_load_index #add_tyre_load_index").val();
  var tyre_load_index_order = $("#add_tyre_load_index #add_tyre_load_index_order").val();
  if(tyre_load_index == "" || tyre_load_index == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-load-index.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_load_index:tyre_load_index,
    tyre_load_index_order:tyre_load_index_order
    }
  }).done(function(data){
    
    $("#add_new_tyre_load_index").append(data);
    $("#add_tyre_load_index #add_tyre_load_index").val("");
    $("#add_tyre_load_index #add_tyre_load_index").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreLoadIndex(tyre_load_index_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_load_index_div = "#tyre_load_index"+tyre_load_index_id;
  var tyre_load_index = $(tyre_load_index_div+" .tyre_load_index").val();
  var tyre_load_index_order = $(tyre_load_index_div+" .tyre_load_index_order").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-load-index.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_load_index_id:tyre_load_index_id,
    tyre_load_index:tyre_load_index,
    tyre_load_index_order:tyre_load_index_order
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_load_index_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreLoadIndex(tyre_load_index_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre load index?');
  var tyre_load_index_div = "#tyre_load_index"+tyre_load_index_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-load-index.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_load_index_id:tyre_load_index_id
      }
    }).done(function(data){
      
      $(tyre_load_index_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function AddTyreSpeedIndex() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_speed_index = $("#add_tyre_speed_index #add_tyre_speed_index").val();
  var tyre_speed_index_order = $("#add_tyre_speed_index #add_tyre_speed_index_order").val();
  if(tyre_speed_index == "" || tyre_speed_index == undefined) {
    alert("Please enter vehicle type!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"tyres/ajax/add/add-tyre-speed-index.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_speed_index:tyre_speed_index,
    tyre_speed_index_order:tyre_speed_index_order
    }
  }).done(function(data){
    
    $("#add_new_tyre_speed_index").append(data);
    $("#add_tyre_speed_index #add_tyre_speed_index").val("");
    $("#add_tyre_speed_index #add_tyre_speed_index").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditTyreSpeedIndex(tyre_speed_index_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_speed_index_div = "#tyre_speed_index"+tyre_speed_index_id;
  var tyre_speed_index = $(tyre_speed_index_div+" .tyre_speed_index").val();
  var tyre_speed_index_order = $(tyre_speed_index_div+" .tyre_speed_index_order").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/edit/edit-tyre-speed-index.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_speed_index_id:tyre_speed_index_id,
    tyre_speed_index:tyre_speed_index,
    tyre_speed_index_order:tyre_speed_index_order
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(tyre_speed_index_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteTyreSpeedIndex(tyre_speed_index_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this tyre speed index?');
  var tyre_speed_index_div = "#tyre_speed_index"+tyre_speed_index_id;
  if(answer) {
    $.ajax({
    url:"tyres/ajax/delete/delete-tyre-speed-index.php",
    type:"POST",
    data:{
      user_access:user_access,
      tyre_speed_index_id:tyre_speed_index_id
      }
    }).done(function(data){
      
      $(tyre_speed_index_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function GetTyreWidthsForVehicleType(result_type) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/get/get-tyre-widths-for-vehicle-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    result_type:result_type
    }
  }).done(function(data){
    
    $("#tyre_widths_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddDeleteWidthToVehicleType(tyre_width_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".selected_vehicle_type a").attr("data-id");
  var tyre_width_checkbox = ($("#tyre_width"+tyre_width_id+" .tyre_width_id").is(":checked")) ? "1":"0";
  //alert(tyre_width_checkbox);return;
  $.ajax({
  url:"tyres/ajax/add/add-delete-tyre-width-to-vehicle-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    tyre_width_id:tyre_width_id,
    tyre_width_checkbox:tyre_width_checkbox
    }
  }).done(function(data){
    
    $(".row_over").removeClass("row_over_edit");
    $("#tyre_width"+tyre_width_id+" td").effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetTyreRatiosForWidth(result_type) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_width_id = $(".selected_tyre_width a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/get/get-tyre-ratios-for-width.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_width_id:tyre_width_id,
    result_type:result_type
    }
  }).done(function(data){
    
    $("#tyres_ratios_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddDeleteRatioToWidth(tyre_ratio_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_width_id = $(".selected_tyre_width a").attr("data-id");
  var tyre_ratio_checkbox = ($("#tyre_ratio"+tyre_ratio_id+" .tyre_ratio_id").is(":checked")) ? "1":"0";
  //alert(tyre_ratio_checkbox);return;
  $.ajax({
  url:"tyres/ajax/add/add-delete-ratio-to-width.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_width_id:tyre_width_id,
    tyre_ratio_id:tyre_ratio_id,
    tyre_ratio_checkbox:tyre_ratio_checkbox
    }
  }).done(function(data){
    
    $(".row_over").removeClass("row_over_edit");
    $("#tyre_ratio"+tyre_ratio_id+" td").effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetTyreDiametersForRatio() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_ratio_id = $(".selected_tyre_ratio a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"tyres/ajax/get/get-tyre-diameters-for-ratio.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_ratio_id:tyre_ratio_id
    }
  }).done(function(data){
    
    $("#tyres_diameters_checkboxes").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddDeleteDiameterToRatio(tyre_diameter_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_ratio_id = $(".selected_tyre_ratio a").attr("data-id");
  var tyre_diameter_checkbox = ($("#tyre_diameter"+tyre_diameter_id+" .tyre_diameter_id").is(":checked")) ? "1":"0";
  //alert(tyre_diameter_checkbox);return;
  $.ajax({
  url:"tyres/ajax/add/add-delete-diameter-to-ratio.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_ratio_id:tyre_ratio_id,
    tyre_diameter_id:tyre_diameter_id,
    tyre_diameter_checkbox:tyre_diameter_checkbox
    }
  }).done(function(data){
    
    $(".row_over").removeClass("row_over_edit");
    $("#tyre_diameter"+tyre_diameter_id+" td").effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddWarehouseType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_name = $("#add_warehouse_type #add_warehouse_type_name").val();
  if(warehouse_type_name == "" || warehouse_type_name == undefined) {
    alert("Please enter warehouse type name!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"warehouses/ajax/add/add-warehouse-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_type_name:warehouse_type_name
    }
  }).done(function(data){
    
    $("#add_new_warehouse_type").append(data);
    $("#add_warehouse_type #add_warehouse_type_name").val("");
    $("#add_warehouse_type #add_warehouse_type_name").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditWarehouseType(warehouse_type_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_div = "#warehouse_type"+warehouse_type_id;
  var warehouse_type_name = $(warehouse_type_div+" .warehouse_type_name").val();
  //alert(friendly_url);return;
  $.ajax({
  url:"warehouses/ajax/edit/edit-warehouse-type.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_type_id:warehouse_type_id,
    warehouse_type_name:warehouse_type_name
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(warehouse_type_div).effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteWarehouseType(warehouse_type_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this warehouse type?');
  var warehouse_type_div = "#warehouse_type"+warehouse_type_id;
  if(answer) {
    $.ajax({
    url:"warehouses/ajax/delete/delete-warehouse-type.php",
    type:"POST",
    data:{
      user_access:user_access,
      warehouse_type_id:warehouse_type_id
      }
    }).done(function(data){
      
      $(warehouse_type_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function GetWarehousesPlaces() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_id = $(".selected_warehouse_type a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"warehouses/ajax/get/get-warehouses-places.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_type_id:warehouse_type_id
    }
  }).done(function(data){
    
    $("#warehouses_places").html(data);
    $("#add_new_warehouse_place").html("");
    $("#add_warehouse_place_field").show();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function AddWarehouse() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_id = $(".selected_warehouse_type a").attr("data-id");
  var warehouse_name = $("#add_warehouse_place_field #add_warehouse_name").val();
  var warehouse_address = $("#add_warehouse_place_field #add_warehouse_address").val();
  var warehouse_info = $("#add_warehouse_place_field #add_warehouse_info").val();
  var warehouse_phone = $("#add_warehouse_place_field #add_warehouse_phone").val();
  if(warehouse_name == "" || warehouse_name == undefined) {
    alert("Please enter warehouse name!");
    HideAjaxLoader();
    return;
  }
  //alert(menu_parent_level);return;
  $.ajax({
  url:"warehouses/ajax/add/add-warehouse.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_type_id:warehouse_type_id,
    warehouse_name:warehouse_name,
    warehouse_address:warehouse_address,
    warehouse_info:warehouse_info,
    warehouse_phone:warehouse_phone
    }
  }).done(function(data){
    
    $("#add_new_warehouse_place").append(data);
    for(var i=0; i<= $("#add_warehouse_place_field input").length; i++) {
      $("#add_warehouse_place_field input").val("");
    }
    $("#add_warehouse_place_field #add_warehouse_name").focus();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditWarehouse(warehouse_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_div = "#warehouse"+warehouse_id;
  var warehouse_name = $(warehouse_div+" .warehouse_name").val();
  var warehouse_address = $(warehouse_div+" .warehouse_address").val();
  var warehouse_info = $(warehouse_div+" .warehouse_info").val();
  var warehouse_phone = $(warehouse_div+" .warehouse_phone").val()
  //alert(friendly_url);return;
  $.ajax({
  url:"warehouses/ajax/edit/edit-warehouse.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_id:warehouse_id,
    warehouse_name:warehouse_name,
    warehouse_address:warehouse_address,
    warehouse_info:warehouse_info,
    warehouse_phone:warehouse_phone
    }
  }).done(function(){
    
    $(".row_over").removeClass("row_over_edit");
    $(warehouse_div+" td").effect("highlight", {}, 3000);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function DeleteWarehouse(warehouse_id) {
  if(CheckDeleteRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var answer = confirm('Are you sure you want to delete this warehouse place?');
  var warehouse_div = "#warehouse"+warehouse_id;
  if(answer) {
    $.ajax({
    url:"warehouses/ajax/delete/delete-warehouse.php",
    type:"POST",
    data:{
      user_access:user_access,
      warehouse_id:warehouse_id
      }
    }).done(function(){
      
      $(warehouse_div).remove();
      
      HideAjaxLoader();
    }).fail(function(error){
      console.log(error);
    })
  }
  else {
    HideAjaxLoader();
    return;
  }
}

function LoadVehiclePlatesForClient(user_id) {
  ShowAjaxLoader();
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  //alert(user_company_type+' | '+user_department);return;
  $.ajax({
  url:"protocols/ajax/get/get-client-car-plates-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    user_id:user_id
    }
  }).done(function(data){
      if(data == "no_plates") {
        $("#vehicle_plate_select").hide();
        $("#vehicle_plate").val("");
        $("#vehicle_plate").show();
      }
      else {
        $("#vehicle_plate").hide();
        $("#vehicle_plate").val("");
        $("#vehicle_plate_select").html(data);
        $("#vehicle_plate_select").show();
      } 
      HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  });
}

function LoadVehicleMakesForTypeInSelect() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".vehicle_type.active").attr("data-id");
  //alert(vehicle_type_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-vehicle-makes-for-type-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id
    }
  }).done(function(data){
    
    $("#vehicle_make").html(data);
    $("#vehicle_model_default").show();
    $("#vehicle_model").hide();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function LoadTyreWidthsForVehicleType() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".vehicle_type.active").attr("data-id");
  //alert(vehicle_type_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-tyre-widths-for-vehicle-type-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id
    }
  }).done(function(data){
    
    $(".tyre_width_default").hide();
    $(".tyre_width").html(data);
    $(".tyre_width").show();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function LoadVehicleModelsForMakeInSelect() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var vehicle_type_id = $(".vehicle_type.active").attr("data-id");
//  var vehicle_type_id = 1; // car
  var vehicle_make_id = "";
  if($("#search_vehicle_make").length) {
    vehicle_make_id = $("#search_vehicle_make").val();
  }
  else {
    vehicle_make_id = $("#vehicle_make").val();
    if(vehicle_make_id == '0') {
      $("#vehicle_model_default").show();
      $("#vehicle_model").hide();
      HideAjaxLoader();
      return;
    }
  }
  //alert(vehicle_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-vehicle-models-for-make-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id
    }
  }).done(function(data){
    
    if($("#search_vehicle_model").length) {
      $("#search_vehicle_model").html(data);
    }
    else {
      $("#vehicle_model_default").hide();
      $("#vehicle_model").show();
      $("#vehicle_model").html(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function LoadTyresModelsForMakeInSelect(tyre_position_code) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var copy_to_next_forms = ($("#copy_to_next_forms").is(":checked") ? "1" : "0");
  var tyre_make_id = $("#tyres_form_"+tyre_position_code+" .tyre_make").val();
  if(tyre_make_id == '0') {
    $("#tyres_form_"+tyre_position_code+" .vehicle_model_default").show();
    $("#tyres_form_"+tyre_position_code+" .vehicle_model").hide();
    $("#tyres_form_"+tyre_position_code+" .ajax_loader").hide();
    HideAjaxLoader();
    return;
  }
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-tyre-models-for-make-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_make_id:tyre_make_id
    }
  }).done(function(data){
    
    if(copy_to_next_forms == "1") {
      $(".tyre_form.active .tyre_make").val(tyre_make_id);
      $(".tyre_model_default").hide();
      $(".tyre_model").show();
      $(".tyre_model").html(data);
    }
    else {
      $("#tyres_form_"+tyre_position_code+" .tyre_model_default").hide();
      $("#tyres_form_"+tyre_position_code+" .tyre_model").show();
      $("#tyres_form_"+tyre_position_code+" .tyre_model").html(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function LoadTyreRatiosForWidthInSelect(tyre_position_code) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var copy_to_next_forms = ($("#copy_to_next_forms").is(":checked") ? "1" : "0");
  var tyre_width_id = $("#tyres_form_"+tyre_position_code+" .tyre_width").val();
  if(tyre_width_id == '0') {
    $("#tyres_form_"+tyre_position_code+" .tyre_model_default").show();
    $("#tyres_form_"+tyre_position_code+" .tyre_model").hide();
    $("#tyres_form_"+tyre_position_code+" .ajax_loader").hide();
    HideAjaxLoader();
    return;
  }
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-tyre-ratios-for-width-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_width_id:tyre_width_id
    }
  }).done(function(data){
    
    if(copy_to_next_forms == "1") {
      $(".tyre_form.active .tyre_width").val(tyre_width_id);
      $(".tyre_ratio_default").hide();
      $(".tyre_ratio").show();
      $(".tyre_ratio").html(data);
    }
    else {
      $("#tyres_form_"+tyre_position_code+" .tyre_ratio_default").hide();
      $("#tyres_form_"+tyre_position_code+" .tyre_ratio").show();
      $("#tyres_form_"+tyre_position_code+" .tyre_ratio").html(data);
    } 
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function LoadTyreDiametersForRatioInSelect(tyre_position_code) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var copy_to_next_forms = ($("#copy_to_next_forms").is(":checked") ? "1" : "0");
  var tyre_ratio_id = $("#tyres_form_"+tyre_position_code+" .tyre_ratio").val();
  if(tyre_ratio_id == '0') {
    $("#tyres_form_"+tyre_position_code+" .tyre_model_default").show();
    $("#tyres_form_"+tyre_position_code+" .tyre_model").hide();
    $("#tyres_form_"+tyre_position_code+" .ajax_loader").hide();
    HideAjaxLoader();
    return;
  }
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-tyre-diameters-for-ratio-for-select.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_ratio_id:tyre_ratio_id
    }
  }).done(function(data){
    
    if(copy_to_next_forms == "1") {
      $(".tyre_form.active .tyre_ratio").val(tyre_ratio_id);
      $(".tyre_diameter_default").hide();
      $(".tyre_diameter").show();
      $(".tyre_diameter").html(data);
    }
    else {
      $("#tyres_form_"+tyre_position_code+" .tyre_diameter_default").hide();
      $("#tyres_form_"+tyre_position_code+" .tyre_diameter").show();
      $("#tyres_form_"+tyre_position_code+" .tyre_diameter").html(data);
    }
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function ShowProtocolForComfirmationBeforeSaving() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_storage_id = $("#tyre_storage_id").val();
  var client_id = $("#client_id").val();
  var client_name = $("#client_name").val();
  var client_error = $("#client_error").val();
  var vehicle_plate_error = $("#vehicle_plate_error").val();
  if(client_id == undefined || client_id == "") {
    HideAjaxLoader();
    alert(client_error);
    $("#client_id").focus();
    return;
  }
  var vehicle_type = $(".vehicle_type.active").html();
  var vehicle_make = $("#vehicle_make option:selected").text();
  var vehicle_model = $("#vehicle_model option:selected").text();
  var vehicle_plate = $("#vehicle_plate").val();
  if(vehicle_plate == undefined || vehicle_plate == "") {
    vehicle_plate = $("#vehicle_plate_select option:selected").text();
  }
  if(vehicle_plate == undefined || vehicle_plate == "") {
    HideAjaxLoader();
    alert(vehicle_plate_error);
    $("#vehicle_plate").focus();
    return;
  }
  var tyre_positions = [];
  var tyre_makes = [];
  var tyre_models = [];
  var tyre_seasons = [];
  var tyre_widths = [];
  var tyre_ratios = [];
  var tyre_diameters = [];
  var tyre_load_indexes = [];
  var tyre_speed_indexes = [];
  var tyre_dots = [];
  var tyre_grapple_depths = [];
  var tyre_defects = [];
  var tyre_has_rim = [];
  var tyre_rim_note = [];
  var i = 0;
  $.each($(".tyre_form.active"), function(){   
      var tyres_form_id = "#"+$(this).attr("id");
      tyre_positions[i] = $(tyres_form_id+" legend").html();
      tyre_makes[i] = $(tyres_form_id+" .tyre_make option:selected").text();
      tyre_models[i] = $(tyres_form_id+" .tyre_model option:selected").text();
      tyre_seasons[i] = $(tyres_form_id+" .tyre_seasons.active").html();
      tyre_widths[i] = $(tyres_form_id+" .tyre_width option:selected").text();
      tyre_ratios[i] = $(tyres_form_id+" .tyre_ratio option:selected").text();
      tyre_diameters[i] = $(tyres_form_id+" .tyre_diameter option:selected").text();
      tyre_load_indexes[i] = $(tyres_form_id+" .tyre_load_index option:selected").text();
      tyre_speed_indexes[i] = $(tyres_form_id+" .tyre_speed_index option:selected").text();
      tyre_dots[i] = $(tyres_form_id+" .tyre_dot").val();
      tyre_grapple_depths[i] = $(tyres_form_id+" .tyre_grapple_depth").val();
      tyre_defects[i] = $(tyres_form_id+" .tyre_defects").val();
      tyre_has_rim[i] = ($(tyres_form_id+" .tyre_has_rim").is(":checked") ? "1" : "0");
      tyre_rim_note[i] = $(tyres_form_id+" .tyre_rim_note").val();
      i++;
  });
  var date_insert = $("#date_insert").val();
  var warehouse_name = $("#warehouse_name").val();
  var tyre_note = $("#tyre_note").val();
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/add/show-protocol-for-comfirmation-before-saving.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    client_name:client_name,
    vehicle_type:vehicle_type,
    vehicle_make:vehicle_make,
    vehicle_model:vehicle_model,
    vehicle_plate:vehicle_plate,
    tyre_positions:tyre_positions,
    tyre_makes:tyre_makes,
    tyre_models:tyre_models,
    tyre_seasons:tyre_seasons,
    tyre_widths:tyre_widths,
    tyre_ratios:tyre_ratios,
    tyre_diameters:tyre_diameters,
    tyre_load_indexes:tyre_load_indexes,
    tyre_speed_indexes:tyre_speed_indexes,
    tyre_dots:tyre_dots,
    tyre_grapple_depths:tyre_grapple_depths,
    tyre_defects:tyre_defects,
    tyre_has_rim:tyre_has_rim,
    tyre_rim_note:tyre_rim_note,
    date_insert:date_insert,
    warehouse_name:warehouse_name,
    tyre_note:tyre_note
    }
  }).done(function(data){
    
    $("#modal_window").html(data);
    CalculateModalWindowSize();
    $("#modal_window_backgr").show();
    $("#modal_window").show();
    $("#modal_window .close").click(function() {
      $("#modal_window_backgr").hide();
      $("#modal_window").hide().html("");
    });
    //alert(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function PrintProtocol() {
  var printContents = document.getElementById("printable_area").innerHTML;
  $("#modal_window_backgr").hide();
  $("#modal_window").hide().html("");
  window.location = "protocols-take-in";
  //alert(printContents);
  var url = "/protocols/print-protocol.php?printContents="+printContents;
  window.open(url,'mywindow','status=no,location=no,resizable=yes,scrollbars=yes,width=950,height=800,left=0,top=0,screenX=0,screenY=0');
}

function PrintProtocolById(tyre_storage_id) {
  var printContents = $(".printable_area_01_"+tyre_storage_id).html();
  printContents += $(".printable_area_02_"+tyre_storage_id).html();
  window.location = "protocols-view";
  //alert(printContents);
  var url = "/protocols/print-protocol.php?printContents="+printContents;
  window.open(url,'mywindow','status=no,location=no,resizable=yes,scrollbars=yes,width=950,height=800,left=0,top=0,screenX=0,screenY=0');
}

function AddTyresToWarehouse() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_storage_id = $("#tyre_storage_id").val();
  var client_id = $("#client_id").val();
  var client_error = $("#client_error").val();
  var vehicle_plate_error = $("#vehicle_plate_error").val();
  if(client_id == undefined || client_id == "") {
    HideAjaxLoader();
    alert(client_error);
    $("#client_id").focus();
    return;
  }
  var vehicle_type_id = $(".vehicle_type.active").attr("data-id");
  var vehicle_make_id = $("#vehicle_make").val();
  var vehicle_model_id = $("#vehicle_model").val();
  var vehicle_plate = $("#vehicle_plate").val();
  if(vehicle_plate == undefined || vehicle_plate == "") {
    vehicle_plate = $("#vehicle_plate_select option:selected").text();
  }
  if(vehicle_plate == undefined || vehicle_plate == "") {
    HideAjaxLoader();
    alert(vehicle_plate_error);
    $("#vehicle_plate").focus();
    return;
  }
  var tyre_position_ids = [];
  var tyre_make_ids = [];
  var tyre_model_ids = [];
  var tyre_season_ids = [];
  var tyre_width_ids = [];
  var tyre_ratio_ids = [];
  var tyre_diameter_ids = [];
  var tyre_load_index_ids = [];
  var tyre_speed_index_ids = [];
  var tyre_dots = [];
  var tyre_grapple_depths = [];
  var tyre_defects = [];
  var tyre_has_rim = [];
  var tyre_rim_note = [];
  var i = 0;
  $.each($(".tyre_form.active"), function(){   
      var tyres_form_id = "#"+$(this).attr("id");
      tyre_position_ids[i] = $(this).attr("tyre-position-id");
      tyre_make_ids[i] = $(tyres_form_id+" .tyre_make").val();
      tyre_model_ids[i] = $(tyres_form_id+" .tyre_model").val();
      tyre_season_ids[i] = $(tyres_form_id+" .tyre_seasons.active").attr("data-id");
      tyre_width_ids[i] = $(tyres_form_id+" .tyre_width").val();
      tyre_ratio_ids[i] = $(tyres_form_id+" .tyre_ratio").val();
      tyre_diameter_ids[i] = $(tyres_form_id+" .tyre_diameter").val();
      tyre_load_index_ids[i] = $(tyres_form_id+" .tyre_load_index").val();
      tyre_speed_index_ids[i] = $(tyres_form_id+" .tyre_speed_index").val();
      tyre_dots[i] = $(tyres_form_id+" .tyre_dot").val();
      tyre_grapple_depths[i] = $(tyres_form_id+" .tyre_grapple_depth").val();
      tyre_defects[i] = $(tyres_form_id+" .tyre_defects").val();
      tyre_has_rim[i] = ($(tyres_form_id+" .tyre_has_rim").is(":checked") ? "1" : "0");
      tyre_rim_note[i] = $(tyres_form_id+" .tyre_rim_note").val();
      i++;
  });
  var date_insert = $("#date_insert").val();
  var warehouse_id = $("#warehouse_id").val();
  var tyre_note = $("#tyre_note").val();
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/add/add-tyre-protocol.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    client_id:client_id,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id,
    vehicle_model_id:vehicle_model_id,
    vehicle_plate:vehicle_plate,
    tyre_position_ids:tyre_position_ids,
    tyre_make_ids:tyre_make_ids,
    tyre_model_ids:tyre_model_ids,
    tyre_season_ids:tyre_season_ids,
    tyre_width_ids:tyre_width_ids,
    tyre_ratio_ids:tyre_ratio_ids,
    tyre_diameter_ids:tyre_diameter_ids,
    tyre_load_index_ids:tyre_load_index_ids,
    tyre_speed_index_ids:tyre_speed_index_ids,
    tyre_dots:tyre_dots,
    tyre_grapple_depths:tyre_grapple_depths,
    tyre_defects:tyre_defects,
    tyre_has_rim:tyre_has_rim,
    tyre_rim_note:tyre_rim_note,
    date_insert:date_insert,
    warehouse_id:warehouse_id,
    tyre_note:tyre_note
    }
  }).done(function(message){
    
    //alert(message);
    $("#choice_btns a").hide();
    $("#message").html(message);
    $("#choice_btns #print_protocol").show();
    //$("#modal_window_backgr").hide();
    //$("#modal_window").hide().html("");
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function EditProtocol(tyre_storage_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var client_id = $("#client_id").val();
  var client_error = $("#client_error").val();
  var vehicle_plate_error = $("#vehicle_plate_error").val();
  if(client_id == undefined || client_id == "") {
    HideAjaxLoader();
    alert(client_error);
    $("#client_id").focus();
    return;
  }
  var vehicle_type_id = $(".vehicle_type.active").attr("data-id");
  var vehicle_make_id = $("#vehicle_make").val();
  var vehicle_model_id = $("#vehicle_model").val();
  var vehicle_plate = $("#vehicle_plate").val();
  if(vehicle_plate == undefined || vehicle_plate == "") {
    vehicle_plate = $("#vehicle_plate_select option:selected").text();
    //alert(vehicle_plate);return;
  }
  if(vehicle_plate == undefined || vehicle_plate == "") {
    HideAjaxLoader();
    alert(vehicle_plate_error);
    $("#vehicle_plate").focus();
    return;
  }
  var tyre_storage_details_ids = [];
  var tyre_position_ids = [];
  var tyre_make_ids = [];
  var tyre_model_ids = [];
  var tyre_season_ids = [];
  var tyre_width_ids = [];
  var tyre_ratio_ids = [];
  var tyre_diameter_ids = [];
  var tyre_load_index_ids = [];
  var tyre_speed_index_ids = [];
  var tyre_dots = [];
  var tyre_grapple_depths = [];
  var tyre_defects = [];
  var tyre_has_rim = [];
  var tyre_rim_note = [];
  var i = 0;
  $.each($(".tyre_form.active"), function(){   
      var tyres_form_id = "#"+$(this).attr("id");
      tyre_storage_details_ids[i] = $(this).attr("tyre-storage-details-id");
      tyre_position_ids[i] = $(this).attr("tyre-position-id");
      tyre_make_ids[i] = $(tyres_form_id+" .tyre_make").val();
      tyre_model_ids[i] = $(tyres_form_id+" .tyre_model").val();
      tyre_season_ids[i] = $(tyres_form_id+" .tyre_seasons.active").attr("data-id");
      tyre_width_ids[i] = $(tyres_form_id+" .tyre_width").val();
      tyre_ratio_ids[i] = $(tyres_form_id+" .tyre_ratio").val();
      tyre_diameter_ids[i] = $(tyres_form_id+" .tyre_diameter").val();
      tyre_load_index_ids[i] = $(tyres_form_id+" .tyre_load_index").val();
      tyre_speed_index_ids[i] = $(tyres_form_id+" .tyre_speed_index").val();
      tyre_dots[i] = $(tyres_form_id+" .tyre_dot").val();
      tyre_grapple_depths[i] = $(tyres_form_id+" .tyre_grapple_depth").val();
      tyre_defects[i] = $(tyres_form_id+" .tyre_defects").val();
      tyre_has_rim[i] = ($(tyres_form_id+" .tyre_has_rim").is(":checked") ? "1" : "0");
      tyre_rim_note[i] = $(tyres_form_id+" .tyre_rim_note").val();
      i++;
  });
  var date_insert = $("#date_insert").val();
  var warehouse_id = $("#warehouse_id").val();
  var tyre_note = $("#tyre_note").val();
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/edit/edit-tyre-protocol.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    client_id:client_id,
    vehicle_type_id:vehicle_type_id,
    vehicle_make_id:vehicle_make_id,
    vehicle_model_id:vehicle_model_id,
    vehicle_plate:vehicle_plate,
    tyre_position_ids:tyre_position_ids,
    tyre_storage_details_ids:tyre_storage_details_ids,
    tyre_make_ids:tyre_make_ids,
    tyre_model_ids:tyre_model_ids,
    tyre_season_ids:tyre_season_ids,
    tyre_width_ids:tyre_width_ids,
    tyre_ratio_ids:tyre_ratio_ids,
    tyre_diameter_ids:tyre_diameter_ids,
    tyre_load_index_ids:tyre_load_index_ids,
    tyre_speed_index_ids:tyre_speed_index_ids,
    tyre_dots:tyre_dots,
    tyre_grapple_depths:tyre_grapple_depths,
    tyre_defects:tyre_defects,
    tyre_has_rim:tyre_has_rim,
    tyre_rim_note:tyre_rim_note,
    date_insert:date_insert,
    warehouse_id:warehouse_id,
    tyre_note:tyre_note
    }
  }).done(function(data){
    
    alert(data);
    window.location = window.location.href;
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetProtocolDetails(tyre_storage_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-protocol-details.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id
    }
  }).done(function(data){
    
    $(".protocols_row").removeClass("row_over_edit");
    $(".row_over_"+tyre_storage_id).addClass("row_over_edit");
    $("#protocol_details").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetProtocolDetailsForDelivery(tyre_storage_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  //alert(tyre_make_id);return;
  $.ajax({
  url:"protocols/ajax/get/get-protocol-details-for-delivery.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id
    }
  }).done(function(data){
    
    $(".protocols_row").removeClass("row_over_edit");
    $(".row_over_"+tyre_storage_id).addClass("row_over_edit");
    $("#protocol_details").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function SearchProtocolForDelivery() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_storage_id = $("#search_tyre_storage_id").val();
  var vehicle_plate = $("#search_vehicle_plate").val();
  var warehouse_id = $("#search_warehouse_id").val();
  var employer_id = $("#search_employer_id").val();
  var vehicle_make_id = $("#search_vehicle_make").val();
  var vehicle_model_id = $("#search_vehicle_model").val();
  var tyre_storage_date = $("#search_tyre_storage_date").val();
  $.ajax({
  url:"protocols/ajax/get/search-protocol-for-delivery.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    vehicle_plate:vehicle_plate,
    warehouse_id:warehouse_id,
    employer_id:employer_id, // user_id
    vehicle_make_id:vehicle_make_id,
    vehicle_model_id:vehicle_model_id,
    tyre_storage_date:tyre_storage_date
    }
  }).done(function(protocols_list){
    
    $("#protocol_details").html("");
    $("#protocols_list").html(protocols_list);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function SearchProtocol() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var tyre_storage_id = $("#search_tyre_storage_id").val();
  var vehicle_plate = $("#search_vehicle_plate").val();
  var warehouse_id = $("#search_warehouse_id").val();
  var employer_id = $("#search_employer_id").val();
  var vehicle_make_id = $("#search_vehicle_make").val();
  var vehicle_model_id = $("#search_vehicle_model").val();
  var tyre_storage_date = $("#search_tyre_storage_date").val();
  $.ajax({
  url:"protocols/ajax/get/search-protocol.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    vehicle_plate:vehicle_plate,
    warehouse_id:warehouse_id,
    employer_id:employer_id, // user_id
    vehicle_make_id:vehicle_make_id,
    vehicle_model_id:vehicle_model_id,
    tyre_storage_date:tyre_storage_date
    }
  }).done(function(protocols_list){
    
    $("#protocol_details").html("");
    $("#protocols_list").html(protocols_list);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetWarehousesPlacesForStorage() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_id = $(".selected_warehouse_type a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"transfer/ajax/get/get-warehouses-places.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_type_id:warehouse_type_id
    }
  }).done(function(data){
    
    $("#warehouses_list").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function GetWarehousesPlacesWithTyresStorage() {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_id = $(".selected_warehouse a").attr("data-id");
  //alert(friendly_url);return;
  $.ajax({
  url:"transfer/ajax/get/get-warehouses-places-with-tyres-storage.php",
  type:"POST",
  data:{
    user_access:user_access,
    warehouse_id:warehouse_id
    }
  }).done(function(data){
    
    $("#warehouses_storages").html(data);
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}

function MoveTyresFromWarehouseToWarehouse(tyre_storage_id) {
  if(CheckEditRights() === false) return;
  ShowAjaxLoader();
  
  var user_access = $(".second_menu .active .active .third_menu_link").attr("users-rights-access");
  if(user_access == undefined) { user_access = $(".second_menu .active .second_menu_link").attr("users-rights-access");}
  if(user_access == undefined) { user_access = $("#menu .selected .active_first_level").attr("users-rights-access");}
  var warehouse_type_name_from = $(".selected_warehouse_type a").html();
  var warehouse_name_from = $(".selected_warehouse a").html();
  var warehouse_id = $("#warehouse"+tyre_storage_id+" .warehouse_id").val();
  var warehouse_name_to = $("#warehouse"+tyre_storage_id+" .warehouse_id option:selected").text();
  //alert(friendly_url);return;
  $.ajax({
  url:"transfer/ajax/edit/edit-tyres-storage-warehouse.php",
  type:"POST",
  data:{
    user_access:user_access,
    tyre_storage_id:tyre_storage_id,
    warehouse_id:warehouse_id,
    warehouse_type_name_from:warehouse_type_name_from,
    warehouse_name_from:warehouse_name_from,
    warehouse_name_to:warehouse_name_to
    }
  }).done(function(data){
    
    alert(data);
    $("#warehouse"+tyre_storage_id).remove();
    
    HideAjaxLoader();
  }).fail(function(error){
    console.log(error);
  })
}
