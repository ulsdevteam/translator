<?php

/**
 * @file controllers/listbuilder/LocaleFileListbuilderGridCellProvider.inc.php
 *
 * Copyright (c) 2014-2019 Simon Fraser University
 * Copyright (c) 2000-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class LocaleFileListbuilderGridCellProvider
 * @ingroup controllers_listbuilder
 *
 * @brief Provide labels for locale file listbuilder.
 */

import('lib.pkp.classes.controllers.grid.GridCellProvider');

class LocaleFileListbuilderGridCellProvider extends GridCellProvider {
	/** @var string The locale code for the locale being translated */
	var $locale;

	/** @var string The locale code for the locale of the current user */
	var $referenceLocale;

	/**
	 * Constructor
	 * @param $locale string The locale being translated
	 */
	function __construct($locale, $referenceLocale) {
		parent::__construct();
		$this->locale = $locale;
		$this->referenceLocale = $referenceLocale;
	}

	//
	// Template methods from GridCellProvider
	//
	/**
	 * @copydoc GridCellProvider::getTemplateVarsFromRowColumn()
	 */
	function getTemplateVarsFromRowColumn($row, $column) {
		$data = $row->getData();
		switch ($column->getId()) {
			case 'key':
				return array(
					'key' => $row->getId(),
					'strong' => !isset($data[MASTER_LOCALE]) || $data[MASTER_LOCALE]==='' ||
						!isset($data[$this->locale]) || $data[$this->locale]==='' ||
						0!=count(array_diff( // Parameters differ
							PKPLocale::getParameterNames($data[MASTER_LOCALE]),
							PKPLocale::getParameterNames($data[$this->locale])
						))
				);
			case 'value':
				$allLocales = PKPLocale::getAllLocales();
				return array(
					'referenceLocale' => $this->referenceLocale,
					'referenceLocaleName' => $allLocales[$this->referenceLocale],
					'reference' => isset($data[$this->referenceLocale])?$data[$this->referenceLocale]:'',
					'translationLocale' => $this->locale,
					'translationLocaleName' => $allLocales[$this->locale],
					'translation' => isset($data[$this->locale])?$data[$this->locale]:'',
				);
		}
		assert(false);
	}
}

