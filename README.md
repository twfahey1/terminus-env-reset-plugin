# Terminus Reset Plugin

## Setup / Usage
- Make sure the site has a multidev called "demo". This will contain the database that will be cloned over
to the dev environment upon reset.
- Make sure to execute the plugin command *inside* a local checked out copy of the site. It will be performing
git commands against this local copy and force pushing them to the site environment.

## Example usage
`terminus envr testreset.dev 82bc552e230fa972e2 commitlog.csv`
This will reset the environment to the commit `82bc552e230fa972e2`, which we would assume is a "breaking commit", and it will subsequently build "dummy" commits by writing to `log.txt` file which will be in the sites docroot. It will then clone the `demo` multidev over to the `dev` environment.

Before running:
https://www.evernote.com/shard/s128/sh/11f64a19-678f-4bde-8431-52917d301623/19f5d870b4c93e2b/res/0706060a-c15e-4032-86c3-f0ad7b16b995

After running:
https://www.evernote.com/shard/s128/sh/1b69b787-f921-4de5-8ce1-ca98d316d496/97b6fdb532e702c2/res/0d70771d-64c4-40c4-9120-c540ac820022

## Configuration

This plugin requires no configuration to use.

## Installation
For help installing, see [Manage Plugins](https://pantheon.io/docs/terminus/plugins/)
```
mkdir -p ~/.terminus/plugins
composer create-project --no-dev -d ~/.terminus/plugins pantheon-systems/terminus-rsync-plugin:~1
```
