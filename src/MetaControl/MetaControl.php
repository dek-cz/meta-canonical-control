<?php

declare(strict_types = 1);

namespace Dekcz\MetaControl;

use Nette;
use Nette\Utils\Html;

class MetaControl extends Nette\Application\UI\Component
{

    private const META_AUTHOR = 'author';
    private const META_DESCRIPTION = 'description';
    private const META_KEYWORDS = 'keywords';
    private const META_ROBOTS = 'robots';

    private ?string $charset = null;

    /** @var UnionArray */
    private ?UnionArray $metadata = null;

    /** @var UnionArray */
    private ?UnionArray $properties = null;

    /** @var UnionArray */
    private ?UnionArray $pragmas = null;
    private ?string $canonical = null;
    private ?string $prev = null;
    private ?string $next = null;

    public function render(): void
    {
        if ($this->getCharset() !== null) {
            echo Html::el('meta', ['charset' => $this->getCharset()]) . "\n";
        }

        foreach ($this->getMeta() as $name => $content) {
            echo Html::el('meta', ['name' => $name, 'content' => $content]) . "\n";
        }

        foreach ($this->getProperties() as $name => $content) {
            echo Html::el('meta', ['property' => $name, 'content' => $content]) . "\n";
        }

        foreach ($this->getPragmas() as $httpEquiv => $content) {
            echo Html::el('meta', ['http-equiv' => $httpEquiv, 'content' => $content]) . "\n";
        }

        if ($this->getCanonical() !== null) {
            echo Html::el('link', ['rel' => 'canonical', 'href' => $this->getCanonical()]) . "\n";
        }

        if ($this->getPrev() !== null) {
            echo Html::el('link', ['rel' => 'prev', 'href' => $this->getPrev()]) . "\n";
        }

        if ($this->getNext() !== null) {
            echo Html::el('link', ['rel' => 'next', 'href' => $this->getNext()]) . "\n";
        }
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function setCharset(?string $charset): void
    {
        $this->charset = $charset;
    }

    public function getMetadata(string $name): ?string
    {
        return $this->getMeta()->offsetGet($name);
    }

    public function getMeta(): UnionArray
    {
        $this->metadata = $this->metadata ?? new UnionArray();
        return $this->metadata;
    }

    public function setMetadata(string $name, ?string $content): void
    {
        if ($content === null) {
            $this->getMeta()->offsetUnsetAll($name);
        } else {
            $this->getMeta()->offsetSet($name, $content);
        }
    }

    public function getProperty(string $name): ?string
    {
        return $this->getProperties()->offsetGet($name);
    }

    public function setProperty(string $property, ?string $content): void
    {
        if ($content === null) {
            $this->getProperties()->offsetUnsetAll($property);
        } else {
            $this->getProperties()->offsetSet($property, $content);
        }
    }

    public function getPragma(string $name): ?string
    {
        return $this->getPragmas()->offsetGet($name);
    }

    public function setPragma(string $httpEquiv, ?string $content): void
    {
        if ($content === null) {
            $this->getPragmas()->offsetUnsetAll($httpEquiv);
        } else {
            $this->getPragmas()->offsetSet($httpEquiv, $content);
        }
    }

    public function getAuthor(): ?string
    {
        return $this->getMetadata(self::META_AUTHOR);
    }

    public function setAuthor(?string $author): void
    {
        $this->setMetadata(self::META_AUTHOR, null);
        $this->setMetadata(self::META_AUTHOR, $author);
    }

    public function getDescription(): ?string
    {
        return $this->getMetadata(self::META_DESCRIPTION);
    }

    public function setDescription(?string $description): void
    {
        $this->setMetadata(self::META_DESCRIPTION, null);
        $this->setMetadata(self::META_DESCRIPTION, $description);
    }

    /**
     * @return string[]
     */
    public function getKeywords(): array
    {
        $keywords = $this->getMetadata(self::META_KEYWORDS);
        return $keywords === null ? [] : array_map('trim', explode(',', $keywords));
    }

    public function setKeywords(string ...$keywords): void
    {
        if ($keywords === []) {
            $this->setMetadata(self::META_KEYWORDS, null);
        } else {
            $keywords = array_map('trim', $keywords);
            $keywords = array_unique($keywords);
            $this->setMetadata(self::META_KEYWORDS, null);
            $this->setMetadata(self::META_KEYWORDS, implode(', ', $keywords));
        }
    }

    public function addKeyword(string $keyword): void
    {
        $keywords = $this->getKeywords();
        $keywords[] = $keyword;
        $this->setKeywords(...$keywords);
    }

    public function getRobots(): ?string
    {
        return $this->getMetadata(self::META_ROBOTS);
    }

    public function setRobots(?string $robots): void
    {
        $this->setMetadata(self::META_ROBOTS, null);
        $this->setMetadata(self::META_ROBOTS, $robots);
    }

    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    public function getPrev(): ?string
    {
        return $this->prev;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setCanonical(?string $canonical): void
    {
        $this->canonical = $canonical;
    }

    public function setPrev(?string $prev): void
    {
        $this->prev = $prev;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }

    public function getProperties(): UnionArray
    {
        $this->properties = $this->properties ?? new UnionArray();
        return $this->properties;
    }

    public function getPragmas(): UnionArray
    {
        $this->pragmas = $this->pragmas ?? new UnionArray();
        return $this->pragmas;
    }

}
