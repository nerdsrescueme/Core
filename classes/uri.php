<?php

namespace Nerd;

class Uri extends Design\Creational\SingletonFactory
{
	public static function construct($uri)
	{
		return Url::site($uri)->uri();
	}

	public static function current()
	{
		return Url::current()->uri();
	}
}