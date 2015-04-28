#!/bin/bash
# A modification of Paul Clark's (http://bit.ly/1GpnxPz) which is in turn a modification of Dean Clatworthy's script (http://bit.ly/1I4BMeJ).
# This one takes the functionality from both but adds checks, asks for better input and displays a better output in the terminal.

# ---------------------------------------------------------------------------
# Vars & Config.
# ---------------------------------------------------------------------------
#
# Set the start time.
START_SECONDS="$(date +%s)"

# Current directory.
CURRENT_DIR=`pwd`
CURRENT_DIR_NAME="$(dirname "$CURRENT_DIR")"
GIT_PATH="$CURRENT_DIR"

# Get plugin slug name.
PLUGIN_SLUG=${PWD##*/}



# ---------------------------------------------------------------------------
# Check plugin name.
# ---------------------------------------------------------------------------
#
read -p "We have detected your plugin slug as '$PLUGIN_SLUG' is this correct? (y/n)? " choice
if [ "$choice" = "n" ]
then
	# Check :: Plugin Slug
	PLUGIN_SLUG=""; i=0
	while [[ $PLUGIN_SLUG = "" ]]; do
		if [ "$i" = "0" ]
		then
	    	read -p "Please enter a plugin slug: " PLUGIN_SLUG
	    else
	    	read -p "You must supply a plugin slug: " PLUGIN_SLUG
	    fi
	    let "i++"
	done
fi
# Main plugin file.
MAINFILE="$PLUGIN_SLUG.php"
# SVN TMP Path.
SVN_PATH="/tmp/$PLUGIN_SLUG"
# SVN URL.
SVN_URL="https://plugins.svn.wordpress.org/$PLUGIN_SLUG/"
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Plugin slug set to $PLUGIN_SLUG. Continuing...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# Check SVN user.
# ---------------------------------------------------------------------------
#
SVN_USER=$(cat ~/.subversion/auth/svn.simple/* | grep -A4 $(echo $SVN_URL | awk -F// '{print $2}' | cut     -d'/' -f1) | tail -n1)
if [ -z "$SVN_USER" ]
then
	# Check :: SVN User
	SVN_USER=""; i=0
	while [[ $SVN_USER = "" ]]; do
		if [ "$i" = "0" ]
		then
	    	read -p "SVN username could not be detected. Please enter here: " SVN_USER
	    else
	    	read -p "You must supply an SVN username: " SVN_USER
	    fi
	    let "i++"
	done
fi
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)SVN user set to $SVN_USER. Continuing...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# Check plugin version.
# ---------------------------------------------------------------------------
#
VERSION_README=$(grep -o "Stable tag.*" $GIT_PATH/readme.txt)
VERSION_README="${VERSION_README/'Stable tag: '/}"
VERSION_FILE=$(grep -o "Version:.*" $GIT_PATH/$MAINFILE)
VERSION_FILE="${VERSION_FILE/'Version: '/}"

# Check :: Plugin Version
RELEASE_VERSION=""; i=0
while [[ $RELEASE_VERSION = "" ]]; do
	if [ "$i" = "0" ]
	then
    	read -p "Please enter a version number for this release: " RELEASE_VERSION
    else
    	read -p "You must supply a version number: " RELEASE_VERSION
    fi
    let "i++"
done

echo -e "------------------------------------------------"

# Confirm
read -p "You have entered '$RELEASE_VERSION' does this match the 'Stable Tag' in the readme.txt file and 'Version' in the $MAINFILE? (y/n)? " choice
if [ "$choice" = "n" ]
then
	# Check :: Plugin Version
	RELEASE_VERSION=""; i=0
	while [[ $RELEASE_VERSION = "" ]]; do
		if [ "$i" = "0" ]
		then
			echo -e "------------------------------------------------"
	    	read -p "Please enter a version number for this release: " RELEASE_VERSION
	    else
	    	read -p "You must supply a version number: " RELEASE_VERSION
	    fi
	    let "i++"
	done
fi

## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Release version set to $RELEASE_VERSION, Continuing...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# Prompt commit message.
# ---------------------------------------------------------------------------
#
# Check :: Commit Message
COMMIT_MSG=""; i=0
while [[ $COMMIT_MSG = "" ]]; do
	if [ "$i" = "0" ]
	then
    	read -p "Please enter a commit message: " COMMIT_MSG
    else
    	read -p "You must supply commit message: " COMMIT_MSG
    fi
    let "i++"
done



# ---------------------------------------------------------------------------
# Git operations.
# ---------------------------------------------------------------------------
#
# Tag version.
git tag -a "$RELEASE_VERSION" -m "Tagging version $RELEASE_VERSION"
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully tagged version as $RELEASE_VERSION in Git...$(tput sgr0)"
echo -e "------------------------------------------------"

# Push to origin.
git push origin master
git push origin master --tags
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully pushed latest commit to origin, with tags...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# SVN operations.
# ---------------------------------------------------------------------------
#
# Create local copy of SVN repo.
svn co $SVN_URL $SVN_PATH
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully created local copy of SVN...$(tput sgr0)"
echo -e "------------------------------------------------"

# Exporting the HEAD of master from git to the trunk of SVN.
git checkout-index -a -f --prefix=$SVN_PATH/trunk/
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully exported HEAD of master to trunk of SVN...$(tput sgr0)"
echo -e "------------------------------------------------"

# Ignoring github specific files and deployment script.
svn propset svn:ignore "deploy.sh
readme.sh
README.md
.git
.gitattributes
.gitignore
map.conf
nginx.log" "$SVN_PATH/trunk/"
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully ignored github specific files and deployment script...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# Commit to SVN trunk.
# ---------------------------------------------------------------------------
#
# Change directory to SVN and committing to trunk.
cd $SVN_PATH/trunk/
# Add all new files that are not set to be ignored.
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
# Apply commit message.
svn commit --username=$SVN_USER -m "$COMMIT_MSG"
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully commited to SVN trunk with message '$COMMIT_MSG'...$(tput sgr0)"
echo -e "------------------------------------------------"

# Create new SVN tag and commit it.
cd $SVN_PATH
svn copy trunk/ tags/$RELEASE_VERSION/
cd $SVN_PATH/tags/$RELEASE_VERSION
svn commit --username=$SVN_USER -m "Tagging version $RELEASE_VERSION"
rm -fr $SVN_PATH/
## Report.
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully tagged version as $RELEASE_VERSION in SVN...$(tput sgr0)"
echo -e "------------------------------------------------"



# ---------------------------------------------------------------------------
# Complete.
# ---------------------------------------------------------------------------
#
END_SECONDS="$(date +%s)"
echo -e "------------------------------------------------"
echo -e "$(tput setaf 2)Successfully deployed plugin in "$(expr $END_SECONDS - $START_SECONDS)" seconds$(tput sgr0)"
echo -e "------------------------------------------------"
