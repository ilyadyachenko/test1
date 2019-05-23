<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\classes;


class View
{
	/**
	 * @param $template
	 * @param array $parameters
	 * @return string
	 * @throws \Exception
	 */
	public static function render($template, array $parameters = [])
	{
		$header = static::getHeader($parameters);
		$mainContent = static::getTemplate($template, $parameters);
		$footer = static::getFooter($parameters);

		return $header . $mainContent . $footer;
	}

	/**
	 * @param $template
	 * @param array $parameters
	 * @return false|string
	 * @throws \Exception
	 */
	public static function renderTemplateOnly($template, array $parameters = [])
	{
		return static::getTemplate($template, $parameters);
	}

	/**
	 * @param $template
	 * @param array $parameters
	 * @return false|string
	 * @throws \Exception
	 */
	protected static function getTemplate($template, array $parameters = [])
	{
		$templatePath = BASEDIR."/views/".$template.".php";
		if (!file_exists($templatePath))
		{
			throw new \Exception('Template '.htmlspecialchars($template).' not found');
		}

		if (!empty($parameters))
		{
			extract($parameters);
		}

		ob_start();
		include_once $templatePath;
		return ob_get_clean();
	}

	/**
	 * @param $parameters
	 * @return false|string
	 * @throws \Exception
	 */
	protected static function getHeader($parameters)
	{
		return static::getTemplate('header', $parameters);
	}

	/**
	 * @param $parameters
	 * @return false|string
	 * @throws \Exception
	 */
	protected static function getFooter($parameters)
	{
		return static::getTemplate('footer', $parameters);
	}
}