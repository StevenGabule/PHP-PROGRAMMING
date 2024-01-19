<?php

class Hoover {
	public $content;

	public function getContent($url) {
		if (!$this->content) {
			if(stripos($url, 'http') !== 0) {
				$url = 'http://' . $url;
			}
			$this->content = new DOMDocument('1.0', 'utf-8');
			$this->content->preserveWhiteSpace = FALSE;
			// @ used to suppress warnings generated from
			// improperly configured web pages
			@$this->content->loadHTMLFile($url);
		}
		return $this->content;
	}

	public function getTags($url, $tag) {
		$count = 0;
		$result = array();
		$elements = $this->getContent($url)->getElementsByTagName($tag);
		foreach ($elements as $node) {
			$result[$count]['value'] = trim(preg_replace('/\s+/', ' ', $node->nodeValue));
			if($node->hasAttribute()) {
				foreach($node->attributes as $name => $attr) {
					$result[$count]['attributes'][$name] = $attr->value;
				}
			}
			$count++;
		}
		return $result;
	}

	public function getAttribute($url, $attr, $domain = NULL) {
		$result = array();
		$elements = $this->getContent($url)->getElementsByTagName('*');
		foreach ($elements as $node) {
			if($node->hasAttribute($attr)) {
				$value = $node->getAttribute($attr);
				if($domain) {
					if(stripos($value, $domain) !== FALSE) {
						$result[] = trim($value);
					}
				} else {
					$result[] = trim($value);
				}
			}
		}
		return $result;
	}
}

define('DEFAULT_URL', 'http://oreilly.com/');
define('DEFAULT_TAG', 'a');

require __DIR__ . '/../Application/Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/..');

// get "vacuum" class
$vac = new Application\Web\Hoover();

// NOTE: the PHP 7 null coalesce operator is used
$url = strip_tags($_GET['url'] ?? DEFAULT_URL);
$tag = strip_tags($_GET['tag'] ?? DEFAULT_TAG);
echo 'Dump of Tags: ' . PHP_EOL;
var_dump($vac->getTags($url, $tag));