#!/usr/bin/env bash
# https://zerowp.com/?p=55

# Requires your WordPress.org username and password added to the repo's GitHub Secrets as:
# SVN_USERNAME
# SVN_PASSWORD

# Get the plugin slug from this git repository + convert to lowercase.
PLUGIN_SLUG="`echo "${PWD##*/}" | perl -ne 'print lc'`"

# Get the current release version
TAG=$(sed -e "s/refs\/tags\///g" <<< $GITHUB_REF)

# Replace the version in these 2 files.
#sed -i -e "s/__STABLE_TAG__/$TAG/g" ./trunk/readme.txt
#sed -i -e "s/__STABLE_TAG__/$TAG/g" "./trunk/$PLUGIN_SLUG.php"

# Get the SVN data from wp.org in a folder named `svn`
svn co --depth immediates "https://plugins.svn.wordpress.org/$PLUGIN_SLUG" ./svn

svn update --set-depth infinity ./svn/trunk
svn update --set-depth infinity ./svn/assets
svn update --set-depth infinity ./svn/tags/$TAG

# Copy files from `src` to `svn/trunk`
cp -R ./src/* ./svn/trunk

# Copy the images from `assets` to `svn/assets`
cp -R ./assets/* ./svn/assets

# 3. Switch to SVN directory
cd ./svn

# Prepare the files for commit in SVN
svn add --force trunk
svn add --force assets

# Create the version tag in svn
svn cp trunk tags/$TAG

# Prepare the tag for commit
svn add --force tags

# Commit files to wordpress.org.
svn ci --message "Release $TAG" --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive