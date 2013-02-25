#!/bin/sh

# Passed the following parameters from git
#  --auto --L1 "somefile (A)" --L2 "somefile (B)" /tmp/4VhUw0_somefile somefile

LOCAL=$6
REMOTE=$7

"C:/Program Files/KDiff3/kdiff3.exe" $(cygpath -w "$LOCAL") "$REMOTE"
