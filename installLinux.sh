#!/bin/bash
if [[ $(/usr/bin/id -u) -eq 0 ]]; then
    echo "Running as root or with sudo. Just run this script as your user."
    exit
fi

# APT
sudo bash <<EOF
  apt-get update

  apt-get install -y bash-completion
  apt-get install -y build-essential
  apt-get install -y cmake
  apt install exfat-fuse
  apt install exfat-utils
  apt-get install -y ffmpeg
  apt-get install -y libssl-dev
  apt-get install -y git-all
  apt-get install -y meld
  apt-get install -y python-gpiozero
  apt-get install -y python3-pip
  apt-get install -y sshfs
  apt-get install -y transmission-cli
  apt-get install -y trash-cli
  apt-get install -y unzip
  apt-get install -y vim
  apt-get install -y vlc
  #apt-get install -y vnc4server
  apt-get install -y x264
  #apt-get install -y xclip
  #apt-get install -y yarnpkg

  # PIP
  pip3 install howdoi
  pip3 install pexpect
  pip3 install ydiff

  # Config
  # Set vim to default editor
  if [[ $(which systemctl | wc -l) == '1' ]] ; then
    update-alternatives --set editor /usr/bin/vim.basic
  fi
EOF

# MISC
#NVM & Node
# See https://github.com/sdesalas/node-pi-zero for available armv6 versions.
export run_as=""
if  [[ $(uname -m) == 'armv6l' ]]
then
  run_as="sudo"
  sudo bash <<EOF
    curl -o- https://raw.githubusercontent.com/sdesalas/node-pi-zero/master/install-node-v15.11.0.sh | bash
EOF
else
  curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
  source ~/.bashrc
  nvm install --lts
fi
$run_as bash <<EOF
  #NPM packages
  #npm install -g carbon-now-cli
  npm install -g chokidar-cli
  npm install -g fkill-cli
  npm install -g live-server
  npm install -g tldr
  #npm install -g ytdl
EOF
