# .bashrc

# Source global definitions
if [ -f /etc/bashrc ]; then
  . /etc/bashrc
fi

##############################
# ALIASES
#############################
# Easier navigation: .., ..., ~ and -
alias ..="cd .."
alias ...="cd ../.."
alias ll='ls -alh'
alias lt='ls -R | grep ":$" | sed -e '"'"'s/:$//'"'"' -e '"'"'s/[^-][^\/]*\//--/g'"'"' -e '"'"'s/^/   /'"'"' -e '"'"'s/-/|    /'"'"''

# List only directories
alias lsd='ls -l | grep "^d"'

#colors for grep:
export GREP_OPTIONS="--color=always"
alias less="less -R"


#flush nginx page speed cache
alias psflush='sudo touch /var/ngx_pagespeed_cache/cache.flush'

# Always use color output for `ls`
if [[ "$OSTYPE" =~ ^darwin ]]; then
    export CLICOLOR=1
    export LSCOLORS=gxBxhxDxfxhxhxhxhxcxcx
    alias ls="command ls -G"
    export PATH=/opt/local/bin:/opt/local/sbin:$PATH
else
    export LS_COLORS='no=00:fi=00:di=01;34:ln=01;36:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.gz=01;31:*.bz2=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.avi=01;35:*.fli=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.ogg=01;35:*.mp3=01;35:*.wav=01;35:'
    alias ls="command ls --color"
fi

if [[ "$OSTYPE" == cygwin ]]; then
  alias gem='C:/\Ruby193/\bin/\gem'
fi


#RUBY SHORTCUTS
alias routes='vim ./config/routes.rb'
alias deploy='vim ./config/deploy.rb'

# GIT STUFF
# Undo a `git push`
alias undopush="git push -f origin HEAD^:master"
alias df_pull="git pull https://drinkspiller@github.com/drinkspiller/dotfiles.git && reload"
alias df_push="git push https://drinkspiller@github.com/drinkspiller/dotfiles.git"
alias gittree='git log --graph --full-history --all --color  --pretty=format:"%x1b[31m%h%x09%x1b[32m%d%x1b[0m%x20%s"'
if [ -f ~/.git-completion.bash ]; then
  . ~/.git-completion.bash
fi
if [ -f ~/.git-prompt.sh ]; then
  . ~/.git-prompt.sh
fi
function gpull()
{
  if [ -z "$2" ]; then
    BRANCH=$(git branch | grep "*" | awk '{print $2}')
  else
    BRANCH=$2
  fi

  if [ -z "$1" ]; then
    REMOTE='origin'
  else
    REMOTE=$1
  fi

  CMD="git pull $REMOTE $BRANCH"
  echo $CMD
  $CMD  
}

function gpush()
{
  if [ -z "$2" ]; then
    BRANCH=$(git branch | grep "*" | awk '{print $2}')
  else
    BRANCH=$2
  fi

  if [ -z "$1" ]; then
    REMOTE='origin'
  else
    REMOTE=$1
  fi

  CMD="git push $REMOTE $BRANCH"
  echo $CMD
  $CMD  
}

alias komodo='open -a "Komodo IDE"'
alias reload='. ~/.bashrc'
alias vi='/usr/bin/vim'
alias vimi='vim -c start' #start vim in insert mode
alias explorethis='cmd.exe /c start .'

#per platform hosts alias
if [ -f /cygdrive/c/Windows/System32/drivers/etc/hosts ]; then
    alias hosts='vim /cygdrive/c/Windows/System32/drivers/etc/hosts'
elif [ -f /private/etc/hosts ]; then
    alias hosts='vim /private/etc/hosts'
elif [ -f /etc/hosts ]; then
    alias hosts='vim /etc/hosts'
fi

alias Desktop='cd /cygdrive/c/Users/sgiordano/Desktop'
alias desktop='cd /cygdrive/c/Users/sgiordano/Desktop'
alias delsvn='echo ">> recursively removing .svn folders from" pwd && rm -rf `find . -type d -name .svn`'
alias delDS='find ./ -name ".DS_Store" -print0 | xargs -0 rm -Rf'
alias delThumbsDb='find . -name "*.db" -exec rm {} \;'
alias delMACOSX='find ./ -name "__MACOSX" -print0 | xargs -0 rm -Rf'
alias mysqlsock='mysqladmin variables | grep sock'

# IP addresses
alias ip="dig +short myip.opendns.com @resolver1.opendns.com"
alias localip="ipconfig getifaddr en1"
alias ips="ifconfig -a | perl -nle'/(\d+\.\d+\.\d+\.\d+)/ && print $1'"


##############################
# PATH
#############################
PATH=$PATH:$HOME/.rvm/bin # Add RVM to PATH for scripting
PATH="/usr/local/bin:/usr/bin:/bin:$PATH"
export PATH


##############################
# CONFIG
#############################
#RVM:
[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm" # Load RVM into a shell session *as a function*

#prompt:
export PS1='\[\033[36m\]\u\[\033[m\]@\[\033[32m\]\h:\[\033[33;1m\][ \w ]$(__git_ps1 "(%s)")\[\033[m\] $ '

##############################
# FUNCTIONS
#############################
# Create a new directory and enter it
function md() {
    mkdir -p "$@" && cd "$@"
}


# find shorthand
function f() {
    find . -name "$1"
}

# Start an HTTP server from a directory, optionally specifying the port
function server() {
    local port="${1:-8000}"
    open "http://localhost:${port}/"
    # Set the default Content-Type to `text/plain` instead of `application/octet-stream`
    # And serve everything as UTF-8 (although not technically correct, this doesn.t break anything for binary files)
    python -c $'import SimpleHTTPServer;\nmap = SimpleHTTPServer.SimpleHTTPRequestHandler.extensions_map;\nmap[""] = "text/plain";\nfor key, value in map.items():\n\tmap[key] = value + ";charset=UTF-8";\nSimpleHTTPServer.test();' "$port"
}

# take this repo and copy it to somewhere else minus the .git stuff.
function gitexport(){
    mkdir -p "$1"
    git archive master | tar -x -C "$1"
}


function extract()      # Handy Extract Program.
{
     if [ -f $1 ] ; then
         case $1 in
             *.tar.bz2)   tar xvjf $1     ;;
             *.tar.gz)    tar xvzf $1     ;;
             *.bz2)       bunzip2 $1      ;;
             *.rar)       unrar x $1      ;;
             *.gz)        gunzip $1       ;;
             *.tar)       tar xvf $1      ;;
             *.tbz2)      tar xvjf $1     ;;
             *.tgz)       tar xvzf $1     ;;
             *.zip)       unzip $1        ;;
             *.Z)         uncompress $1   ;;
             *.7z)        7z x $1         ;;
             *)           echo "'$1' cannot be extracted via >extract<" ;;
         esac
     else
         echo "'$1' is not a valid file"
     fi
}
