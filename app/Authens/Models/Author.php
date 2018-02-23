<?php

namespace Users\Models;

use Phalcon\Mvc\Model;

/**
 * @SWG\Definition(definition="Author", type="object", required={"name"})
 */
class Author extends Model
{
    /**
     * @param string $authorId
     * @param string $firstName
     * @param string $lastName
     *
     * @return Author
     */
    public static function instance($authorId, $firstName, $lastName)
    {
        $author = new self();

        $author->authorId  = $authorId;
        $author->firstName = $firstName;
        $author->lastName  = $lastName;

        return $author;
    }

    public $authorId;

    /**
     * @SWG\Property()
     * @var string
     */
    public $firstName;

    /**
     * @var string
     * @SWG\Property()
     */
    public $lastName;
}

/**
 *  @SWG\Definition(
 *   definition="Author",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/Author"),
 *       @SWG\Schema(
 *           required={"id"},
 *           @SWG\Property(property="authorId", format="int64", type="integer")
 *       )
 *   }
 * )
 */