<?php

declare(strict_types=1);

namespace VrestihnatTests\Unit;

use Vrestihnat\MetaControl\MetaControl;

class MetaControlTest extends Test
{

  private function getRenderingOutput(MetaControl $control): string
  {
    ob_start();
    $control->render();
    return (string) ob_get_clean();
  }

  public function testCharset(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getCharset());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setCharset('utf-8');
    $this->assertSame('utf-8', $control->getCharset());
    $this->assertSame("<meta charset=\"utf-8\">\n", $this->getRenderingOutput($control));

    $control->setCharset(null);
    $this->assertSame(null, $control->getCharset());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testMetadata(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getMetadata('author'));
    $this->assertSame(null, $control->getMetadata('description'));
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setMetadata('author', 'Jon Doe');
    $control->setMetadata('description', 'Lorem ipsum');
    $this->assertSame('Jon Doe', $control->getMetadata('author'));
    $this->assertSame('Lorem ipsum', $control->getMetadata('description'));
    $this->assertSame(
            "<meta name=\"author\" content=\"Jon Doe\">\n<meta name=\"description\" content=\"Lorem ipsum\">\n",
            $this->getRenderingOutput($control),
    );

    $control->setMetadata('author', null);
    $control->setMetadata('description', null);
    $this->assertSame(null, $control->getMetadata('author'));
    $this->assertSame(null, $control->getMetadata('description'));
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testProperty(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getProperty('og:title'));
    $this->assertSame(null, $control->getProperty('og:url'));
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setProperty('og:title', 'Foo title');
    $control->setProperty('og:url', 'https://example.com');
    $this->assertSame('Foo title', $control->getProperty('og:title'));
    $this->assertSame('https://example.com', $control->getProperty('og:url'));
    $this->assertSame(
            "<meta property=\"og:title\" content=\"Foo title\">\n<meta property=\"og:url\" content=\"https://example.com\">\n",
            $this->getRenderingOutput($control),
    );

    $control->setProperty('og:title', null);
    $control->setProperty('og:url', null);
    $this->assertSame(null, $control->getProperty('og:title'));
    $this->assertSame(null, $control->getProperty('og:url'));
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testPragma(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getPragma('content-type'));
    $this->assertSame(null, $control->getPragma('refresh'));
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setPragma('content-type', 'text/html; charset=UTF-8');
    $control->setPragma('refresh', '42');
    $this->assertSame('text/html; charset=UTF-8', $control->getPragma('content-type'));
    $this->assertSame('42', $control->getPragma('refresh'));
    $this->assertSame(
            "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">\n<meta http-equiv=\"refresh\" content=\"42\">\n",
            $this->getRenderingOutput($control),
    );

    $control->setPragma('content-type', null);
    $control->setPragma('refresh', null);
    $this->assertSame(null, $control->getPragma('content-type'));
    $this->assertSame(null, $control->getPragma('refresh'));
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testAuthor(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getAuthor());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setAuthor('Jon Doe');
    $this->assertSame('Jon Doe', $control->getAuthor());
    $this->assertSame("<meta name=\"author\" content=\"Jon Doe\">\n", $this->getRenderingOutput($control));

    $control->setAuthor(null);
    $this->assertSame(null, $control->getAuthor());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testDescription(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getDescription());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setDescription('Foo description');
    $this->assertSame('Foo description', $control->getDescription());
    $this->assertSame("<meta name=\"description\" content=\"Foo description\">\n", $this->getRenderingOutput($control));

    $control->setDescription(null);
    $this->assertSame(null, $control->getDescription());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testKeywords(): void
  {
    $control = new MetaControl();

    $this->assertSame([], $control->getKeywords());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setKeywords('foo', 'bar');
    $control->addKeyword('baz');
    $control->addKeyword(' bar ');
    $this->assertSame(['foo', 'bar', 'baz'], $control->getKeywords());
    $this->assertSame("<meta name=\"keywords\" content=\"foo, bar, baz\">\n", $this->getRenderingOutput($control));

    $control->setKeywords();
    $this->assertSame([], $control->getKeywords());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testRobots(): void
  {
    $control = new MetaControl();

    $this->assertSame(null, $control->getRobots());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setRobots('noindex, nofollow');
    $this->assertSame('noindex, nofollow', $control->getRobots());
    $this->assertSame("<meta name=\"robots\" content=\"noindex, nofollow\">\n", $this->getRenderingOutput($control));

    $control->setRobots(null);
    $this->assertSame(null, $control->getRobots());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testCanonical(): void
  {
    $control = new MetaControl();
    $this->assertSame(null, $control->getCanonical());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setCanonical('/test/3');
    $this->assertSame('/test/3', $control->getCanonical());
    $this->assertSame("<link rel=\"canonical\" href=\"/test/3\">\n", $this->getRenderingOutput($control));

    $control->setCanonical(null);
    $this->assertSame(null, $control->getCanonical());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testPrev(): void
  {
    $control = new MetaControl();
    $this->assertSame(null, $control->getPrev());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setPrev('/test/3/page/1');
    $this->assertSame('/test/3/page/1', $control->getPrev());
    $this->assertSame("<link rel=\"prev\" href=\"/test/3/page/1\">\n", $this->getRenderingOutput($control));

    $control->setPrev(null);
    $this->assertSame(null, $control->getPrev());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testNext(): void
  {
    $control = new MetaControl();
    $this->assertSame(null, $control->getNext());
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setNext('/test/3/page/3');
    $this->assertSame('/test/3/page/3', $control->getNext());
    $this->assertSame("<link rel=\"next\" href=\"/test/3/page/3\">\n", $this->getRenderingOutput($control));

    $control->setNext(null);
    $this->assertSame(null, $control->getNext());
    $this->assertSame('', $this->getRenderingOutput($control));
  }

  public function testMetaMulti(): void
  {
    $tag = 'google-site-verification';
    $val = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGH';
    $control = new MetaControl();
    $this->assertSame(null, $control->getMetadata($tag));
    $this->assertSame('', $this->getRenderingOutput($control));

    $control->setMetadata($tag, $val);
    $control->setMetadata($tag, strrev($val));
    $this->assertSame($val, $control->getMetadata($tag));
    $this->assertSame([$val, strrev($val)], $control->getMeta()->offsetGetAll($tag));
    $this->assertSame("<meta name=\"$tag\" content=\"$val\">\n<meta name=\"$tag\" content=\"" . strrev($val) . "\">\n", $this->getRenderingOutput($control));

    $control->setMetadata($tag, null);
    $this->assertSame(null, $control->getMetadata($tag));
    $this->assertSame('', $this->getRenderingOutput($control));
  }

}
