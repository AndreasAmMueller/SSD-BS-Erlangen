<?php

/**
 * menu.php
 *
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

namespace AMWD;

/**
 * A class to handle the Menu.
 *
 * @package    AMWD
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 * @version    v1.0-20160425 | stable
 */

class Menu
{
	/**
	 * Contains all Menus of the navbar.
	 * @var MenuNode[]
	 */
	private $menu;
	
	/**
	 * Indicates whether the menu is oriented to the right.
	 * @var bool
	 */
	private $right;
	
	/**
	 * Initializes a new instance of the Menu class.
	 * 
	 * @param  boolean  [$floatRight  = false]  A value indicating whether the menu is right oriented.
	 */
	function __construct($floatRight = false)
	{
		$this->right = $floatRight;
		$this->menu = array();
	}
	
	/**
	 * Returning a complete menu for bootstrap.
	 * 
	 * @return string The menu with HTML structure.
	 */
	function __toString()
	{
		return $this->build();
	}
	
	/**
	 * Sets the flag for a right oriented menu.
	 * 
	 * @param boolean [$floatRight  = true] A value indicating whether the menu is right oriented.
	 */
	public function setFloatRight($floatRight = true)
	{
		$this->right = $floatRight;
	}
	
	/**
	 * Adds a menu element to the menu.
	 * 
	 * @param  string   $name               The visible title of the menu element.
	 * @param  string   $link               The link to the site.
	 * @param  boolean  [$active  = false]  A value indicating whether the menu element is active.
	 * @return MenuNode                     The new created node to add submenu elements.
	 */
	public function addNode($name, $link, $active = false)
	{
		$node = new MenuNode($name, $link, $active);
		$this->menu[] = $node;
		return $node;
	}
	
	/**
	 * Creates the HTML structure of the menu. Bootstrap compatible.
	 * 
	 * @return string HTML structure of the menu.
	 */
	private function build()
	{
		$menu = '<ul class="nav navbar-nav'.($this->right ? ' navbar-right' : '').'">';
		
		foreach ($this->menu as $node)
		{
			$menu .= $node;
		}
		
		$menu .= '</ul>';
		
		return $menu;
	}
}

/**
 * A class to handle a menu element.
 *
 * @package    AMWD
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 * @version    v1.0-20160425 | stable
 */

class MenuNode
{
	/**
	 * The Url to the destination.
	 * @var string
	 */
	private $link;
	
	/**
	 * The visible text for the menu element.
	 * @var string
	 */
	private $name;
	
	/**
	 * Flag if the menu element is active.
	 * @var boolean
	 */
	private $active;
	
	/**
	 * An array with MenuNodes as submenu.
	 * @var MenuNode[]
	 */
	private $submenu;
	
	/**
	 * Initializes a new instance of MenuNode class.
	 * 
	 * @param  string   $name               The visible title of the menu element.
	 * @param  string   $link               The link to the site.
	 * @param  boolean  [$active  = false]  A value indicating whether the menu element is active.
	 */
	function __construct($name, $link, $active = false)
	{
		$this->link = $link;
		$this->name = $name;
		$this->active = $active;
		$this->submenu = array();
	}
	
	/**
	 * Returning a menu element with its submenu.
	 * 
	 * @return  string  HTML structure of an menu element.
	 */
	function __toString()
	{
		return $this->build();
	}
	
	/**
	 * Sets the menu element active or inactive.
	 * 
	 * @param  boolean  [$active  = true]  A value indicating whether the menu element is active.
	 */
	public function setActive($active = true)
	{
		$this->active = $active;
	}
	
	/**
	 * Adds a menu element as submenu.
	 * 
	 * @param  MenuNode  $node  A submenu element.
	 */
	public function addSubmenu(MenuNode $node)
	{
		$this->submenu[] = $node;
	}
	
	/**
	 * Creates the HTML structure of the element with its submenu.
	 * 
	 * @return string HTML structure of the menu element.
	 */
	private function build()
	{
		$class = $this->active ? 'active' : '';
		if (count($this->submenu) > 0 && !empty($class))
			$class .= ' ';
		if (count($this->submenu) > 0)
			$class .= 'dropdown';
		
		$node = empty($class) ? '<li>' : '<li class="'.$class.'">';
		
		if (strpos($class, 'dropdown') === false)
		{
			$node .= '<a href="'.$this->link.'">'.$this->name.'</a>';
		}
		else
		{
			$node .= '<a href="'.$this->link.'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$this->name.' <span class="caret"></span></a>';
		}
		
		if (count($this->submenu) > 0)
		{
			$node .= '<ul class="dropdown-menu">';
			foreach ($this->submenu as $sub)
			{
				$node .= $sub;
			}
			$node .= '</ul>';
		}
		
		$node .= '</li>';
		
		return $node;
	}
}

?>