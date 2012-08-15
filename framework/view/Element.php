<?php
/**
 * Element
 *
 * @author Elze Kool
 * @copyright Elze Kool, Kool Software en Webdevelopment
 *
 * @package KoolDevelop
 * @subpackage Core
 **/

namespace KoolDevelop\View;

/**
 * Element
 *
 * @author Elze Kool
 * @copyright Elze Kool, Kool Software en Webdevelopment
 *
 * @package KoolDevelop
 * @subpackage Core
 **/
abstract class Element extends \KoolDevelop\View\View
{

    /**
     * Parent View
     * @var \KoolDevelop\View\View
     */
    private $Parent;


    /**
     * Create new Element instance
     *
     * @param \KoolDevelop\View\View $view Parent View
     */
    public function __construct(\KoolDevelop\View\View &$view) {
        parent::__construct();
        $this->addObservable('beforeElement');
        $this->Parent = $view;
    }

    /**
	 * Set View
	 *
	 * @param string $view View
	 *
	 * @return void
	 */
	public function setView($view) {

        if (preg_match('/^[a-z](([a-z0-9_])+(\/|\\\)?([a-z0-9_])*)*[a-z0-9]$/', $view) == false) {
			throw new \InvalidArgumentException(__f("View name contains invalid characters",'kooldevelop'));
		}

		// Kijk of view wel bestaat
		$view_file = \KoolDevelop\Configuration::getInstance('core')->get('path.element') . DS . str_replace(array('\\', '/'), DS, $view) . '.php';
		if (!file_exists($view_file)) {
			throw new \InvalidArgumentException(__f("Element file not found",'kooldevelop'));
		}

		$this->View = $view;

    }

	/**
	 * Get Helper
	 *
	 * @param string $classname Helper classname
	 * @param string $namespace Namespace
	 *
	 * @return \Helper Helper
	 */
	public function helper($classname, $namespace = '\\View\\Helper\\') {
		return $this->Parent->helper($classname, $namespace);
	}


    /**
	 * View Renderen
	 *
	 * @return void
	 */
	public function render() {

		if ($this->View == null) {
			throw new \LogicException(__f("No view set to render",'kooldevelop'));
		}

		// Set View vars
		foreach ($this->ViewVars as $var_name => $var_value) {
			$$var_name = $var_value;
		}

        $this->fireObservable('beforeElement');

		// Load/Render Element
        require \KoolDevelop\Configuration::getInstance('core')->get('path.element') . DS . str_replace(array('\\', '/'), DS, $this->View) . '.php';


		// Unset set Vars
		foreach ($this->ViewVars as $var_name => $var_value) {
			unset($$var_name);
		}

	}

}