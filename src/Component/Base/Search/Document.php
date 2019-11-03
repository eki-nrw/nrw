<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Search;

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
     * An array of sub-documents.
     *
     * @var \Eki\NRW\Component\SPBase\Search\Document[]
     */
    public $documents = array();
}
