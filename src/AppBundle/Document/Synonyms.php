<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Synonyms
 * @package AppBundle\Document
 * @ODM\Document
 */
class Synonyms
{
    /**
     * @var string $id
     * @ODM\Id
     */
    public $id;

    /**
     * Implied format:
     * [
     *    "word" => "html string with synonyms"
     * ]
     *
     * @var array $synonyms
     * @ODM\Field(type="hash")
     */
    public $synonyms;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Synonyms
     */
    public function setId(string $id): Synonyms
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function getSynonyms(): array
    {
        return $this->synonyms;
    }

    /**
     * @param array $synonyms
     * @return Synonyms
     */
    public function setSynonyms(array $synonyms): Synonyms
    {
        $this->synonyms = $synonyms;

        return $this;
    }


}