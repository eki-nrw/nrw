<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Search;

use Eki\NRW\Component\Base\Engine\Values\ValueObject;

/**
 * Base class for documents.
 */
class Document extends ValueObject
{
    /**
     * Id of the document.
     *
     * @var string
     */
    public $id;

    /**
     * Translation language code that the documents represents.
     *
     * @var string
     */
    public $languageCode;

    /**
     * Denotes that document's translation is the main translation and it is
     * always available.
     *
     * @var bool
     */
    public $alwaysAvailable;

    /**
     * Denotes that document's translation is a main translation of the Content.
     *
     * @var bool
     */
    public $isMainTranslation;

    /**
     * An array of sub-documents.
     *
     * @var \Eki\NRW\Component\SPBase\Search\Document[]
     */
    public $documents = array();
}
