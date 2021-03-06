<?php

namespace QUI\ERP\Accounting\Template;

use QUI;
use QUI\ERP\Output\OutputTemplateProviderInterface;
use QUI\Interfaces\Template\EngineInterface;
use QUI\Locale;

class Template implements OutputTemplateProviderInterface
{
    /**
     * Entity types
     */
    const ENTITY_TYPE_CANCELLED   = 'Canceled';
    const ENTITY_TYPE_CONTRACT    = 'Contract';
    const ENTITY_TYPE_CREDIT_NOTE = 'CreditNote';
    const ENTITY_TYPE_INVOICE     = 'Invoice';
    const ENTITY_TYPE_OFFER       = 'Offer';
    const ENTITY_TYPE_DUNNING     = 'Dunning';

    /**
     * Get all output types the template package provides templates for
     *
     * @return string[]
     */
    public static function getEntityTypes()
    {
        return [
            self::ENTITY_TYPE_CANCELLED,
            self::ENTITY_TYPE_CONTRACT,
            self::ENTITY_TYPE_CREDIT_NOTE,
            self::ENTITY_TYPE_INVOICE,
            self::ENTITY_TYPE_OFFER,
            self::ENTITY_TYPE_DUNNING
        ];
    }

    /**
     * Get all available templates for $entityType
     *
     * @param string $entityType
     * @return string[]|int[] - Collection of templateIds
     */
    public static function getTemplates(string $entityType)
    {
        switch ($entityType) {
            case self::ENTITY_TYPE_CANCELLED:
            case self::ENTITY_TYPE_CONTRACT:
            case self::ENTITY_TYPE_CREDIT_NOTE:
            case self::ENTITY_TYPE_INVOICE:
            case self::ENTITY_TYPE_OFFER:
            case self::ENTITY_TYPE_DUNNING:
                return ['system_default'];
                break;
        }

        return [];
    }

    /**
     * Get title of Template
     *
     * @param string|int $templateId
     * @param Locale $Locale (optional) - If omitted use \QUI::getLocale()
     * @return string
     */
    public static function getTemplateTitle($templateId, Locale $Locale = null)
    {
        if (empty($Locale)) {
            $Locale = QUI::getLocale();
        }

        return $Locale->get('quiqqer/erp-accounting-templates', 'template.title');
    }

    /**
     * Get HTML for document header area
     *
     * @param string|int $templateId
     * @param string $entityType
     * @param EngineInterface $Engine
     * @param mixed $Entity - The entity the output is created for
     * @return string|false
     *
     * @throws QUI\Exception
     */
    public static function getHeaderHtml($templateId, string $entityType, EngineInterface $Engine, $Entity)
    {
        $tplDir     = self::getTemplateDir();
        $tplTypeDir = $tplDir.$entityType.'/';

        if (\file_exists($tplTypeDir.'header.html')) {
            $htmlFile = $tplTypeDir.'header.html';
        } else {
            $htmlFile = $tplDir.'header.html';
        }

        $output = '';

        if (\file_exists($tplTypeDir.'header.css')) {
            $output .= '<style>'.\file_get_contents($tplTypeDir.'header.css').'</style>';
        } else {
            $output .= '<style>'.\file_get_contents($tplDir.'header.css').'</style>';
        }

        $output .= $Engine->fetch($htmlFile);

        return $output;
    }

    /**
     * Get HTML for document body area
     *
     * @param string|int $templateId
     * @param string $entityType
     * @param EngineInterface $Engine
     * @param mixed $Entity - The entity the output is created for
     * @return string|false
     *
     * @throws QUI\Exception
     */
    public static function getBodyHtml($templateId, string $entityType, EngineInterface $Engine, $Entity)
    {
        $tplTypeDir = self::getTemplateDir().$entityType.'/';
        $htmlFile   = $tplTypeDir.'body.html';

        if (!\file_exists($htmlFile)) {
            throw new QUI\Exception('Template file '.$htmlFile.' not found.');
        }

        $output = '';

        if (\file_exists($tplTypeDir.'body.css')) {
            $output .= '<style>'.\file_get_contents($tplTypeDir.'body.css').'</style>';
        }

        $output .= $Engine->fetch($htmlFile);

        return $output;
    }

    /**
     * Get HTML for document footer area
     *
     * @param string|int $templateId
     * @param string $entityType
     * @param EngineInterface $Engine
     * @param mixed $Entity - The entity the output is created for
     * @return string|false
     *
     * @throws QUI\Exception
     */
    public static function getFooterHtml($templateId, string $entityType, EngineInterface $Engine, $Entity)
    {
        $tplDir     = self::getTemplateDir();
        $tplTypeDir = $tplDir.$entityType.'/';

        if (\file_exists($tplTypeDir.'footer.html')) {
            $htmlFile = $tplTypeDir.'footer.html';
        } else {
            $htmlFile = $tplDir.'footer.html';
        }

        $output = '';

        if (\file_exists($tplTypeDir.'footer.css')) {
            $output .= '<style>'.\file_get_contents($tplTypeDir.'footer.css').'</style>';
        } else {
            $output .= '<style>'.\file_get_contents($tplDir.'footer.css').'</style>';
        }

        $output .= $Engine->fetch($htmlFile);

        return $output;
    }

    /**
     * Get main template directory
     *
     * @return string
     *
     * @throws QUI\Exception
     */
    protected static function getTemplateDir()
    {
        return QUI::getPackage('quiqqer/erp-accounting-templates')->getDir().'template/';
    }
}
