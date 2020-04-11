#!/bin/bash
if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Not running as root. Try running this script with sudo."
    exit
fi

# APT
apt-get update

apt-get install -y build-essential
apt-get install -y cmake
apt-get install -y ffmpeg
apt-get install -y libssl-dev
apt-get install -y git
apt-get install -y meld
apt-get install -y mysql-server
apt-get install -y python3-pip
apt-get install -y sshfs
apt-get install -y trash-cli
apt-get install -y unzip
apt-get install -y x264
apt-get install -y vim
apt-get install -y vlc
apt-get install -y vnc4server
apt-get install -y xclip
apt-get install -y yarnpkg

# PIP
pip3 install howdoi
pip3 install pexpect
pip3 install ydiff

# MISC
#NVM & Node
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
mkdir ~/.nvm
nvm install --lts

source ~/.bashrc
