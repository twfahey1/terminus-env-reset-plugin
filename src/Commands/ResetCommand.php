<?php
/**
 * This command will run git commands on a Pantheon site.
 *
 * See README.md for usage information.
 */

namespace Pantheon\TerminusEnvReset\Commands;

use Consolidation\OutputFormatters\StructuredData\PropertyList;
use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Run git commands on a Pantheon instance
 */
class ResetCommand extends TerminusCommand implements SiteAwareInterface
{
    use SiteAwareTrait;

    protected $info;
    protected $tmpDirs = [];

    /**
     * Object constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Reset a Pantheon site to a given commit.
     * 
     * My process has involved restoring from a backup in Dev, 
     * deploying all the way up to Live, 
     * then cloning the database+files from Dev to Live. Then create a new backup. That part is easy, but time consuming.
     * 
     * Steps to take:
     * - Reset git to certain point of history
     * - Delete all "pantheon_test_*" and "pantheon_live_*" tags
     * - Commit and force push to repo
     *
     * @command env:reset
     * @aliases envr
     *
     * @param string $site_env_id Source site to clone
     * @param string $commit The commit hash to reset to.
     * @param string $commit_log The text file to use for a fake commit log
     */
    public function resetCommand($site_env_id, $commit, $commit_log)
    {
        echo "todo";
    }

    protected function passthru($command)
    {
        $result = 0;
        passthru($command, $result);

        if ($result != 0) {
            throw new TerminusException('Command `{command}` failed with exit code {status}', ['command' => $command, 'status' => $result]);
        }
    }

}
