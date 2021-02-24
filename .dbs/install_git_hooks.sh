#!/bin/bash
#
# This script creates a soft link from the dbs directory to the .git/hooks
# directory.
#
# This allows hook scripts to be kept under revision control
#
# It must be run from within the project directory.
#
# @author Harold Bradley III - 12/20/2016
#
#######################################################################

DBS_HOOKS_DIR=$(env -u GIT_DIR /usr/bin/git rev-parse --show-toplevel)/.dbs/hooks
GIT_HOOKS_DIR=$(env -u GIT_DIR /usr/bin/git rev-parse --show-toplevel)/.git/hooks

# prompt()
function prompt() { # prints a message and prompts for yes or no interaction
	echo
	echo -n "$1 (Y/N) "
	while read -r -n 1 -s _ANSWER; do
		if [[ $_ANSWER = [YyNn] ]]; then
			[[ $_ANSWER = [Yy] ]] && _VALUE=0
			[[ $_ANSWER = [Nn] ]] && _VALUE=1
			break
		fi
	done
	echo
	return $_VALUE
}

# Check if git hook directory is alreay linked to hooks
if [[ $(readlink $GIT_HOOKS_DIR) == $DBS_HOOKS_DIR ]] ; then
	echo "           Hooks already linked."
	exit  # Nothing to do.
fi

# Make sure git hooks directory doesn't already exist
if [[ ! -d $GIT_HOOKS_DIR ]] && [[ ! -f $GIT_HOOKS_DIR ]] && [[ ! -L $GIT_HOOKS_DIR ]] ; then
    echo "           Hooks directory doesn't exist. Linking git hooks..."
    ln -s $DBS_HOOKS_DIR $GIT_HOOKS_DIR
	exit  # Finished
fi

# Git hook directory already exists, but is not yet linked
if prompt "           A hooks directory already exists but does not link to dotfiles hooks. Remove it and use slate githooks?"; then
	rm -r $GIT_HOOKS_DIR
	echo "           Linking git hooks..."
	ln -s $DBS_HOOKS_DIR $GIT_HOOKS_DIR
fi
