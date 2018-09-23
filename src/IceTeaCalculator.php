<?php

use Gtk\Application;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license MIT
 * @version 0.0.1
 */
class IceTeaCalculator
{	
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->app = new Application(GLADE_FILE, "main-window");
		$this->app->setTitle("IceTea Calculator");
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		$appPointer = &$this->app;

		for ($i=0; $i <= 9; $i++) { 
			$this->app->find("in-{$i}")->on("clicked", function() use (&$appPointer, $i) {
				$appPointer->find("input")->setText(
					$appPointer->find("input")->getText().$i
				);
			});
		}

		foreach ([
			"+" => "plus",
			"-" => "min",
			"/" => "divide",
			"*" => "times",
			"^" => "xor",
			"&" => "and",
			"|" => "or",
			"!" => "not",
			"(" => "open-par",
			")" => "close-par",
			"return" => "return",
			";" => "semicolon"
		] as $key => $value) {
			$this->app->find("in-{$value}")->on("clicked", function () use (&$appPointer, $key) {
				$appPointer->find("input")->setText(
					$appPointer->find("input")->getText().$key
				);
			});
		}

		$this->app->find("del")->on("clicked", function () use (&$appPointer, $key) {
			$text = $appPointer->find("input")->getText();
			$appPointer->find("input")->setText(
				substr($text, 0, strlen($text) - 1)
			);
		});

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
