# Terminus Reset Plugin

## Setup / Usage
- Make sure the site has a multidev called "demo". This will contain the database that will be cloned over
to the dev environment upon reset.
- Make sure to execute the plugin command *inside* a local checked out copy of the site. It will be performing
git commands against this local copy and force pushing them to the site environment.

## Example usage
`terminus envr testreset.dev 82bc552e230fa972e2 commitlog.csv`
This will reset the environment to the commit `82bc552e230fa972e2`, which we would assume is a "breaking commit", and it will subsequently build "dummy" commits by writing to `log.txt` file which will be in the sites docroot. It will then clone the `demo` multidev over to the `dev` environment.

## Configuration

This plugin requires no configuration to use.

## Installation
For help installing, see [Manage Plugins](https://pantheon.io/docs/terminus/plugins/)
```
mkdir -p ~/.terminus/plugins
composer create-project --no-dev -d ~/.terminus/plugins pantheon-systems/terminus-rsync-plugin:~1
```