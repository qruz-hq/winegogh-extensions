#!/bin/bash

# Check if a version number is provided
if [ -z "$1" ]; then
  echo "Usage: ./update.sh <version>"
  exit 1
fi

VERSION=$1

# Add all changes to git
git add .

# Commit changes with a message
git commit -m "Update to version $VERSION"

# Push changes to the main branch
git push origin main

# Tag the new version
git tag -a "v$VERSION" -m "Release version $VERSION"

# Push tags to GitHub
git push origin --tags

echo "Update to version $VERSION completed successfully."