<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\URLAliasService as URLAliasServiceInterface;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;

use Eki\NRW\Component\Base\Persistence\UrlAlias\Handler;
use Eki\NRW\Common\Res\Model\ResInterface;

use Eki\NRW\Component\Base\Engine\Values\Content\Location;
use Eki\NRW\Component\Base\Engine\Values\Content\URLAlias;

use Eki\NRW\Component\Base\Persistence\Content\URLAlias as SPIURLAlias;
use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Base\Engine\Exceptions\ForbiddenException;
use Exception;

/**
 * URLAlias service.
 *
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class URLAliasService implements URLAliasServiceInterface
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    protected $engine;

    /**
     * @var \Eki\NRW\Component\Base\Persistence\UrlAlias\Handler
     */
    protected $urlAliasHandler;

    /**
     * @var array
     */
    protected $settings;

    /**
     * Setups service with reference to engine object that created it & corresponding handler.
     *
     * @param \Eki\NRW\Component\Base\Engine\Engine $engine
     * @param \Eki\NRW\Component\Base\Persistence\Content\UrlAlias\Handler $urlAliasHandler
     * @param array $settings
     */
    public function __construct(EngineInterface $engine, Handler $urlAliasHandler, array $settings = array())
    {
        $this->engine = $engine;
        $this->urlAliasHandler = $urlAliasHandler;
        // Union makes sure default settings are ignored if provided in argument
        $this->settings = $settings + array(
            'showAllTranslations' => false,
        );
        // Get prioritized languages from language service to not have to call it several times
        $this->settings['prioritizedLanguageList'] = $engine->getContentLanguageService()->getPrioritizedLanguageCodeList();
    }

    /**
     * Create a user chosen $alias pointing to $location in $languageCode.
     *
     * This method runs URL filters and transformers before storing them.
     * Hence the path returned in the URLAlias Value may differ from the given.
     * $alwaysAvailable makes the alias available in all languages.
     *
     * @param ResInterface $subject
     * @param string $path
     * @param bool $forwarding if true a redirect is performed
     * @param string $languageCode the languageCode for which this alias is valid
     * @param bool $alwaysAvailable
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if the path already exists for the given language
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias
     */
    public function createUrlAlias(ResInterface $subject, $path, $languageCode, $forwarding = false, $alwaysAvailable = false)
    {
        $path = $this->cleanUrl($path);

        $this->engine->beginTransaction();
        try {
            $urlAlias = $this->urlAliasHandler->createCustomUrlAlias(
                $subject->getId(),
                $path,
                $forwarding,
                $languageCode,
                $alwaysAvailable
            );
            $this->engine->commit();
        } catch (ForbiddenException $e) {
            $this->engine->rollback();
            throw new InvalidArgumentException(
                '$path',
                $e->getMessage(),
                $e
            );
        } catch (Exception $e) {
            $this->engine->rollback();
            throw $e;
        }

		return $urlAlias;
    }

    /**
     * Create a user chosen $alias pointing to a resource in $languageCode.
     *
     * This method does not handle location resources - if a user enters a location target
     * the createCustomUrlAlias method has to be used.
     * This method runs URL filters and and transformers before storing them.
     * Hence the path returned in the URLAlias Value may differ from the given.
     *
     * $alwaysAvailable makes the alias available in all languages.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if the path already exists for the given
     *         language or if resource is not valid
     *
     * @param string $resource
     * @param string $path
     * @param string $languageCode
     * @param bool $forwarding
     * @param bool $alwaysAvailable
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias
     */
    public function createGlobalUrlAlias($resource, $path, $languageCode, $forwarding = false, $alwaysAvailable = false)
    {
        if (!preg_match('#^([a-zA-Z0-9_]+):(.+)$#', $resource, $matches)) {
            throw new InvalidArgumentException('$resource', 'argument is not valid');
        }

        $path = $this->cleanUrl($path);

        $this->engine->beginTransaction();
        try {
            $urlAlias = $this->urlAliasHandler->createGlobalUrlAlias(
                $matches[1] . ':' . $this->cleanUrl($matches[2]),
                $path,
                $forwarding,
                $languageCode,
                $alwaysAvailable
            );
            $this->engine->commit();
        } catch (ForbiddenException $e) {
            $this->engine->rollback();
            throw new InvalidArgumentException('$path', $e->getMessage(), $e);
        } catch (Exception $e) {
            $this->engine->rollback();
            throw $e;
        }

		return $urlAlias;
    }

    /**
     * Determines alias language code.
     *
     * Method will return false if language code can't be matched against alias language codes or language settings.
     *
     * @param \Eki\NRW\Component\Base\Persistence\Content\URLAlias $spiUrlAlias
     * @param string|null $languageCode
     * @param bool $showAllTranslations
     * @param string[] $prioritizedLanguageList
     *
     * @return string|bool
     */
    protected function selectAliasLanguageCode(
        SPIURLAlias $spiUrlAlias,
        $languageCode,
        $showAllTranslations,
        $prioritizedLanguageList
    ) {
        if (isset($languageCode) && !in_array($languageCode, $spiUrlAlias->languageCodes)) {
            return false;
        }

        foreach ($prioritizedLanguageList as $languageCode) {
            if (in_array($languageCode, $spiUrlAlias->languageCodes)) {
                return $languageCode;
            }
        }

        if ($spiUrlAlias->alwaysAvailable || $showAllTranslations) {
            $lastLevelData = end($spiUrlAlias->pathData);
            reset($lastLevelData['translations']);

            return key($lastLevelData['translations']);
        }

        return false;
    }

    /**
     * Returns path extracted from normalized path data returned from persistence, using language settings.
     *
     * Will return false if path could not be determined.
     *
     * @param \Eki\NRW\Component\Base\Persistence\Content\URLAlias $spiUrlAlias
     * @param string $languageCode
     * @param bool $showAllTranslations
     * @param string[] $prioritizedLanguageList
     *
     * @return string|bool
     */
    protected function extractPath(
        SPIURLAlias $spiUrlAlias,
        $languageCode,
        $showAllTranslations,
        $prioritizedLanguageList
    ) {
        $pathData = array();
        $pathLevels = count($spiUrlAlias->pathData);

        foreach ($spiUrlAlias->pathData as $level => $levelEntries) {
            if ($level === $pathLevels - 1) {
                $prioritizedLanguageCode = $this->selectAliasLanguageCode(
                    $spiUrlAlias,
                    $languageCode,
                    $showAllTranslations,
                    $prioritizedLanguageList
                );
            } else {
                $prioritizedLanguageCode = $this->choosePrioritizedLanguageCode(
                    $levelEntries,
                    $showAllTranslations,
                    $prioritizedLanguageList
                );
            }

            if ($prioritizedLanguageCode === false) {
                return false;
            }

            $pathData[$level] = $levelEntries['translations'][$prioritizedLanguageCode];
        }

        return implode('/', $pathData);
    }

    /**
     * Returns language code with highest priority.
     *
     * Will return false if language code could nto be matched with language settings in place.
     *
     * @param array $entries
     * @param bool $showAllTranslations
     * @param string[] $prioritizedLanguageList
     *
     * @return string|bool
     */
    protected function choosePrioritizedLanguageCode(array $entries, $showAllTranslations, $prioritizedLanguageList)
    {
        foreach ($prioritizedLanguageList as $prioritizedLanguageCode) {
            if (isset($entries['translations'][$prioritizedLanguageCode])) {
                return $prioritizedLanguageCode;
            }
        }

        if ($entries['always-available'] || $showAllTranslations) {
            reset($entries['translations']);

            return key($entries['translations']);
        }

        return false;
    }

    /**
     * Matches path string with normalized path data returned from persistence.
     *
     * Returns matched path string (possibly case corrected) and array of corresponding language codes or false
     * if path could not be matched.
     *
     * @param \Eki\NRW\Component\Base\Persistence\Content\URLAlias $spiUrlAlias
     * @param string $path
     * @param string $languageCode
     *
     * @return array
     */
    protected function matchPath(SPIURLAlias $spiUrlAlias, $path, $languageCode)
    {
        $matchedPathElements = array();
        $matchedPathLanguageCodes = array();
        $pathElements = explode('/', $path);
        $pathLevels = count($spiUrlAlias->pathData);

        foreach ($pathElements as $level => $pathElement) {
            if ($level === $pathLevels - 1) {
                $matchedLanguageCode = $this->selectAliasLanguageCode(
                    $spiUrlAlias,
                    $languageCode,
                    $this->settings['showAllTranslations'],
                    $this->settings['prioritizedLanguageList']
                );
            } else {
                $matchedLanguageCode = $this->matchLanguageCode($spiUrlAlias->pathData[$level], $pathElement);
            }

            if ($matchedLanguageCode === false) {
                return array(false, false);
            }

            $matchedPathLanguageCodes[] = $matchedLanguageCode;
            $matchedPathElements[] = $spiUrlAlias->pathData[$level]['translations'][$matchedLanguageCode];
        }

        return array(implode('/', $matchedPathElements), $matchedPathLanguageCodes);
    }

    /**
     * @param array $pathElementData
     * @param string $pathElement
     *
     * @return string|bool
     */
    protected function matchLanguageCode(array $pathElementData, $pathElement)
    {
        foreach ($this->sortTranslationsByPrioritizedLanguages($pathElementData['translations']) as $translationData) {
            if (strtolower($pathElement) === strtolower($translationData['text'])) {
                return $translationData['lang'];
            }
        }

        return false;
    }

    /**
     * Needed when translations for the part of the alias are the same for multiple languages.
     * In that case we need to ensure that more prioritized language is chosen.
     *
     * @param array $translations
     *
     * @return array
     */
    private function sortTranslationsByPrioritizedLanguages(array $translations)
    {
        $sortedTranslations = array();
        foreach ($this->settings['prioritizedLanguageList'] as $languageCode) {
            if (isset($translations[$languageCode])) {
                $sortedTranslations[] = array(
                    'lang' => $languageCode,
                    'text' => $translations[$languageCode],
                );
                unset($translations[$languageCode]);
            }
        }

        foreach ($translations as $languageCode => $translation) {
            $sortedTranslations[] = array(
                'lang' => $languageCode,
                'text' => $translation,
            );
        }

        return $sortedTranslations;
    }

    /**
     * Returns true or false depending if URL alias is loadable or not for language settings in place.
     *
     * @param \Eki\NRW\Component\Base\Persistence\Content\URLAlias $spiUrlAlias
     * @param string|null $languageCode
     *
     * @return bool
     */
    protected function isUrlAliasLoadable(
        SPIURLAlias $spiUrlAlias,
        $languageCode,
        $showAllTranslations,
        $prioritizedLanguageList
    ) {
        if (isset($languageCode) && !in_array($languageCode, $spiUrlAlias->languageCodes)) {
            return false;
        }

        if ($showAllTranslations) {
            return true;
        }

        foreach ($spiUrlAlias->pathData as $levelPathData) {
            if ($levelPathData['always-available']) {
                continue;
            }

            foreach ($levelPathData['translations'] as $translationLanguageCode => $translation) {
                if (in_array($translationLanguageCode, $prioritizedLanguageList)) {
                    continue 2;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Returns true or false depending if URL alias is loadable or not for language settings in place.
     *
     * @param array $pathData
     * @param array $languageCodes
     *
     * @return bool
     */
    protected function isPathLoadable(array $pathData, array $languageCodes)
    {
        if ($this->settings['showAllTranslations']) {
            return true;
        }

        foreach ($pathData as $level => $levelPathData) {
            if ($levelPathData['always-available']) {
                continue;
            }

            if (in_array($languageCodes[$level], $this->settings['prioritizedLanguageList'])) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * List global aliases.
     *
     * @param string $languageCode filters those which are valid for the given language
     * @param int $offset
     * @param int $limit
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias[]
     */
    public function listGlobalAliases($languageCode = null, $offset = 0, $limit = -1)
    {
        $urlAliasList = array();
        $spiUrlAliasList = $this->urlAliasHandler->listGlobalURLAliases(
            $languageCode,
            $offset,
            $limit
        );

        foreach ($spiUrlAliasList as $spiUrlAlias) {
            $path = $this->extractPath(
                $spiUrlAlias,
                $languageCode,
                $this->settings['showAllTranslations'],
                $this->settings['prioritizedLanguageList']
            );

            if ($path === false) {
                continue;
            }

            $urlAliasList[] = $this->buildUrlAliasDomainObject($spiUrlAlias, $path);
        }

        return $urlAliasList;
    }

    /**
     * Removes urls aliases.
     *
     * This method does not remove autogenerated aliases for locations.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if alias list contains
     *         autogenerated alias
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias[] $aliasList
     */
    public function removeAliases(array $aliasList)
    {
        $spiUrlAliasList = array();
        foreach ($aliasList as $alias) {
            if (!$alias->isCustom) {
                throw new InvalidArgumentException(
                    '$aliasList',
                    'Alias list contains autogenerated alias'
                );
            }
            $spiUrlAliasList[] = $this->buildSPIUrlAlias($alias);
        }

        $this->engine->beginTransaction();
        try {
            $this->urlAliasHandler->removeURLAliases($spiUrlAliasList);
            $this->engine->commit();
        } catch (Exception $e) {
            $this->engine->rollback();
            throw $e;
        }
    }

    /**
     * Builds persistence domain object.
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias $urlAlias
     *
     * @return \Eki\NRW\Component\Base\Persistence\Content\URLAlias
     */
    protected function buildSPIUrlAlias(URLAlias $urlAlias)
    {
        return new SPIURLAlias(
            array(
                'id' => $urlAlias->id,
                'type' => $urlAlias->type,
                'destination' => $urlAlias->destination,
                'isCustom' => $urlAlias->isCustom,
            )
        );
    }

    /**
     * looks up the URLAlias for the given url.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if the path does not exist or is not valid for the given language
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if the path exceeded maximum depth level
     *
     * @param string $url
     * @param string $languageCode
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias
     */
    public function lookup($url, $languageCode = null)
    {
        $url = $this->cleanUrl($url);

        $spiUrlAlias = $this->urlAliasHandler->lookup($url);

        list($path, $languageCodes) = $this->matchPath($spiUrlAlias, $url, $languageCode);
        if ($path === false || !$this->isPathLoadable($spiUrlAlias->pathData, $languageCodes)) {
            throw new NotFoundException('URLAlias', $url);
        }

        return $this->buildUrlAliasDomainObject($spiUrlAlias, $path);
    }

    /**
     * Returns the URL alias for the given location in the given language.
     *
     * If $languageCode is null the method returns the url alias in the most prioritized language.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if no url alias exist for the given language
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Content\Location $location
     * @param string $languageCode
     * @param null|bool $showAllTranslations
     * @param null|string[] $prioritizedLanguageList
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias
     */
    public function reverseLookup(
        Location $location,
        $languageCode = null,
        $showAllTranslations = null,
        array $prioritizedLanguageList = null
    ) {
        if ($showAllTranslations === null) {
            $showAllTranslations = $this->settings['showAllTranslations'];
        }
        if ($prioritizedLanguageList === null) {
            $prioritizedLanguageList = $this->settings['prioritizedLanguageList'];
        }
        $urlAliases = $this->listLocationAliases(
            $location,
            false,
            $languageCode,
            $showAllTranslations,
            $prioritizedLanguageList
        );

        foreach ($prioritizedLanguageList as $prioritizedLanguageCode) {
            foreach ($urlAliases as $urlAlias) {
                if (in_array($prioritizedLanguageCode, $urlAlias->languageCodes)) {
                    return $urlAlias;
                }
            }
        }

        foreach ($urlAliases as $urlAlias) {
            if ($urlAlias->alwaysAvailable) {
                return $urlAlias;
            }
        }

        if (!empty($urlAliases) && $showAllTranslations) {
            return reset($urlAliases);
        }

        throw new NotFoundException('URLAlias', $location->id);
    }

    /**
     * Loads URL alias by given $id.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException
     *
     * @param string $id
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Content\URLAlias
     */
    public function load($id)
    {
        $spiUrlAlias = $this->urlAliasHandler->loadUrlAlias($id);
        $path = $this->extractPath(
            $spiUrlAlias,
            null,
            $this->settings['showAllTranslations'],
            $this->settings['prioritizedLanguageList']
        );

        if ($path === false) {
            throw new NotFoundException('URLAlias', $id);
        }

        return $this->buildUrlAliasDomainObject($spiUrlAlias, $path);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function cleanUrl($url)
    {
        return trim($url, '/ ');
    }
}
