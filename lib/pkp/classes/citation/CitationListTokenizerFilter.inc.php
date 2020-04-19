<?php

/**
 * @file classes/citation/CitationListTokenizerFilter.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CitationListTokenizerFilter
 * @ingroup classes_citation
 *
 * @brief Class that takes an unformatted list of citations
 *  and returns an array of raw citation strings.
 */

import('lib.pkp.classes.filter.Filter');

class CitationListTokenizerFilter extends Filter {
	/**
	 * Constructor
	 */
	function CitationListTokenizerFilter() {
		$this->setDisplayName('Citation Tokenizer');

		parent::Filter();
	}

	//
	// Implement template methods from Filter
	//
	/**
	 * @see Filter::getSupportedTransformation()
	 */
	function getSupportedTransformation() {
		return array('primitive::string', 'primitive::string[]');
	}

	/**
	 * @see Filter::getClassName()
	 */
	function getClassName() {
		return 'lib.pkp.classes.citation.CitationListTokenizerFilter';
	}

	/**
	 * @see Filter::process()
	 * @param $input string
	 * @return mixed array
	 */
	function &process(&$input) {
		// The default implementation assumes that raw citations are
		// separated with line endings.
		// 1) Remove empty lines and normalize line endings
		$input = StringUtils::regexp_replace('/[\r\n]+/s', "\n", $input);
		// 2) Break up at line endings
		$output = explode("\n", $input);
		// FIXME: Implement more complex treatment, e.g. filtering of
		// number strings at the beginning of each string, etc.
		return $output;
	}
}
?>