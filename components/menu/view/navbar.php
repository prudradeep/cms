<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*View:		Navbar View
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper grey lighten-5">
        <a href="<?=BASE?>" class="brand-logo"><img src="<?=BASE.COMP_PATH.'/'.$this->component?>/assets/images/logo.png"/></a>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
      	<ul class="right hide-on-med-and-down">
          <?php
	      	foreach ($data as $key => $value) {
	      		if(count($value['children'])>=1){
	      			echo "<li><a class='dropdown' href='#!' data-activates='dropdown_{$value['id']}'>{$value['name']}<i class='material-icons right'>keyboard_arrow_down</i></a></li>";
	      			echo $this->createDropdown($value['children'], $value['id'],true);
	      		}else if(strpos($value['link'], '#')===false)
	      			echo "<li><a href='".BASE."{$value['link']}'>{$value['name']}</a></li>";
            else
              echo "<li><a href='{$value['link']}'>{$value['name']}</a></li>";
	      	}
	      	?>
        </ul>
        <ul id="slide-out" class="side-nav">
        	<?php
	      	foreach ($data as $key => $value) {
	      		if(count($value['children'])>=1){
	      			echo $this->createSideDropdown($value['children'], $value['name']);
	      		}else if(strpos($value['link'], '#')===false)
	      			echo "<li><a href='".BASE."{$value['link']}'>{$value['name']}</a></li>";
            else
              echo "<li><a href='{$value['link']}'>{$value['name']}</a></li>";
	      	}
	      	?>
    	</ul>
      </div>
    </nav>
  </div>
<script>
$('.dropdown').dropdown({
    hover: false,
    belowOrigin: true,
  });
$('.dropdown-button2').dropdown({
    hover: true,
    gutter: ($('.dropdown').width()*3)/2 + 5
});
$('.button-collapse').sideNav({
      menuWidth: 300,
      closeOnClick: true
    }
);
$('.collapsible').collapsible();
</script>