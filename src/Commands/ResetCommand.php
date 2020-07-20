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
     * Run this command from within a Panthoen site repo cloned locally.
     *
     * Steps to take:
     * - Reset git to certain point of history
     * - Delete all "pantheon_test_*" and "pantheon_live_*" tags
     * - Commit and force push to repo
     * 
     * Example usage:
     * terminus envr foo 69cad75587a22c278e commitlog.csv
     * 
     * This would reset the "foo" environment back to commit 69cad75587a22c278e, 
     * and then rebuild commits based on commitlog.csv.
     *
     * @command env:reset
     * @aliases envr
     *
     * @param string $site_env_id Source site to clone
     * @param string $commit The commit hash to reset to.
     *   This is built on a model that the commit resetting to is a "breaking commit". This 
     * commit is rewritten to the current date/time of the command executing.
     * @param string $commit_log The text file to use for a fake commit log
     *   See the commitlog-example.csv for a template how this should be setup.
     */
    public function resetCommand($site_env_id, $commit, $commit_log, $db_url)
    {
        // Get the site repo
        list($site, $env) = $this->getSiteEnv($site_env_id);
        $env_id = $env->getName();
        $siteInfo = $site->serialize();
        $site_id = $siteInfo['id'];
        $repo_path = "ssh://codeserver.dev.$site_id@codeserver.dev.$site_id.drush.in:2222/~/repository.git";


        // Reset history back to the commit passed.
        $this->passthru('git reset --hard ' . $commit);

        // Rewrite this breaking commit to be recent.
        $this->passthru('GIT_COMMITTER_DATE="$(date)" git commit --amend --no-edit --date "$(date)"');

        // Get our commit log into an array.
        $commits = array_map('str_getcsv', file($commit_log));

        foreach ($commits as $commit) {
            $this->passthru('echo "Adding ' . $commit[0] . '" >> log.txt');
            $this->passthru('git add log.txt');
            $this->passthru('git commit -m "' . $commit[0] . '"');
        }
        
        // Force push changes to Pantheon.
        $this->passthru('git remote remove pantheon');
        $this->passthru('git remote add pantheon ' . $repo_path);
        $this->passthru('git push pantheon master --force');


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
