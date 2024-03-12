<?php
/**
 * PHP Version 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://pear.php.net/package/PHP_CodeSniffer_CakePHP
 * @since         CakePHP CodeSniffer 0.1.14
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PHP_CodeSniffer\Standards\CakePHP\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Ensures no whitespaces and one whitespace is placed around each comma
 *
 */
class CakePHP_Sniffs_WhiteSpace_CommaSpacingSniff implements Sniff
{

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
    public function register()
    {
        return array(T_COMMA);
    }

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param File $phpcsFile All the tokens found in the document.
 * @param integer $stackPtr The position of the current token
 *    in the stack passed in $tokens.
 * @return void
 */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

        if ($tokens[$next]['code'] !== T_WHITESPACE && ($next !== $stackPtr + 2)) {
            // Last character in a line is ok.
            if ($tokens[$next]['line'] === $tokens[$stackPtr]['line']) {
                $error = 'Missing space after comma';
                $fix = $phpcsFile->addFixableError($error, $next);
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }

        $previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);

        if ($tokens[$previous]['code'] !== T_WHITESPACE && ($previous !== $stackPtr - 1)) {
            $error = 'Space before comma, expected none, though';
            $phpcsFile->addError($error, $next);
        }
    }
}
