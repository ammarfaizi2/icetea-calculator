<?php

use Gtk\Application;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license MIT
 * @version 0.0.1
 */
class IceTea
{	
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->app = new Application(GLADE_FILE, "main-window");
		$this->app->setTitle("Ice Tea Calculator");
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		$appPointer = &$this->app;

		$this->app->find("calculate")->on("clicked", function() use (&$appPointer) {
	    	
	    	$input = trim($appPointer->find("input")->getText());
	    	if ($input === "") {
	    		$appPointer->find("result")->setText(
	    			"Please fill the input first!"
	    		);
	    	} else {

			    try {
			    	eval("\$text = {$input};");	
			    } catch (\Error $e) {
			    	$text = "Error: ".$e->getMessage();
			    }

		    	$appPointer->find("result")->setText(
			    	sprintf("Here is the result:\n\t%s", $text)
			    );
		    }

		});

		$this->app->find("reset")->on("clicked", function() use (&$appPointer) {
		    $appPointer->find("input")->setText("");
		    $appPointer->find("result")->setText("");
		});
	}

	/**
	 * @return void
	 */
	public function run()
	{
		$this->app->run();
	}
}
