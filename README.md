Meta Control
============

[![Build Status](https://github.com/dek-cz/meta-canonical-control/workflows/CI/badge.svg)](https://github.com/dek-cz/meta-canonical-control/actions?query=workflow%3ACI+branch%3Amaster)
[![Downloads this Month](https://img.shields.io/packagist/dm/vrestihnat/meta-canonical-control.svg)](https://packagist.org/packages/vrestihnat/meta-canonical-control)
[![Latest stable](https://img.shields.io/packagist/v/vrestihnat/meta-canonical-control.svg)](https://packagist.org/packages/vrestihnat/meta-canonical-control)
[![Coverage Status](https://coveralls.io/repos/github/dek-cz/meta-canonical-control/badge.svg?branch=master)](https://coveralls.io/github/dek-cz/meta-canonical-control?branch=master)

Installation
------------

Via Composer:

```sh
$ composer require vrestihnat/meta-canonical-control
```


Usage
-----

First register the control factory in your config and optionally set up default metadata:
```yaml
services:
    -
        implement: Vrestihnat\MetaControl\IMetaControlFactory
        setup:
            - setCharset('utf-8')
            - setAuthor('Jon Doe')
```

Use the control factory in your presenter:
```php
protected function createComponentMeta(): Vrestihnat\MetaControl\MetaControl
{
    $control = $this->metaControlFactory->create();
    $control->setDescription('Lorem ipsum');
    return $control;
}
```

And render it in your Latte template:
```html
<html>
<head>
    {control meta}
</head>
<body>
    ...
</body>
</html>
```

### Overview of supported meta tags

Charset:
```php
// <meta charset="utf-8">
$control->setCharset('utf-8');
$control->getCharset(); // 'utf-8'
```

Document metadata:
```php
// <meta name="author" content="John Doe">
$control->setMetadata('author', 'Jon Doe');
$control->getMetadata('author'); // 'Jon Doe'
```

Document properties:
```php
// <meta property="og:title" content="Foo title">
$control->setProperty('og:title', 'Foo title');
$control->getProperty('og:title'); // 'Foo title'
```

Pragma directives:
```php
// <meta http-equiv="content-type" content="text/html; charset=UTF-8">
$control->setPragma('content-type', 'text/html; charset=UTF-8');
$control->getPragma('content-type'); // 'text/html; charset=UTF-8'
```

### Shorthands for standard metadata

Author:
```php
// <meta name="author" content="John Doe">
$control->setAuthor('Jon Doe');
$control->getAuthor(); // 'Jon Doe'
```

Description:
```php
// <meta name="description" content="Lorem ipsum">
$control->setDescription('Lorem ipsum');
$control->getDescription(); // 'Lorem ipsum'
```

Keywords:
```php
// <meta name="keywords" content="foo, bar, baz">
$control->setKeywords('foo', 'bar');
$control->addKeyword('baz');
$control->getKeywords(); // ['foo', 'bar', 'baz']
```

Robots:
```php
// <meta name="robots" content="noindex, nofollow">
$control->setRobots('noindex, nofollow');
$control->getRobots(); // 'noindex, nofollow'
```
### New features

Canonical link:
```php
// <link rel="canonical" href="/test/3">
$control->setCanonical('/test/3');
```
Prev (paging):
```php
// <link rel="prev" href="test/3/page/1">
$control->setPrev('/test/3/page/1');
```
Next (paging):
```php
// <link rel="next" href="/test/3/page/3">
$control->setNext('/test/3/page/3');
```
Set not unique meta e.g. google-site-verification
```php
// <meta name="google-site-verification" content="123456789abcdefghijklmnopqrstuvwxyzABCDEFGH">\n<meta name="google-site-verification" content="HGFEDCBAzyxwvutsrqponmlkjihgfedcba987654321">\n

$control->setMetadata('google-site-verification', '123456789abcdefghijklmnopqrstuvwxyzABCDEFGH');
$control->setMetadata('google-site-verification', 'HGFEDCBAzyxwvutsrqponmlkjihgfedcba987654321');
```
