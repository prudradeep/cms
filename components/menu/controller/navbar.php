<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Menu
*Class:		Navbar
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Navbar extends Controller{
	
	function main(){
		$Menus = $this->helper->model('menus');
		//$menu = $Menus->select('id','parent','name','link')->Eq('type','top')->andEq('status',1)->Order('parent','ASC')->Order('_order', 'ASC')->prepare()->execute()->fetchAll();
		$menu = $Menus->select('id','parent','display_name as name','link')->Isnotnull('JSON_SEARCH(user_access, "one", '.$_SESSION[SESSION_USER].')')->orIsnotnull('JSON_SEARCH(user_access, "one", "*")')->Order('parent','ASC')->Order('_order', 'ASC')->prepare()->execute()->fetchAll();
		$menu = $this->helper->buildTree($menu);
		$this->view('navbar', $menu);
	}

	protected function createDropdown($array, $id, $parent=false){
		$menu = "<ul id='dropdown_{$id}' class='dropdown-content ";
		$menu .= $parent?'autowidth':'';
		$menu .= "'>";
	    foreach ($array as $key => $value) {
	  		if(count($value['children'])>=1){
	  			$menu .= "<li><a class='dropdown-button2' href='#!' data-activates='dropdown_{$value['id']}'>{$value['name']}<i class='material-icons right'>keyboard_arrow_right</i></a></li>";
	  			$menu .= self::createDropdown($value['children'], $value['id']);
	  		}else if(strpos($value['link'], '#')===false)
	  			$menu .= "<li><a href='".BASE."{$value['link']}'>{$value['name']}</a></li>";
	  		else
	  			$menu .= "<li><a href='{$value['link']}'>{$value['name']}</a></li>";
	  	}
		$menu .= "</ul>";
		return $menu;
	}

	protected function createSideDropdown($array, $name){
		$menu = "<li class='no-padding'><ul class='collapsible collapsible-accordion'><li><a class='collapsible-header'>{$name}<i class='material-icons right'>keyboard_arrow_down</i></a><div class='collapsible-body'><ul>";
	    foreach ($array as $key => $value) {
	  		if(count($value['children'])>=1){
	  			$menu .= self::createSideDropdown($value['children'], $value['name']);
	  		}else if(strpos($value['link'], '#')===false)
	  			$menu .= "<li><a href='".BASE."{$value['link']}'>{$value['name']}</a></li>";
	  		else
	  			$menu .= "<li><a href='{$value['link']}'>{$value['name']}</a></li>";
	  	}
		$menu .= "</ul></div></li></ul></li>";
		return $menu;
	}

}