<?php

/**
 * page.php
 *
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

namespace AMWD;

/**
 * A class to handle the page layout.
 *
 * @package    AMWD
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 * @version    v1.0-20160425 | stable
 */

class Page
{
	/**
	 * Contains the whole HTML structure.
	 * @var string
	 */
	private $layout;
	
	/**
	 * Contains the title printed at the browser tab.
	 * @var string
	 */
	private $title;
	
	/**
	 * Contains the title printed at the left side of the menu.
	 * @var string
	 */
	private $brand;
	
	/**
	 * Contains the title printed at the <head/> section.
	 * @var string
	 */
	private $site;
	
	/**
	 * Contains all JavaScript files that should be added.
	 * @var string[]
	 */
	private $js;
	
	/**
	 * Contains all CSS files that should be added.
	 * @var string[]
	 */
	private $css;
	
	/**
	 * Contains all content as HTML structure to be set in the main div.
	 * @var string
	 */
	private $content;
	
	/**
	 * Contains the content of the footer.
	 * @var string
	 */
	private $footer;
	
	/**
	 * Contains the menu
	 * @var Menu[]
	 */
	private $menu;
	
	/**
	 * Initializes a new instance of the page.
	 * 
	 * @param string $file path to the file that contains the HTML structure.
	 */
	function __construct($file)
	{
		$this->js = array();
		$this->css = array();
		$this->menu = array();
		$this->layout = file_get_contents($file);
	}
	
	/**
	 * Override of the toString method.
	 * 
	 * @return string
	 */
	function __toString()
	{
		return $this->replace();
	}
	
	/**
	 * Adds a JavaScript file to the list.
	 * 
	 * @param string $file Url to the file to add.
	 */
	public function addJS($file)
	{
		foreach($this->js as $js)
		{
			if (strpos($js, $file) !== false)
			{
				// File already in list
				return;
			}
		}
		
		$this->js[] = '<script type="text/javascript" src="'.$file.'"></script>';
	}
	
	/**
	 * Adds a style file to the list.
	 * 
	 * @param string $file Url to the file to add.
	 */
	public function addCSS($file)
	{
		foreach ($this->css as $css)
		{
			if (strpos($css, $file) !== false)
			{
				// File already in list
				return;
			}
		}
		
		$this->css[] = '<link rel="stylesheet" type="text/css" href="'.$file.'">';
	}
	
	/**
	 * Adds a menu to the site.
	 * 
	 * @param Menu $menu A menu to add.
	 */
	public function addMenu(Menu $menu)
	{
		$this->menu[] = $menu;
	}
	
	/**
	 * Sets the title to the left of the menu.
	 * 
	 * @param string $brand Title
	 */
	public function setBrand($brand)
	{
		$this->brand = $brand;
	}
	
	/**
	 * Sets the title printed in the browsers tab.
	 * 
	 * @param string $title Title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * Sets the footer's content.
	 * 
	 * @param string $footer The footer's content.
	 */
	public function setFooter($footer)
	{
		$this->footer = $footer;
	}
	
	/**
	 * Sets the content of the page.
	 * 
	 * @param string $content The whole content of the page.
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}
	
	/**
	 * Adds the given content to the already existing one.
	 * 
	 * @param string $content The new content to add.
	 */
	public function addContent($content)
	{
		$this->content .= $content;
	}
	
	/**
	 * Replaces the placeholder of the layout against its content.
	 * 
	 * @return string The HTML of the whole page.
	 */
	private function replace()
	{
		$css = implode(PHP_EOL, $this->css);
		$js = implode(PHP_EOL, $this->js);
		$menu = '';
		foreach ($this->menu as $m)
			$menu .= $m;
		
		$src = array('{CSS}', '{JS}', '{BRAND}', '{TITLE}', '{MENU}', '{CONTENT}', '{FOOTER}', '{URL}');
		$dst = array($css, $js, $this->brand, $this->title, $menu, $this->content, $this->footer, URL);
		
		return str_replace($src, $dst, $this->layout);
	}
}


?>