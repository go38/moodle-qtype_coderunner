<?php
// This file is part of CodeRunner - http://coderunner.org.nz/
//
// CodeRunner is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CodeRunner is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CodeRunner.  If not, see <http://www.gnu.org/licenses/>.

/* The qtype_coderunner_equality_grader class. Compares the output from a given test case,
 *  awarding full marks if and only if the output exactly matches the expected
 *  output. Otherwise, zero marks are awarded. The output is deemed to match
 *  the expected if the two are byte for byte identical after trailing white
 *  space has been removed from both.
 *  "Trailing white space" means all white space at the end of the strings
 *  plus all white space from the end of each line in the strings. It does
 *  not include blank lines within the strings or white space within the lines.
 */

/**
 * @package    qtype
 * @subpackage coderunner
 * @copyright  Richard Lobb, 2013, The University of Canterbury
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('graderbase.php');

class qtype_coderunner_equality_grader extends qtype_coderunner_grader {

    public function name() {
        return 'equality_grader';
    }

    /*  Called to grade the output generated by a student's code for
     *  a given testcase. Returns a single TestResult object.
     *  Should not be called if the execution failed (syntax error, exception
     *  etc).
     */
    public function grade_known_good(&$output, &$testcase) {
        $cleanedoutput = qtype_coderunner_grader::clean($output);
        $cleanedexpected = qtype_coderunner_grader::clean($testcase->expected);
        $iscorrect = $cleanedoutput == $cleanedexpected;
        $awardedmark = $iscorrect ? $testcase->mark : 0.0;
        if (isset($testcase->stdin)) {
            $resultstdin = qtype_coderunner_grader::tidy($testcase->stdin);
        } else {
            $resultstdin = null;
        }

        return new qtype_coderunner_test_result(
                qtype_coderunner_grader::tidy($testcase->testcode),
                $testcase->mark,
                $iscorrect,
                $awardedmark,
                qtype_coderunner_grader::snip($cleanedexpected),
                qtype_coderunner_grader::snip($cleanedoutput),
                $resultstdin,
                qtype_coderunner_grader::tidy($testcase->extra)
        );
    }
}