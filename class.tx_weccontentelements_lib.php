<?php

class tx_weccontentelements_lib {

	public function addContentElement($extensionKey, $key, $flexformPath = '', $type = '', $title = '', $description = '', $icon = '', $wizardIcon = '') {
		global $TCA;
		t3lib_div::loadTCA('tt_content');

			// Set defaults for title, description, icons, and content element type.
		$locallangPath = 'LLL:EXT:' . $extensionKey . '/' . $key . '/locallang.xml:tt_content.' . $key . '.';
		if (!$title) {
			$title = $locallangPath . 'title';
		}
		if (!$description) {
			$description = $locallangPath . 'description';
		}
		if (!$icon) {
			$icon = 'EXT:' . $extensionKey . '/' . $key . '/icon.gif';
		}
		if (!$wizardIcon) {
			$wizardIcon = t3lib_extMgm::extRelPath($extensionKey) . $key . '/wizard-icon.gif';
		}
		if (!$type) {
			$type = 'special';
		}
		if (!$flexformPath && @file_exists(t3lib_extMgm::extPath($extensionKey) . $key . '/flexform.xml')) {
			$flexformPath = 'FILE:EXT:' . $extensionKey . '/' . $key . '/flexform.xml';
		}


		if ($flexformPath) {
			$TCA['tt_content']['columns']['pi_flexform']['config']['ds']['*,' . $key] = $flexformPath;
			if (1 /* dschoene TODO t3lib_div::int_from_ver(TYPO3_version) >= 4005000*/) {
				$TCA['tt_content']['types'][$key]['showitem'] = '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header,
																	--div--;' . $title . ',
																	pi_flexform;;;;1-1-1,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.behaviour,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
			} else {
				$TCA['tt_content']['types'][$key] = array(
					'showitem' => 'CType;;4;;1-1-1, hidden, header;;3;;2-2-2, linkToTop;;;;3-3-3, --div--;' . $title . ', pi_flexform;;;;1-1-1, --div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime'
				);
			}
		} else {
			if (1 /* dschoene TODO t3lib_div::int_from_ver(TYPO3_version) >= 4005000*/) {
				$TCA['tt_content']['types'][$key]['showitem'] = '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
																	--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.behaviour,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
			} else {
				$TCA['tt_content']['types'][$key] = array(
					'showitem' => 'CType;;4;;1-1-1, hidden, header;;3;;2-2-2, linkToTop;;;;3-3-3, --div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime'
				);
			}
		}

		t3lib_extMgm::addPlugin(array(
			$title,
			$key,
			$icon
		), 'CType');


		$TSConfig = 
			'wizards.newContentElement.wizardItems.' . $type . ' {
				elements {
					' . $key . ' {
						icon = ' . $wizardIcon . '
						title = ' . $title . '
						description = ' . $description . '
						tt_content_defValues {
							CType = ' . $key .'
						}
					}
				}
				show := addToList(' . $key .')
			}';

		t3lib_extMgm::addPageTSConfig(
			'mod.' . $TSConfig . chr(10) .
			'templavoila.' . $TSConfig . chr(10)
		);
	}

	public function addTyposcript($extensionKey, $key, $typoScriptPath = '') {
		if (!$typoScriptPath) {
			$typoScriptPath = t3lib_extMgm::extPath($extensionKey) . $key . '/content.ts';
		}

		$type = 'CType';
		$typoScriptContent = t3lib_div::getURL($typoScriptPath);
		if ($typoScriptContent) {
			t3lib_extMgm::addTypoScript($key, 'setup', '
			# Setting ' . $key . ' TypoScript
			' . $typoScriptContent . '
			', 43);
		}
	}
	

}

?>