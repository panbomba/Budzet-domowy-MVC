<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
			//$twig->addGlobal('session', $_SESSION);
			$twig->addGlobal('current_user', \App\Auth::getUser());
			$twig->addGlobal('start_date', \App\Controllers\Balance::getStartDate());
			$twig->addGlobal('end_date', \App\Controllers\Balance::getEndDate());
			$twig->addGlobal('sum_of_expenses', \App\Controllers\Balance::showSumOfExpenses());
			$twig->addGlobal('sum_of_incomes', \App\Controllers\Balance::showSumOfIncomes());
			$twig->addGlobal('table_of_incomes', \App\Controllers\Balance::getIncomeTable());
			$twig->addGlobal('table_of_expenses', \App\Controllers\Balance::getExpenseTable());
			$twig->addGlobal('bilans', \App\Controllers\Balance::getBalance());
			$twig->addGlobal('flash_messages', \App\Flash::getMessages());
        }

        echo $twig->render($template, $args);
    }
}
